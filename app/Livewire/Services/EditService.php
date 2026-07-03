<?php

namespace App\Livewire\Services;

use App\Models\Category;
use App\Models\Location;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditService extends Component
{
    public Service $service;
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

    public function mount(Service $service): void
    {
        abort_if($service->provider_id !== Auth::id(), 403);

        $this->service = $service;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->location_id = $service->location_id;
        $this->category = $service->category;
        $this->price = $service->price;
        $this->status = $service->status;
        $this->loadOptions();
    }

    public function loadOptions(): void
    {
        $this->locations = Location::orderBy('name')->get();
        $this->categories = Category::orderBy('name')->get();
    }

    public function updateService()
    {
        $validated = $this->validate();

        $this->service->update($validated);

        session()->flash('success', __('Service updated successfully.'));

        return redirect()->route('services.index');
    }

    public function render()
    {
        return view('livewire.services.edit');
    }
}
