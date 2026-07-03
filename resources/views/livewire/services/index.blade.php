<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ __('Services') }}</h1>
                    <p class="text-gray-600 mt-2">{{ __('Manage all services in the system.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <flux:input
                    wire:model.live="search"
                    type="search"
                    :placeholder="__('Search services...')"
                    icon="magnifying-glass"
                    class="md:col-span-2" />

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

            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Name') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Provider') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Location') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Category') }}</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($services as $service)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">{{ $service->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $service->provider->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $service->location->name }}</td>
                                <td class="px-4 py-4 text-sm text-gray-600">{{ $service->category }}</td>
                                <td class="px-4 py-4 text-sm">
                                    <flux:badge :color="$service->status === 'active' ? 'green' : 'yellow'" size="sm">
                                        {{ ucfirst($service->status) }}
                                    </flux:badge>
                                </td>
                                <td class="px-4 py-4 text-right text-sm font-medium">
                                    <flux:button
                                        href="{{ route('admin.services.edit', $service) }}"
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        wire:navigate />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
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
