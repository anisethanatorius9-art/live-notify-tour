<?php

namespace App\Livewire\Admin;

use App\Models\Booking;
use Livewire\Component;

class AdminBookingIndex extends Component
{
    public function render()
    {
        $bookings = Booking::with('service.location', 'service.provider')->latest()->get();
        return view('livewire.bookings.index', compact('bookings'));
    }
}
