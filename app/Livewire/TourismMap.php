<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PayPalPaymentService;
use App\Services\SelcomPaymentService;
use App\Services\TransportFareService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use RuntimeException;

class TourismMap extends Component
{
    public string $origin = '';
    public string $destination = '';
    public string $transportType = 'bolt';
    public string $bookingDate = '';
    public int $numberOfPeople = 1;
    public string $phone = '';
    public string $paymentMethod = 'mastercard';
    public ?float $distanceKm = null;
    public ?int $durationMinutes = null;
    public ?float $originLatitude = null;
    public ?float $originLongitude = null;
    public ?float $destinationLatitude = null;
    public ?float $destinationLongitude = null;
    public float $estimatedFare = 0;

    public $locations = [
        [
            'name' => 'Serengeti National Park',
            'lat' => -2.1540,
            'lng' => 34.6857,
            'description' => 'Famous for its spectacular annual wildebeest migration.',
            'attractions' => ['Wildlife Safari', 'Great Migration', 'Photography'],
            'bestTime' => 'Jul-Oct',
            'rating' => 4.9,
            'price' => '$100-500/day',
            'difficulty' => 'Moderate',
            'accommodation' => ['Safari Lodges', 'Camping', '5-Star Resorts'],
            'services' => ['Restaurants', 'Guides', 'Transportation'],
            'distance' => '340 km from Dar',
            'hours' => '7-8 hours drive'
        ],
        [
            'name' => 'Ngorongoro Crater',
            'lat' => -3.2481,
            'lng' => 35.4875,
            'description' => 'A breathtaking volcanic caldera teeming with wildlife.',
            'attractions' => ['Crater Views', 'Lion Sightings', 'Hiking'],
            'bestTime' => 'Jan-Mar',
            'rating' => 4.8,
            'price' => '$80-400/day',
            'difficulty' => 'Moderate to Hard',
            'accommodation' => ['Mountain Lodges', 'Ecolodges', 'Camps'],
            'services' => ['Guides', 'Picnic Areas', 'Photography Spots'],
            'distance' => '250 km from Dar',
            'hours' => '5-6 hours drive'
        ],
        [
            'name' => 'Zanzibar Stone Town',
            'lat' => -6.1659,
            'lng' => 39.1990,
            'description' => 'Rich Swahili history, narrow alleys, and beautiful beaches.',
            'attractions' => ['Historic Walking Tour', 'Spice Tour', 'Beach Relaxation'],
            'bestTime' => 'Jun-Oct',
            'rating' => 4.7,
            'price' => '$50-300/day',
            'difficulty' => 'Easy',
            'accommodation' => ['Beach Hotels', 'Riads', 'Resorts'],
            'services' => ['Restaurants', 'Spice Markets', 'Water Sports'],
            'distance' => '50 km from Dar (ferry)',
            'hours' => '2 hours ferry'
        ],
        [
            'name' => 'Mount Kilimanjaro',
            'lat' => -3.0674,
            'lng' => 37.3556,
            'description' => 'Africa\'s highest peak offering challenging climbing routes.',
            'attractions' => ['Mountain Climbing', 'Scenic Views', 'Adventure'],
            'bestTime' => 'Jul-Sep',
            'rating' => 4.9,
            'price' => '$1,500-5,000/trek',
            'difficulty' => 'Very Hard',
            'accommodation' => ['Mountain Huts', 'Base Camp Hotels'],
            'services' => ['Experienced Guides', 'Porters', 'Acclimatization Tours'],
            'distance' => '350 km from Dar',
            'hours' => '7-8 hours drive'
        ],
        [
            'name' => 'Lake Victoria',
            'lat' => -2.3473,
            'lng' => 33.8820,
            'description' => 'Africa\'s largest freshwater lake with stunning views.',
            'attractions' => ['Boat Tours', 'Fishing', 'Bird Watching'],
            'bestTime' => 'Jan-Mar',
            'rating' => 4.6,
            'price' => '$60-250/day',
            'difficulty' => 'Easy to Moderate',
            'accommodation' => ['Waterfront Hotels', 'Beach Resorts', 'Lodges'],
            'services' => ['Boat Rentals', 'Fish Markets', 'Local Restaurants'],
            'distance' => '280 km from Dar',
            'hours' => '5 hours drive'
        ],
    ];

    public function mount(): void
    {
        $this->bookingDate = now()->addDay()->toDateString();
        $this->phone = (string) (Auth::user()?->phone ?? '');
    }

