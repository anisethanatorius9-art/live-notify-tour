<x-layouts.app>
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700 mb-4 inline-block">
                    ← {{ __('Back to Dashboard') }}
                </a>
                <h1 class="text-4xl font-bold text-gray-900">{{ __('Complete Your Booking') }}</h1>
                <p class="text-gray-600 mt-2">{{ __('Review the service details and proceed with your booking') }}</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Service Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Service Details') }}</h2>

                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $service->name }}</h3>
                                    <p class="text-gray-600 mt-1">📍 {{ $service->location->name }}</p>
                                </div>
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $service->category }}
                                </span>
                            </div>

                            <p class="text-gray-600">{{ $service->description }}</p>

                            <div class="flex items-center gap-2 text-sm">
                                <span class="text-gray-500">{{ __('Provider') }}:</span>
                                <span class="font-medium text-gray-900">{{ $service->provider->name }}</span>
                            </div>

                            <div class="flex items-center gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $service->rating)
                                    <span class="text-yellow-400">⭐</span>
                                    @else
                                    <span class="text-gray-300">☆</span>
                                    @endif
                                    @endfor
                                    <span class="text-sm text-gray-600 ml-2">({{ $service->rating }}/5)</span>
                            </div>

                            <div class="border-t pt-4">
                                <p class="text-lg font-bold text-gray-900">
                                    {{ __('Price per person') }}:
                                    <span class="text-blue-600">{{ number_format($service->price, 2) }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Booking Information') }}</h2>

                        <form wire:submit.prevent="createBooking" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Booking Date') }} *
                                </label>
                                <input
                                    wire:model="bookingDate"
                                    type="date"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                                @error('bookingDate')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Number of People') }} *
                                </label>
                                <input
                                    wire:model="numberOfPeople"
                                    type="number"
                                    min="1"
                                    max="50"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none" />
                                @error('numberOfPeople')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ __('Additional Notes') }}
                                </label>
                                <textarea
                                    wire:model="notes"
                                    rows="4"
                                    placeholder="{{ __('Add any special requests or notes...') }}"
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none"></textarea>
                                @error('notes')
                                <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                                {{ __('Proceed to Payment') }}
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('Price Summary') }}</h3>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-gray-600">
                                <span>{{ __('Price per person') }}</span>
                                <span>{{ number_format($service->price, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray.600">
                                <span>{{ __('Number of people') }}</span>
                                <span>{{ $numberOfPeople }}</span>
                            </div>
                            <div class="border-t pt-3 flex justify-between font-bold text-lg">
                                <span>{{ __('Total') }}</span>
                                <span class="text-blue-600">{{ number_format($totalPrice, 2) }}</span>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-700">
                            <p>{{ __('You will be able to choose a payment method on the next step.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
