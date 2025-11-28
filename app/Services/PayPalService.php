<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;
use Exception;

class PayPalService
{
    protected $client;

    public function __construct()
    {
        $mode = config('services.paypal.mode','sandbox');
        if ($mode === 'live') {
            $env = new ProductionEnvironment(config('services.paypal.client_id'), config('services.paypal.secret'));
        } else {
            $env = new SandboxEnvironment(config('services.paypal.client_id'), config('services.paypal.secret'));
        }
        $this->client = new PayPalHttpClient($env);
    }

    public function createOrder($booking, $returnUrl, $cancelUrl)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => (string)$booking->id,
                "amount" => [
                    "currency_code" => strtoupper($booking->currency),
                    "value" => number_format($booking->total, 2, '.', '')
                ]
            ]],
            "application_context" => [
                "return_url" => $returnUrl,
                "cancel_url" => $cancelUrl
            ]
        ];

        $response = $this->client->execute($request);
        return $response;
    }

    public function captureOrder(string $orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');
        $response = $this->client->execute($request);
        return $response;
    }

    /**
     * Refund capture (captureId)
     */
    public function refundCapture(string $captureId, float $amount = null)
    {
        $request = new CapturesRefundRequest($captureId);
        $body = [];
        if ($amount !== null) {
            $body['amount'] = [
                'value' => number_format($amount, 2, '.', ''),
                'currency_code' => config('app.currency','USD')
            ];
        }
        $request->body = $body;
        $response = $this->client->execute($request);
        return $response;
    }
}
