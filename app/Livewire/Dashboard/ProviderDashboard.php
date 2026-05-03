<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class ProviderDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleServiceStatus($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service && $service->provider_id === Auth::id()) {
            $service->update([
                'status' => $service->status === 'active' ? 'inactive' : 'active'
            ]);
            $this->dispatch('notify', message: 'Service status updated');
        }
    }

    public function deleteService($serviceId)
    {
        $service = Service::find($serviceId);
        if ($service && $service->provider_id === Auth::id()) {
            $service->delete();
            $this->dispatch('notify', message: 'Service deleted');
        }
    }

    public function render()
    {
        $user = Auth::user();

        // Get provider's services
        $servicesQuery = $user->services();

        if ($this->search) {
            $servicesQuery->where('name', 'like', "%{$this->search}%");
        }

        $services = $servicesQuery->paginate(10);

        // Get bookings for provider's services
        $bookingsQuery = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('provider_id', $user->id);
        })->with('tourist', 'service');

        if ($this->filterStatus) {
            $bookingsQuery->where('status', $this->filterStatus);
        }

        $bookings = $bookingsQuery->latest()->paginate(10);

        // Statistics
        $totalServices = $user->services()->count();
        $activeServices = $user->services()->where('status', 'active')->count();
        $pendingBookings = Booking::whereHas('service', function ($q) use ($user) {
            $q->where('provider_id', $user->id);
        })->where('status', 'pending')->count();
        $totalEarnings = $user->services()
            ->join('bookings', 'services.id', '=', 'bookings.service_id')
            ->where('bookings.status', 'completed')
            ->sum('bookings.total_price') ?? 0;

        return view('livewire.dashboard.provider-dashboard', [
            'services' => $services,
            'bookings' => $bookings,
            'totalServices' => $totalServices,
            'activeServices' => $activeServices,
            'pendingBookings' => $pendingBookings,
            'totalEarnings' => $totalEarnings,
        ]);
    }
}
