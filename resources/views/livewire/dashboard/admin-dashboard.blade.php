<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <!-- Header Section -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-3xl font-bold text-gray-900">
                    {{ __('Admin Dashboard') }}
                </h1>
                <p class="text-gray-600 mt-2">{{ __('System overview and management') }}</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Total Users') }}</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalUsers }}</p>
                            <p class="text-xs text-gray-600 mt-2">
                                👥 Tourists: {{ $totalTourists }} | 🏢 Providers: {{ $totalProviders }}
                            </p>
                        </div>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Total Services') }}</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalServices }}</p>
                            <p class="text-xs text-gray-600 mt-2">{{ __('Active across platform') }}</p>
                        </div>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Total Bookings') }}</p>
                            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalBookings }}</p>
                            <p class="text-xs text-gray-600 mt-2">
                                ✅ Completed: {{ $completedBookings }} | ⏳ Pending: {{ $pendingBookings }}
                            </p>
                        </div>
                    </div>
                </flux:card>

                <flux:card>
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">{{ __('Total Revenue') }}</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-2">
                                {{ number_format($totalRevenue, 2) }}
                            </p>
                            <p class="text-xs text-gray-600 mt-2">{{ __('From completed bookings') }}</p>
                        </div>
                    </div>
                </flux:card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Users Management -->
                    <flux:card>
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">{{ __('Users Management') }}</h2>
                            <flux:link :href="route('admin.users.create')" wire:navigate class="text-blue-600">
                                {{ __('Add User') }}
                            </flux:link>
                        </div>

                        <!-- Search and Filter -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <flux:input
                                wire:model.live="search"
                                type="search"
                                :placeholder="__('Search users...')"
                                icon="magnifying-glass"
                            />
                            <flux:select
                                wire:model.live="filterRole"
                                :options="['' => 'All Roles', 'tourist' => 'Tourists', 'provider' => 'Providers', 'admin' => 'Admins']"
                            />
                        </div>

                        <!-- Users Table -->
                        @if($users->count())
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="border-b border-gray-200">
                                        <tr>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Name') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Email') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Role') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Joined') }}</th>
                                            <th class="text-left py-3 px-2 text-sm font-semibold text-gray-900">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $user)
                                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                <td class="py-3 px-2 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                                                <td class="py-3 px-2 text-sm text-gray-600">{{ $user->email }}</td>
                                                <td class="py-3 px-2 text-sm">
                                                    <flux:badge
                                                        :color="match($user->role) {
                                                            'tourist' => 'blue',
                                                            'provider' => 'green',
                                                            'admin' => 'red',
                                                            default => 'gray'
                                                        }"
                                                        size="sm"
                                                    >
                                                        {{ ucfirst($user->role) }}
                                                    </flux:badge>
                                                </td>
                                                <td class="py-3 px-2 text-sm text-gray-600">
                                                    {{ $user->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="py-3 px-2 text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <flux:button
                                                            href="{{ route('admin.users.edit', $user) }}"
                                                            size="sm"
                                                            variant="ghost"
                                                            icon="pencil"
                                                            wire:navigate
                                                        />
                                                        <flux:button
                                                            wire:click="deleteUser({{ $user->id }})"
                                                            size="sm"
                                                            variant="ghost"
                                                            icon="trash"
                                                            class="text-red-600 hover:text-red-700"
                                                        />
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-6">
                                {{ $users->links() }}
                            </div>
                        @else
                            <div class="text-center py-12 text-gray-500">
                                <p>{{ __('No users found') }}</p>
                            </div>
                        @endif
                    </flux:card>

                    <!-- Recent Activities -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Recent Services -->
                        <flux:card>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Recent Services') }}</h3>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($recentServices as $service)
                                    <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                                        <p class="font-medium text-gray-900 text-sm">{{ $service->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $service->provider->name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $service->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">{{ __('No services') }}</p>
                                @endforelse
                            </div>
                        </flux:card>

                        <!-- Recent Payments -->
                        <flux:card>
                            <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Recent Payments') }}</h3>
                            <div class="space-y-3 max-h-96 overflow-y-auto">
                                @forelse($recentPayments as $payment)
                                    <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900 text-sm">{{ $payment->user->name }}</p>
                                                <p class="text-xs text-gray-600">{{ $payment->booking->service->name }}</p>
                                            </div>
                                            <span class="text-sm font-bold text-green-600">
                                                +{{ number_format($payment->amount, 2) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center mt-2">
                                            <p class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</p>
                                            <flux:badge
                                                :color="$payment->status === 'completed' ? 'green' : 'yellow'"
                                                size="xs"
                                            >
                                                {{ ucfirst($payment->status) }}
                                            </flux:badge>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-sm">{{ __('No payments') }}</p>
                                @endforelse
                            </div>
                        </flux:card>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- System Health -->
                    <flux:card>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('System Health') }}</h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('Database') }}</span>
                                <flux:badge color="green" size="sm">{{ __('Healthy') }}</flux:badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('Storage') }}</span>
                                <flux:badge color="green" size="sm">{{ __('Good') }}</flux:badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ __('API') }}</span>
                                <flux:badge color="green" size="sm">{{ __('Active') }}</flux:badge>
                            </div>
                        </div>
                    </flux:card>

                    <!-- Quick Links -->
                    <flux:card>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Quick Links') }}</h3>
                        <div class="space-y-2">
                            <flux:link :href="route('admin.locations.index')" wire:navigate class="block text-blue-600 hover:underline">
                                {{ __('Manage Locations') }}
                            </flux:link>
                            <flux:link :href="route('admin.services.index')" wire:navigate class="block text-blue-600 hover:underline">
                                {{ __('View All Services') }}
                            </flux:link>
                            <flux:link :href="route('admin.bookings.index')" wire:navigate class="block text-blue-600 hover:underline">
                                {{ __('View All Bookings') }}
                            </flux:link>
                            <flux:link :href="route('admin.payments.index')" wire:navigate class="block text-blue-600 hover:underline">
                                {{ __('View Payments') }}
                            </flux:link>
                            <flux:link :href="route('notifications.index')" wire:navigate class="block text-blue-600 hover:underline">
                                {{ __('View Notifications') }}
                            </flux:link>
                        </div>
                    </flux:card>

                    <!-- Settings -->
                    <flux:card>
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Settings') }}</h3>
                        <flux:button href="{{ route('admin.settings.index') }}" variant="ghost" wire:navigate class="w-full">
                            {{ __('System Settings') }}
                        </flux:button>
                    </flux:card>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
