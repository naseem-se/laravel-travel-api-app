<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\CancelBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\StripeService;
use App\Services\PayPalService;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request, StripeService $stripe, PayPalService $paypal)
    {
        $user = $request->user();
        $experience = \App\Models\Experience::findOrFail($request->experience_id);
        $currency = strtoupper($request->currency ?? config('app.currency','USD'));

        // compute totals
        $subtotal = (float) $request->total_amount;
        $platformFeePercent = config('app.platform_fee', env('PLATFORM_FEE_PERCENT', 10));
        $platformFee = round(($subtotal * $platformFeePercent) / 100, 2);
        $total = round($subtotal + $platformFee, 2);


        // check if the user has already booked this experience for the same date
        $existingBooking = Booking::where('traveler_id', $user->id)
            ->where('experience_id', $experience->id)
            ->whereDate('start_at', Carbon::parse($request->input('start_at')))
            ->whereIn('status', ['pending_payment', 'paid'])
            ->first();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'You have already booked this experience for the selected date.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'traveler_id' => $user->id,
                'experience_id' => $experience->id,
                'start_at' => $request->start_at ? Carbon::parse($request->start_at) : Carbon::now(),
                'guests' => $request->guests ?? 1,
                'subtotal' => $subtotal,
                'platform_fee' => $platformFee,
                'total' => $total,
                'currency' => $currency,
                'status' => 'pending_payment',
                'payment_provider' => $request->payment_provider,
            ]);

            if ($request->payment_provider === 'stripe') {
                $intent = $stripe->createPaymentIntent($booking);
                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'payer_id' => $user->id,
                    'provider' => 'stripe',
                    'provider_payment_id' => $intent['id'],
                    'amount' => $booking->total,
                    'currency' => $booking->currency,
                    'status' => 'pending',
                    'meta' => ['client_secret' => $intent['client_secret']],
                ]);

                $booking->payment_provider_id = $intent['id'];
                $booking->save();

                DB::commit();

                return response()->json([
                    'success'=>true,
                    'provider'=>'stripe',
                    'client_secret' => $intent['client_secret'],
                    'booking' => new BookingResource($booking)
                ], 201);

            } else {
                // PayPal
                $returnUrl = route('api.bookings.paypal.return', ['booking' => $booking->id]);
                $cancelUrl = route('api.bookings.paypal.cancel', ['booking' => $booking->id]);

                $orderResp = $paypal->createOrder($booking, $returnUrl, $cancelUrl);

                $order = $orderResp->result;
                $approveUrl = null;
                foreach ($order->links as $link) {
                    if ($link->rel === 'approve') { $approveUrl = $link->href; break; }
                }

                $payment = Payment::create([
                    'booking_id' => $booking->id,
                    'payer_id' => $user->id,
                    'provider' => 'paypal',
                    'provider_payment_id' => $order->id ?? null,
                    'amount' => $booking->total,
                    'currency' => $booking->currency,
                    'status' => 'pending',
                    'meta' => json_decode(json_encode($order), true)
                ]);

                $booking->payment_provider_id = $order->id ?? null;
                $booking->save();

                DB::commit();

                return response()->json([
                    'success'=>true,
                    'provider'=>'paypal',
                    'approve_url' => $approveUrl,
                    'booking' => new BookingResource($booking)
                ], 201);
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking.store error: '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>'Booking creation failed','error'=>$e->getMessage()],500);
        }
    }

    // Stripe webhook handler (route: /webhooks/stripe)
    public function stripeWebhook(\Illuminate\Http\Request $request, StripeService $stripe)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {

            if (app()->environment('local')) {
                $event = json_decode($payload);
            } else {
                $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, config('services.stripe.webhook_secret'));
            }

            
        } catch(\UnexpectedValueException $e) {
            return response('Invalid payload',400);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature',400);
        }

        // handle payment_intent.succeeded
        if ($event->type === 'payment_intent.succeeded') {
            $intent = $event->data->object;
            $bookingId = $intent->metadata->booking_id ?? null;

            if ($bookingId) {
                DB::transaction(function() use($bookingId, $intent) {
                    $booking = Booking::find($bookingId);
                    if (!$booking) return;
                    // mark payment
                    $payment = Payment::where('provider','stripe')->where('provider_payment_id',$intent->id)->first();
                    if ($payment) {
                        $payment->status = 'succeeded';
                        $payment->meta = array_merge($payment->meta ?? [], ['raw' => (array)$intent]);
                        $payment->save();
                    } else {
                        Payment::create([
                            'booking_id'=>$booking->id,'payer_id'=>$booking->traveler_id,
                            'provider'=>'stripe','provider_payment_id'=>$intent->id,
                            'amount'=>$booking->total,'currency'=>$booking->currency,'status'=>'succeeded',
                            'meta'=>['raw' => (array)$intent]
                        ]);
                    }
                    $booking->status = 'paid';
                    $booking->payment_provider_id = $intent->id;
                    $booking->save();
                });
            }
        }

        return response('ok',200);
    }

    // PayPal return capture handler
    public function paypalReturn(\Illuminate\Http\Request $request, PayPalService $paypal, Booking $booking)
    {
        $token = $request->query('token'); // PayPal order id
        if (!$token) return redirect(config('app.frontend_url','/').'/payment-failed');

        try {
            $resp = $paypal->captureOrder($token);
            // find capture id
            $captures = $resp->result->purchase_units[0]->payments->captures ?? null;
            $captureId = $captures[0]->id ?? null;

            DB::transaction(function() use($booking, $resp, $captureId) {
                $payment = $booking->payments()->where('provider','paypal')->latest()->first();
                if ($payment) {
                    $payment->status = 'succeeded';
                    $payment->provider_payment_id = $resp->result->id ?? $payment->provider_payment_id;
                    $payment->meta = array_merge($payment->meta ?? [], ['raw' => json_decode(json_encode($resp->result), true)]);
                    $payment->save();
                } else {
                    $booking->payments()->create([
                        'payer_id' => $booking->traveler_id,
                        'provider' => 'paypal',
                        'provider_payment_id' => $resp->result->id ?? null,
                        'amount' => $booking->total,
                        'currency' => $booking->currency,
                        'status' => 'succeeded',
                        'meta' => json_decode(json_encode($resp->result), true)
                    ]);
                }
                $booking->status = 'paid';
                $booking->payment_provider_id = $resp->result->id ?? $booking->payment_provider_id;
                $booking->save();
            });

            return redirect(config('app.frontend_url','/')."/payment-success?booking={$booking->id}");
        } catch (Exception $e) {
            Log::error('PayPal capture failed: '.$e->getMessage());
            return redirect(config('app.frontend_url','/').'/payment-failed');
        }
    }

    public function paypalCancel(\Illuminate\Http\Request $request, Booking $booking)
    {
        return redirect(config('app.frontend_url','/')."/payment-cancelled?booking={$booking->id}");
    }

    // Cancel booking (user or admin)
    public function cancel(CancelBookingRequest $request)
    {
        $user = $request->user();
        $booking = Booking::findOrFail($request->booking_id);

        // authorization: owner or admin
        if ($user->id !== $booking->traveler_id && $user->role !== 'admin') {
            return response()->json(['success'=>false,'message'=>'Unauthorized'],403);
        }

        // cannot cancel past bookings
        if ($booking->start_at && Carbon::parse($booking->start_at)->isPast()) {
            return response()->json(['success'=>false,'message'=>'Cannot cancel past booking'],400);
        }

        DB::beginTransaction();
        try {
            // if paid -> refund
            if ($booking->status === 'paid') {
                $payment = $booking->payments()->where('status','succeeded')->latest()->first();
                if ($payment) {
                    if ($payment->provider === 'stripe') {
                        $stripe = new StripeService();
                        $refund = $stripe->refundPaymentIntent($payment->provider_payment_id, $payment->amount);
                        $payment->status = 'refunded';
                        $payment->meta = array_merge($payment->meta ?? [], ['refund' => $refund]);
                        $payment->save();

                        $booking->refund_provider_id = $refund->id ?? null;
                        $booking->status = 'refunded';
                    } elseif ($payment->provider === 'paypal') {
                        // need capture id — stored in payment->meta (from capture)
                        $captureId = data_get($payment->meta, 'purchase_units.0.payments.captures.0.id');
                        if (!$captureId) {
                            throw new Exception('PayPal capture id not found for refund.');
                        }
                        $paypal = new PayPalService();
                        $refundResp = $paypal->refundCapture($captureId, $payment->amount);
                        // update records
                        $payment->status = 'refunded';
                        $payment->meta = array_merge($payment->meta ?? [], ['refund' => json_decode(json_encode($refundResp->result), true)]);
                        $payment->save();

                        $booking->refund_provider_id = $refundResp->result->id ?? null;
                        $booking->status = 'refunded';
                    }
                }
            } else {
                $booking->status = 'cancelled';
            }

            $booking->save();
            DB::commit();

            return response()->json(['success'=>true,'message'=>'Booking cancelled/refunded','booking'=> new \App\Http\Resources\BookingResource($booking)]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Booking cancel error: '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>'Could not cancel booking','error'=>$e->getMessage()],500);
        }
    }


}

