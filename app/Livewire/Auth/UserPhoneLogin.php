<?php

namespace App\Livewire\Auth;

use App\Models\PhoneVerificationOtp;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

#[\Livewire\Attributes\Layout('components.layouts.auth.simple')]
class UserPhoneLogin extends Component
{
    public string $phone = '';
    public string $code = '';
    public ?string $formattedPhone = null;
    public ?string $statusMessage = null;
    public string $country = 'TZ';

    protected array $callingCodes = [
        'US' => '+1',
        'KE' => '+254',
        'TZ' => '+255',
        'UG' => '+256',
        'RW' => '+250',
        'BI' => '+257',
        'CD' => '+243',
        'SS' => '+211',
        'ET' => '+251',
        'SO' => '+252',
        'DJ' => '+253',
        'ER' => '+291',
        'SD' => '+249',
    ];

    public function updatedPhone(): void
    {
        $this->formattedPhone = $this->formatPhone($this->phone, $this->country);
    }

    public function updatedCountry(): void
    {
        $this->formattedPhone = $this->formatPhone($this->phone, $this->country);
    }

    public function sendOtp(): void
    {
        $this->validate([
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'country' => ['required', 'string', 'size:2'],
        ]);

        // Sanitize phone
        $this->phone = preg_replace('/[\s\-\(\)]/', '', $this->phone);

        // Find user by phone and country, excluding admins
        $user = User::where('phone', $this->phone)
            ->where('country', $this->country)
            ->where('role', '!=', 'admin')
            ->first();

        if (! $user) {
            $this->addError('phone', 'No account found with this phone number.');
            return;
        }

        $otp = PhoneVerificationOtp::generateUniqueOtp();
        $formattedPhone = $this->formatPhone($this->phone, $this->country);

        // Create OTP record in database
        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'email' => '',
            'phone' => $this->phone,
            'country' => $this->country,
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'attempt_count' => 0,
            'last_attempt_at' => null,
            'sms_status' => 'pending',
            'sms_response' => null,
        ]);

        // Attempt to send SMS
        $smsSent = SmsService::sendOtp($formattedPhone, $otp, $this->country);

        if (! $smsSent) {
            // Update OTP record with failed status
            $otpRecord->update(['sms_status' => 'failed']);
            $this->addError('phone', __('Unable to send the verification code. Please try again later.'));
            return;
        }

        // Update OTP record with sent status
        $otpRecord->update(['sms_status' => 'sent']);

        // Store session data
        session()->put('user_phone_login', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'country' => $this->country,
            'otp_id' => $otpRecord->id,
        ]);

        $this->formattedPhone = $formattedPhone;
        $this->statusMessage = __('A verification code has been sent via SMS to :phone.', ['phone' => $this->formattedPhone]);

        Log::info('User phone login OTP sent via SMS', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'otp_id' => $otpRecord->id,
        ]);
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $loginData = session('user_phone_login');
        if (! $loginData) {
            $this->addError('code', 'Session expired. Please request a new code.');
            return;
        }

        $otpRecord = PhoneVerificationOtp::find($loginData['otp_id'] ?? null);
        if (! $otpRecord) {
            $this->addError('code', 'The verification code is invalid or expired.');
            return;
        }

        $otpRecord->recordAttempt();

        if ($otpRecord->hasTooManyAttempts()) {
            $this->addError('code', 'Too many failed attempts. Please request a new code.');
            return;
        }

        if ($otpRecord->hasExpired()) {
            $this->addError('code', 'The verification code has expired. Please request a new code.');
            return;
        }

        if ($otpRecord->otp !== $this->code) {
            $this->addError('code', 'The verification code is incorrect. Please try again.');
            return;
        }

        $user = User::find($loginData['user_id']);
        if (! $user) {
            $this->addError('code', 'User not found.');
            return;
        }

        $otpRecord->markAsVerified();

        Auth::login($user);

        // Clean up session data
        session()->forget([
            'user_phone_login',
            'user_phone_login_last_resent_at',
        ]);

        session()->regenerate();

        $this->redirectRoute('dashboard');
    }

    public function resend(): void
    {
        $loginData = session('user_phone_login');

        if (! $loginData) {
            $this->addError('code', 'Session expired. Please request a new code.');
            return;
        }

        // Rate limiting
        $lastResentAt = session('user_phone_login_last_resent_at');
        if ($lastResentAt && now()->timestamp - $lastResentAt < 60) {
            $this->statusMessage = __('Please wait 60 seconds before requesting a new code.');
            return;
        }

        $otp = PhoneVerificationOtp::generateUniqueOtp();
        $user = User::find($loginData['user_id']);

        if (! $user) {
            $this->addError('code', 'User not found.');
            return;
        }

        // Create new OTP record
        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'email' => '',
            'phone' => $loginData['phone'],
            'country' => $loginData['country'],
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'attempt_count' => 0,
            'last_attempt_at' => null,
            'sms_status' => 'pending',
            'sms_response' => null,
        ]);

        $this->formattedPhone = $this->formatPhone($loginData['phone'], $loginData['country']);

        // Attempt to send SMS
        $smsSent = SmsService::sendOtp($this->formattedPhone, $otp, $loginData['country']);

        if (! $smsSent) {
            $otpRecord->update(['sms_status' => 'failed']);
            $this->addError('code', __('Unable to send the verification code. Please try again later.'));
            return;
        }

        // Update OTP record with sent status
        $otpRecord->update(['sms_status' => 'sent']);

        // Update session with new OTP ID
        $loginData['otp_id'] = $otpRecord->id;
        session()->put('user_phone_login', $loginData);
        session()->put('user_phone_login_last_resent_at', now()->timestamp);

        $this->statusMessage = __('A new verification code has been sent via SMS to :phone.', [
            'phone' => $this->formattedPhone,
        ]);

        Log::info('User phone login OTP resent via SMS', [
            'user_id' => $loginData['user_id'],
            'phone' => $loginData['phone'],
            'otp_id' => $otpRecord->id,
        ]);
    }

    protected function formatPhone(?string $phone, ?string $country): string
    {
        $callingCode = $this->callingCodes[$country] ?? '';

        if ($callingCode && $phone && ! str_starts_with($phone, $callingCode)) {
            return $callingCode . ltrim($phone, '+');
        }

        return $phone ?? '';
    }

    public function render()
    {
        return view('livewire.auth.user-phone-login');
    }
}
