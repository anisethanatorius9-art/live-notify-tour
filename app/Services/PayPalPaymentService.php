<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalPaymentService
{
    /**
     * @return array{url: string, order_id: string}
     */
    public function createOrder(Payment $payment): array
    {
        $currency = config('services.paypal.currency', 'USD');
        $amount = (float) $payment->amount;

        if ($currency === 'USD') {
            $rate = config('services.paypal.tzs_to_usd_rate');

            if (! is_numeric($rate) || (float) $rate <= 0) {
                throw new RuntimeException('Set PAYPAL_TZS_TO_USD_RATE before enabling PayPal payments.');
            }

            $amount *= (float) $rate;
        }

        $accessToken = $this->accessToken();
        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(rtrim(config('services.paypal.base_url'), '/') . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $payment->transaction_id,
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'return_url' => route('payments.paypal.success', $payment),
                    'cancel_url' => route('payments.paypal.cancel', $payment),
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('PayPal order creation failed.');
        }

        $orderId = $response->json('id');
        $url = collect($response->json('links', []))->firstWhere('rel', 'approve')['href'] ?? null;

        if (! is_string($orderId) || ! is_string($url)) {
            throw new RuntimeException('PayPal did not return an approval URL.');
        }

        return ['url' => $url, 'order_id' => $orderId];
    }

    public function captureOrder(string $orderId): bool
    {
        $response = Http::withToken($this->accessToken())
            ->acceptJson()
            ->post(rtrim(config('services.paypal.base_url'), '/') . "/v2/checkout/orders/{$orderId}/capture");

        return $response->successful() && $response->json('status') === 'COMPLETED';
    }

    private function accessToken(): string
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.client_secret');

        if (! is_string($clientId) || ! is_string($clientSecret) || $clientId === '' || $clientSecret === '') {
            throw new RuntimeException('PayPal payment is not configured.');
        }

        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post(rtrim(config('services.paypal.base_url'), '/') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed() || ! is_string($response->json('access_token'))) {
            throw new RuntimeException('PayPal authentication failed.');
        }

        return $response->json('access_token');
    }
}
