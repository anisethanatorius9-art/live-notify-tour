<?php

namespace App\Http\Controllers\Auth;

use App\Models\AdminAccessKey;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class AdminLoginController extends Controller
{
    /**
     * Handle email/password-based admin login (with optional access key).
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'admin_key' => ['nullable', 'string'],
        ]);

        // If an admin access key is provided, validate it
        if (! empty($data['admin_key'])) {
            return $this->loginWithAccessKey($data['admin_key'], $request);
        }

        // Standard email/password login
        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'role' => 'admin'], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('The provided credentials do not match our records or you do not have admin access.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.admin'));
    }

    /**
     * Handle admin login via access key.
     */
    protected function loginWithAccessKey(string $accessKey, Request $request): RedirectResponse
    {
        // First check config-level global access key
        $configKey = config('admin.login_key');
        if ($configKey && hash_equals($configKey, $accessKey)) {
            // Find the first active admin user
            $admin = User::where('role', 'admin')->first();

            if (! $admin) {
                throw ValidationException::withMessages([
                    'admin_key' => __('No admin account found.'),
                ]);
            }

            Auth::login($admin, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.admin'));
        }

        // Check per-user access keys stored in database
        $accessKeyRecord = AdminAccessKey::where('access_key', hash('sha256', $accessKey))->first();

        if (! $accessKeyRecord || ! $accessKeyRecord->isValid()) {
            throw ValidationException::withMessages([
                'admin_key' => __('The admin access key is invalid or has expired.'),
            ]);
        }

        $user = $accessKeyRecord->user;

        if (! $user || $user->role !== 'admin') {
            throw ValidationException::withMessages([
                'admin_key' => __('The admin access key is invalid.'),
            ]);
        }

        Auth::login($user, $request->boolean('remember'));

        // Record the key usage for audit trail
        $accessKeyRecord->recordUsage($request->ip(), $request->userAgent());

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.admin'));
    }
}
