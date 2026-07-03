<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TouristDashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $selectedLocation = null;
    public ?string $filterCategory = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedLocation' => ['except' => null],
        'filterCategory' => ['except' => null],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedLocation()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
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

        // Load curated parks from config/parks.php early so we can map images to services
        $parks = config('parks', []);

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

        $services = $servicesQuery->paginate(6)->withPath('/dashboard/tourist');

        // If a service is a Park (or mentions a park name), map a real park image from config
        if (! empty($parks) && $services->count()) {
            // build lowercase park name => image map
            $parkNameMap = [];
            foreach ($parks as $slug => $p) {
                if (! empty($p['name']) && ! empty($p['image_url'])) {
                    $parkNameMap[strtolower($p['name'])] = $p['image_url'];
                }
            }

            foreach ($services as $svc) {
                try {
                    $svcName = strtolower($svc->name ?? '');
                    $svcCategory = strtolower($svc->category ?? '');

                    // only override if no image_url already set
                    if (empty($svc->image_url)) {
                        // direct category match
                        if ($svcCategory === 'park') {
                            // try exact park name match first
                            foreach ($parkNameMap as $parkName => $img) {
                                if (strpos($svcName, $parkName) !== false || $svcName === $parkName) {
                                    $svc->image_url = $img;
                                    break;
                                }
                            }
                        } else {
                            // Some services may mention park name in title; try to match park names inside service name
                            foreach ($parkNameMap as $parkName => $img) {
                                if (strpos($svcName, $parkName) !== false) {
                                    $svc->image_url = $img;
                                    break;
                                }
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    // ignore mapping errors
                }
            }
        }

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

        // Country flags mapping for locations (basic mapping by location name)
        $flags = [
            'Nairobi' => 'KE',
            'Mombasa' => 'KE',
            'Zanzibar' => 'TZ',
            'Kigali' => 'RW',
            'Dar es Salaam' => 'TZ',
            'Entebbe' => 'UG',
            'Arusha' => 'TZ',
            'Serengeti' => 'TZ',
        ];

        // Load curated parks from config/parks.php
        $parks = config('parks', []);

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
            'flags' => $flags,
            'parks' => $parks,
            'totalBookings' => $user->bookings()->count(),
            'totalNotifications' => $user->notifications()->count(),
        ]);
    }
}
