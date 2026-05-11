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

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard*')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>

                @auth
                @if(auth()->user()->role === 'tourist')
                <flux:navlist.group :heading="__('Tourism')" class="grid">
                    <flux:navlist.item icon="magnifying-glass" href="#" :current="request()->routeIs('services.*')" wire:navigate>
                        {{ __('Services') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="calendar" :href="route('bookings.index')" :current="request()->routeIs('bookings.*')" wire:navigate>
                        {{ __('My Bookings') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="bell" :href="route('notifications.index')" :current="request()->routeIs('notifications.*')" wire:navigate>
                        {{ __('Notifications') }}
                    </flux:navlist.item>
                </flux:navlist.group>
                @elseif(auth()->user()->role === 'provider')
                <flux:navlist.group :heading="__('Business')" class="grid">
                    <flux:navlist.item icon="cube" :href="route('services.index')" :current="request()->routeIs('services.*')" wire:navigate>
                        {{ __('My Services') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="document" href="#" wire:navigate>
                        {{ __('Create Service') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="calendar-check" href="#" wire:navigate>
                        {{ __('Bookings') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="currency-dollar" href="#" wire:navigate>
                        {{ __('Earnings') }}
                    </flux:navlist.item>
                </flux:navlist.group>
                @elseif(auth()->user()->role === 'admin')
                <flux:navlist.group :heading="__('Administration')" class="grid">
                    <flux:navlist.item icon="users" :href="route('admin.users.index')" :current="request()->routeIs('admin.users.*')" wire:navigate>
                        {{ __('Users') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="map-pin" :href="route('admin.locations.index')" :current="request()->routeIs('admin.locations.*')" wire:navigate>
                        {{ __('Locations') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="cube" :href="route('admin.services.index')" :current="request()->routeIs('admin.services.*')" wire:navigate>
                        {{ __('Services') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="calendar" :href="route('admin.bookings.index')" :current="request()->routeIs('admin.bookings.*')" wire:navigate>
                        {{ __('Bookings') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="credit-card" :href="route('admin.payments.index')" :current="request()->routeIs('admin.payments.*')" wire:navigate>
                        {{ __('Payments') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="bell" :href="route('notifications.index')" :current="request()->routeIs('notifications.*')" wire:navigate>
                        {{ __('Notifications') }}
                    </flux:navlist.item>
                    <flux:navlist.item icon="cog-6-tooth" :href="route('admin.settings.index')" :current="request()->routeIs('admin.settings.*')" wire:navigate>
                        {{ __('Settings') }}
                    </flux:navlist.item>
                </flux:navlist.group>
                @endif
                @endauth
            </flux:navlist>

            <flux:spacer />



            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button" />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
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
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <div class="flex-1 flex flex-col">
            <!-- Mobile User Menu -->
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
                                        <span
                                            class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
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
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                                {{ __('Log Out') }}</flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:header>

            <div class="flex-1">
                {{ $slot }}
            </div>
        </div>
    </div>
@else
    <div class="min-h-screen">
        <div class="flex-1">
            {{ $slot }}
        </div>
    </div>
@endif

    @fluxScripts
</body>

</html>
