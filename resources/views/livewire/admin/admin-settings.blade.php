<div>
    <button
        wire:click="toggleModal"
        type="button"
        class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-200 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700"
        title="{{ __('Settings') }}"
    >
        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.533 1.533 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
        {{ __('Settings') }}
    </button>

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
                        <button wire:click="toggleModal" type="button" class="text-gray-400 hover:text-gray-600 dark:text-zinc-400 dark:hover:text-white">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveSettings" class="space-y-6">
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="space-y-4">
                                <flux:card class="p-4">
                                    <label class="flex cursor-pointer items-start gap-3">
                                        <input type="checkbox" wire:model="maintenanceMode" class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600" />
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ __('Maintenance Mode') }}</p>
                                            <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Put the platform in maintenance mode for updates and fixes') }}</p>
                                        </div>
                                    </label>
                                    @if($maintenanceMode)
                                        <div class="mt-3 rounded-lg border border-yellow-200 bg-yellow-50 p-3 text-sm text-yellow-700">
                                            ⚠️ {{ __('Maintenance mode is ON. Users will see a maintenance message.') }}
                                        </div>
                                    @endif
                                </flux:card>

                                <flux:card class="p-4">
                                    <label class="flex cursor-pointer items-start gap-3">
                                        <input type="checkbox" wire:model="autoApprovePayments" class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600" />
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ __('Auto-approve payments') }}</p>
                                            <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Approve booking payments automatically when they arrive.') }}</p>
                                        </div>
                                    </label>
                                </flux:card>

                                <flux:card class="p-4">
                                    <label class="flex cursor-pointer items-start gap-3">
                                        <input type="checkbox" wire:model="showAnnouncements" class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600" />
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ __('Show system announcements') }}</p>
                                            <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Display announcements for admins and users across the platform.') }}</p>
                                        </div>
                                    </label>
                                </flux:card>
                            </div>

                            <div class="space-y-4">
                                <flux:card class="p-4">
                                    <label class="block">
                                        <p class="mb-2 font-semibold text-gray-900 dark:text-white">{{ __('Max Upload Size (MB)') }}</p>
                                        <input type="number" wire:model="maxUploadSize" min="1" max="100" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                                        <p class="mt-2 text-sm text-gray-600 dark:text-zinc-400">{{ __('Maximum file size users can upload') }}</p>
                                    </label>
                                </flux:card>

                                <flux:card class="p-4">
                                    <label class="block">
                                        <p class="mb-2 font-semibold text-gray-900 dark:text-white">{{ __('Session Timeout (Minutes)') }}</p>
                                        <input type="number" wire:model="sessionTimeout" min="5" max="1440" class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white" />
                                        <p class="mt-2 text-sm text-gray-600 dark:text-zinc-400">{{ __('How long before inactive users are logged out') }}</p>
                                    </label>
                                </flux:card>

                                <flux:card class="p-4">
                                    <label class="flex cursor-pointer items-start gap-3">
                                        <input type="checkbox" wire:model="emailNotifications" class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-600" />
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ __('Email Notifications') }}</p>
                                            <p class="text-sm text-gray-600 dark:text-zinc-400">{{ __('Send email notifications for important events') }}</p>
                                        </div>
                                    </label>
                                </flux:card>
                            </div>
                        </div>

                        <div class="flex flex-col gap-3 border-t border-gray-200 pt-4 dark:border-zinc-700 sm:flex-row">
                            <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition hover:bg-blue-700">
                                {{ __('Save Settings') }}
                            </button>
                            <button type="button" wire:click="toggleModal" class="flex-1 rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-700 transition hover:bg-gray-300 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700">
                                {{ __('Cancel') }}
                            </button>
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
