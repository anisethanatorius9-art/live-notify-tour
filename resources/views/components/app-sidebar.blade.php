@props(['user' => auth()->user()])

<flux:sidebar sticky collapsible="mobile" class="bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.header class="px-4 py-6 border-b border-zinc-200 dark:border-zinc-700">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-lg">
                LN
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-gray-900 dark:text-white text-sm">LNT</h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">Live & Notify</p>
            </div>
        </div>

        <flux:sidebar.collapse class="lg:hidden mt-2" />
    </flux:sidebar.header>

    <form action="{{ route('dashboard') }}" method="GET" class="m-4">
        <label for="sidebar-search" class="sr-only">{{ __('Search') }}</label>
        <div class="relative">
            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-zinc-400">
                <flux:icon class="size-4" icon="magnifying-glass" variant="outline" />
            </span>
            <input
                id="sidebar-search"
                name="search"
                type="search"
                value="{{ request('search') }}"
                placeholder="{{ __('Search parks, services...') }}"
                class="h-10 w-full rounded-lg border border-zinc-200 bg-white px-10 pr-12 text-sm text-zinc-700 placeholder:text-zinc-400 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100"
            />
            <button type="submit" class="absolute inset-y-0 end-0 flex items-center justify-center px-3 text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-white">
                <flux:icon class="size-4" icon="magnifying-glass" variant="outline" />
            </button>
        </div>
    </form>

    <flux:sidebar.nav class="space-y-2 px-3">
        {{-- Dashboard Section --}}
        <div class="mt-2 mb-6">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Platform</p>
            <flux:sidebar.item
                icon="home"
                href="{{ route('dashboard') }}"
                :current="request()->routeIs('dashboard*')"
                class="rounded-lg">
                Dashboard
            </flux:sidebar.item>
        </div>

        {{-- Tourist Routes - Added nullsafe check ?-> --}}
        @if($user?->role === 'tourist')
        <div class="mb-6">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Tourism</p>
            <flux:sidebar.item
                icon="calendar"
                href="{{ route('bookings.index') }}"
                :current="request()->routeIs('bookings.*')"
                class="rounded-lg">
                My Bookings
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="map-pin"
                href="{{ route('locations.index') }}"
                :current="request()->routeIs('locations.*')"
                class="rounded-lg">
                Locations
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="tag"
                href="{{ route('categories.index') }}"
                :current="request()->routeIs('categories.*')"
                class="rounded-lg">
                Categories
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="bell"
                badge="{{ $user->notifications->count() }}"
                href="{{ route('notifications.index') }}"
                :current="request()->routeIs('notifications.*')"
                class="rounded-lg">
                Notifications
            </flux:sidebar.item>
        </div>
        @endif

        {{-- Service Provider Routes - Added nullsafe check ?-> --}}
        @if($user?->role === 'provider')
        <div class="mb-6">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Services</p>
            <flux:sidebar.item
                icon="briefcase"
                href="{{ route('services.index') }}"
                :current="request()->routeIs('services.*')"
                class="rounded-lg">
                My Services
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="chart-bar"
                href="{{ route('services.index') }}"
                :current="request()->routeIs('bookings.*')"
                class="rounded-lg">
                Service Bookings
            </flux:sidebar.item>

            <flux:sidebar.item
                icon="bell"
                badge="{{ $user->notifications->count() }}"
                href="{{ route('notifications.index') }}"
                :current="request()->routeIs('notifications.*')"
                class="rounded-lg">
                Notifications
            </flux:sidebar.item>
        </div>
        @endif

        {{-- Admin Routes - Added nullsafe check ?-> --}}
        @if($user?->role === 'admin')
        <div class="mb-6">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Management</p>
            <flux:sidebar.group expandable heading="Admin" class="rounded-lg space-y-1">
                <flux:sidebar.item
                    icon="users"
                    href="{{ route('admin.users.index') }}"
                    :current="request()->routeIs('admin.users.*')"
                    class="rounded-lg">
                    Users
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="map-pin"
                    href="{{ route('admin.locations.index') }}"
                    :current="request()->routeIs('admin.locations.*')"
                    class="rounded-lg">
                    Locations
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="currency-dollar"
                    href="{{ route('admin.payments.index') }}"
                    :current="request()->routeIs('admin.payments.*')"
                    class="rounded-lg">
                    Payments
                </flux:sidebar.item>

                <flux:sidebar.item
                    icon="bell"
                    href="{{ route('notifications.index') }}"
                    :current="request()->routeIs('notifications.*')"
                    class="rounded-lg">
                    Notifications
                </flux:sidebar.item>
            </flux:sidebar.group>
        </div>
        @endif
    </flux:sidebar.nav>

    <flux:sidebar.spacer />

    <flux:sidebar.nav class="space-y-1 px-3 pb-4 border-t border-zinc-200 dark:border-zinc-700 pt-4">
        <flux:sidebar.item icon="cog-6-tooth" href="{{ route('profile.edit') }}" wire:navigate class="rounded-lg">Settings</flux:sidebar.item>
        <flux:sidebar.item icon="information-circle" href="{{ route('help') }}" wire:navigate class="rounded-lg">Help</flux:sidebar.item>
    </flux:sidebar.nav>

    {{-- User Profile Dropdown - Only show if user is logged in --}}
    @auth
    <flux:dropdown position="top" align="start" class="max-lg:hidden px-3 pb-3">
        <flux:sidebar.profile
            :avatar="$user->profile_photo ?? 'https://fluxui.dev/img/demo/user.png'"
            :name="$user->name" />

        <flux:menu>
            <flux:menu.item icon="user" href="{{ route('profile.edit') }}">Profile</flux:menu.item>
            <flux:menu.item icon="cog-6-tooth" href="{{ route('profile.edit') }}">Settings</flux:menu.item>
            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:menu.item type="button" onclick="this.closest('form').submit();" icon="arrow-right-start-on-rectangle">
                    Logout
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
    @else
    <div class="px-3 pb-3">
        <flux:sidebar.item icon="arrow-right-end-on-rectangle" href="{{ route('login') }}" class="rounded-lg">
            Login
        </flux:sidebar.item>
    </div>
    @endauth
</flux:sidebar>
