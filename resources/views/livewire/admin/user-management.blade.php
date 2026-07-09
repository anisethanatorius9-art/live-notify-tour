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
                    <input type="text" wire:model.live="search" placeholder="Search by name or email..." class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-zinc-300">Filter by Role</label>
                    <select wire:model.live="filterRole" class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                        <option value="">All Roles</option>
                        <option value="tourist">Tourist</option>
                        <option value="provider">Service Provider</option>
                        <option value="admin">Admin</option>
                    </select>
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
                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                                        @if($user->role === 'tourist') bg-blue-100 text-blue-800
                                        @elseif($user->role === 'provider') bg-green-100 text-green-800
                                        @elseif($user->role === 'admin') bg-red-100 text-red-800
                                        @endif
                                    ">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <select wire:change="updateUserRole({{ $user->id }}, $event.target.value)" class="rounded-lg border border-gray-300 px-3 py-1 text-sm focus:border-transparent focus:ring-2 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white">
                                        <option value="tourist" @if($user->role === 'tourist') selected @endif>Tourist</option>
                                        <option value="provider" @if($user->role === 'provider') selected @endif>Provider</option>
                                        <option value="admin" @if($user->role === 'admin') selected @endif>Admin</option>
                                    </select>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-zinc-400">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if($user->id !== auth()->id())
                                        <button type="button" wire:click="deleteUser({{ $user->id }})" wire:confirm="Are you sure you want to delete this user?" class="font-medium text-red-600 hover:text-red-900">
                                            Delete
                                        </button>
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
