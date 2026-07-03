<?php

namespace App\Livewire\Services;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use App\Models\Location;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ServiceIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterLocation = '';
    public $filterCategory = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterLocation' => ['except' => ''],
        'filterCategory' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterLocation()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function toggleServiceStatus(int $serviceId): void
    {
        $service = Service::find($serviceId);

        if (! $service) {
            return;
        }

        if ($service->provider_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $service->update([
            'status' => $service->status === 'active' ? 'inactive' : 'active',
        ]);

        $this->dispatch('notify', message: __('Service status updated.'));
    }

    public function deleteService(int $serviceId): void
    {
        $service = Service::find($serviceId);

        if (! $service) {
            return;
        }

        if ($service->provider_id !== Auth::id() && Auth::user()->role !== 'admin') {
            abort(403);
        }

        $service->delete();

        $this->dispatch('notify', message: __('Service deleted.'));
        $this->resetPage();
    }

    public function render()
    {
        $query = Service::with(['location', 'provider']);

        if (Auth::user()->role === 'provider') {
            $query->where('provider_id', Auth::id());
        }

        if ($this->search) {
            $query->where(fn ($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhere('category', 'like', "%{$this->search}%")
            );
        }

        if ($this->filterLocation) {
            $query->where('location_id', $this->filterLocation);
        }

        if ($this->filterCategory) {
            $query->where('category', $this->filterCategory);
        }

        $services = $query->latest()->paginate(10);

        return view('livewire.services.index', [
            'services' => $services,
            'locations' => Location::pluck('name', 'id')->toArray(),
            'categories' => Category::pluck('name', 'name')->toArray(),
        ]);
    }
}
