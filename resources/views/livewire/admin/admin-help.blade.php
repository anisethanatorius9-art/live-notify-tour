<div>
    <!-- Help Button -->
    <button
        wire:click="toggleModal"
        type="button"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold transition"
        title="{{ __('Help') }}">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        {{ __('Help') }}
    </button>

    <!-- Help Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 py-12">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="toggleModal"></div>

            <!-- Modal Panel -->
            <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full mx-auto">
                <div class="space-y-6 p-8">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('Admin Help Center') }}</h2>
                        <button
                            wire:click="toggleModal"
                            type="button"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Help Content -->
                    <div class="space-y-6 max-h-96 overflow-y-auto">
                        <!-- Dashboard Help -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                📊 {{ __('Dashboard Overview') }}
                            </h3>
                            <p class="text-gray-600">
                                {{ __('The Admin Dashboard shows real-time statistics about your platform including total users, services, bookings, and revenue. Use the metrics cards to monitor platform health at a glance.') }}
                            </p>
                        </div>

                        <!-- User Management -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                👥 {{ __('User Management') }}
                            </h3>
                            <ul class="space-y-2 text-gray-600 list-disc list-inside">
                                <li>{{ __('Search users by name or email') }}</li>
                                <li>{{ __('Filter by role: Tourist, Provider, or Admin') }}</li>
                                <li>{{ __('Edit user details and manage permissions') }}</li>
                                <li>{{ __('Remove users from the platform') }}</li>
                            </ul>
                        </div>

                        <!-- Services Management -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                🎯 {{ __('Services & Bookings') }}
                            </h3>
                            <ul class="space-y-2 text-gray-600 list-disc list-inside">
                                <li>{{ __('Monitor all active services on your platform') }}</li>
                                <li>{{ __('Track booking status and completion rates') }}</li>
                                <li>{{ __('View payment history and revenue reports') }}</li>
                            </ul>
                        </div>

                        <!-- Locations & Categories -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                📍 {{ __('Locations & Categories') }}
                            </h3>
                            <p class="text-gray-600">
                                {{ __('Manage tour locations and service categories. Add new locations and categories to help providers create better services and help tourists find what they\'re looking for.') }}
                            </p>
                        </div>

                        <!-- Tips -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">
                                💡 {{ __('Pro Tips') }}
                            </h3>
                            <ul class="space-y-2 text-blue-700 list-disc list-inside">
                                <li>{{ __('Use search and filters to quickly find specific users or data') }}</li>
                                <li>{{ __('Regularly review revenue reports to understand platform growth') }}</li>
                                <li>{{ __('Monitor pending bookings to ensure customer satisfaction') }}</li>
                                <li>{{ __('Keep locations and categories updated for better user experience') }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-gray-200 pt-4 flex gap-3">
                        <button
                            wire:click="toggleModal"
                            type="button"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition">
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
