<?php

namespace Tests\Feature\Bookings;

use App\Livewire\Bookings\CreateBooking;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateBookingPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_creation_creates_a_pending_payment(): void
    {
        $tourist = User::factory()->create(['role' => 'tourist']);
        $provider = User::factory()->create(['role' => 'provider']);
        $service = Service::factory()->create([
            'provider_id' => $provider->id,
            'price' => 120.50,
        ]);

        $this->actingAs($tourist);

        Livewire::test(CreateBooking::class, ['service' => $service])
            ->set('bookingDate', now()->addDay()->toDateString())
            ->set('numberOfPeople', 2)
            ->call('createBooking');

        $booking = Booking::where('tourist_id', $tourist->id)->latest()->first();

        $this->assertNotNull($booking);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'user_id' => $tourist->id,
            'status' => 'pending',
            'amount' => 241.00,
        ]);
    }
}
