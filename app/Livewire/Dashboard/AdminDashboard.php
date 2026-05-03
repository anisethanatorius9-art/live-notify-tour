<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\Payment;

class AdminDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function toggleUserStatus($userId)
    {
        // Implement user activation/deactivation logic
        $this->dispatch('notify', message: 'User status updated');
    }

    public function deleteUser($userId)
    {
        // Implement user deletion logic with proper authorization
        $this->dispatch('notify', message: 'User deleted');
    }

    public function render()
    {
        // System Statistics
        $totalUsers = User::count();
        $totalTourists = User::where('role', 'tourist')->count();
        $totalProviders = User::where('role', 'provider')->count();
        $totalServices = Service::count();
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount') ?? 0;
        $completedBookings = Booking::where('status', 'completed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

        // Users Management
        $usersQuery = User::query();

        if ($this->search) {
            $usersQuery->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        if ($this->filterRole) {
            $usersQuery->where('role', $this->filterRole);
        }

        $users = $usersQuery->latest()->paginate(15);

        // Recent Services
        $recentServices = Service::with('provider', 'location')
            ->latest()
            ->take(8)
            ->get();

        // Recent Bookings
        $recentBookings = Booking::with('tourist', 'service')
            ->latest()
            ->take(8)
            ->get();

        // Recent Payments
        $recentPayments = Payment::with('user', 'booking.service')
            ->latest()
            ->take(8)
            ->get();

        return view('livewire.dashboard.admin-dashboard', [
            'totalUsers' => $totalUsers,
            'totalTourists' => $totalTourists,
            'totalProviders' => $totalProviders,
            'totalServices' => $totalServices,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'completedBookings' => $completedBookings,
            'pendingBookings' => $pendingBookings,
            'users' => $users,
            'recentServices' => $recentServices,
            'recentBookings' => $recentBookings,
            'recentPayments' => $recentPayments,
        ]);
    }
}
