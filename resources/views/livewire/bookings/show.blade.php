<x-layouts.app>
    <div class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Booking Details') }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Review the details of your reservation.') }}</p>
                </div>
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-zinc-700 dark:bg-zinc-950 dark:text-gray-200 dark:hover:bg-zinc-800">
                    ← {{ __('Back to Bookings') }}
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 overflow-hidden shadow-sm">
                        @if($booking->service->image_url)
                        <div class="h-56 overflow-hidden">
                            <img src="{{ $booking->service->image_url }}" alt="{{ $booking->service->name }}" class="w-full h-full object-cover" />
                        </div>
                        @endif
                        <div class="p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $booking->service->name }}</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $booking->service->description }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-sm font-semibold text-blue-700 dark:bg-blue-950 dark:text-blue-300">
                                    {{ $booking->service->category }}</span>
                            </div>

                            <div class="grid gap-4 mt-6 sm:grid-cols-2">
                                <div class="rounded-2xl bg-gray-50 dark:bg-zinc-950 p-4">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Provider') }}</p>
                                    <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $booking->service->provider->name }}</p>
                                </div>
                                <div class="rounded-2xl bg-gray-50 dark:bg-zinc-950 p-4">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Location') }}</p>
                                    <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $booking->service->location->name }}</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl bg-gray-50 dark:bg-zinc-950 p-4">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Booking Date') }}</p>
                                    <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $booking->booking_date->format('d M Y') }}</p>
                                </div>
                                <div class="rounded-2xl bg-gray-50 dark:bg-zinc-950 p-4">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Guests') }}</p>
                                    <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $booking->number_of_people }}</p>
                                </div>
                                <div class="rounded-2xl bg-gray-50 dark:bg-zinc-950 p-4">
                                    <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                                    <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ ucfirst($booking->status) }}</p>
                                </div>
                            </div>

                            <div class="mt-6 border-t border-gray-200 dark:border-zinc-800 pt-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Notes') }}</h3>
                                <p class="mt-3 text-gray-600 dark:text-gray-400">{{ $booking->notes ?: __('No additional notes provided.') }}</p>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 p-6 shadow-sm">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Billing Summary') }}</h2>
                            <div class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex justify-between">
                                    <span>{{ __('Price per person') }}</span>
                                    <span>{{ number_format($booking->service->price, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>{{ __('Number of people') }}</span>
                                    <span>{{ $booking->number_of_people }}</span>
                                </div>
                                <div class="flex justify-between font-semibold text-gray-900 dark:text-white pt-2 border-t border-gray-200 dark:border-zinc-800">
                                    <span>{{ __('Total') }}</span>
                                    <span>Ksh {{ number_format($booking->total_price, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-gray-200 dark:border-zinc-800 p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Service Info') }}</h3>
                            <div class="mt-4 space-y-3 text-sm text-gray-600 dark:text-gray-400">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('Service') }}</p>
                                    <p>{{ $booking->service->name }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('Provider') }}</p>
                                    <p>{{ $booking->service->provider->name }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('Location') }}</p>
                                    <p>{{ $booking->service->location->name }}</p>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ __('Category') }}</p>
                                    <p>{{ $booking->service->category }}</p>
                                </div>
                            </div>
                        </div>

                        @if($booking->status === 'pending')
                        <div class="bg-yellow-50 dark:bg-yellow-950 rounded-xl border border-yellow-200 dark:border-yellow-800 p-4 text-sm text-yellow-800 dark:text-yellow-200">
                            {{ __('Your booking is pending confirmation. We will notify you once it is confirmed.') }}
                        </div>
                        @elseif($booking->status === 'confirmed')
                        <div class="bg-green-50 dark:bg-green-950 rounded-xl border border-green-200 dark:border-green-800 p-4 text-sm text-green-800 dark:text-green-200">
                            {{ __('This booking has been confirmed. Get ready for your trip!') }}
                        </div>
                        @elseif($booking->status === 'cancelled')
                        <div class="bg-red-50 dark:bg-red-950 rounded-xl border border-red-200 dark:border-red-800 p-4 text-sm text-red-800 dark:text-red-200">
                            {{ __('This booking was cancelled. Contact support if you need help.') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
</x-layouts.app>
