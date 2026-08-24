<section class="mb-10 rounded-2xl border border-blue-100 bg-blue-50/60 p-5 sm:p-6" aria-labelledby="assistant-heading">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Tourism assistant</p>
            <h2 id="assistant-heading" class="mt-1 text-xl font-bold text-zinc-900">Ask about the platform or Tanzania travel</h2>
            <p class="mt-1 text-sm text-zinc-600">Get quick guidance while you plan your next experience.</p>
        </div>
        @if(count($chatHistory) > 0)
            <button type="button" wire:click="clearChat" class="text-sm font-medium text-blue-700 hover:text-blue-900">Clear chat</button>
        @endif
    </div>

    @if(count($chatHistory) > 0)
        <div class="mt-5 max-h-96 space-y-3 overflow-y-auto rounded-xl bg-white p-4" aria-live="polite">
            @foreach($chatHistory as $message)
                <div wire:key="chat-message-{{ $loop->index }}" class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                    <p class="max-w-3xl whitespace-pre-line rounded-xl px-4 py-3 text-sm {{ $message['role'] === 'user' ? 'bg-blue-600 text-white' : 'bg-zinc-100 text-zinc-800' }}">
                        {{ $message['content'] }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    @if($error)
        <p class="mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700" role="alert">{{ $error }}</p>
    @endif

    <form wire:submit="sendMessage" class="mt-5 flex flex-col gap-3 sm:flex-row">
        <label for="ai-question" class="sr-only">Ask the tourism assistant</label>
        <input id="ai-question" type="text" wire:model="userMessage" placeholder="e.g. How do I pay with M-Pesa?" maxlength="2000" class="min-w-0 flex-1 rounded-xl border border-zinc-200 bg-white px-4 py-3 outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" wire:loading.attr="disabled" class="rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700 disabled:cursor-wait disabled:opacity-60">
            <span wire:loading.remove>Ask assistant</span>
            <span wire:loading>Thinking...</span>
        </button>
    </form>
    @error('userMessage') <p class="mt-2 text-sm text-red-700">{{ $message }}</p> @enderror
</section>
