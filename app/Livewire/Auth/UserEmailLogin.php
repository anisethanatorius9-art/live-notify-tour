<?php

namespace App\Livewire\Auth;

use App\Mail\UserOtpEmail;
use App\Models\PhoneVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

#[\Livewire\Attributes\Layout('components.layouts.auth.simple')]
class UserEmailLogin extends Component
{
    public string $email = '';
    public string $code = '';
    public ?string $statusMessage = null;
    public ?string $statusEmail = null;
    public bool $showOtpForDebug = false;
    public ?string $step = null;

    public function mount(): void
    {
        $this->showOtpForDebug = env('MAIL_DEBUG', false);
        $this->step = request()->query('step');

        $queryEmail = request()->query('email');
        if ($queryEmail && ! session()->has('user_email_login')) {
            $this->email = rawurldecode((string) $queryEmail);
        }

        $loginData = session('user_email_login');
        if ($loginData) {
            $this->email = $loginData['email'];
            $this->statusEmail = $this->email;
            $this->statusMessage = __('A verification code has been sent to :email. Check your inbox and spam folder.', [
                'email' => $this->statusEmail,
            ]);
            $this->step = 'verify';
        }
    }

    public function sendOtp(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $cleanEmail = Str::lower(trim($this->email));
        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [$cleanEmail])
            ->first();

        if ($user && in_array($user->role, User::ADMIN_ROLE_VALUES, true)) {
            $this->addError('email', __('This email is reserved for administrator login.'));
            return;
        }

        $recipientEmail = $user?->email ?? $cleanEmail;

        if (! $user) {
            $user = User::create([
                'name' => Str::before($cleanEmail, '@'),
                'email' => $cleanEmail,
                'password' => bcrypt(Str::random(24)),
                'role' => 'tourist',
                'email_verified_at' => now(),
            ]);
        }

        $otp = PhoneVerificationOtp::generateUniqueOtp();

        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'email' => $recipientEmail,
            'phone' => '',
            'country' => '',
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'attempt_count' => 0,
            'last_attempt_at' => null,
            'sms_status' => null,
            'sms_response' => null,
        ]);

        session()->put('user_email_login', [
            'user_id' => $user->id,
            'email' => $recipientEmail,
            'normalized_email' => $cleanEmail,
            'otp_id' => $otpRecord->id,
        ]);

        try {
            Mail::mailer(config('mail.default', 'smtp'))->to($recipientEmail)->send(new UserOtpEmail($otp));
        } catch (\Throwable $exception) {
            Log::error('User email OTP failed', [
                'user_id' => $user->id,
                'email' => $this->email,
                'mail_driver' => config('mail.default'),
                'error' => $exception->getMessage(),
            ]);

            $this->addError('email', __('Unable to send the verification code. Please try again later.'));
            return;
        }

        if ($this->showOtpForDebug) {
            session()->put('user_email_login_otp_display', $otp);
        }

        $this->email = $recipientEmail;
        $this->statusEmail = $recipientEmail;
        $this->statusMessage = __('A verification code has been sent to :email. Check your inbox and spam folder.', [
            'email' => $this->statusEmail,
        ]);
        $this->step = 'verify';

        Log::info('User email login OTP sent', [
            'user_id' => $user->id,
            'email' => $this->email,
            'otp_id' => $otpRecord->id,
        ]);

        $this->redirectRoute('login.email', [
            'step' => 'verify',
            'email' => $recipientEmail,
        ]);
    }

    public function useDifferentEmail(): void
    {
        session()->forget([
            'user_email_login',
            'user_email_login_last_resent_at',
            'user_email_login_otp_display',
        ]);

        $this->email = '';
        $this->code = '';
        $this->statusMessage = null;
        $this->statusEmail = null;
        $this->step = null;

        $this->redirectRoute('login.email');
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $loginData = session('user_email_login');
        if (! $loginData) {
            $this->addError('code', __('Session expired. Please request a new code.'));
            return;
        }

        $otpRecord = PhoneVerificationOtp::find($loginData['otp_id'] ?? null);
        if (! $otpRecord) {
            $this->addError('code', __('The verification code is invalid or expired.'));
            return;
        }

        $otpRecord->recordAttempt();

        if ($otpRecord->hasTooManyAttempts()) {
            $this->addError('code', __('Too many failed attempts. Please request a new code.'));
            return;
        }

        if ($otpRecord->hasExpired()) {
            $this->addError('code', __('The verification code has expired. Please request a new code.'));
            return;
        }

        if ($otpRecord->otp !== $this->code) {
            $this->addError('code', __('The verification code is incorrect. Please try again.'));
            return;
        }

        $user = User::find($loginData['user_id']);
        if (! $user) {
            $this->addError('code', __('User not found.'));
            return;
        }

        $otpRecord->markAsVerified();

        Auth::login($user);

        session()->forget([
            'user_email_login',
            'user_email_login_otp_display',
        ]);

        session()->regenerate();

        $this->redirectRoute('dashboard');
    }

    public function resend(): void
    {
        $loginData = session('user_email_login');
        if (! $loginData) {
            $this->addError('code', __('Session expired. Please request a new code.'));
            return;
        }

        $lastResentAt = session('user_email_login_last_resent_at');
        if ($lastResentAt && now()->timestamp - $lastResentAt < 60) {
            $this->statusMessage = __('Please wait 60 seconds before requesting a new code.');
            return;
        }

        $otp = PhoneVerificationOtp::generateUniqueOtp();
        $user = User::find($loginData['user_id']);
        if (! $user) {
            $this->addError('code', __('User not found.'));
            return;
        }

        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'email' => $loginData['email'],
            'phone' => '',
            'country' => '',
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'attempt_count' => 0,
            'last_attempt_at' => null,
            'sms_status' => null,
            'sms_response' => null,
        ]);

        $loginData['otp_id'] = $otpRecord->id;
        session()->put('user_email_login', $loginData);
        session()->put('user_email_login_last_resent_at', now()->timestamp);

        try {
            Mail::mailer(config('mail.default', 'smtp'))->to($loginData['email'])->send(new UserOtpEmail($otp));
        } catch (\Throwable $exception) {
            Log::error('User email OTP resend failed', [
                'user_id' => $loginData['user_id'],
                'email' => $loginData['email'],
                'mail_driver' => config('mail.default'),
                'error' => $exception->getMessage(),
            ]);

            $this->addError('code', __('Unable to send the verification code. Please try again later.'));
            return;
        }

        if ($this->showOtpForDebug) {
            session()->put('user_email_login_otp_display', $otp);
        }

        $this->statusMessage = __('A new verification code has been sent to :email. Check your inbox and spam folder.', [
            'email' => $loginData['email'],
        ]);
    }

    public function render()
    {
        return view('livewire.auth.user-email-login');
    }
}
