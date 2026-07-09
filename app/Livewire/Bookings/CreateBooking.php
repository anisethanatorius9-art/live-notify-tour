<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateBooking extends Component
{
    public ?Service $service = null;
    public $bookingDate;
    public $numberOfPeople = 1;
    public $totalPrice = 0;
    public $notes = '';

    public function mount(Service $service)
    {
        $this->service = $service;
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

    public function createBooking()
    {
        $this->validate([
            'bookingDate' => 'required|date|after:today',
            'numberOfPeople' => 'required|integer|min:1|max:50',
            'notes' => 'nullable|string|max:1000',
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
            'payment_method' => 'manual',
            'transaction_id' => 'PAY-' . strtoupper(Str::random(6)) . '-' . $booking->id,
            'status' => 'pending',
        ]);

        session()->flash('success', __('Booking created successfully! Total: ') . number_format($this->totalPrice, 2));
        return redirect()->route('bookings.index');
    }

    public function render()
    {
        return view('livewire.bookings.create');
    }
}
