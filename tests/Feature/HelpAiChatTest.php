<?php

namespace Tests\Feature;

use App\Livewire\HelpAiChat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class HelpAiChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_assistant_appends_the_question_and_answer_to_chat_history(): void
    {
        config(['services.gemini.key' => 'test-key']);
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [['content' => ['parts' => [['text' => 'Use the M-Pesa option at checkout.']]]]],
            ]),
        ]);

        Livewire::test(HelpAiChat::class)
            ->set('userMessage', 'How do I pay with M-Pesa?')
            ->call('sendMessage')
            ->assertSet('userMessage', '')
            ->assertSet('chatHistory.0.content', 'How do I pay with M-Pesa?')
            ->assertSet('chatHistory.1.content', 'Use the M-Pesa option at checkout.')
            ->assertHasNoErrors();
    }

    public function test_assistant_reports_configuration_errors_without_losing_the_question(): void
    {
        config(['services.gemini.key' => null]);

        Livewire::test(HelpAiChat::class)
            ->set('userMessage', 'Recommend places to visit in Dar es Salaam')
            ->call('sendMessage')
            ->assertSet('userMessage', 'Recommend places to visit in Dar es Salaam')
            ->assertSet('error', 'The tourism assistant is not configured.')
            ->assertSet('chatHistory', []);
    }
}
