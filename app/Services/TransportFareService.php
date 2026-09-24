<?php

namespace App\Services;

class TransportFareService
{
    /**
     * @return array<string, array{label: string, description: string, base: float, per_km: float, minimum: float}>
     */
    public function options(): array
    {
        return [
            'bolt' => [
                'label' => 'Bolt ride',
                'description' => 'A private car picked up from your location.',
                'base' => 1500,
                'per_km' => 850,
                'minimum' => 3500,
            ],
            'standard_car' => [
                'label' => 'Standard car',
                'description' => 'Comfortable private transport for up to 4 people.',
                'base' => 1800,
                'per_km' => 1000,
                'minimum' => 4500,
            ],
            'van' => [
                'label' => 'Tour van',
                'description' => 'More space for groups and luggage.',
                'base' => 5000,
                'per_km' => 1400,
                'minimum' => 9000,
            ],
        ];
    }

    public function calculate(string $transportType, float $distanceKm, int $passengers = 1): float
    {
        $option = $this->options()[$transportType] ?? $this->options()['bolt'];
        $passengerSurcharge = max(0, $passengers - 4) * 500;

        return round(max(
            $option['minimum'],
            $option['base'] + ($distanceKm * $option['per_km']) + $passengerSurcharge
        ), 2);
    }
}
