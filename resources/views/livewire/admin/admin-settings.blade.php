<div>
    <!-- Settings Button -->
    <flux:button
        wire:click="toggleModal"
        variant="ghost"
        icon="cog-6-tooth"
        class="text-gray-600 hover:text-gray-900"
        title="{{ __('Settings') }}"
    />

    <!-- Settings Modal -->
    <flux:modal wire:model="showModal" variant="flyout" class="md:max-w-2xl">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Admin Settings') }}</h2>
                <flux:button
                    wire:click="toggleModal"
                    variant="ghost"
                    icon="x-mark"
                    size="sm"
                />
            </div>

            <!-- Settings Form -->
            <form wire:submit.prevent="saveSettings" class="space-y-6">
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
                        <flux:input
                            type="number"
                            wire:model="maxUploadSize"
                            min="1"
                            max="100"
                            :placeholder="__('Enter maximum upload size')"
                        />
                        <p class="text-sm text-gray-600 mt-2">{{ __('Maximum file size users can upload') }}</p>
                    </label>
                </div>

                <!-- Session Timeout -->
                <div class="border-b border-gray-200 pb-6">
                    <label class="block">
                        <p class="font-semibold text-gray-900 mb-2">{{ __('Session Timeout (Minutes)') }}</p>
                        <flux:input
                            type="number"
                            wire:model="sessionTimeout"
                            min="5"
                            max="1440"
                            :placeholder="__('Enter session timeout duration')"
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
                    <flux:button
                        type="submit"
                        variant="primary"
                        class="flex-1"
                    >
                        {{ __('Save Settings') }}
                    </flux:button>
                    <flux:button
                        wire:click="toggleModal"
                        variant="ghost"
                        class="flex-1"
                    >
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">
                    ℹ️ {{ __('Settings are cached and will be applied immediately to all new requests.') }}
                </p>
            </div>
        </div>
    </flux:modal>
</div>
