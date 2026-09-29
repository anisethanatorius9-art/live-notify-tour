<x-layouts.app>
    <div class="space-y-6 bg-gray-50 px-4 py-6 dark:bg-zinc-950 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">User Management</h1>
                <p class="mt-2 text-gray-600 dark:text-zinc-400">Manage user roles and permissions</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-900">
                <div class="text-sm font-medium text-gray-600 dark:text-zinc-400">Total Users</div>
                <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-900">
                <div class="text-sm font-medium text-gray-600 dark:text-zinc-400">Tourists</div>
                <div class="mt-2 text-3xl font-bold text-blue-600">{{ $stats['tourists'] }}</div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-900">
                <div class="text-sm font-medium text-gray-600 dark:text-zinc-400">Providers</div>
                <div class="mt-2 text-3xl font-bold text-green-600">{{ $stats['providers'] }}</div>
            </div>
            <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-900">
                <div class="text-sm font-medium text-gray-600 dark:text-zinc-400">Admins</div>
                <div class="mt-2 text-3xl font-bold text-red-600">{{ $stats['admins'] }}</div>
            </div>
        </div>

        <div class="rounded-lg bg-white p-6 shadow dark:bg-zinc-900">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-zinc-300">Search</label>
                    <flux:input type="search" wire:model.live="search" placeholder="Search by name or email..." aria-label="Search by name or email" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-zinc-300">Filter by Role</label>
                    <flux:select wire:model.live="filterRole" aria-label="Filter by role">
                        <flux:select.option value="">All Roles</flux:select.option>
                        <flux:select.option value="tourist">Tourist</flux:select.option>
                        <flux:select.option value="provider">Service Provider</flux:select.option>
                        <flux:select.option value="admin">Admin</flux:select.option>
                    </flux:select>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:border-zinc-800 dark:bg-zinc-800/70">
                        <tr>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 hover:bg-gray-100 dark:text-zinc-300" wire:click="toggleSort('name')">
                                Name
                                @if($sortBy === 'name')
                                    <span class="ml-2">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 hover:bg-gray-100 dark:text-zinc-300" wire:click="toggleSort('email')">
                                Email
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-zinc-300">
                                Current Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-zinc-300">
                                Change Role
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 hover:bg-gray-100 dark:text-zinc-300" wire:click="toggleSort('created_at')">
                                Joined
                                @if($sortBy === 'created_at')
                                    <span class="ml-2">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-zinc-300">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-zinc-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/60">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="text-sm text-gray-600 dark:text-zinc-400">{{ $user->email }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <flux:badge :color="$user->role === 'tourist' ? 'blue' : ($user->role === 'provider' ? 'green' : 'red')">
                                        {{ ucfirst($user->role) }}
                                    </flux:badge>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <flux:select wire:change="updateUserRole({{ $user->id }}, $event.target.value)" aria-label="Change role for {{ $user->name }}">
                                        <flux:select.option value="tourist" :selected="$user->role === 'tourist'">Tourist</flux:select.option>
                                        <flux:select.option value="provider" :selected="$user->role === 'provider'">Provider</flux:select.option>
                                        <flux:select.option value="admin" :selected="$user->role === 'admin'">Admin</flux:select.option>
                                    </flux:select>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-zinc-400">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if($user->id !== auth()->id())
                                        <flux:button type="button" wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" variant="danger" size="sm">
                                            Delete
                                        </flux:button>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-zinc-400">
                                    No users found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-center">
            {{ $users->links() }}
        </div>
    </div>
</x-layouts.app>
