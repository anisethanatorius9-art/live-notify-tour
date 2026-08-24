<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TourismAiService
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are the helpful support and tourism assistant for Live and Notify Tourism in Tanzania.
Answer clearly and concisely. For platform questions, explain the relevant Live and Notify Tourism features without inventing policies, prices, booking status, or payment confirmation. For tourism questions, give practical Tanzania-focused suggestions and mention when details such as opening hours, availability, or prices should be verified. If you do not know something, say so and direct the user to Contact Support. Never claim to have completed an action or accessed private account data.
PROMPT;

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function ask(string $message, array $history = []): string
    {
        $apiKey = config('services.gemini.key');

        if (! is_string($apiKey) || trim($apiKey) === '') {
            throw new RuntimeException('The tourism assistant is not configured.');
        }

        $contents = collect($history)
            ->filter(fn (array $entry): bool => in_array($entry['role'] ?? null, ['user', 'model'], true))
            ->map(fn (array $entry): array => [
                'role' => $entry['role'],
                'parts' => [['text' => $entry['content']]],
            ])
            ->push([
                'role' => 'user',
                'parts' => [['text' => $message]],
            ])
            ->values()
            ->all();

        try {
            $response = Http::timeout(30)
                ->acceptJson()
                ->post(sprintf(
                    'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
                    config('services.gemini.model', 'gemini-2.0-flash'),
                    urlencode($apiKey),
                ), [
                    'system_instruction' => [
                        'parts' => [['text' => self::SYSTEM_PROMPT]],
                    ],
                    'contents' => $contents,
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 600,
                    ],
                ])
                ->throw();
        } catch (\Throwable $exception) {
            Log::error('Tourism assistant request failed', [
                'error' => $exception->getMessage(),
            ]);

            throw new RuntimeException('The tourism assistant is temporarily unavailable.', 0, $exception);
        }

        $answer = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($answer) || trim($answer) === '') {
            Log::warning('Tourism assistant returned no answer', ['response' => $response->json()]);

            throw new RuntimeException('The tourism assistant returned an empty response.');
        }

        return trim($answer);
    }
}
