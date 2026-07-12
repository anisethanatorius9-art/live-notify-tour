<?php

namespace App\Livewire;

use Livewire\Component;

class TourismMap extends Component
{
    public $locations = [
        [
            'name' => 'Serengeti National Park',
            'lat' => -2.1540,
            'lng' => 34.6857,
            'description' => 'Famous for its spectacular annual wildebeest migration.'
        ],
        [
            'name' => 'Ngorongoro Crater',
            'lat' => -3.2481,
            'lng' => 35.4875,
            'description' => 'A breathtaking volcanic caldera teeming with wildlife.'
        ],
        [
            'name' => 'Zanzibar Stone Town',
            'lat' => -6.1659,
            'lng' => 39.1990,
            'description' => 'Rich Swahili history, narrow alleys, and beautiful beaches.'
        ],
    ];

    public function render()
    {
        return view('livewire.tourism-map');
    }
}
