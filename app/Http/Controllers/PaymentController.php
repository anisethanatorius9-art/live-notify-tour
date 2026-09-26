<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\SelcomPaymentService;
use App\Services\PayPalPaymentService;
use App\Mail\BookingReceiptEmail;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class PaymentController extends Controller
{
    public function paypalSuccess(Request $request, Booking $booking, PayPalPaymentService $paypal): mixed
    {
        abort_if($booking->tourist_id !== $request->user()->id, 403);
        /** @var Payment $payment */
        $payment = $booking->payment()->where('status', 'pending')->firstOrFail();

        if ($request->filled('token') && $request->string('token')->toString() === $payment->gateway_transaction_id
            && $paypal->captureOrder($payment->gateway_transaction_id)) {
            DB::transaction(function () use ($payment, $booking): void {
                $payment->update(['status' => 'completed', 'paid_at' => now()]);
                $booking->update(['status' => 'confirmed']);
            });
            $this->emailReceipt($payment->fresh());

            return redirect()->route('bookings.show', $booking)->with('success', 'PayPal payment completed.');
        }

        return redirect()->route('bookings.index')->with('error', 'PayPal payment was not completed.');
    }

    public function paypalCancel(Request $request, Booking $booking): mixed
    {
        abort_if($booking->tourist_id !== $request->user()->id, 403);

        return redirect()->route('bookings.index')->with('error', 'PayPal payment was cancelled.');
    }

    public function start(Request $request, Booking $booking, SelcomPaymentService $selcom): mixed
    {
        abort_if($booking->tourist_id !== $request->user()->id, 403);

        $data = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);
        /** @var Payment $payment */
        $payment = $booking->payment()->where('status', 'pending')->firstOrFail();

        try {
            $checkout = $selcom->createOrder($payment, $request->user(), $data['phone']);
        } catch (RuntimeException $exception) {
            Log::error('Selcom checkout creation failed', ['error' => $exception->getMessage()]);

            return back()->withErrors(['payment' => 'Payment could not be started. Please try again later.']);
        }

        return redirect()->away($checkout['url']);
    }

    public function webhook(Request $request, SelcomPaymentService $selcom): JsonResponse
    {
        $signedFields = (string) $request->header('Signed-Fields');
        $timestamp = (string) $request->header('Timestamp');
        $digest = (string) $request->header('Digest');

        if (! $selcom->verifyWebhook($request->all(), $timestamp, $signedFields, $digest)) {
            return response()->json(['resultcode' => '401', 'result' => 'FAIL'], 401);
        }

        $data = $request->validate([
            'order_id' => ['required', 'string'],
            'transid' => ['nullable', 'string'],
            'reference' => ['nullable', 'string'],
            'payment_status' => ['required', 'string'],
            'resultcode' => ['nullable', 'string'],
        ]);

        $completed = in_array(strtoupper($data['payment_status']), ['COMPLETED', 'COMPLETE'], true)
            && ($data['resultcode'] ?? '000') === '000';

        $completedPaymentId = DB::transaction(function () use ($data, $completed): ?int {
            /** @var Payment|null $payment */
            $payment = Payment::where('transaction_id', $data['order_id'])->lockForUpdate()->first();

            if (! $payment || ! $payment->isPending()) {
                return null;
            }

            $payment->update([
                'status' => $completed ? 'completed' : 'failed',
                'paid_at' => $completed ? now() : null,
            ]);

            if ($completed && $payment->booking?->status === 'pending') {
                $payment->booking->update(['status' => 'confirmed']);
            }

            return $completed ? $payment->id : null;
        });

        if ($completedPaymentId !== null) {
            $this->emailReceipt(Payment::findOrFail($completedPaymentId));
        }

        return response()->json(['resultcode' => '000', 'result' => 'SUCCESS']);
    }

    private function emailReceipt(Payment $payment): void
    {
        $payment->loadMissing('booking.tourist', 'booking.service');
        $email = $payment->booking?->tourist?->email;

        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new BookingReceiptEmail($payment));
        } catch (Throwable $exception) {
            Log::error('Booking receipt email could not be sent.', [
                'payment_id' => $payment->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