    public function updatedTransportType(): void
    {
        $this->calculateFare(app(TransportFareService::class));
    }

    public function updatedNumberOfPeople(): void
    {
        $this->calculateFare(app(TransportFareService::class));
    }

    public function setRoute(array $route): void
    {
        $this->origin = trim((string) ($route['origin'] ?? ''));
        $this->destination = trim((string) ($route['destination'] ?? ''));
        $this->originLatitude = (float) ($route['originLatitude'] ?? 0);
        $this->originLongitude = (float) ($route['originLongitude'] ?? 0);
        $this->destinationLatitude = (float) ($route['destinationLatitude'] ?? 0);
        $this->destinationLongitude = (float) ($route['destinationLongitude'] ?? 0);
        $this->distanceKm = round((float) ($route['distanceKm'] ?? 0), 2);
        $this->durationMinutes = max(1, (int) ($route['durationMinutes'] ?? 1));
        $this->calculateFare(app(TransportFareService::class));
    }

    public function createTransportBooking(SelcomPaymentService $selcom, PayPalPaymentService $paypal): mixed
    {
        $this->validate([
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'originLatitude' => ['required', 'numeric', 'between:-90,90'],
            'originLongitude' => ['required', 'numeric', 'between:-180,180'],
            'destinationLatitude' => ['required', 'numeric', 'between:-90,90'],
            'destinationLongitude' => ['required', 'numeric', 'between:-180,180'],
            'distanceKm' => ['required', 'numeric', 'gt:0', 'max:5000'],
            'durationMinutes' => ['required', 'integer', 'min:1', 'max:10000'],
            'transportType' => ['required', 'in:bolt,standard_car,van'],
            'bookingDate' => ['required', 'date', 'after_or_equal:today'],
            'numberOfPeople' => ['required', 'integer', 'min:1', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
            'paymentMethod' => ['required', 'in:mastercard,paypal'],
        ]);

        $fareService = app(TransportFareService::class);
        $this->calculateFare($fareService);

        $booking = Booking::create([
            'tourist_id' => Auth::id(),
            'service_id' => null,
            'booking_type' => 'transport',
            'transport_type' => $this->transportType,
            'origin' => $this->origin,
            'destination' => $this->destination,
            'origin_latitude' => $this->originLatitude,
            'origin_longitude' => $this->originLongitude,
            'destination_latitude' => $this->destinationLatitude,
            'destination_longitude' => $this->destinationLongitude,
            'distance_km' => $this->distanceKm,
            'duration_minutes' => $this->durationMinutes,
            'booking_date' => $this->bookingDate,
            'booking_time' => now()->format('H:i:s'),
            'number_of_people' => $this->numberOfPeople,
            'total_price' => $this->estimatedFare,
            'notes' => 'Tourism Map transport booking',
            'status' => 'pending',
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => $this->estimatedFare,
            'payment_method' => $this->paymentMethod,
            'transaction_id' => 'TRN-' . strtoupper(Str::random(6)) . '-' . $booking->id,
            'status' => 'pending',
        ]);

        try {
            if ($this->paymentMethod === 'paypal') {
                $checkout = $paypal->createOrder($payment);
                $payment->update(['gateway_transaction_id' => $checkout['order_id']]);

                return redirect()->away($checkout['url']);
            }

            if (trim($this->phone) === '') {
                session()->flash('error', __('Add a payment phone number before starting checkout.'));
                return redirect()->route('tourism.map');
            }

            $checkout = $selcom->createOrder($payment, Auth::user(), $this->phone);
        } catch (RuntimeException $exception) {
            report($exception);
            session()->flash('error', __('Payment could not be started. Please try again later.'));
            return redirect()->route('tourism.map');
        }

        return redirect()->away($checkout['url']);
    }

    private function calculateFare(TransportFareService $fareService): void
    {
        $this->estimatedFare = $this->distanceKm && $this->distanceKm > 0
            ? $fareService->calculate($this->transportType, $this->distanceKm, $this->numberOfPeople)
            : 0;
    }

    public function render()
    {
        $transportOptions = app(TransportFareService::class)->options();

        foreach ($transportOptions as $key => &$option) {
            $option['quote'] = $this->distanceKm && $this->distanceKm > 0
                ? app(TransportFareService::class)->calculate($key, $this->distanceKm, $this->numberOfPeople)
                : null;
        }

        return view('livewire.tourism-map', [
            'transportOptions' => $transportOptions,
        ]);
    }
}
