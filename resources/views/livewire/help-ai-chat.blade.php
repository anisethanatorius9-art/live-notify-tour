<flux:card class="my-6 w-full overflow-hidden p-0">
    <header class="flex items-center justify-between gap-4 border-b border-zinc-200 px-5 py-4 dark:border-zinc-700 sm:px-6">
        <div class="flex min-w-0 items-center gap-3">
            <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">AI</div>
            <div class="min-w-0">
                <flux:heading size="sm">LNT Travel Assistant</flux:heading>
                <flux:text size="sm">Platform support and worldwide tourism guide</flux:text>
            </div>
        </div>
        <flux:badge color="green">Online</flux:badge>
    </header>

    @if (! $isConfigured)
    <div class="border-b border-red-200 bg-red-50 px-5 py-3 dark:border-red-900 dark:bg-red-950/30" role="alert">
        <flux:text size="sm" color="red">
            <strong>System Notice:</strong> The tourism assistant is missing an active API key. Please set <code>GEMINI_API_KEY</code> on Render.
        </flux:text>
    </div>
    @endif

    <div class="h-96 space-y-4 overflow-y-auto bg-zinc-50/70 p-4 dark:bg-zinc-900/40 sm:p-6" id="chat-window" aria-live="polite" aria-relevant="additions">
        @foreach ($messages as $message)
        @if ($message['role'] === 'user')
        <div wire:key="chat-message-{{ $loop->index }}" class="flex justify-end">
            <flux:text size="sm" class="max-w-[85%] whitespace-pre-line break-words rounded-2xl rounded-tr-sm bg-blue-600 px-4 py-3 !text-white sm:max-w-[80%]">{{ $message['content'] }}</flux:text>
        </div>
        @else
        <div wire:key="chat-message-{{ $loop->index }}" class="flex items-start gap-3">
            <flux:badge color="blue" class="flex size-8 shrink-0 items-center justify-center !rounded-full">AI</flux:badge>
            <flux:text size="sm" class="max-w-[85%] whitespace-pre-line break-words rounded-2xl rounded-tl-sm border border-zinc-200 bg-white px-4 py-3 text-zinc-800 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100 sm:max-w-[80%]">{{ $message['content'] }}</flux:text>
        </div>
        @endif
        @endforeach

        <div wire:loading.flex wire:target="sendMessage" class="items-start gap-3" role="status" aria-live="polite">
            <flux:badge color="blue" class="flex size-8 shrink-0 items-center justify-center !rounded-full">AI</flux:badge>
            <flux:text size="sm" class="rounded-2xl rounded-tl-sm border border-zinc-200 bg-white px-4 py-3 text-zinc-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-400">Thinking...</flux:text>
        </div>
    </div>

    <form wire:submit.prevent="sendMessage" class="flex items-end gap-3 border-t border-zinc-200 p-4 dark:border-zinc-700 sm:p-5">
        <flux:textarea
            id="ai-question"
            wire:model="userMessage"
            rows="1"
            maxlength="2000"
            placeholder="Ask about payments, bookings, or destinations worldwide..."
            aria-label="Ask the LNT Travel Assistant"
            class="min-w-0 flex-1"
            :disabled="! $isConfigured"></flux:textarea>
        <flux:button type="submit" variant="primary" wire:loading.attr="disabled" wire:target="sendMessage" :disabled="! $isConfigured">
            Send
        </flux:button>
    </form>
</flux:card>
