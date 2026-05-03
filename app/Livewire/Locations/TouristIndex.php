<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Livewire\Component;

class TouristIndex extends Component
{
    public function render()
    {
        return view('livewire.locations.tourist-index', [
            'locations' => Location::all()->map(fn($location) => [
                'id' => $location->id,
                'name' => $location->name,
                'count' => $location->services()->count(),
            ]),
        ]);
    }
}
