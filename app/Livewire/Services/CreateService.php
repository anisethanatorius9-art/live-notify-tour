<?php

namespace App\Livewire\Services;

use App\Models\Category;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateService extends Component
{
    public string $name = '';
    public string $description = '';
    public ?int $location_id = null;
    public string $category = '';
    public string $price = '';
    public string $status = 'active';
    /** @var \Illuminate\Database\Eloquent\Collection|array */
    public $locations;
    /** @var \Illuminate\Database\Eloquent\Collection|array */
    public $categories;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'location_id' => 'required|exists:locations,id',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function mount(): void
    {
        $this->loadOptions();
    }

    public function loadOptions(): void
    {
        $this->locations = Location::orderBy('name')->get();
        $this->categories = Category::orderBy('name')->get();
    }

    public function createService()
    {
        $validated = $this->validate();

        Service::create([
            'provider_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'],
            'location_id' => $validated['location_id'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        session()->flash('success', __('Service created successfully.'));

        return redirect()->route('services.index');
    }

    public function render()
    {
        return view('livewire.services.create');
    }
}
