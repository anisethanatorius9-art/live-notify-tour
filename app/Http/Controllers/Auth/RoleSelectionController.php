<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectionController extends Controller
{
    public function index()
    {
        $roles = [
            'tourist' => [
                'name' => 'Tourist',
                'description' => 'Explore locations, view services, book tours and experiences',
                'icon' => '🗺️',
                'color' => 'blue',
            ],
            'provider' => [
                'name' => 'Service Provider',
                'description' => 'Offer services, manage bookings, and grow your business',
                'icon' => '🏢',
                'color' => 'green',
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'Manage users, services, and system operations',
                'icon' => '⚙️',
                'color' => 'red',
            ],
        ];

        return view('pages.auth.role-selection', compact('roles'));
    }

    public function store(Request $request)
    {
        $roles = ['tourist', 'provider', 'admin'];

        $request->validate([
            'role' => ['required', 'in:'.implode(',', $roles)],
        ]);

        $user = Auth::user();
        $user->update([
            'role' => $request->role,
            'role_selected_at' => now(),
        ]);

        return match ($request->role) {
            'tourist' => redirect()->route('dashboard.tourist'),
            'provider' => redirect()->route('dashboard.provider'),
            'admin' => redirect()->route('dashboard.admin'),
        };
    }
}
