<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="shortcut icon" href="/favicon.svg?v=3" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/favicon.svg?v=3">

    @fluxAppearance

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-800 dark:text-white antialiased">
    {{-- Sidebar Navigation --}}
    <x-app-sidebar />

    {{-- Mobile Header --}}
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="start">
            <flux:profile :avatar="auth()->user()->profile_photo ?? 'https://fluxui.dev/img/demo/user.png'" />

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
    </flux:header>

    {{-- Main Content --}}
    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
    @livewireScripts
</body>
</html>
