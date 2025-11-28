<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RequestWithdraw;
use App\Models\Withdrawal;
use App\Services\StripeService;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
    use AuthorizesRequests;
    public function requestWithdraw(RequestWithdraw $request)
    {
        $user = $request->user();

        // validate available_balance() method or calculate from ledger
        if (method_exists($user,'available_balance')) {
            if ($user->available_balance() < $request->amount) {
                return response()->json(['success'=>false,'message'=>'Insufficient balance'],400);
            }
        }

        $withdraw = Withdrawal::create([
            'guide_id' => $user->id,
            'amount' => $request->amount,
            'currency' => config('app.currency','USD'),
            'status' => 'pending'
        ]);

        // notify admin or queue email
        return response()->json(['success'=>true,'withdrawal' => $withdraw],201);
    }

    // Approve — admin only
    public function approve(\Illuminate\Http\Request $request, Withdrawal $withdrawal, StripeService $stripe)
    {
        $this->authorize('approveWithdrawals');

        if ($withdrawal->status !== 'pending') {
            return response()->json(['message'=>'Already processed'],400);
        }

        DB::beginTransaction();
        try {
            // Example using Stripe Connect: guide must have stripe_connect_id on user
            $guide = $withdrawal->guide;
            $connectId = $guide->stripe_connect_id ?? null;
            if (!$connectId) throw new \Exception('Guide not connected to Stripe');

            // transfer platform funds to guide (you will probably compute guide share)
            $transfer = $stripe->transferToGuide($connectId, $withdrawal->amount);

            $withdrawal->update([
                'status' => 'paid',
                'payout_provider' => 'stripe',
                'payout_provider_id' => $transfer->id,
                'meta' => ['raw' => $transfer->jsonSerialize() ?? null]
            ]);

            DB::commit();
            return response()->json(['success'=>true,'withdrawal'=>$withdrawal]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdraw approve error: '.$e->getMessage());
            return response()->json(['success'=>false,'message'=>'Error approving payout','error'=>$e->getMessage()],500);
        }
    }

    public function reject(\Illuminate\Http\Request $request, Withdrawal $withdrawal)
    {
        $this->authorize('approveWithdrawals');

        if ($withdrawal->status !== 'pending') {
            return response()->json(['message'=>'Already processed'],400);
        }

        $withdrawal->update(['status' => 'rejected', 'admin_note' => $request->input('note')]);
        return response()->json(['success'=>true,'withdrawal'=>$withdrawal]);
    }
}

