<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsService
{
    /**
     * Send OTP via SMS using Twilio
     */
    public static function sendOtp(string $phone, string $otp, string $country = 'TZ'): bool
    {
        $provider = config('services.sms.provider', 'log');

        return match ($provider) {
            'twilio' => self::sendViaTwilio($phone, $otp, $country),
            'twilio_whatsapp' => self::sendViaWhatsApp($phone, $otp, $country),
            default => self::logOtp($phone, $otp),
        };
    }

    /**
     * Send OTP via Twilio SMS
     */
    private static function sendViaTwilio(string $phone, string $otp, string $country): bool
    {
        try {
            $accountSid = config('services.twilio.account_sid');
            $authToken = config('services.twilio.auth_token');
            $fromNumber = config('services.twilio.from_number');

            if (! $accountSid || ! $authToken || ! $fromNumber) {
                Log::warning('Twilio SMS: Missing credentials in configuration');
                return false;
            }

            $client = new \Twilio\Rest\Client($accountSid, $authToken);

            $message = $client->messages->create(
                $phone,
                [
                    'from' => $fromNumber,
                    'body' => "Your Live and Notify Tourism OTP is: {$otp}. Valid for 10 minutes.",
                ]
            );

            Log::info('SMS sent via Twilio', [
                'phone' => $phone,
                'sid' => $message->sid,
            ]);

            return true;
        } catch (\Throwable $exception) {
            Log::error('SMS via Twilio failed', [
                'phone' => $phone,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send OTP via Twilio WhatsApp
     */
    private static function sendViaWhatsApp(string $phone, string $otp, string $country): bool
    {
        try {
            $accountSid = config('services.twilio.account_sid');
            $authToken = config('services.twilio.auth_token');
            $fromWhatsapp = config('services.twilio.from_whatsapp');

            if (! $accountSid || ! $authToken || ! $fromWhatsapp) {
                Log::warning('Twilio WhatsApp: Missing credentials in configuration');
                return false;
            }

            $client = new \Twilio\Rest\Client($accountSid, $authToken);

            // Format phone to WhatsApp format: whatsapp:+<country_code><phone>
            $whatsappPhone = 'whatsapp:' . (str_starts_with($phone, '+') ? $phone : '+' . $phone);

            $message = $client->messages->create(
                $whatsappPhone,
                [
                    'from' => 'whatsapp:' . $fromWhatsapp,
                    'body' => "Your Live and Notify Tourism OTP is: {$otp}\nValid for 10 minutes.",
                ]
            );

            Log::info('OTP sent via Twilio WhatsApp', [
                'phone' => $phone,
                'sid' => $message->sid,
            ]);

            return true;
        } catch (\Throwable $exception) {
            Log::error('OTP via Twilio WhatsApp failed', [
                'phone' => $phone,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Log OTP for development/testing
     */
    private static function logOtp(string $phone, string $otp): bool
    {
        Log::info('OTP (development/test mode)', [
            'phone' => $phone,
            'otp' => $otp,
        ]);

        return true;
    }
}
