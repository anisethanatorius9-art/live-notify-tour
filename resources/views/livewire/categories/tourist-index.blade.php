<x-layouts.app>
    <div class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header with Icon -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center text-white">
                        <!-- Heroicons: Tag -->
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 2a1 1 0 00-.894.553L7.382 4H4a2 2 0 000 4h.118l1.07 14.546a2 2 0 001.971 1.834h12.882a2 2 0 001.971-1.834L19.882 8H20a2 2 0 000-4h-3.382l-.724-1.447A1 1 0 0015 2H9z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Browse by Category') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Find services by what interests you') }}</p>
                    </div>
                </div>
            </div>

            <!-- Categories Table -->
            @php
                $categories = \App\Models\Service::distinct()
                    ->pluck('category')
                    ->filter()
                    ->values()
                    ->map(fn($category) => [
                        'name' => $category,
                        'count' => \App\Models\Service::where('category', $category)->count(),
                        'icon' => $category,
                    ]);
            @endphp

            @if(count($categories) > 0)
                <flux:card class="overflow-hidden">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>{{ __('Category') }}</flux:table.column>
                            <flux:table.column class="text-center">{{ __('Services Available') }}</flux:table.column>
                            <flux:table.column class="text-end">{{ __('Action') }}</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($categories as $category)
                            <flux:table.row>
                                <flux:table.cell>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-600 rounded-lg flex-shrink-0 flex items-center justify-center text-white">
                                            @if($category['name'] === 'Tour')
                                                <!-- Heroicons: Briefcase -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.256A4.256 4.256 0 0019.591 9H4.409A4.256 4.256 0 003 13.256V19a2 2 0 002 2h16a2 2 0 002-2v-5.744z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V7a2 2 0 012-2h2a2 2 0 012 2v2M9 13h6" />
                                                </svg>
                                            @elseif($category['name'] === 'Activity')
                                                <!-- Heroicons: Rocket Launch -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            @elseif($category['name'] === 'Accommodation')
                                                <!-- Heroicons: Building Office 2 -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                                                    <polyline points="17 21 17 13 7 13 7 21" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    <polyline points="7 5 7 13 17 13 17 5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                </svg>
                                            @elseif($category['name'] === 'Transport')
                                                <!-- Heroicons: Truck -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    <circle cx="8" cy="19" r="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                    <circle cx="20" cy="19" r="2" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                </svg>
                                            @elseif($category['name'] === 'Dining')
                                                <!-- Heroicons: Utensils -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 002.332 2.25c1.191 0 2.25-.75 2.448-1.628M9 12l2-8m5.618 0A3 3 0 0015.75 15H11.371m0-5h4.002c.291 0 .556.144.646.426.07.192.032.385-.064.531L12.5 19m6-14h2.25a2.25 2.25 0 012.25 2.25v2.25H21V9a2.25 2.25 0 00-2.25-2.25h-.375zM11 19h1.946l-1.618-4.854" />
                                                </svg>
                                            @else
                                                <!-- Heroicons: Sparkles (Entertainment) -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5a4 4 0 100-8 4 4 0 000 8z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $category['name'] }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell class="text-center">
                                    <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $category['count'] }} services
                                    </span>
                                </flux:table.cell>
                                <flux:table.cell class="text-end">
                                    <flux:button href="{{ route('dashboard.tourist') }}?filterCategory={{ $category['name'] }}" size="sm" icon="magnifying-glass">
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
                                <path d="M9 2a1 1 0 00-.894.553L7.382 4H4a2 2 0 000 4h.118l1.07 14.546a2 2 0 001.971 1.834h12.882a2 2 0 001.971-1.834L19.882 8H20a2 2 0 000-4h-3.382l-.724-1.447A1 1 0 0015 2H9z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ __('No categories found') }}</p>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>
</x-layouts.app>
