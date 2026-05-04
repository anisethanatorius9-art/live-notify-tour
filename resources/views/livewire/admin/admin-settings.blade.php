<div>
    <!-- Settings Button -->
    <button
        wire:click="toggleModal"
        type="button"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 font-semibold transition"
        title="{{ __('Settings') }}"
    >
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
        {{ __('Settings') }}
    </button>

    <!-- Settings Modal -->
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
                        <h2 class="text-2xl font-bold text-gray-900">{{ __('Admin Settings') }}</h2>
                        <button
                            wire:click="toggleModal"
                            type="button"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Settings Form -->
                    <form wire:submit.prevent="saveSettings" class="space-y-6 max-h-96 overflow-y-auto">
                        <!-- Maintenance Mode -->
                        <div class="border-b border-gray-200 pb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="maintenanceMode"
                                    class="w-4 h-4 text-blue-600 rounded"
                                />
                                <div>
                                    <p class="font-semibold text-gray-900">{{ __('Maintenance Mode') }}</p>
                                    <p class="text-sm text-gray-600">{{ __('Put the platform in maintenance mode for updates and fixes') }}</p>
                                </div>
                            </label>
                            @if($maintenanceMode)
                                <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <p class="text-sm text-yellow-700">
                                        ⚠️ {{ __('Maintenance mode is ON. Users will see a maintenance message.') }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Max Upload Size -->
                        <div class="border-b border-gray-200 pb-6">
                            <label class="block">
                                <p class="font-semibold text-gray-900 mb-2">{{ __('Max Upload Size (MB)') }}</p>
                                <input
                                    type="number"
                                    wire:model="maxUploadSize"
                                    min="1"
                                    max="100"
                                    placeholder="{{ __('Enter maximum upload size') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <p class="text-sm text-gray-600 mt-2">{{ __('Maximum file size users can upload') }}</p>
                            </label>
                        </div>

                        <!-- Session Timeout -->
                        <div class="border-b border-gray-200 pb-6">
                            <label class="block">
                                <p class="font-semibold text-gray-900 mb-2">{{ __('Session Timeout (Minutes)') }}</p>
                                <input
                                    type="number"
                                    wire:model="sessionTimeout"
                                    min="5"
                                    max="1440"
                                    placeholder="{{ __('Enter session timeout duration') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                />
                                <p class="text-sm text-gray-600 mt-2">{{ __('How long before inactive users are logged out') }}</p>
                            </label>
                        </div>

                        <!-- Email Notifications -->
                        <div class="pb-6">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    wire:model="emailNotifications"
                                    class="w-4 h-4 text-blue-600 rounded"
                                />
                                <div>
                                    <p class="font-semibold text-gray-900">{{ __('Email Notifications') }}</p>
                                    <p class="text-sm text-gray-600">{{ __('Send email notifications for important events') }}</p>
                                </div>
                            </label>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-3 pt-4 border-t border-gray-200">
                            <button
                                type="submit"
                                class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition"
                            >
                                {{ __('Save Settings') }}
                            </button>
                            <button
                                type="button"
                                wire:click="toggleModal"
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition"
                            >
                                {{ __('Cancel') }}
                            </button>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-700">
                            ℹ️ {{ __('Settings are cached and will be applied immediately to all new requests.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
