<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

@props(['hideSidebar' => false])

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-800 dark:text-white">
    @if (! $hideSidebar)
    <div class="flex min-h-screen">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

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
                @auth
                @if(auth()->user()->role === 'tourist')
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
                        badge="{{ auth()->user()->notifications->count() }}"
                        href="{{ route('notifications.index') }}"
                        :current="request()->routeIs('notifications.*')"
                        class="rounded-lg">
                        Notifications
                    </flux:sidebar.item>
                </div>
                @endif

                {{-- Service Provider Routes --}}
                @if(auth()->user()->role === 'provider')
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
                        badge="{{ auth()->user()->notifications->count() }}"
                        href="{{ route('notifications.index') }}"
                        :current="request()->routeIs('notifications.*')"
                        class="rounded-lg">
                        Notifications
                    </flux:sidebar.item>
                </div>
                @endif

                {{-- Admin Routes --}}
                @if(auth()->user()->role === 'admin')
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
                            icon="cube"
                            href="{{ route('admin.services.index') }}"
                            :current="request()->routeIs('admin.services.*')"
                            class="rounded-lg">
                            Services
                        </flux:sidebar.item>

                        <flux:sidebar.item
                            icon="calendar"
                            href="{{ route('admin.bookings.index') }}"
                            :current="request()->routeIs('admin.bookings.*')"
                            class="rounded-lg">
                            Bookings
                        </flux:sidebar.item>

                        <flux:sidebar.item
                            icon="bell"
                            href="{{ route('notifications.index') }}"
                            :current="request()->routeIs('notifications.*')"
                            class="rounded-lg">
                            Notifications
                        </flux:sidebar.item>

                        <flux:sidebar.item
                            icon="cog-6-tooth"
                            href="{{ route('admin.settings.index') }}"
                            :current="request()->routeIs('admin.settings.*')"
                            class="rounded-lg">
                            Settings
                        </flux:sidebar.item>
                    </flux:sidebar.group>
                </div>
                @endif
                @endauth
            </flux:sidebar.nav>

            <flux:sidebar.spacer />

            <flux:sidebar.nav class="space-y-1 px-3 pb-4 border-t border-zinc-200 dark:border-zinc-700 pt-4">
                <flux:sidebar.item icon="cog-6-tooth" href="{{ route('profile.edit') }}" wire:navigate class="rounded-lg">Settings</flux:sidebar.item>
                <flux:sidebar.item icon="information-circle" href="{{ route('help') }}" wire:navigate class="rounded-lg">Help</flux:sidebar.item>
            </flux:sidebar.nav>

            {{-- User Profile Dropdown --}}
            @auth
            <flux:dropdown position="top" align="start" class="max-lg:hidden px-3 pb-3">
                <flux:sidebar.profile
                    :avatar="auth()->user()->profile_photo ?? 'https://fluxui.dev/img/demo/user.png'"
                    :name="auth()->user()->name" />

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

        <div class="flex-1 flex flex-col">
            {{-- Mobile User Menu --}}
            <flux:header class="lg:hidden">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

                <flux:spacer />

                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down" />

                    <flux:menu>
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                        <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                            {{ auth()->user()->initials() }}
                                        </span>
                                    </span>

                                    <div class="grid flex-1 text-start text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">{{ __('Log Out') }}</flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:header>

            <flux:main>
                {{ $slot }}
            </flux:main>
        </div>
    </div>
    @else
    <flux:main>
        {{ $slot }}
    </flux:main>
    @endif

    @fluxScripts
</body>

</html>