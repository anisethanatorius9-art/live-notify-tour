<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TourismAiService
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are "LNT Concierge," the official AI travel assistant and platform guide for Live & Notify Tourism (LNT).

YOUR KNOWLEDGE & CAPABILITIES:
1. PLATFORM NAVIGATION: Guide users on using LNT features (Dashboard, My Bookings, Locations, Tourism Map, Categories, Account Settings, Notifications).
2. PAYMENTS & BOOKINGS: Explain how to complete bookings, handle failed payment transactions, and use supported payment options (Mobile Money: M-Pesa, Tigo Pesa, Airtel Money, as well as Card payments).
3. LOCAL & WORLDWIDE TOURISM GUIDE: Act as an expert travel guide. Answer questions about Tanzanian attractions and global destinations, travel itineraries, passport and visa considerations, packing tips, and regional travel advice.

TONE & BEHAVIOR:
- Be warm, polite, conversational, and informative, like a professional travel concierge.
- Use structured bullet points and bold headers to keep answers scannable.
- Never invent account data, booking status, prices, policies, or payment confirmation. Recommend verifying current opening hours, availability, visa rules, and prices.
- If an account-specific billing failure requires database intervention, direct the user to Contact Support through the Help Center.
PROMPT;

    public function ask(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:1000',
        ]);
        $answer = $this->generateResponse([
            ['role' => 'user', 'content' => $request->input('prompt')],
        ]);

        if ($answer === 'ERROR_NOT_CONFIGURED') {
            return response()->json(['error' => 'The tourism assistant is temporarily unavailable.'], 500);
        }

        return $answer;
    }

    /**
     * @param  array<int, array{role: string, content: string}>  $messages
     */
    public function generateResponse(array $messages): string
    {
        $apiKey = config('services.gemini.key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');

        if (! $apiKey) {
            Log::error('Gemini API key is missing in config/services.php or .env');
            return 'ERROR_NOT_CONFIGURED';
        }

        $endpoint = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
            $model,
            urlencode($apiKey)
        );

        try {
            $response = Http::timeout(60)
                ->connectTimeout(15)
                ->post($endpoint, [
                    'system_instruction' => [
                        'parts' => [['text' => self::SYSTEM_PROMPT]],
                    ],
                    'contents' => [
                        ...array_map(static fn(array $message): array => [
                            'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                            'parts' => [['text' => $message['content']]],
                        ], $messages),
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1000,
                    ],
                ]);
        } catch (\Throwable $exception) {
            Log::error('Tourism assistant request failed', [
                'error' => $exception->getMessage(),
            ]);

            return 'I am having trouble connecting to the travel service right now. Please try again shortly!';
        }

        $answer = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($answer) || trim($answer) === '') {
            return "I couldn't process that request. How else can I assist your travel plans?";
        }

        return trim($answer);
    }
}
