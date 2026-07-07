<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased dark:bg-zinc-950 dark:text-white">
        <div class="flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 sm:py-10 md:px-10 md:py-12">
            <div class="w-full max-w-md overflow-hidden rounded-[2rem] border border-zinc-200/70 bg-white/95 p-0 shadow-2xl shadow-zinc-900/10 backdrop-blur-xl ring-1 ring-zinc-100 dark:border-zinc-800/70 dark:bg-zinc-950/95 dark:ring-zinc-800/40">
                <div class="bg-white/90 p-6 sm:p-8 dark:bg-zinc-950/90">
                    <div class="flex flex-col gap-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3 text-base font-semibold text-zinc-900 transition hover:text-blue-600 dark:text-white dark:hover:text-blue-400" wire:navigate>
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-200">
                                <img src="/logo.svg" alt="LTN" class="h-7 w-auto" />
                            </span>
                            <span>{{ config('app.name', 'Laravel') }}</span>
                        </a>

                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
        @livewireScripts
    </body>
</html>
