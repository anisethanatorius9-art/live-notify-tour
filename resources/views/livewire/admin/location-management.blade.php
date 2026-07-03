<x-layouts.app>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Location Management</h1>
                <p class="text-sm text-gray-600 dark:text-zinc-400">Manage tourism locations</p>
            </div>
            <flux:button wire:click="create" variant="primary">
                <flux:icon.plus class="w-5 h-5" />
                New Location
            </flux:button>
        </div>

        <!-- Search -->
        <flux:input
            wire:model.live="search"
            icon="magnifying-glass"
            placeholder="Search locations..."
            type="search" />

        <!-- Locations Table -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow">
            <table class="w-full">
                <thead class="border-b border-gray-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Description</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Coordinates</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @php /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Location[] $locations */ @endphp
                    @php /** @var \App\Models\Location $location */ @endphp
                    @forelse($locations as $location)
                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $location->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-zinc-400">{{ Str::limit($location->description, 50) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-zinc-400">
                            @if($location->latitude && $location->longitude)
                            {{ $location->latitude }}, {{ $location->longitude }}
                            @else
                            -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <flux:button wire:click="edit({{ $location->id }})" size="sm" variant="ghost">Edit</flux:button>
                            <flux:button wire:click="delete({{ $location->id }})" size="sm" variant="danger">Delete</flux:button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-600 dark:text-zinc-400">
                            No locations found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $locations->links() }}
        </div>

        <!-- Modal (Flux) -->
        <flux:modal wire:model="showModal" name="location-modal" class="max-w-md">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                {{ $editingId ? 'Edit Location' : 'New Location' }}
            </h2>

            <flux:input
                wire:model="name"
                label="Location Name"
                placeholder="Enter location name"
                :error="$errors->first('name')" />

            <flux:textarea
                wire:model="description"
                label="Description"
                placeholder="Location description"
                :error="$errors->first('description')" />

            <flux:input
                wire:model="latitude"
                label="Latitude"
                placeholder="-6.7924"
                type="number"
                step="0.0001"
                :error="$errors->first('latitude')" />

            <flux:input
                wire:model="longitude"
                label="Longitude"
                placeholder="39.2083"
                type="number"
                step="0.0001"
                :error="$errors->first('longitude')" />

            <div class="flex gap-2 pt-4">
                <flux:modal.close>
                    <flux:button variant="ghost" class="flex-1">Cancel</flux:button>
                </flux:modal.close>

                <flux:button wire:click="save" variant="primary" class="flex-1">Save</flux:button>
            </div>
        </flux:modal>
    </div>
</x-layouts.app>
