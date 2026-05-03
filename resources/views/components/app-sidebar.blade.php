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

    <flux:sidebar.search placeholder="Search..." class="m-4" />

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

        {{-- Tourist Routes --}}
        @if($user->role === 'tourist')
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

        {{-- Service Provider Routes --}}
        @if($user->role === 'provider')
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

        {{-- Admin Routes --}}
        @if($user->role === 'admin')
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
        <flux:sidebar.item icon="cog-6-tooth" href="#" class="rounded-lg">Settings</flux:sidebar.item>
        <flux:sidebar.item icon="information-circle" href="#" class="rounded-lg">Help</flux:sidebar.item>
    </flux:sidebar.nav>

    {{-- User Profile Dropdown --}}
    <flux:dropdown position="top" align="start" class="max-lg:hidden px-3 pb-3">
        <flux:sidebar.profile
            :avatar="$user->profile_photo ?? 'https://fluxui.dev/img/demo/user.png'"
            :name="$user->name" />

        <flux:menu>
            <flux:menu.item icon="user">Profile</flux:menu.item>
            <flux:menu.item icon="cog-6-tooth">Settings</flux:menu.item>
            <flux:menu.separator />

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <flux:menu.item type="button" onclick="this.closest('form').submit();" icon="arrow-right-start-on-rectangle">
                    Logout
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>
