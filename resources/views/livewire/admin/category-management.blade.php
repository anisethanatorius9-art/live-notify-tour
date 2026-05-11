<x-layouts.app>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Category Management</h1>
                <p class="text-sm text-gray-600 dark:text-zinc-400">Manage tourism categories</p>
            </div>
            <flux:button wire:click="create" variant="primary">
                <flux:icon.plus class="w-5 h-5" />
                New Category
            </flux:button>
        </div>

        <!-- Search -->
        <flux:input
            wire:model.live="search"
            icon="magnifying-glass"
            placeholder="Search categories..."
            type="search" />

        <!-- Categories Table -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow">
            <table class="w-full">
                <thead class="border-b border-gray-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-zinc-400">{{ Str::limit($category->description, 50) }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <flux:button wire:click="edit({{ $category->id }})" size="sm" variant="ghost">Edit</flux:button>
                            <flux:button wire:click="delete({{ $category->id }})" size="sm" variant="danger">Delete</flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-600 dark:text-zinc-400">
                            No categories found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $categories->links() }}
        </div>

        <!-- Modal -->
        @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg max-w-md w-full mx-4 p-6 space-y-4">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $editingId ? 'Edit Category' : 'New Category' }}
                </h2>

                <flux:input
                    wire:model="name"
                    label="Category Name"
                    placeholder="Enter category name"
                    :error="$errors->first('name')" />

                <flux:textarea
                    wire:model="description"
                    label="Description"
                    placeholder="Category description"
                    :error="$errors->first('description')" />

                <div class="flex gap-2 pt-4">
                    <flux:button wire:click="closeModal" variant="ghost" class="flex-1">Cancel</flux:button>
                    <flux:button wire:click="save" variant="primary" class="flex-1">Save</flux:button>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>
        <flux:button wire:click="create" variant="primary">
            <flux:icon.plus class="w-5 h-5" />
            New Category
        </flux:button>
    </div>

    <!-- Search -->
    <flux:input
        wire:model.live="search"
        icon="magnifying-glass"
        placeholder="Search categories..."
        type="search" />

    <!-- Categories Table -->
    <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow">
        <table class="w-full">
            <thead class="border-b border-gray-200 dark:border-zinc-700">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Description</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($category->description, 50) }}</td>
                    <td class="px-6 py-4 text-sm space-x-2">
                        <flux:button wire:click="edit({{ $category->id }})" size="sm" variant="ghost">Edit</flux:button>
                        <flux:button wire:click="delete({{ $category->id }})" size="sm" variant="danger">Delete</flux:button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-600 dark:text-gray-400">
                        No categories found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div>
        {{ $categories->links() }}
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-lg max-w-md w-full mx-4 p-6 space-y-4">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $editingId ? 'Edit Category' : 'New Category' }}
            </h2>

            <flux:input
                wire:model="name"
                label="Category Name"
                placeholder="Enter category name"
                :error="$errors->first('name')" />

            <flux:textarea
                wire:model="description"
                label="Description"
                placeholder="Category description"
                :error="$errors->first('description')" />

            <div class="flex gap-2 pt-4">
                <flux:button wire:click="closeModal" variant="ghost" class="flex-1">Cancel</flux:button>
                <flux:button wire:click="save" variant="primary" class="flex-1">Save</flux:button>
            </div>
        </div>
    </div>
    @endif
</div>
