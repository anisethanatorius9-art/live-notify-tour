<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TouristDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedLocation = null;
    public $filterCategory = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedLocation' => ['except' => null],
        'filterCategory' => ['except' => null],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Get available services based on filters
        $servicesQuery = Service::with(['provider', 'location'])
            ->where('status', 'active');

        if ($this->search) {
            $servicesQuery->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->selectedLocation) {
            $servicesQuery->where('location_id', $this->selectedLocation);
        }

        if ($this->filterCategory) {
            $servicesQuery->where('category', $this->filterCategory);
        }

        $services = $servicesQuery->paginate(6);

        // Get user's bookings
        $bookings = $user->bookings()
            ->with('service.provider', 'service.location')
            ->latest()
            ->take(5)
            ->get();

        // Get unread notifications
        $unreadNotifications = $user->notifications()
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        // Get all locations for filter
        $locations = \App\Models\Location::pluck('name', 'id')->toArray();

        // Get all categories - convert to key=>value pairs
        $categoryArray = Service::distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();
        $categories = array_combine($categoryArray, $categoryArray);

        return view('livewire.dashboard.tourist-dashboard', [
            'services' => $services,
            'bookings' => $bookings,
            'unreadNotifications' => $unreadNotifications,
            'locations' => $locations,
            'categories' => $categories,
            'totalBookings' => $user->bookings()->count(),
            'totalNotifications' => $user->notifications()->count(),
        ]);
    }
}
