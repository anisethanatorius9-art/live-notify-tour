<?php

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

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
        $this->dispatch('notify', message: 'User status updated');
    }

    public function deleteUser($userId)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($userId);

        if ($authUser && $authUser->id === $user->id) {
            $this->dispatch('notify', message: 'You cannot delete your own account', type: 'error');
            return;
        }

        DB::transaction(function () use ($user): void {
            Notification::where('user_id', $user->id)->delete();

            $bookings = Booking::where('tourist_id', $user->id)->get();
            foreach ($bookings as $booking) {
                Payment::where('booking_id', $booking->id)->delete();
                $booking->delete();
            }

            $services = Service::where('provider_id', $user->id)->get();
            foreach ($services as $service) {
                Booking::where('service_id', $service->id)->get()->each(function (Booking $booking): void {
                    Payment::where('booking_id', $booking->id)->delete();
                    $booking->delete();
                });
                $service->delete();
            }

            $user->delete();
        });

        $this->resetPage();
        $this->dispatch('notify', message: 'User deleted successfully', type: 'success');
    }

    public function create()
    {
        $this->dispatch('notify', message: 'Unable to perform create action here.', type: 'warning');
    }

    public function render()
    {
        $totalUsers = User::count();
        $totalTourists = User::where('role', 'tourist')->count();
        $totalProviders = User::where('role', 'provider')->count();
        $totalServices = Service::count();
        $totalBookings = Booking::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount') ?? 0;
        $completedBookings = Booking::where('status', 'completed')->count();
        $pendingBookings = Booking::where('status', 'pending')->count();

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

        $recentServices = Service::with('provider', 'location')
            ->latest()
            ->take(8)
            ->get();

        $recentBookings = Booking::with('tourist', 'service')
            ->latest()
            ->take(8)
            ->get();

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
