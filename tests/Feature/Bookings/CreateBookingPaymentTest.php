<?php

namespace Tests\Feature\Bookings;

use App\Livewire\Bookings\CreateBooking;
use App\Livewire\TourismMap;
use App\Mail\BookingReceiptEmail;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Services\PayPalPaymentService;
use App\Services\SelcomPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    public function test_tourism_map_recalculates_the_fare_when_transport_is_changed(): void
    {
        $tourist = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($tourist);
        $this->mock(PayPalPaymentService::class, function ($mock): void {
            $mock->shouldReceive('createOrder')->once()->andReturn([
                'url' => 'https://paypal.test/checkout',
                'order_id' => 'PAYPAL-VAN-1',
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
            ->set('transportType', 'van')
            ->set('numberOfPeople', 6)
            ->assertSet('estimatedFare', 19160.0)
            ->call('createTransportBooking');

        $this->assertDatabaseHas('bookings', [
            'tourist_id' => $tourist->id,
            'booking_type' => 'transport',
            'transport_type' => 'van',
            'number_of_people' => 6,
            'total_price' => 19160.00,
        ]);
    }

    public function test_successful_paypal_payment_emails_a_receipt_for_a_regular_booking(): void
    {
        Mail::fake();
        $tourist = User::factory()->create(['role' => 'tourist']);
        $provider = User::factory()->create(['role' => 'provider']);
        $service = Service::factory()->create(['provider_id' => $provider->id]);
        $booking = Booking::create([
            'tourist_id' => $tourist->id,
            'service_id' => $service->id,
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00:00',
            'number_of_people' => 2,
            'total_price' => 241,
            'status' => 'pending',
        ]);
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $tourist->id,
            'amount' => 241,
            'payment_method' => 'paypal',
            'transaction_id' => 'PAY-REGULAR-1',
            'gateway_transaction_id' => 'ORDER-REGULAR-1',
            'status' => 'pending',
        ]);

        $this->mock(PayPalPaymentService::class, function ($mock): void {
            $mock->shouldReceive('captureOrder')->once()->with('ORDER-REGULAR-1')->andReturnTrue();
        });

        $this->actingAs($tourist)
            ->get(route('payments.paypal.success', ['booking' => $booking, 'token' => 'ORDER-REGULAR-1']))
            ->assertRedirect(route('bookings.show', $booking));

        Mail::assertSent(BookingReceiptEmail::class, fn (BookingReceiptEmail $mail): bool =>
            $mail->hasTo($tourist->email)
            && $mail->payment->is($payment)
            && $mail->payment->booking->number_of_people === 2
        );
    }

    public function test_successful_selcom_payment_emails_a_transport_receipt(): void
    {
        Mail::fake();
        $tourist = User::factory()->create(['role' => 'tourist']);
        $booking = Booking::create([
            'tourist_id' => $tourist->id,
            'service_id' => null,
            'booking_type' => 'transport',
            'transport_type' => 'bolt',
            'origin' => 'Arusha Airport',
            'destination' => 'Mount Meru Hotel',
            'booking_date' => now()->addDay()->toDateString(),
            'booking_time' => '10:00:00',
            'number_of_people' => 3,
            'total_price' => 9490,
            'status' => 'pending',
        ]);
        Payment::create([
            'booking_id' => $booking->id,
            'user_id' => $tourist->id,
            'amount' => 9490,
            'payment_method' => 'mastercard',
            'transaction_id' => 'TRN-TRANSPORT-1',
            'status' => 'pending',
        ]);

        $this->mock(SelcomPaymentService::class, function ($mock): void {
            $mock->shouldReceive('verifyWebhook')->once()->andReturnTrue();
        });

        $this->postJson(route('payments.selcom.webhook'), [
            'order_id' => 'TRN-TRANSPORT-1',
            'payment_status' => 'COMPLETED',
            'resultcode' => '000',
        ])->assertOk();

        Mail::assertSent(BookingReceiptEmail::class, fn (BookingReceiptEmail $mail): bool =>
            $mail->hasTo($tourist->email)
            && $mail->payment->booking->booking_type === 'transport'
            && $mail->payment->booking->number_of_people === 3
        );
    }
}
