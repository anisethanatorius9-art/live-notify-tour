<div>
    <!-- Help Button -->
    <flux:button
        wire:click="toggleModal"
        variant="ghost"
        icon="question-mark-circle"
        class="text-gray-600 hover:text-gray-900"
        title="{{ __('Help') }}"
    />

    <!-- Help Modal -->
    <flux:modal wire:model="showModal" variant="flyout" class="md:max-w-2xl">
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Admin Help Center') }}</h2>
                <flux:button
                    wire:click="toggleModal"
                    variant="ghost"
                    icon="x-mark"
                    size="sm"
                />
            </div>

            <!-- Help Content -->
            <div class="space-y-6">
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
            <div class="border-t border-gray-200 pt-4">
                <p class="text-sm text-gray-500">
                    {{ __('Need more help?') }}
                    <a href="mailto:support@livenotifytour.com" class="text-blue-600 hover:text-blue-700 font-semibold">
                        {{ __('Contact Support') }}
                    </a>
                </p>
            </div>
        </div>
    </flux:modal>
</div>
