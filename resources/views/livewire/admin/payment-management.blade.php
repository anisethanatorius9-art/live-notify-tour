<x-layouts.app>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Payments Management') }}</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">{{ __('Review and approve pending booking payments with a clearer admin workflow.') }}</p>
            </div>
        </div>

        <flux:card class="p-4 sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <input
                    type="text"
                    wire:model.debounce.500ms="search"
                    placeholder="{{ __('Search payments, users, or services...') }}"
                    class="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                />
                <select
                    wire:model="filterStatus"
                    class="rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                >
                    <option value="">{{ __('All status') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="failed">{{ __('Failed') }}</option>
                </select>
            </div>
        </flux:card>

        @if($payments->count())
            <div class="grid gap-6">
                @foreach($payments as $payment)
                    <flux:card class="overflow-hidden">
                        <div class="grid gap-6 md:grid-cols-[1.4fr_0.9fr]">
                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $payment->booking?->service?->name ?? __('Unknown service') }}
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                📍 {{ $payment->booking?->service?->location?->name ?? __('Location unknown') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold
                                            @if($payment->status === 'pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900/60 dark:text-yellow-200
                                            @elseif($payment->status === 'completed')
                                                bg-green-100 text-green-800 dark:bg-green-900/60 dark:text-green-200
                                            @elseif($payment->status === 'failed')
                                                bg-red-100 text-red-800 dark:bg-red-900/60 dark:text-red-200
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

                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Tourist') }}</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">{{ $payment->booking?->tourist?->name ?? __('Unknown') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Booking Date') }}</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">📅 {{ $payment->booking?->booking_date?->format('d M Y') ?? __('N/A') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Guests') }}</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900 dark:text-white">👥 {{ $payment->booking?->number_of_people ?? '0' }}</p>
                                    </div>
                                </div>

                                @if($payment->booking?->notes)
                                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-3 dark:border-blue-900 dark:bg-blue-950/30">
                                        <p class="mb-1 text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-300">{{ __('Notes') }}</p>
                                        <p class="text-sm text-blue-900 dark:text-blue-100">{{ $payment->booking->notes }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="space-y-4">
                                <div class="rounded-xl bg-gray-50 p-4 dark:bg-zinc-800/70">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Amount') }}</span>
                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">Ksh {{ number_format($payment->amount, 2) }}</span>
                                    </div>
                                    <div class="mt-3 border-t border-gray-200 pt-3 dark:border-gray-700">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Transaction ID') }}</p>
                                        <p class="mt-1 text-sm font-mono text-gray-900 dark:text-white">{{ $payment->transaction_id }}</p>
                                    </div>
                                    <div class="mt-3 border-t border-gray-200 pt-3 dark:border-gray-700">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Payment Method') }}</p>
                                        <p class="mt-1 text-sm font-medium capitalize text-gray-900 dark:text-white">{{ $payment->payment_method }}</p>
                                    </div>
                                    <div class="mt-3 border-t border-gray-200 pt-3 dark:border-gray-700">
                                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Submitted') }}</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $payment->created_at?->format('d M Y H:i') ?? __('N/A') }}</p>
                                    </div>
                                </div>

                                @if($payment->status === 'pending')
                                    <div class="flex flex-col gap-2 sm:flex-row">
                                        <button wire:click="approvePayment({{ $payment->id }})" class="flex-1 rounded-lg bg-green-600 px-4 py-2 font-semibold text-white transition hover:bg-green-700">
                                            ✓ {{ __('Accept') }}
                                        </button>
                                        <button wire:click="rejectPayment({{ $payment->id }})" class="flex-1 rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition hover:bg-red-700">
                                            ✗ {{ __('Reject') }}
                                        </button>
                                    </div>
                                @else
                                    <div class="rounded-lg bg-gray-100 p-3 text-center dark:bg-zinc-800">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('No action available') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </flux:card>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @else
            <flux:card class="py-12 text-center">
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('No payments found') }}</p>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ __('There are no payments matching your current filters.') }}</p>
            </flux:card>
        @endif
    </div>
</x-layouts.app>
