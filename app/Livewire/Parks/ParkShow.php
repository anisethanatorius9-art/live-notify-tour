<?php

namespace App\Livewire\Parks;

use Livewire\Component;

class ParkShow extends Component
{
    public string $parkKey;
    public array $parkData = [];

    public function mount($park)
    {
        $this->parkKey = $park;
        $parks = config('parks', []);
        if (! isset($parks[$park])) {
            abort(404);
        }
        $this->parkData = $parks[$park];
    }

    public function render()
    {
        return view('livewire.parks.show', [
            'park' => $this->parkData,
        ]);
    }
}
