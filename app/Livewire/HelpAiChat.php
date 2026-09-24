<?php

namespace App\Livewire;

use App\Services\TourismAiService;
use Livewire\Component;
class HelpAiChat extends Component
{
    public string $userMessage = '';

    /** @var array<int, array{role: string, content: string}> */
    public array $messages = [];

    public bool $isConfigured = true;
    public bool $isLoading = false;

    public function mount(): void
    {
        $this->isConfigured = (bool) config('services.gemini.key');
        $this->messages[] = [
            'role' => 'assistant',
            'content' => "Hello! I'm your LNT Travel Concierge. Ask me anything about our platform, bookings, payment methods, or tourism recommendations worldwide!",
        ];
    }

    public function sendMessage(TourismAiService $aiService): void
    {
        $message = trim($this->userMessage);

        if ($message === '' || ! $this->isConfigured || $this->isLoading) {
            return;
        }

        $this->validate(['userMessage' => ['string', 'max:2000']]);
        $this->messages[] = ['role' => 'user', 'content' => $message];
        $this->userMessage = '';
        $this->isLoading = true;

        try {
            $response = $aiService->generateResponse($this->messages);
        } finally {
            $this->isLoading = false;
        }

        if ($response === 'ERROR_NOT_CONFIGURED') {
            $this->isConfigured = false;
            return;
        }

        $this->messages[] = ['role' => 'assistant', 'content' => $response];
    }

    public function render()
    {
        return view('livewire.help-ai-chat');
    }
}
