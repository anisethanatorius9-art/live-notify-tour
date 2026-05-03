<x-layouts.app>
    <div class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header with Icon -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center text-white">
                        <!-- Heroicons: Map Pin -->
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Explore Locations') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Browse amazing destinations') }}</p>
                    </div>
                </div>
            </div>

            <!-- Locations Table -->
            @php
                $locations = \App\Models\Location::all()->map(fn($location) => [
                    'id' => $location->id,
                    'name' => $location->name,
                    'count' => $location->services()->count(),
                ]);
            @endphp

            @if(count($locations) > 0)
                <flux:card class="overflow-hidden">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>{{ __('Location') }}</flux:table.column>
                            <flux:table.column class="text-center">{{ __('Services Available') }}</flux:table.column>
                            <flux:table.column class="text-end">{{ __('Action') }}</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($locations as $location)
                            <flux:table.row>
                                <flux:table.cell>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-green-400 to-green-600 rounded-lg flex-shrink-0 flex items-center justify-center text-white">
                                            <!-- Heroicons: Map Pin -->
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $location['name'] }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell class="text-center">
                                    <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $location['count'] }} services
                                    </span>
                                </flux:table.cell>
                                <flux:table.cell class="text-end">
                                    <flux:button href="{{ route('dashboard.tourist') }}?selectedLocation={{ $location['id'] }}" size="sm" icon="magnifying-glass">
                                        {{ __('Browse') }}
                                    </flux:button>
                                </flux:table.cell>
                            </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                </flux:card>
            @else
                <flux:card class="text-center py-16">
                    <div class="space-y-4">
                        <div class="flex justify-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ __('No locations found') }}</p>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>
</x-layouts.app>
