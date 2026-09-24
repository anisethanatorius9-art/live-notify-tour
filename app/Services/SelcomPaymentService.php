<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class SelcomPaymentService
{
    /**
     * Create a hosted Selcom checkout order and return its payment URL.
     *
     * @return array{url: string, response: array<string, mixed>}
     */
    public function createOrder(Payment $payment, User $user, string $phone): array
    {
        $config = config('services.selcom');

        foreach (['vendor', 'api_key', 'api_secret'] as $key) {
            if (! is_string($config[$key] ?? null) || trim($config[$key]) === '') {
                throw new RuntimeException('Selcom payment is not configured.');
            }
        }

        $payload = [
            'vendor' => $config['vendor'],
            'order_id' => $payment->transaction_id,
            'buyer_email' => $user->email,
            'buyer_name' => $user->name,
            'buyer_user_id' => (string) $user->id,
            'buyer_phone' => $this->formatPhone($phone),
            'amount' => (string) $payment->amount,
            'currency' => 'TZS',
            'payment_methods' => 'ALL',
            'redirect_url' => base64_encode(route('bookings.index')),
            'cancel_url' => base64_encode(route('bookings.index')),
            'webhook' => base64_encode(route('payments.selcom.webhook')),
            'buyer_remarks' => 'LNT tourism booking',
            'merchant_remarks' => $payment->transaction_id,
            'no_of_items' => 1,
        ];

        $response = $this->request('POST', '/v1/checkout/create-order', $payload);
        $url = data_get($response, 'data.0.payment_gateway_url');

        if (! is_string($url) || $url === '') {
            throw new RuntimeException(data_get($response, 'message', 'Selcom did not return a payment URL.'));
        }

        $decodedUrl = base64_decode($url, true);

        return [
            'url' => is_string($decodedUrl) && filter_var($decodedUrl, FILTER_VALIDATE_URL) ? $decodedUrl : $url,
            'response' => $response,
        ];
    }

    public function verifyWebhook(array $payload, string $timestamp, string $signedFields, string $digest): bool
    {
        $secret = config('services.selcom.api_secret');

        if (! is_string($secret) || $secret === '' || $signedFields === '' || $timestamp === '') {
            return false;
        }

        $signingString = 'timestamp=' . $timestamp;

        foreach (explode(',', $signedFields) as $field) {
            if (! array_key_exists($field, $payload)) {
                return false;
            }

            $signingString .= '&' . $field . '=' . $payload[$field];
        }

        $expectedDigest = base64_encode(hash_hmac('sha256', $signingString, $secret, true));

        return hash_equals($expectedDigest, $digest);
    }

    /**
     * @param  array<string, string|int>  $payload
     * @return array<string, mixed>
     */
    private function request(string $method, string $path, array $payload): array
    {
        $timestamp = now()->format('c');
        $signedFields = implode(',', array_keys($payload));
        $signingString = 'timestamp=' . $timestamp;

        foreach (array_keys($payload) as $field) {
            $signingString .= '&' . $field . '=' . $payload[$field];
        }

        $digest = base64_encode(hash_hmac('sha256', $signingString, config('services.selcom.api_secret'), true));
        $authorization = 'SELCOM ' . base64_encode(config('services.selcom.api_key'));

        $response = Http::acceptJson()
            ->withHeaders([
                'Authorization' => $authorization,
                'Digest-Method' => 'HS256',
                'Digest' => $digest,
                'Timestamp' => $timestamp,
                'Signed-Fields' => $signedFields,
            ])
            ->timeout(30)
            ->post(rtrim(config('services.selcom.base_url'), '/') . $path, $payload);

        if ($response->failed()) {
            throw new RuntimeException('Selcom payment request failed.');
        }

        return $response->json();
    }

    private function formatPhone(string $phone): string
    {
        $phone = preg_replace('/\D+/', '', $phone) ?? '';

        return Str::startsWith($phone, '0') ? '255' . substr($phone, 1) : $phone;
    }
}
