<x-layouts.app>
    <div class="min-h-screen bg-gray-50 dark:bg-zinc-950">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Services') }}</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Create and manage services so visitors can enjoy the platform.') }}</p>
                </div>
                <flux:button href="{{ route('admin.services.create') }}" wire:navigate icon="plus" variant="primary">
                    {{ __('Add Service') }}
                </flux:button>
            </div>

            <flux:card class="mb-6 p-4 sm:p-6">
                <div class="grid gap-4 md:grid-cols-3">
                    <flux:input wire:model.live="search" type="search" :placeholder="__('Search services...')" icon="magnifying-glass" class="md:col-span-2" />

                    <flux:select wire:model.live="filterLocation" class="w-full">
                        <flux:select.option value="">{{ __('All Locations') }}</flux:select.option>
                        @foreach($locations as $locationId => $locationName)
                            <flux:select.option value="{{ $locationId }}">{{ $locationName }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:select wire:model.live="filterCategory" class="w-full">
                        <flux:select.option value="">{{ __('All Categories') }}</flux:select.option>
                        @foreach($categories as $categoryKey => $categoryLabel)
                            <flux:select.option value="{{ $categoryKey }}">{{ $categoryLabel }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </flux:card>

            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-800">
                    <thead class="bg-gray-50 dark:bg-zinc-800/70">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">{{ __('Name') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">{{ __('Provider') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">{{ __('Location') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">{{ __('Category') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-zinc-400">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $service->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-zinc-300">{{ $service->provider->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-zinc-300">{{ $service->location->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600 dark:text-zinc-300">{{ $service->category }}</td>
                                <td class="px-4 py-4 text-sm">
                                    <flux:badge :color="$service->status === 'active' ? 'green' : 'yellow'" size="sm">
                                        {{ ucfirst($service->status) }}
                                    </flux:badge>
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium">
                                    <flux:button href="{{ route('admin.services.edit', $service) }}" size="sm" variant="ghost" icon="pencil" wire:navigate />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-zinc-400">
                                    {{ __('No services found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
