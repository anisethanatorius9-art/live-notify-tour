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
            'description' => 'Famous for its spectacular annual wildebeest migration.',
            'attractions' => ['Wildlife Safari', 'Great Migration', 'Photography'],
            'bestTime' => 'Jul-Oct',
            'rating' => 4.9
        ],
        [
            'name' => 'Ngorongoro Crater',
            'lat' => -3.2481,
            'lng' => 35.4875,
            'description' => 'A breathtaking volcanic caldera teeming with wildlife.',
            'attractions' => ['Crater Views', 'Lion Sightings', 'Hiking'],
            'bestTime' => 'Jan-Mar',
            'rating' => 4.8
        ],
        [
            'name' => 'Zanzibar Stone Town',
            'lat' => -6.1659,
            'lng' => 39.1990,
            'description' => 'Rich Swahili history, narrow alleys, and beautiful beaches.',
            'attractions' => ['Historic Walking Tour', 'Spice Tour', 'Beach Relaxation'],
            'bestTime' => 'Jun-Oct',
            'rating' => 4.7
        ],
        [
            'name' => 'Mount Kilimanjaro',
            'lat' => -3.0674,
            'lng' => 37.3556,
            'description' => 'Africa\'s highest peak offering challenging climbing routes.',
            'attractions' => ['Mountain Climbing', 'Scenic Views', 'Adventure'],
            'bestTime' => 'Jul-Sep',
            'rating' => 4.9
        ],
        [
            'name' => 'Lake Victoria',
            'lat' => -2.3473,
            'lng' => 33.8820,
            'description' => 'Africa\'s largest freshwater lake with stunning views.',
            'attractions' => ['Boat Tours', 'Fishing', 'Bird Watching'],
            'bestTime' => 'Jan-Mar',
            'rating' => 4.6
        ],
    ];

    public function render()
    {
        return view('livewire.tourism-map');
    }
}
