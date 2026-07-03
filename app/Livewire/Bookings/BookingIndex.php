<?php

namespace App\Livewire\Bookings;

use App\Models\Booking;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class BookingIndex extends Component
{
    public function deleteBooking(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);

        // Ensure user can only delete their own bookings
        abort_if($booking->tourist_id !== Auth::id(), 403);

        // Only allow deletion of pending bookings
        if ($booking->status !== 'pending') {
            $this->dispatch('notify', message: __('Only pending bookings can be deleted'), type: 'error');
            return;
        }

        $booking->delete();
        $this->dispatch('notify', message: __('Booking deleted successfully'), type: 'success');
        $this->redirect(route('bookings.index'));
    }

    public function render()
    {
        /** @var User $user */
        $user = Auth::user();
        $bookings = $user->bookings()->with('service.location', 'service.provider')->latest()->get();
        return view('livewire.bookings.index', compact('bookings'));
    }
}
