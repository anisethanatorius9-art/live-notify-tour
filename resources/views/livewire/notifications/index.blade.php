<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900">{{ __('Notifications') }}</h1>
                <p class="text-gray-600 mt-2">{{ __('Your latest updates and notifications') }}</p>
            </div>

            <!-- Notifications Table -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                @php
                    $notifications = auth()->user()->notifications;
                @endphp

                @if($notifications && count($notifications) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Type') }}</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Message') }}</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Date') }}</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Status') }}</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notifications as $notification)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <!-- Heroicons: Bell -->
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                </div>
                                                <span class="font-semibold text-gray-900">Booking Update</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-gray-700">Your booking notification message</p>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 text-sm">
                                            <span class="text-gray-600">{{ date('d M Y H:i') }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">
                                                Unread
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="p-2 hover:bg-red-100 rounded-lg transition text-red-600" title="Delete">
                                                <!-- Heroicons: Trash -->
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="space-y-4">
                            <!-- Heroicons: Bell Slash -->
                            <div class="flex justify-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-medium text-gray-600">{{ __('No notifications') }}</p>
                                <p class="text-sm text-gray-500 mt-2">{{ __('You are all caught up! Check back later.') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
