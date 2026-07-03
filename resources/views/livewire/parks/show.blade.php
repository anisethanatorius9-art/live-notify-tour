<x-layouts.app :title="$park['name']">
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <flux:card>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    <div class="md:col-span-2">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $park['name'] }}</h1>
                        <p class="text-gray-600 mt-2 flex items-center gap-2">
                            <img src="https://flagcdn.com/16x12/{{ strtolower($park['flag']) }}.png" alt="{{ $park['flag'] }}" />
                            {{ $park['location'] }}
                        </p>

                        <div class="mt-4">
                            <img src="{{ $park['image_url'] }}" alt="{{ $park['name'] }}" class="rounded-md w-full h-80 object-cover" />
                        </div>

                        <div class="mt-6 text-gray-700">
                            <p>{{ __('Sample park experience and guided visits. Prices shown are approximate and in local currency.') }}</p>
                        </div>
                    </div>

                    <div class="md:col-span-1 bg-white rounded-lg border border-gray-200 p-4">
                        <div class="text-xl font-bold">{{ $park['currency'] }} {{ number_format($park['price']) }}</div>
                        <div class="text-sm text-gray-500">{{ __('Approx. price per person (local currency)') }}</div>

                        <div class="mt-4">
                            <a href="{{ route('bookings.create.park', $parkKey) }}" class="w-full inline-block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">{{ __('Book') }}</a>
                        </div>

                        <div class="mt-4 text-sm text-gray-500">{{ __('Contact us for custom packages and group discounts.') }}</div>
                    </div>
                </div>
            </flux:card>
        </div>
    </div>
</x-layouts.app>
