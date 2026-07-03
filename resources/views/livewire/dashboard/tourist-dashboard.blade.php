<x-layouts.app :title="__('Tourist Dashboard')">
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white border-b border-gray-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
                <div class="py-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">
                                {{ __('Welcome') }}, {{ Auth::user()->name }}
                            </h1>
                            <p class="text-gray-600 mt-2">{{ __('Explore amazing services and experiences') }}</p>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-2">
                                <p class="text-sm text-blue-600 font-medium">{{ $totalBookings }} {{ __('Bookings') }}</p>
                            </div>
                            <div class="bg-orange-50 border border-orange-200 rounded-lg px-4 py-2">
                                <p class="text-sm text-orange-600 font-medium">{{ $totalNotifications }} {{ __('Updates') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-8">
                <!-- Search and Filters -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-8">
                    <div class="space-y-4">
                        <input
                            wire:model.live="search"
                            type="search"
                            placeholder="{{ __('Search services, locations...') }}"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition" />

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <select
                                wire:model.live="selectedLocation"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 appearance-none">
                                <option value="">{{ __('All Locations') }}</option>
                                @foreach($locations as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <select
                                wire:model.live="filterCategory"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 dark:border-gray-700 appearance-none">
                                <option value="">{{ __('All Categories') }}</option>
                                @foreach($categories as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Services Grid -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ __('Available Services') }}
                    </h2>

                    @if($services->count())
                    <div class="overflow-x-auto py-4">
                        <div class="flex gap-4 w-max">
                            @foreach($services as $service)
                            <div class="w-72 bg-white rounded-lg border border-gray-200 p-3 hover:shadow transition-shadow flex-shrink-0">
                                <div class="h-32 rounded-md overflow-hidden bg-gray-100">
                                    @php
                                    $category = strtolower($service->category ?? '');
                                    $placeholderMap = [
                                    'tour' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80',
                                    'accommodation' => 'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
                                    'dining' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                                    'meal' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=80',
                                    'park' => 'https://images.unsplash.com/photo-1525011268546-5a0b3cee4d78?auto=format&fit=crop&w=1200&q=80',
                                    ];
                                    $placeholder = $placeholderMap[$category] ?? 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80';
                                    $img = $service->image_url ?: $placeholder;
                                    @endphp
                                    <img src="{{ $img }}" alt="{{ $service->name }}" class="w-full h-full object-cover" />
                                </div>

                                <div class="mt-2">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900">{{ $service->name }}</h3>
                                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                                @php $flagCode = $flags[$service->location->name] ?? null; @endphp
                                                @if($flagCode)
                                                <img src="https://flagcdn.com/16x12/{{ strtolower($flagCode) }}.png" alt="{{ $flagCode }}" class="inline-block" />
                                                @endif
                                                {{ $service->location->name }}
                                            </p>
                                        </div>
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ $service->category }}</span>
                                    </div>

                                    <p class="text-xs text-gray-600 mt-2 line-clamp-3">{{ $service->description }}</p>

                                    <div class="flex items-center justify-between mt-3">
                                        <div>
                                            <div class="text-base font-bold text-gray-900">
                                                @php $symbol = $service->currency ?? '$'; @endphp
                                                {{ $symbol }}{{ number_format($service->price, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ __('per person') }}</div>
                                        </div>
                                        <a href="{{ route('bookings.create', $service) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm">{{ __('Book') }}</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $services->links('pagination::tailwind') }}
                    </div>
                    @else
                    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <div class="text-gray-500">
                            <p class="text-lg">{{ __('No services found') }}</p>
                            <p class="text-sm mt-2">{{ __('Try adjusting your filters') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- National Parks (East Africa) -->
                <div class="mt-12">
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('National Parks') }}</h2>
                    <p class="text-sm text-gray-600 mt-2">{{ __('Discover a larger selection of East Africa parks with real photos and local pricing.') }}</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-5">
                        @foreach($parks as $slug => $park)
                        <div class="w-full bg-white rounded-lg border border-gray-200 p-3 hover:shadow transition-shadow">
                            <div class="h-32 rounded-md overflow-hidden bg-gray-100">
                                @if(!empty($park['image_url']))
                                <img src="{{ $park['image_url'] }}" alt="{{ $park['name'] }}" class="w-full h-full object-cover" />
                                @else
                                <div class="flex items-center justify-center h-full text-gray-400">{{ __('No image') }}</div>
                                @endif
                            </div>

                            <div class="mt-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $park['name'] }}</h3>
                                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                            <img src="https://flagcdn.com/16x12/{{ strtolower($park['flag']) }}.png" alt="{{ $park['flag'] }}" class="inline-block" />
                                            {{ $park['location'] }}
                                        </p>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">{{ __('Park') }}</span>
                                </div>

                                <div class="mt-3 flex items-center justify-between gap-2">
                                    <div>
                                        <div class="text-base font-bold text-gray-900">{{ $park['currency'] }} {{ number_format($park['price']) }}</div>
                                        <div class="text-xs text-gray-500">{{ __('per person') }}</div>
                                    </div>
                                    <a href="{{ route('parks.show', $slug) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-md text-sm">{{ __('View') }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
