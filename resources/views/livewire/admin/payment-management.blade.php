<x-layouts.app>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Payments Management') }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Review and approve pending booking payments.') }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <input
                type="text"
                wire:model.debounce.500ms="search"
                placeholder="{{ __('Search payments, users, or services...') }}"
                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            />
            <select
                wire:model="filterStatus"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
                <option value="">{{ __('All status') }}</option>
                <option value="pending">{{ __('Pending') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="failed">{{ __('Failed') }}</option>
            </select>
        </div>

        <!-- Payments Grid -->
        @if($payments->count())
            <div class="grid gap-6">
                @foreach($payments as $payment)
                    <flux:card class="overflow-hidden">
                        <div class="grid md:grid-cols-3 gap-6">
                            <!-- Booking & Service Info -->
                            <div class="md:col-span-2 space-y-4">
                                <!-- Service Header -->
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $payment->booking?->service?->name ?? __('Unknown service') }}
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                📍 {{ $payment->booking?->service?->location?->name ?? __('Location unknown') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold
                                            @if($payment->status === 'pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($payment->status === 'completed')
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($payment->status === 'failed')
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif
                                        ">
                                            @if($payment->status === 'pending')
                                                ⏳ {{ __('Pending') }}
                                            @elseif($payment->status === 'completed')
                                                ✓ {{ __('Completed') }}
                                            @elseif($payment->status === 'failed')
                                                ✗ {{ __('Failed') }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ __('Tourist') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                            {{ $payment->booking?->tourist?->name ?? __('Unknown') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ __('Booking Date') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                            📅 {{ $payment->booking?->booking_date?->format('d M Y') ?? __('N/A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ __('Guests') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                                            👥 {{ $payment->booking?->number_of_people ?? '0' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Notes -->
                                @if($payment->booking?->notes)
                                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-3">
                                        <p class="text-xs text-blue-600 dark:text-blue-300 font-semibold mb-1">{{ __('Notes') }}</p>
                                        <p class="text-sm text-blue-900 dark:text-blue-100">{{ $payment->booking->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Payment & Action Info -->
                            <div class="space-y-4">
                                <!-- Payment Summary -->
                                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Amount') }}</span>
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                            Ksh {{ number_format($payment->amount, 2) }}
                                        </span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ __('Transaction ID') }}</p>
                                        <p class="text-sm font-mono text-gray-900 dark:text-white mt-1">{{ $payment->transaction_id }}</p>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ __('Payment Method') }}</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1 capitalize">{{ $payment->payment_method }}</p>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-semibold">{{ __('Submitted') }}</p>
                                        <p class="text-sm text-gray-900 dark:text-white mt-1">{{ $payment->created_at?->format('d M Y H:i') ?? __('N/A') }}</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                @if($payment->status === 'pending')
                                    <div class="flex gap-2">
                                        <button
                                            wire:click="approvePayment({{ $payment->id }})"
                                            class="flex-1 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white font-semibold py-2 px-4 rounded-lg transition"
                                        >
                                            ✓ {{ __('Accept') }}
                                        </button>
                                        <button
                                            wire:click="rejectPayment({{ $payment->id }})"
                                            class="flex-1 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white font-semibold py-2 px-4 rounded-lg transition"
                                        >
                                            ✗ {{ __('Reject') }}
                                        </button>
                                    </div>
                                @else
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3 text-center">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('No action available') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @else
            <flux:card class="text-center py-12">
                <div class="space-y-2">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('No payments found') }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('There are no payments matching your current filters.') }}</p>
                </div>
            </flux:card>
        @endif
    </div>
</x-layouts.app>
