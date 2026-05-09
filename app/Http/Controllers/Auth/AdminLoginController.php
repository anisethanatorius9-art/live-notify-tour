<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'admin_key' => ['nullable', 'string'],
        ]);

        $adminKey = config('admin.login_key');
        if ($adminKey && $data['admin_key'] !== $adminKey) {
            throw ValidationException::withMessages([
                'admin_key' => __('The admin access key is invalid.'),
            ]);
        }

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'admin'], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records or you do not have admin access.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.admin'));
    }
}
