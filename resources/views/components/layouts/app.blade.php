@props(['hideSidebar' => false])

<x-layouts.app.sidebar :title="$title ?? null" :hide-sidebar="$hideSidebar">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
