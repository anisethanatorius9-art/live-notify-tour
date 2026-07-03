<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <flux:card>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            {{ $service ? __('Edit Service') : __('Create New Service') }}
                        </h1>
                        <p class="text-gray-600 mt-2">
                            {{ $service ? __('Update service details and pricing.') : __('Add a new service so tourists can book it.') }}
                        </p>
                    </div>
                    <flux:button href="{{ route('services.index') }}" variant="secondary" wire:navigate>
                        {{ __('Back to services') }}
                    </flux:button>
                </div>
            </flux:card>

            <flux:card class="mt-8">
                <form wire:submit.prevent="saveService" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:input
                            wire:model.defer="name"
                            label="{{ __('Service name') }}"
                            placeholder="{{ __('Enter a clear service title') }}"
                            :error="$errors->first('name')" />

                        <flux:input
                            wire:model.defer="price"
                            type="number"
                            step="0.01"
                            min="0"
                            label="{{ __('Price per person') }}"
                            placeholder="{{ __('Enter the price') }}"
                            :error="$errors->first('price')" />
                    </div>

                    <flux:textarea
                        wire:model.defer="description"
                        label="{{ __('Description') }}"
                        placeholder="{{ __('Describe the service, itinerary, or experience') }}"
                        :error="$errors->first('description')" />

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:select wire:model.defer="location_id" label="{{ __('Location') }}" :error="$errors->first('location_id')">
                            <flux:select.option value="">{{ __('Select a location') }}</flux:select.option>
                            @foreach($locations as $locationValue => $locationLabel)
                                <flux:select.option value="{{ $locationValue }}">{{ $locationLabel }}</flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:select wire:model.defer="category" label="{{ __('Category') }}" :error="$errors->first('category')">
                            <flux:select.option value="">{{ __('Select a category') }}</flux:select.option>
                            @foreach($categories as $categoryValue => $categoryLabel)
                                <flux:select.option value="{{ $categoryValue }}">{{ $categoryLabel }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>

                    <flux:input
                        wire:model.defer="customCategory"
                        label="{{ __('Or add a new category') }}"
                        placeholder="{{ __('Enter a new category if none fit') }}"
                        :error="$errors->first('customCategory')" />

                    <div>
                        <label class="block text-sm font-medium text-gray-700">{{ __('Service Image') }}</label>
                        <div class="mt-2 flex items-center gap-4">
                            <div class="w-32 h-20 rounded-md bg-gray-100 overflow-hidden">
                                @if($image)
                                <img src="{{ $image->temporaryUrl() }}" alt="preview" class="w-full h-full object-cover" />
                                @elseif($image_url)
                                <img src="{{ $image_url }}" alt="preview" class="w-full h-full object-cover" />
                                @else
                                <div class="flex items-center justify-center h-full text-gray-400">No image</div>
                                @endif
                            </div>

                            <div class="flex flex-col gap-2">
                                <input type="file" wire:model="image" accept="image/*" class="text-sm" />
                                @if($errors->first('image'))
                                <div class="text-sm text-red-600">{{ $errors->first('image') }}</div>
                                @endif
                                <flux:input
                                    wire:model.defer="image_url"
                                    label="{{ __('Or image URL') }}"
                                    placeholder="{{ __('Add a service picture URL') }}"
                                    :error="$errors->first('image_url')" />
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <flux:select wire:model.defer="status" label="{{ __('Status') }}" :error="$errors->first('status')">
                            <flux:select.option value="active">{{ __('Active') }}</flux:select.option>
                            <flux:select.option value="inactive">{{ __('Inactive') }}</flux:select.option>
                        </flux:select>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <flux:button type="submit" variant="primary">
                            {{ $service ? __('Save Service') : __('Create Service') }}
                        </flux:button>
                        <flux:button type="button" variant="secondary" href="{{ route('services.index') }}" wire:navigate>
                            {{ __('Cancel') }}
                        </flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
</x-layouts.app>
