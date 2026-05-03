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
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                            <option value="">{{ __('All Locations') }}</option>
                            @foreach($locations as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <select
                            wire:model.live="filterCategory"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($services as $service)
                    <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="space-y-4">
                            <!-- Service Header -->
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ $service->name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        📍 {{ $service->location->name }}
                                    </p>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $service->category }}
                                </span>
                            </div>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm line-clamp-2">
                                {{ $service->description }}
                            </p>

                            <!-- Provider Info -->
                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-500">{{ __('By') }}:</span>
                                <span class="font-medium text-gray-900">
                                    {{ $service->provider->name }}
                                </span>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $service->rating)
                                    <span class="text-yellow-400">⭐</span>
                                    @else
                                    <span class="text-gray-300">☆</span>
                                    @endif
                                    @endfor
                                    <span class="text-sm text-gray-600 ml-2">
                                        ({{ $service->rating }}/5)
                                    </span>
                            </div>

                            <!-- Price and Button -->
                            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                                <div>
                                    <span class="text-2xl font-bold text-gray-900">
                                        {{ number_format($service->price, 2) }}
                                    </span>
                                    <span class="text-gray-600 ml-1">{{ __('per person') }}</span>
                                </div>
                                <a href="{{ route('bookings.create', $service) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                                    {{ __('Book Now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $services->links() }}
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
        </div>
    </div>
</div>
