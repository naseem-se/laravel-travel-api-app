<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Refund;
use Stripe\Transfer;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a PaymentIntent for the booking.
     * Returns ['id','client_secret']
     */
    public function createPaymentIntent($booking)
    {
        $amount = intval(round($booking->total * 100));

        $intent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => strtolower($booking->currency),
            'description' => "Booking #{$booking->id} for experience '{$booking->experience->title}'",
            'metadata' => [
                'booking_id' => $booking->id." - ".$booking->experience->title,
                'traveler_id' => $booking->traveler_id." - ".$booking->traveler->full_name,
            ],
        ]);

        return ['id' => $intent->id, 'client_secret' => $intent->client_secret];
    }

    public function refundPaymentIntent(string $paymentIntentId, float $amount = null)
    {
        $params = ['payment_intent' => $paymentIntentId];
        if ($amount !== null) $params['amount'] = intval(round($amount * 100));
        return Refund::create($params);
    }

    /**
     * Transfer funds (Stripe Connect). $destination = connected account ID
     */
    public function transferToGuide(string $destinationAccountId, float $amount)
    {
        $transfer = Transfer::create([
            'amount' => intval(round($amount * 100)),
            'currency' => config('app.currency','USD'),
            'destination' => $destinationAccountId,
            'metadata' => []
        ]);
        return $transfer;
    }
}
