<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class RoleSelection extends Component
{
    public $selectedRole = null;

    public $loadingConfirmRole = false;

    public $roles = [
        'tourist' => [
            'name' => 'Tourist',
            'description' => 'Explore locations, view services, book tours and experiences',
            'icon' => 'map-pin',
            'color' => 'blue',
            'features' => [
                'Discover amazing destinations and attractions',
                'Browse and book tours and activities',
                'Read reviews and ratings from other travelers',
                'Secure payment and booking management',
                'Create wishlist of favorite places',
                'Get notifications about special offers',
            ],
        ],
    ];

    public function selectRole($role)
    {
        if (! isset($this->roles[$role])) {
            return $this->addError('role', 'Invalid role selected');
        }

        $this->selectedRole = $role;
        $this->resetValidation('role'); // Clear error when valid role selected
    }

    public function confirmRole()
    {
        if (! $this->selectedRole) {
            $this->addError('role', 'Please select a role');

            return;
        }

        // Only allow tourist role to be selected directly by users
        if ($this->selectedRole !== 'tourist') {
            $this->addError('role', 'You can only select the Tourist role. Contact an administrator to become a Service Provider.');

            return;
        }

        $this->loadingConfirmRole = true;
        $this->resetValidation('role');

        /** @var User $user */
        $user = Auth::user();
        if (! $user) {
            $this->loadingConfirmRole = false;

            return redirect()->route('login');
        }

        $user->update([
            'role' => 'tourist',
            'role_selected_at' => now(),
        ]);

        $this->loadingConfirmRole = false;

        // Redirect to tourist dashboard
        return redirect()->route('dashboard.tourist');
    }

    public function render()
    {
        return view('livewire.auth.role-selection');
    }
}
