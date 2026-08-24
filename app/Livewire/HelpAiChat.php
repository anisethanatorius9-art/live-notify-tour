<?php

namespace App\Livewire;

use App\Services\TourismAiService;
use Livewire\Component;
use Throwable;

class HelpAiChat extends Component
{
    public string $userMessage = '';

    /** @var array<int, array{role: string, content: string}> */
    public array $chatHistory = [];

    public ?string $error = null;

    public function sendMessage(TourismAiService $tourismAiService): void
    {
        $this->validate([
            'userMessage' => ['required', 'string', 'max:2000'],
        ]);

        $message = trim($this->userMessage);

        if ($message === '') {
            $this->addError('userMessage', 'Please enter a question.');
            return;
        }

        $this->error = null;

        try {
            $answer = $tourismAiService->ask($message, $this->chatHistory);
        } catch (Throwable $exception) {
            $this->error = $exception->getMessage();
            return;
        }

        $this->chatHistory[] = ['role' => 'user', 'content' => $message];
        $this->chatHistory[] = ['role' => 'model', 'content' => $answer];
        $this->chatHistory = array_slice($this->chatHistory, -12);
        $this->userMessage = '';
    }

    public function clearChat(): void
    {
        $this->chatHistory = [];
        $this->error = null;
    }

    public function render()
    {
        return view('livewire.help-ai-chat');
    }
}
