<x-layouts.app>
    <div class="space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Payments') }}</h1>
                <p class="mt-2 text-gray-600">{{ __('Review and approve pending booking payments.') }}</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <input
                    type="text"
                    wire:model.debounce.500ms="search"
                    placeholder="{{ __('Search payments, users, or services...') }}"
                    class="w-full sm:w-72 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <select
                    wire:model="filterStatus"
                    class="w-full sm:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">{{ __('All status') }}</option>
                    <option value="pending">{{ __('Pending') }}</option>
                    <option value="completed">{{ __('Completed') }}</option>
                    <option value="failed">{{ __('Failed') }}</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            @if($payments->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Transaction') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('User') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Service') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Amount') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Status') }}</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Paid At') }}</th>
                                <th class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <div class="font-medium">{{ $payment->transaction_id }}</div>
                                        <div class="text-gray-500">{{ ucfirst($payment->payment_method) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $payment->user?->name ?? __('Unknown user') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $payment->booking?->service?->name ?? __('Unknown service') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">Ksh {{ number_format($payment->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($payment->status === 'pending')
                                            <span class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('Pending') }}</span>
                                        @elseif($payment->status === 'completed')
                                            <span class="inline-flex items-center gap-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('Completed') }}</span>
                                        @elseif($payment->status === 'failed')
                                            <span class="inline-flex items-center gap-2 bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">{{ __('Failed') }}</span>
                                        @else
                                            <span class="inline-flex items-center gap-2 bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($payment->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $payment->paid_at?->format('d M Y H:i') ?? __('Not paid yet') }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        @if($payment->status === 'pending')
                                            <button
                                                wire:click="approvePayment({{ $payment->id }})"
                                                class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white hover:bg-blue-700"
                                            >
                                                {{ __('Approve') }}
                                            </button>
                                        @else
                                            <span class="text-sm text-gray-500">{{ __('No action') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="p-12 text-center">
                    <p class="text-lg font-semibold text-gray-900">{{ __('No payments found.') }}</p>
                    <p class="text-sm text-gray-500 mt-2">{{ __('There are no payments matching your current filters.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
