<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ __('Service Provider Dashboard') }}
                        </h1>
                        <p class="text-gray-600 mt-2">{{ __('Manage your services and bookings') }}</p>
                    </div>
                    <flux:button
                        href="{{ route('services.create') }}"
                        variant="primary"
                        icon="plus"
                        wire:navigate
                    >
                        {{ __('Add New Service') }}
                    </flux:button>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Total Services') }}</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalServices }}</p>
                        </div>
                        <span class="text-2xl">📦</span>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Active Services') }}</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $activeServices }}</p>
                        </div>
                        <span class="text-2xl">✅</span>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Pending Bookings') }}</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingBookings }}</p>
                        </div>
                        <span class="text-2xl">⏳</span>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Total Earnings') }}</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">
                                {{ number_format($totalEarnings, 2) }}
                            </p>
                        </div>
                        <span class="text-2xl">💰</span>
                    </div>
                </flux:card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Services Section -->
                    <flux:card class="mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Your Services') }}</h2>
                        </div>

                        <!-- Search -->
                        <flux:input
                            wire:model.live="search"
                            type="search"
                            :placeholder="__('Search services...')"
                            icon="magnifying-glass"
                            class="mb-6"
                        />

                        <!-- Services List -->
                        @if($services->count())
                            <div class="space-y-4">
                                @foreach($services as $service)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <h3 class="font-bold text-gray-900">{{ $service->name }}</h3>
                                                    <flux:badge
                                                        :color="$service->status === 'active' ? 'green' : 'gray'"
                                                        size="sm"
                                                    >
                                                        {{ ucfirst($service->status) }}
                                                    </flux:badge>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-2">{{ $service->description }}</p>
                                                <div class="flex items-center gap-4 text-sm text-gray-600">
                                                    <span>📍 {{ $service->location->name }}</span>
                                                    <span>💰 {{ number_format($service->price, 2) }}</span>
                                                    <span>⭐ {{ $service->rating }}/5</span>
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center gap-2 ml-4">
                                                <flux:button
                                                    href="{{ route('services.edit', $service) }}"
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="pencil"
                                                    wire:navigate
                                                />
                                                <flux:button
                                                    wire:click="toggleServiceStatus({{ $service->id }})"
                                                    size="sm"
                                                    variant="ghost"
                                                    :icon="$service->status === 'active' ? 'eye-off' : 'eye'"
                                                />
                                                <flux:button
                                                    wire:click="deleteService({{ $service->id }})"
                                                    size="sm"
                                                    variant="ghost"
                                                    icon="trash"
                                                    class="text-red-600 hover:text-red-700"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $services->links() }}
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <p class="text-lg">{{ __('No services yet') }}</p>
                                <p class="text-sm mt-2">{{ __('Create your first service to get started') }}</p>
                            </div>
                        @endif
                    </flux:card>

                    <!-- Bookings Section -->
                    <flux:card>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Bookings') }}</h2>
                        </div>

                        <!-- Filter -->
                        <flux:select
                            wire:model.live="filterStatus"
                            :options="['' => 'All Statuses', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'completed' => 'Completed', 'cancelled' => 'Cancelled']"
                            class="mb-6"
                        />

                        <!-- Bookings List -->
                        @if($bookings->count())
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="border-b border-gray-200">
                                        <tr>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Service') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Tourist') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Date') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Amount') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bookings as $booking)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="py-3 px-2 text-sm">{{ $booking->service->name }}</td>
                                                <td class="py-3 px-2 text-sm">{{ $booking->tourist->name }}</td>
                                                <td class="py-3 px-2 text-sm">{{ $booking->booking_date->format('M d, Y') }}</td>
                                                <td class="py-3 px-2 text-sm font-medium">{{ number_format($booking->total_price, 2) }}</td>
                                                <td class="py-3 px-2 text-sm">
                                                    <flux:badge
                                                        :color="match($booking->status) {
                                                            'pending' => 'yellow',
                                                            'confirmed' => 'blue',
                                                            'completed' => 'green',
                                                            'cancelled' => 'red',
                                                            default => 'gray'
                                                        }"
                                                        size="sm"
                                                    >
                                                        {{ ucfirst($booking->status) }}
                                                    </flux:badge>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $bookings->links() }}
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <p class="text-lg">{{ __('No bookings yet') }}</p>
                            </div>
                        @endif
                    </flux:card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <flux:card>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Quick Stats') }}</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">{{ __('Services Created') }}</span>
                                <span class="font-bold text-gray-900">{{ $totalServices }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200">
                                <span class="text-gray-600">{{ __('Active Right Now') }}</span>
                                <span class="font-bold text-green-600">{{ $activeServices }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600">{{ __('Avg. Rating') }}</span>
                                <span class="font-bold text-gray-900">
                                    @php
                                        $avgRating = auth()->user()->services()->avg('rating') ?? 0;
                                    @endphp
                                    {{ number_format($avgRating, 1) }}/5
                                </span>
                            </div>
                        </div>
                    </flux:card>

                    <!-- Help & Support -->
                    <flux:card>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Need Help?') }}</h3>
                        <div class="space-y-3">
                            <flux:link href="#" class="block text-blue-600 hover:underline">
                                {{ __('How to add services') }}
                            </flux:link>
                            <flux:link href="#" class="block text-blue-600 hover:underline">
                                {{ __('Pricing tips') }}
                            </flux:link>
                            <flux:link href="#" class="block text-blue-600 hover:underline">
                                {{ __('Increase your ratings') }}
                            </flux:link>
                        </div>
                    </flux:card>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
