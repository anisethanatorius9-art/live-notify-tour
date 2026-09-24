<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use App\Services\SelcomPaymentService;
use App\Services\PayPalPaymentService;
use RuntimeException;

class CreateBooking extends Component
{
    public ?Service $service = null;
    public $bookingDate;
    public $numberOfPeople = 1;
    public $totalPrice = 0;
    public $notes = '';
    public string $phone = '';
    public string $paymentMethod = 'mastercard';

    public function mount(Service $service)
    {
        $this->service = $service;
        $this->phone = (string) (Auth::user()?->phone ?? '');
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        if ($this->service) {
            $this->totalPrice = round($this->numberOfPeople * $this->service->price, 2);
        }
    }

    public function updatedNumberOfPeople()
    {
        $this->calculateTotal();
    }

    public function createBooking(SelcomPaymentService $selcom, PayPalPaymentService $paypal)
    {
        $this->validate([
            'bookingDate' => 'required|date|after:today',
            'numberOfPeople' => 'required|integer|min:1|max:50',
            'notes' => 'nullable|string|max:1000',
            'phone' => ['nullable', 'string', 'max:20'],
            'paymentMethod' => ['required', 'in:mastercard,paypal'],
        ]);

        $booking = Booking::create([
            'tourist_id' => Auth::id(),
            'service_id' => $this->service->id,
            'booking_date' => $this->bookingDate,
            'booking_time' => now()->format('H:i:s'),
            'number_of_people' => $this->numberOfPeople,
            'total_price' => $this->totalPrice,
            'notes' => $this->notes,
            'status' => 'pending',
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => round($this->totalPrice, 2),
            'payment_method' => $this->paymentMethod,
            'transaction_id' => 'PAY-' . strtoupper(Str::random(6)) . '-' . $booking->id,
            'status' => 'pending',
        ]);

        try {
            if ($this->paymentMethod === 'paypal') {
                $checkout = $paypal->createOrder($booking->payment);
                $booking->payment->update(['gateway_transaction_id' => $checkout['order_id']]);

                return redirect()->away($checkout['url']);
            }

            if (trim($this->phone) === '') {
                session()->flash('error', __('Add a payment phone number before starting checkout.'));
                return redirect()->route('bookings.index');
            }

            $checkout = $selcom->createOrder($booking->payment, Auth::user(), $this->phone);
        } catch (RuntimeException $exception) {
            report($exception);
            session()->flash('error', __('Payment could not be started. Please try again later.'));
            return redirect()->route('bookings.index');
        }

        return redirect()->away($checkout['url']);
    }

    public function render()
    {
        return view('livewire.bookings.create');
    }
}
