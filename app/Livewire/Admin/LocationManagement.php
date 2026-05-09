<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Location;

class LocationManagement extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $description = '';
    public $latitude = '';
    public $longitude = '';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1000',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ];

    public function render()
    {
        $locations = Location::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->paginate(10);

        return view('livewire.admin.location-management', [
            'locations' => $locations,
        ]);
    }

    public function create()
    {
        $this->reset(['editingId', 'name', 'description', 'latitude', 'longitude']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $location = Location::findOrFail($id);
        $this->editingId = $id;
        $this->name = $location->name;
        $this->description = $location->description;
        $this->latitude = $location->latitude;
        $this->longitude = $location->longitude;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $location = Location::findOrFail($this->editingId);
            $location->update([
                'name' => $this->name,
                'description' => $this->description,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]);
            $this->dispatch('notify', message: 'Location updated successfully!');
        } else {
            Location::create([
                'name' => $this->name,
                'description' => $this->description,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ]);
            $this->dispatch('notify', message: 'Location created successfully!');
        }

        $this->showModal = false;
        $this->resetPage();
    }

    public function delete($id)
    {
        Location::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Location deleted successfully!');
        $this->resetPage();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'description', 'latitude', 'longitude']);
    }
}
