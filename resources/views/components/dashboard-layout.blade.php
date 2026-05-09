<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white text-zinc-900 dark:bg-zinc-800 dark:text-white antialiased">
    <x-app-sidebar />

    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts
    @livewireScripts
</body>

</html>
