<div class="p-6 max-w-6xl mx-auto">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold">Help Center</h1>
            <p class="text-gray-600 mt-2">Quick answers and support for common questions.</p>
        </div>
    </div>

    <div class="mb-8">
        <label for="help-search" class="sr-only">How can we help you?</label>
        <div class="relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input
                id="help-search"
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="How can we help you?"
                class="w-full pl-12 pr-4 py-4 rounded-2xl border border-zinc-200 focus:ring-2 focus:ring-blue-500 outline-none"
            >
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-10">
        @foreach($categories as $category)
            <button
                type="button"
                wire:click="selectCategory('{{ $category['name'] }}')"
                class="flex flex-col gap-3 p-6 rounded-2xl shadow {{ $selectedCategory === $category['name'] ? 'border border-blue-500 shadow-lg' : 'bg-white' }} hover:shadow-lg transition"
            >
                <span class="inline-flex items-center justify-center h-12 w-12 rounded-2xl bg-blue-50 text-blue-600">
                    @if($category['icon'] === 'user')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    @elseif($category['icon'] === 'map-pin')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21s-6-5.686-6-10a6 6 0 1112 0c0 4.314-6 10-6 10z" />
                        </svg>
                    @elseif($category['icon'] === 'credit-card')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="2" y="5" width="20" height="14" rx="2" ry="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 10h20" />
                        </svg>
                    @elseif($category['icon'] === 'bell')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
                        </svg>
                    @elseif($category['icon'] === 'cog-6-tooth')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-1.14 1.971-1.14 2.271 0a1.724 1.724 0 002.588 1.028l.84-.484c1.065-.614 2.363.215 2.363 1.4v1.05c0 .593.231 1.163.646 1.577l.745.744c.785.785.785 2.057 0 2.842l-.745.745a2.238 2.238 0 00-.646 1.577v1.05c0 1.185-1.298 2.014-2.363 1.4l-.84-.484a1.724 1.724 0 00-2.588 1.028c-.3 1.14-1.97 1.14-2.27 0a1.724 1.724 0 00-2.588-1.028l-.84.484c-1.065.614-2.363-.215-2.363-1.4v-1.05c0-.593-.231-1.163-.646-1.577l-.745-.745c-.785-.785-.785-2.057 0-2.842l.745-.745c.415-.414.646-.984.646-1.577V6.87c0-1.185 1.298-2.014 2.363-1.4l.84.484a1.724 1.724 0 002.588-1.028z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    @endif
                </span>
                <div class="text-left">
                    <h3 class="text-lg font-semibold">{{ $category['name'] }}</h3>
                    <p class="text-gray-500 text-sm">{{ $category['description'] }}</p>
                </div>
            </button>
        @endforeach
    </div>

    <div class="mb-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
            <h2 class="text-xl font-bold">Frequently Asked Questions</h2>
            <p class="text-gray-500">Showing {{ $filteredFaqs->count() }} answer(s)</p>
        </div>

        <div x-data="{ open: null }" class="space-y-3">
            @forelse($filteredFaqs as $index => $faq)
                <div class="border rounded-xl p-4">
                    <button @click="open === {{ $index }} ? open = null : open = {{ $index }}" class="w-full text-left font-medium">
                        {{ $faq['question'] }}
                    </button>
                    <p x-show="open === {{ $index }}" x-cloak class="mt-2 text-gray-500">
                        {{ $faq['answer'] }}
                    </p>
                </div>
            @empty
                <div class="border rounded-xl p-4 bg-zinc-50 text-gray-600">
                    No matching help articles were found.
                </div>
            @endforelse
        </div>
    </div>

    <div class="bg-blue-50 p-6 rounded-2xl text-center">
        <h3 class="text-lg font-semibold mb-2">Still need help?</h3>
        <p class="text-gray-600 mb-4">Contact our support team for additional assistance.</p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-xl">Contact Support</button>
            <button class="px-4 py-2 border border-zinc-300 rounded-xl">Report an Issue</button>
        </div>
    </div>
</div>
