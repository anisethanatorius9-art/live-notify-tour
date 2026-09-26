<div class="my-6 w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-200 bg-slate-50 px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">AI</div>
            <div>
                <h3 class="text-sm font-semibold text-slate-800">LNT Travel Assistant</h3>
                <p class="text-xs text-slate-500">Platform support and worldwide tourism guide</p>
            </div>
        </div>
        <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-medium text-emerald-800">Online</span>
    </div>

    @if(! $isConfigured)
    <div class="border-b border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
        <strong>System Notice:</strong> The tourism assistant is missing an active API key. Please set <code>GEMINI_API_KEY</code> on Render.
    </div>
    @endif

    <div class="h-96 space-y-4 overflow-y-auto bg-slate-50/50 p-6" id="chat-window" aria-live="polite">
        @foreach($messages as $message)
        @if($message['role'] === 'user')
        <div wire:key="chat-message-{{ $loop->index }}" class="flex justify-end">
            <div class="max-w-[80%] rounded-2xl rounded-tr-none bg-blue-600 px-4 py-3 text-sm text-white shadow-sm">{{ $message['content'] }}</div>
        </div>
        @else
        <div wire:key="chat-message-{{ $loop->index }}" class="flex justify-start gap-3">
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">AI</div>
            <div class="max-w-[85%] space-y-2 whitespace-pre-line rounded-2xl rounded-tl-none border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 shadow-sm">{{ $message['content'] }}</div>
        </div>
        @endif
        @endforeach
        <div wire:loading.flex wire:target="sendMessage" class="justify-start gap-3">
            <div class="flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">AI</div>
            <div class="rounded-2xl rounded-tl-none border border-slate-200 bg-white px-4 py-3 text-sm text-slate-500 shadow-sm">Thinking...</div>
        </div>
    </div>

    <form wire:submit.prevent="sendMessage" class="flex gap-2 border-t border-slate-200 bg-white p-4">
        <label for="ai-question" class="sr-only">Ask the LNT Travel Assistant</label>
        <input id="ai-question" type="text" wire:model="userMessage" maxlength="2000" placeholder="Ask about payments, bookings, or destinations worldwide..." class="min-w-0 flex-1 rounded-xl border-0 bg-slate-100 px-4 py-3 text-sm transition focus:bg-white focus:ring-2 focus:ring-blue-500" @disabled(! $isConfigured)>
        <button type="submit" wire:loading.attr="disabled" wire:target="sendMessage" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-wait disabled:opacity-50" @disabled(! $isConfigured)>Send</button>
    </form>
</div>
