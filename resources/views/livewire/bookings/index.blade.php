<x-layouts.app>
    <div class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header with Icon -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center text-white">
                        <!-- Heroicons: Calendar -->
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('My Bookings') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage and track all your service bookings') }}</p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6 text-green-800 dark:text-green-200 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
            @endif

            <!-- Bookings Table -->
            @php
            $bookings = auth()->user()->bookings()->with('service.location', 'service.provider')->latest()->get();
            @endphp

            @if($bookings->count())
                <flux:card class="overflow-hidden">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>{{ __('Service') }}</flux:table.column>
                            <flux:table.column>{{ __('Provider') }}</flux:table.column>
                            <flux:table.column>{{ __('Date') }}</flux:table.column>
                            <flux:table.column class="text-center">{{ __('People') }}</flux:table.column>
                            <flux:table.column class="text-end">{{ __('Price') }}</flux:table.column>
                            <flux:table.column>{{ __('Status') }}</flux:table.column>
                            <flux:table.column class="text-end">{{ __('Actions') }}</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @foreach($bookings as $booking)
                            <flux:table.row>
                                <flux:table.cell>
                                    <div class="space-y-1">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $booking->service->name }}</p>
                                        <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-sm">{{ $booking->service->location->name }}</span>
                                        </div>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-pink-600 rounded-full flex-shrink-0 flex items-center justify-center text-white font-bold text-sm">
                                            {{ substr($booking->service->provider->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $booking->service->provider->name }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $booking->booking_date->format('d M Y') }}</span>
                                </flux:table.cell>
                                <flux:table.cell class="text-center">
                                    <span class="text-gray-900 dark:text-gray-100">{{ $booking->number_of_people }}</span>
                                </flux:table.cell>
                                <flux:table.cell class="text-end">
                                    <span class="font-semibold text-blue-600 dark:text-blue-400">Ksh {{ number_format($booking->total_price, 2) }}</span>
                                </flux:table.cell>
                                <flux:table.cell>
                                    @if($booking->status === 'pending')
                                        <span class="inline-flex items-center gap-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-3 py-1 rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.447.894l1.006 3.018a1 1 0 001.894-.894l-.85-2.56V6z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Pending') }}
                                        </span>
                                    @elseif($booking->status === 'confirmed')
                                        <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Confirmed') }}
                                        </span>
                                    @elseif($booking->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-3 py-1 rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Cancelled') }}
                                        </span>
                                    @else
                                        <span class="inline-block bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full text-xs font-bold">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    @endif
                                </flux:table.cell>
                                <flux:table.cell class="text-end">
                                    <div class="flex items-center justify-end gap-2">
                                        <flux:button size="sm" variant="ghost" icon="eye" href="#" />
                                        @if($booking->status === 'pending')
                                            <flux:button size="sm" variant="ghost" icon="trash" href="#" />
                                        @endif
                                    </div>
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
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ __('No bookings yet') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ __('Start by exploring and booking amazing services') }}</p>
                        </div>
                        <flux:button href="{{ route('dashboard.tourist') }}" class="mt-4">
                            {{ __('Browse Services') }}
                        </flux:button>
                    </div>
                </flux:card>
            @endif
        </div>
    </div>
</x-layouts.app>
