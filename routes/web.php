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
use App\Http\Controllers\Auth\SocialAuthController;
use App\Livewire\Auth\AdminPhoneLogin;
use App\Livewire\Auth\AdminRegisterVerify;
use App\Livewire\Bookings\CreateBooking;
use App\Livewire\Bookings\BookingIndex;
use App\Livewire\Admin\AdminBookingIndex;
use App\Models\Booking;
use App\Models\User;
use App\Models\Location;
use App\Models\Service;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['guest'])->group(function () {
    Route::view('/admin/login', 'livewire.auth.admin-login')->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store'])->name('admin.login.store');
    Route::get('/admin/register', [AdminRegisterController::class, 'show'])->name('admin.register');
    Route::post('/admin/register', [AdminRegisterController::class, 'store'])->name('admin.register.store');
    Route::get('/admin/register/verify', AdminRegisterVerify::class)->name('admin.register.verify');

    // Phone-based admin OTP login
    Route::get('/admin/login/phone', AdminPhoneLogin::class)->name('admin.login.phone');

    // User login routes
    Route::get('/login/google', [SocialAuthController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('/login/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');
    Route::get('/login/phone', \App\Livewire\Auth\UserPhoneLogin::class)->name('login.phone');
});

// Smart Dashboard Route - redirects based on role
Route::middleware(['auth', 'verified', 'role.check'])->group(function () {
    Route::get('/dashboard', function () {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $query = request()->query();

        // Otherwise redirect to role-specific dashboard with query string preserved
        return match($user->role) {
            'tourist' => redirect()->route('dashboard.tourist', $query),
            'provider' => redirect()->route('dashboard.provider', $query),
            'admin' => redirect()->route('dashboard.admin', $query),
            default => redirect()->route('dashboard.tourist', $query),
        };
    })->name('dashboard');
});

// Tourist Routes
Route::middleware(['auth', 'verified', 'role.check', 'role:tourist'])->group(function () {
    Route::get('/dashboard/tourist', TouristDashboard::class)->name('dashboard.tourist');
    // Park detail page
    Route::get('/parks/{park}', \App\Livewire\Parks\ParkShow::class)->name('parks.show');
    Route::name('bookings.')->group(function () {
        Route::get('/bookings', BookingIndex::class)->name('index');
        Route::get('/bookings/create/{service}', CreateBooking::class)->name('create');
        // Create or find a Service for a park and redirect to standard booking flow
        Route::get('/bookings/create/park/{park}', function ($park) {
            $parks = config('parks', []);
            if (! isset($parks[$park])) {
                abort(404);
            }

            $data = $parks[$park];

            // Find a provider user (seeded) or first provider
            /** @var User|null $provider */
            $provider = User::where('role', 'provider')->first();
            if (!$provider) {
                abort(500, 'No provider user available for park bookings');
            }
            assert($provider instanceof User);

            // Ensure location exists
            /** @var Location $location */
            $location = Location::firstOrCreate(['name' => $data['location']]);

            // Create or update a Service representing this park
            $service = Service::firstOrCreate(
                ['name' => $data['name']],
                [
                    'provider_id' => $provider->id,
                    'location_id' => $location->id,
                    'description' => $data['name'] . ' - Park experience',
                    'price' => $data['price'],
                    'category' => 'Park',
                    'status' => 'active',
                    'rating' => 5,
                    'image_url' => $data['image_url'],
                ]
            );

            return redirect()->route('bookings.create', $service);
        })->name('create.park');
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
        Route::get('/services', \App\Livewire\Services\ServiceIndex::class)->name('index');
            Route::get('/services/create', \App\Livewire\Services\ServiceForm::class)->name('create');
            Route::get('/services/{service}/edit', \App\Livewire\Services\ServiceForm::class)->name('edit');
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
            Route::get('/payments', \App\Livewire\Admin\PaymentManagement::class)->name('index');
        });

        // Services
        Route::name('services.')->group(function () {
            Route::get('/services', \App\Livewire\Services\ServiceIndex::class)->name('index');
            Route::get('/services/{service}/edit', \App\Livewire\Services\ServiceForm::class)->name('edit');
        });

        // Bookings
        Route::name('bookings.')->group(function () {
            Route::get('/bookings', AdminBookingIndex::class)->name('index');
            Route::get('/bookings/{booking}', function (Booking $booking) {
                $booking->load('service.location', 'service.provider');
                return view('livewire.bookings.show', compact('booking'));
            })->name('show');
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

    // Booking details - accessible to owner (tourist) or admin
    Route::get('/bookings/{booking}', function (Booking $booking) {
        $user = auth()->user();
        abort_if(!$user || ($booking->tourist_id !== $user->id && $user->role !== 'admin'), 403);
        $booking->load('service.location', 'service.provider');
        return view('livewire.bookings.show', compact('booking'));
    })->name('bookings.show');
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
