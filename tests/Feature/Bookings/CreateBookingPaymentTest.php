<?php

namespace Tests\Feature\Bookings;

use App\Livewire\Bookings\CreateBooking;
use App\Livewire\TourismMap;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Services\PayPalPaymentService;
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

    public function test_tourism_map_creates_a_transport_booking_from_a_route(): void
    {
        $tourist = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($tourist);
        $this->mock(PayPalPaymentService::class, function ($mock): void {
            $mock->shouldReceive('createOrder')->once()->andReturn([
                'url' => 'https://paypal.test/checkout',
                'order_id' => 'PAYPAL-TRANSPORT-1',
            ]);
        });

        Livewire::test(TourismMap::class)
            ->set('paymentMethod', 'paypal')
            ->call('setRoute', [
                'origin' => 'Arusha Airport',
                'destination' => 'Mount Meru Hotel',
                'originLatitude' => -3.363,
                'originLongitude' => 36.625,
                'destinationLatitude' => -3.386,
                'destinationLongitude' => 36.687,
                'distanceKm' => 9.4,
                'durationMinutes' => 24,
            ])
            ->call('createTransportBooking');

        $booking = Booking::where('tourist_id', $tourist->id)->latest()->first();

        $this->assertNotNull($booking);
        $this->assertSame('transport', $booking->booking_type);
        $this->assertSame('bolt', $booking->transport_type);
        $this->assertSame('Arusha Airport', $booking->origin);
        $this->assertSame('Mount Meru Hotel', $booking->destination);
        $this->assertSame('9490.00', (string) $booking->total_price);
        $this->assertDatabaseHas('payments', [
            'booking_id' => $booking->id,
            'status' => 'pending',
            'amount' => 9490.00,
            'gateway_transaction_id' => 'PAYPAL-TRANSPORT-1',
        ]);
    }
}
