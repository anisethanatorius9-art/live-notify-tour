<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\Service;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
            $this->totalPrice = $this->numberOfPeople * $this->service->price;
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

        session()->flash('success', __('Booking created successfully! Total: ') . number_format($this->totalPrice, 2));
        return redirect()->route('bookings.index');
    }

    public function render()
    {
        return view('livewire.bookings.create');
    }
}
