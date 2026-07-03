<?php

namespace App\Livewire\Admin;

use App\Models\Notification;
use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function approvePayment(int $paymentId): void
    {
        $payment = Payment::with(['booking.service', 'booking.tourist'])->findOrFail($paymentId);

        if ($payment->status !== 'pending') {
            $this->dispatch('notify', message: 'Only pending payments can be approved.', type: 'error');
            return;
        }

        $payment->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $booking = $payment->booking;

        if ($booking && $booking->status === 'pending') {
            $booking->update(['status' => 'confirmed']);
        }

        if ($booking && $booking->tourist) {
            Notification::create([
                'user_id' => $booking->tourist->id,
                'title' => 'Booking approved',
                'message' => "Your booking for {$booking->service->name} on {$booking->booking_date->format('d M Y')} has been approved.",
                'type' => 'booking',
                'is_read' => false,
            ]);
        }

        $this->dispatch('notify', message: 'Payment approved and booking confirmed.', type: 'success');
        $this->resetPage();
    }

    public function rejectPayment(int $paymentId): void
    {
        $payment = Payment::with(['booking.service', 'booking.tourist'])->findOrFail($paymentId);

        if ($payment->status !== 'pending') {
            $this->dispatch('notify', message: 'Only pending payments can be rejected.', type: 'error');
            return;
        }

        $payment->update([
            'status' => 'failed',
        ]);

        if ($payment->booking && $payment->booking->tourist) {
            Notification::create([
                'user_id' => $payment->booking->tourist->id,
                'title' => 'Booking rejected',
                'message' => "Your booking for {$payment->booking->service->name} could not be confirmed. Please contact support.",
                'type' => 'booking',
                'is_read' => false,
            ]);
        }

        $this->dispatch('notify', message: 'Payment rejected.', type: 'success');
        $this->resetPage();
    }

    public function render()
    {
        $paymentsQuery = Payment::with(['booking.service', 'booking.tourist', 'user']);

        if ($this->search) {
            $paymentsQuery->where(function ($query) {
                $query->where('transaction_id', 'like', "%{$this->search}%")
                    ->orWhereHas('booking', function ($q) {
                        $q->whereHas('service', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
                    })
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterStatus) {
            $paymentsQuery->where('status', $this->filterStatus);
        }

        $payments = $paymentsQuery->latest()->paginate(10);

        return view('livewire.admin.payment-management', compact('payments'));
    }
}
