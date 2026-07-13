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

    public function render()
    {
        return view('livewire.tourism-map');
    }
}
