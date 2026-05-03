<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterRole = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updateUserRole($userId, $role)
    {
        $user = User::findOrFail($userId);
        $authUser = Auth::user();

        // Prevent users from changing their own admin status
        if ($authUser && $authUser->id === $userId && $authUser->role === 'admin' && $role !== 'admin') {
            $this->dispatch('notify', message: 'Cannot remove your own admin role', type: 'error');
            return;
        }

        // Validate role
        if (!in_array($role, ['tourist', 'provider', 'admin'])) {
            $this->dispatch('notify', message: 'Invalid role selected', type: 'error');
            return;
        }

        $user->update(['role' => $role]);
        $this->dispatch('notify', message: "User role updated to {$role}", type: 'success');
    }

    public function deleteUser($userId)
    {
        $authUser = Auth::user();

        // Prevent deleting yourself
        if ($authUser && $authUser->id === $userId) {
            $this->dispatch('notify', message: 'You cannot delete your own account', type: 'error');
            return;
        }

        $user = User::findOrFail($userId);
        $user->delete();
        $this->dispatch('notify', message: 'User deleted successfully', type: 'success');
    }

    public function toggleSort($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = User::query();

        // Search by name or email
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        // Filter by role
        if ($this->filterRole) {
            $query->where('role', $this->filterRole);
        }

        // Sort
        $query->orderBy($this->sortBy, $this->sortDirection);

        $users = $query->paginate(15);

        $stats = [
            'total' => User::count(),
            'tourists' => User::where('role', 'tourist')->count(),
            'providers' => User::where('role', 'provider')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return view('livewire.admin.user-management', compact('users', 'stats'));
    }
}
