<div>
    <flux:button
        wire:click="toggleModal"
        type="button"
        variant="outline"
        class="inline-flex items-center gap-2"
        title="{{ __('Settings') }}"
    >
        {{ __('Settings') }}
    </flux:button>

    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 py-12">
            <div class="fixed inset-0 bg-black/60 transition-opacity" wire:click="toggleModal"></div>

            <div class="relative mx-auto w-full max-w-3xl rounded-2xl bg-white shadow-2xl dark:bg-zinc-900">
                <div class="space-y-6 p-6 sm:p-8">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-4 dark:border-zinc-700">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Admin Settings') }}</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">{{ __('Manage the daily operations of the administration panel.') }}</p>
                        </div>
                        <flux:button wire:click="toggleModal" type="button" variant="ghost" icon="x-mark" aria-label="{{ __('Close settings') }}" />
                    </div>

                    <form wire:submit.prevent="saveSettings" class="space-y-6">
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="space-y-4">
                                <flux:card class="p-4">
                                    <div class="space-y-2">
                                        <flux:checkbox wire:model="maintenanceMode" label="{{ __('Maintenance Mode') }}" />
                                        <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Put the platform in maintenance mode for updates and fixes') }}</p>
                                    </div>
                                    @if($maintenanceMode)
                                        <div class="mt-3 rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-sm text-yellow-700">
                                            ⚠️ {{ __('Maintenance mode is ON. Users will see a maintenance message.') }}
                                        </div>
                                    @endif
                                </flux:card>

                                <flux:card class="p-4">
                                    <div class="space-y-2">
                                        <flux:checkbox wire:model="autoApprovePayments" label="{{ __('Auto-approve payments') }}" />
                                        <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Approve booking payments automatically when they arrive.') }}</p>
                                    </div>
                                </flux:card>

                                <flux:card class="p-4">
                                    <div class="space-y-2">
                                        <flux:checkbox wire:model="showAnnouncements" label="{{ __('Show system announcements') }}" />
                                        <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Display announcements for admins and users across the platform.') }}</p>
                                    </div>
                                </flux:card>
                            </div>

                            <div class="space-y-4">
                                <flux:card class="p-4">
                                    <flux:input type="number" wire:model="maxUploadSize" min="1" max="100" label="{{ __('Max Upload Size (MB)') }}" />
                                    <p class="mt-2 text-sm text-gray-600 dark:text-zinc-400">{{ __('Maximum file size users can upload') }}</p>
                                </flux:card>

                                <flux:card class="p-4">
                                    <flux:input type="number" wire:model="sessionTimeout" min="5" max="1440" label="{{ __('Session Timeout (Minutes)') }}" />
                                    <p class="mt-2 text-sm text-gray-600 dark:text-zinc-400">{{ __('How long before inactive users are logged out') }}</p>
                                </flux:card>

                                <flux:card class="p-4">
                                    <div class="space-y-2">
                                        <flux:checkbox wire:model="emailNotifications" label="{{ __('Email Notifications') }}" />
                                        <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Send email notifications for important events') }}</p>
                                    </div>
                                </flux:card>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 border-t border-gray-200 pt-4 dark:border-zinc-700 sm:flex-row">
                            <flux:button type="submit" variant="primary" class="flex-1">
                                {{ __('Save Settings') }}
                            </flux:button>
                            <flux:button type="button" wire:click="toggleModal" variant="outline" class="flex-1">
                                {{ __('Cancel') }}
                            </flux:button>
                        </div>
                    </form>

                    <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-200">
                        ℹ️ {{ __('Settings are cached and will be applied immediately to all new requests.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
