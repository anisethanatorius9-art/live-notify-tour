@props(['hideSidebar' => false])

<x-layouts.app.sidebar :title="$title ?? null" :hide-sidebar="$hideSidebar">
    {{ $slot }}
</x-layouts.app.sidebar>