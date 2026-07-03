<?php

namespace App\Livewire\Auth;

use App\Models\AdminAccessKey;
use App\Models\PhoneVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminPhoneLogin extends Component
{
    public string $phone = '';
    public string $code = '';
    public ?string $formattedPhone = null;
    public ?string $statusMessage = null;
    public ?string $statusPhone = null;
    public bool $showOtpForDebug = false;
    public string $country = 'TZ';
    public string $otpChannel = 'SMS';
    public ?string $step = null;

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

    public function mount(): void
    {
        $this->showOtpForDebug = env('SMS_DEBUG', false);
        $this->step = request()->query('step');

        // Check if there's an existing OTP session
        $loginData = session('admin_phone_login');
        if ($loginData) {
            $this->phone = $loginData['phone'];
            $this->country = $loginData['country'];
            $this->formattedPhone = $this->formatPhone($this->phone, $this->country);
            $this->otpChannel = $this->getOtpChannel();
            $this->statusPhone = $this->formattedPhone;
            $this->statusMessage = __('A verification code has been sent via :channel to :phone.', [
                'channel' => $this->otpChannel,
                'phone' => $this->statusPhone,
            ]);
        }
    }

    protected function sendSmsToPhone(string $phone, string $message, ?PhoneVerificationOtp $otpRecord = null): bool
    {
        $provider = env('SMS_PROVIDER', 'log');

        if ($provider === 'twilio') {
            $accountSid = env('TWILIO_ACCOUNT_SID');
            $authToken = env('TWILIO_AUTH_TOKEN');
            $from = env('TWILIO_FROM');

            if (! $accountSid || ! $authToken || ! $from) {
                Log::warning('Twilio SMS configuration is missing.');
                return false;
            }

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'From' => $from,
                    'To' => $phone,
                    'Body' => $message,
                ]);

            if ($response->failed()) {
                Log::error('Twilio SMS failed.', [
                    'phone' => $phone,
                    'response' => $response->body(),
                ]);
                if ($otpRecord) {
                    $otpRecord->update([
                        'sms_status' => 'failed',
                        'sms_response' => $response->body(),
                    ]);
                }
                return false;
            }

            if ($otpRecord) {
                $otpRecord->update([
                    'sms_status' => 'sent',
                    'sms_response' => $response->body(),
                ]);
            }
            return true;
        }

        if ($provider === 'twilio_whatsapp') {
            $accountSid = env('TWILIO_ACCOUNT_SID');
            $authToken = env('TWILIO_AUTH_TOKEN');
            $from = env('TWILIO_WHATSAPP_FROM', env('TWILIO_FROM'));

            if (! $accountSid || ! $authToken || ! $from) {
                Log::warning('Twilio WhatsApp configuration is missing.');
                return false;
            }

            $response = Http::withBasicAuth($accountSid, $authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json", [
                    'From' => $this->formatWhatsappAddress($from),
                    'To' => $this->formatWhatsappAddress($phone),
                    'Body' => $message,
                ]);

            if ($response->failed()) {
                Log::error('Twilio WhatsApp failed.', [
                    'phone' => $phone,
                    'response' => $response->body(),
                ]);
                if ($otpRecord) {
                    $otpRecord->update([
                        'sms_status' => 'failed',
                        'sms_response' => $response->body(),
                    ]);
                }
                return false;
            }

            if ($otpRecord) {
                $otpRecord->update([
                    'sms_status' => 'sent',
                    'sms_response' => $response->body(),
                ]);
            }
            return true;
        }

        if ($provider === 'log' || app()->environment('local')) {
            Log::info('SMS debug message', [
                'to' => $phone,
                'message' => $message,
                'otp_id' => $otpRecord?->id,
            ]);
            return true;
        }

        Log::warning('SMS provider is not configured.', ['provider' => $provider]);

        return false;
    }

    protected function formatWhatsappAddress(string $number): string
    {
        $sanitized = preg_replace('/[\s\-\(\)]+/', '', $number);

        return str_starts_with($sanitized, 'whatsapp:') ? $sanitized : "whatsapp:{$sanitized}";
    }

    protected function getOtpChannel(): string
    {
        return env('SMS_PROVIDER', 'log') === 'twilio_whatsapp' ? 'WhatsApp' : 'SMS';
    }

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

        // Sanitize phone: remove spaces, dashes, parentheses
        $this->phone = preg_replace('/[\s\-\(\)]/', '', $this->phone);

        $user = User::where('phone', $this->phone)
            ->where('country', $this->country)
            ->where('role', 'admin')
            ->first();

        if (! $user) {
            $this->addError('phone', 'No admin account found with this phone number.');
            return;
        }

        // Generate a unique OTP (guaranteed to be different from all other OTPs)
        $otp = PhoneVerificationOtp::generateUniqueOtp();

        // Create OTP record in database
        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'phone' => $this->phone,
            'country' => $this->country,
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'sms_status' => 'pending',
        ]);

        // Store minimal data in session for quick access
        session()->put('admin_phone_login', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'country' => $this->country,
            'otp_id' => $otpRecord->id,
        ]);

        $this->formattedPhone = $this->formatPhone($this->phone, $this->country);
        $this->otpChannel = $this->getOtpChannel();
        $this->statusPhone = $this->formattedPhone;

        $message = __('Your LTN admin login code is :code. This code expires in 10 minutes.', ['code' => $otp]);
        $sent = $this->sendSmsToPhone($this->formattedPhone, $message, $otpRecord);

        if (! $sent) {
            // Mark SMS as failed in database
            $otpRecord->update(['sms_status' => 'failed']);
            $this->addError('phone', __('Unable to send verification code. Please check your phone number and try again. If the problem persists, contact support.'));
            return;
        }

        // Mark SMS as sent in database
        $otpRecord->update(['sms_status' => 'sent']);

        if ($this->showOtpForDebug) {
            session()->put('admin_phone_login_otp_display', $otp);
        }

        $this->statusMessage = __('A verification code has been sent via :channel to :phone. Please check your phone.', [
            'channel' => $this->otpChannel,
            'phone' => $this->statusPhone,
        ]);

        Log::info('Admin phone login OTP sent', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'otp_id' => $otpRecord->id,
            'sms_status' => 'sent',
        ]);

        $this->step = 'verify';
    }

    public function useDifferentNumber(): void
    {
        session()->forget([
            'admin_phone_login',
            'admin_phone_login_last_resent_at',
            'admin_phone_login_otp_display',
        ]);

        $this->phone = '';
        $this->code = '';
        $this->formattedPhone = null;
        $this->statusMessage = null;
        $this->otpChannel = 'SMS';
        $this->step = null;

        $this->redirectRoute('admin.login.phone');
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $loginData = session('admin_phone_login');
        if (! $loginData) {
            $this->addError('code', 'Session expired. Please request a new code.');
            return;
        }

        // Retrieve OTP from database
        $otpRecord = PhoneVerificationOtp::find($loginData['otp_id'] ?? null);
        if (! $otpRecord) {
            $this->addError('code', 'The verification code is invalid or expired.');
            return;
        }

        // Record this verification attempt
        $otpRecord->recordAttempt();

        // Check if too many failed attempts
        if ($otpRecord->hasTooManyAttempts()) {
            $this->addError('code', 'Too many failed attempts. Please request a new code.');
            return;
        }

        // Check if OTP has expired
        if ($otpRecord->hasExpired()) {
            $this->addError('code', 'The verification code has expired. Please request a new code.');
            return;
        }

        // Check if OTP is correct
        if ($otpRecord->otp !== $this->code) {
            $this->addError('code', 'The verification code is incorrect. Please try again.');
            return;
        }

        $user = User::find($loginData['user_id']);
        if (! $user) {
            $this->addError('code', 'User not found.');
            return;
        }

        // Mark OTP as verified
        $otpRecord->markAsVerified();

        // Generate or retrieve an admin access key for the user
        $accessKey = $this->getOrCreateAccessKey($user);

        Auth::login($user);

        // Clean up session data
        session()->forget([
            'admin_phone_login',
            'admin_phone_login_otp_display',
        ]);

        session()->regenerate();
        session()->flash('access_key_generated', true);
        session()->flash('status', __('Logged in successfully. Your admin access key has been provided below.'));

        // Store the key temporarily in session for display after redirect
        session()->put('generated_access_key', $accessKey->access_key);

        Log::info('Admin phone login verified', [
            'user_id' => $user->id,
            'otp_id' => $otpRecord->id,
        ]);

        $this->redirectRoute('dashboard.admin');
    }

    public function resend(): void
    {
        $loginData = session('admin_phone_login');

        if (! $loginData) {
            $this->addError('code', 'Session expired. Please request a new code.');
            return;
        }

        // Rate limiting: prevent resends faster than 60 seconds
        $lastResentAt = session('admin_phone_login_last_resent_at');
        if ($lastResentAt && now()->timestamp - $lastResentAt < 60) {
            $this->statusMessage = 'Please wait 60 seconds before requesting a new code.';
            return;
        }

        // Generate a new unique OTP (guaranteed different from previous one)
        $otp = PhoneVerificationOtp::generateUniqueOtp();

        // Create new OTP record in database
        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $loginData['user_id'],
            'otp' => $otp,
            'phone' => $loginData['phone'],
            'country' => $loginData['country'],
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'sms_status' => 'pending',
        ]);

        // Update session with new OTP ID
        $loginData['otp_id'] = $otpRecord->id;
        session()->put('admin_phone_login', $loginData);
        session()->put('admin_phone_login_last_resent_at', now()->timestamp);

        $message = __('Your new LTN admin login code is :code. This code expires in 10 minutes.', ['code' => $otp]);
        $sent = $this->sendSmsToPhone($this->formattedPhone, $message, $otpRecord);

        if (! $sent) {
            // Mark SMS as failed in database
            $otpRecord->update(['sms_status' => 'failed']);
            $this->addError('code', __('Unable to send verification code. Please try again or contact support.'));
            return;
        }

        // Mark SMS as sent in database
        $otpRecord->update(['sms_status' => 'sent']);

        if ($this->showOtpForDebug) {
            session()->put('admin_phone_login_otp_display', $otp);
        }

        $this->otpChannel = $this->getOtpChannel();
        $message = __('A new verification code has been sent via :channel to :phone. Please check your phone.', [
            'channel' => $this->otpChannel,
            'phone' => $this->formattedPhone,
        ]);

        Log::info('Admin phone login OTP resent', [
            'user_id' => $loginData['user_id'],
            'phone' => $loginData['phone'],
            'otp_id' => $otpRecord->id,
        ]);
    }

    protected function getOrCreateAccessKey(User $user): AdminAccessKey
    {
        // Check for an existing active key
        $existingKey = AdminAccessKey::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingKey) {
            return $existingKey;
        }

        // Generate a new access key (cryptographically secure 48-character token)
        return AdminAccessKey::create([
            'user_id' => $user->id,
            'access_key' => hash('sha256', random_bytes(32)),
            'is_active' => true,
            'expires_at' => now()->addMonths(6),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
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
        return view('livewire.auth.admin-phone-login');
    }
}
