<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Livewire\Auth\RoleSelection;
use App\Livewire\Dashboard\TouristDashboard;
use App\Livewire\Dashboard\ProviderDashboard;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\HelpPage;
use App\Livewire\Admin\CategoryManagement;
use App\Livewire\Admin\LocationManagement;
use App\Livewire\Admin\UserManagement;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\AdminRegisterController;
use App\Livewire\Auth\AdminRegisterVerify;
use App\Livewire\Bookings\CreateBooking;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['guest'])->group(function () {
    Route::view('/admin/login', 'livewire.auth.admin-login')->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');
    Route::get('/admin/register', [AdminRegisterController::class, 'show'])->name('admin.register');
    Route::post('/admin/register', [AdminRegisterController::class, 'store'])->name('admin.register.store');
    Route::get('/admin/register/verify', AdminRegisterVerify::class)->name('admin.register.verify');
});

// Smart Dashboard Route - redirects based on role
Route::middleware(['auth', 'verified', 'role.check'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Otherwise redirect to role-specific dashboard
        return match($user->role) {
            'tourist' => redirect()->route('dashboard.tourist'),
            'provider' => redirect()->route('dashboard.provider'),
            'admin' => redirect()->route('dashboard.admin'),
            default => redirect()->route('dashboard.tourist'),
        };
    })->name('dashboard');
});

// Tourist Routes
Route::middleware(['auth', 'verified', 'role.check', 'role:tourist'])->group(function () {
    Route::get('/dashboard/tourist', TouristDashboard::class)->name('dashboard.tourist');
    Route::name('bookings.')->group(function () {
        Route::get('/bookings', function () {
            return view('livewire.bookings.index');
        })->name('index');
        Route::get('/bookings/create/{service}', CreateBooking::class)->name('create');
    });
    Route::get('/locations', function () {
        return view('livewire.locations.tourist-index');
    })->name('locations.index');
    Route::get('/categories', function () {
        return view('livewire.categories.tourist-index');
    })->name('categories.index');
});

// Service Provider Routes
Route::middleware(['auth', 'verified', 'role.check', 'role:provider'])->group(function () {
    Route::get('/dashboard/provider', ProviderDashboard::class)->name('dashboard.provider');
    Route::name('services.')->group(function () {
        Route::get('/services', function () {
            return view('livewire.services.index');
        })->name('index');
        Route::get('/services/create', function () {
            return view('livewire.services.create');
        })->name('create');
        Route::get('/services/{service}/edit', function () {
            return view('livewire.services.edit');
        })->name('edit');
    });
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role.check', 'admin.check'])->group(function () {
    Route::get('/dashboard/admin', AdminDashboard::class)->name('dashboard.admin');

    // Admin management routes under /admin prefix with admin.* name prefix
    Route::prefix('admin')->name('admin.')->group(function () {
        // Users
        Route::name('users.')->group(function () {
            Route::get('/users', UserManagement::class)->name('index');
            Route::get('/users/create', function () {
                return view('livewire.users.create');
            })->name('create');
            Route::get('/users/{user}/edit', function () {
                return view('livewire.users.edit');
            })->name('edit');
        });

        // Locations
        Route::name('locations.')->group(function () {
            Route::get('/locations', LocationManagement::class)->name('index');
        });

        // Categories
        Route::name('categories.')->group(function () {
            Route::get('/categories', CategoryManagement::class)->name('index');
        });

        // Payments
        Route::name('payments.')->group(function () {
            Route::get('/payments', function () {
                return view('livewire.payments.index');
            })->name('index');
        });

        // Services
        Route::name('services.')->group(function () {
            Route::get('/services', function () {
                return view('livewire.services.index');
            })->name('index');
        });

        // Bookings
        Route::name('bookings.')->group(function () {
            Route::get('/bookings', function () {
                return view('livewire.bookings.index');
            })->name('index');
        });

        // Settings
        Route::name('settings.')->group(function () {
            Route::get('/settings', function () {
                return view('livewire.settings.index');
            })->name('index');
        });
    });
});

// Notifications - accessible to all authenticated users
Route::middleware(['auth', 'verified', 'role.check'])->group(function () {
    Route::get('/notifications', function () {
        return view('livewire.notifications.index');
    })->name('notifications.index');

    Route::get('/help', HelpPage::class)->name('help');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
