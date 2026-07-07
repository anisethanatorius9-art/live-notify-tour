<?php

namespace App\Livewire\Auth;

use App\Mail\AdminOtpEmail;
use App\Models\AdminAccessKey;
use App\Models\PhoneVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class AdminPhoneLogin extends Component
{
    public string $email = '';
    public string $code = '';
    public ?string $statusMessage = null;
    public ?string $statusEmail = null;
    public bool $showOtpForDebug = false;
    public ?string $step = null;

    #[\Livewire\Attributes\Layout('components.layouts.auth.simple')]
    public function mount(): void
    {
        $this->showOtpForDebug = env('MAIL_DEBUG', false);
        $this->step = request()->query('step');

        // If an email is provided as a query parameter, prefill the form field so
        // users hitting the URL (for example from a link) see their email and can
        // request a code. Do not auto-send the OTP on GET to avoid sending mail
        // unexpectedly.
        $queryEmail = request()->query('email');
        if ($queryEmail && ! session()->has('admin_email_login')) {
            $this->email = rawurldecode((string) $queryEmail);
        }

        $loginData = session('admin_email_login');
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
            ->whereIn('role', User::ADMIN_ROLE_VALUES)
            ->first();

        if (! $user) {
            $this->addError('email', __('No admin account found with this email address.'));
            return;
        }

        $otp = PhoneVerificationOtp::generateUniqueOtp();
        $accessKey = $this->createAccessKey($user);

        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'email' => $this->email,
            'phone' => '',
            'country' => '',
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'sms_status' => null,
        ]);

        $normalizedEmail = Str::lower(trim($this->email));

        session()->put('admin_email_login', [
            'user_id' => $user->id,
            'email' => $this->email,
            'normalized_email' => $normalizedEmail,
            'otp_id' => $otpRecord->id,
            'access_key_id' => $accessKey->id,
        ]);

        try {
            Mail::to($normalizedEmail)->send(new AdminOtpEmail($otp, $accessKey->access_key));
        } catch (\Throwable $exception) {
            Log::error('Admin email OTP failed', [
                'user_id' => $user->id,
                'email' => $this->email,
                'error' => $exception->getMessage(),
            ]);
            $this->addError('email', __('Unable to send the verification code. Please try again later.'));
            return;
        }

        if ($this->showOtpForDebug) {
            session()->put('admin_email_login_otp_display', $otp);
        }

        $this->statusEmail = $this->email;
        $this->statusMessage = __('A verification code has been sent to :email. Check your inbox and spam folder.', [
            'email' => $this->statusEmail,
        ]);
        $this->step = 'verify';

        Log::info('Admin email login OTP sent', [
            'user_id' => $user->id,
            'email' => $this->email,
            'otp_id' => $otpRecord->id,
        ]);

        // Include the normalized email in the redirect so the verification view
        // can display which address the code was sent to and keep the field
        // prefilled for the user.
        $this->redirectRoute('admin.login.email', [
            'step' => 'verify',
            'email' => $normalizedEmail,
        ]);
    }

    public function useDifferentEmail(): void
    {
        session()->forget([
            'admin_email_login',
            'admin_email_login_last_resent_at',
            'admin_email_login_otp_display',
        ]);

        $this->email = '';
        $this->code = '';
        $this->statusMessage = null;
        $this->statusEmail = null;
        $this->step = null;

        $this->redirectRoute('admin.login.email');
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $loginData = session('admin_email_login');
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

        $accessKey = AdminAccessKey::find($loginData['access_key_id'] ?? null);
        if (! $accessKey || ! $accessKey->isValid()) {
            $accessKey = $this->createAccessKey($user);
        }

        Auth::login($user);

        session()->forget([
            'admin_email_login',
            'admin_email_login_otp_display',
        ]);

        session()->regenerate();
        session()->flash('access_key_generated', true);
        session()->flash('status', __('Logged in successfully. Your admin access key has been provided below.'));
        session()->put('generated_access_key', $accessKey->access_key);

        Log::info('Admin email login verified', [
            'user_id' => $user->id,
            'otp_id' => $otpRecord->id,
        ]);

        $this->redirectRoute('dashboard.admin');
    }

    public function resend(): void
    {
        $loginData = session('admin_email_login');

        if (! $loginData) {
            $this->addError('code', 'Session expired. Please request a new code.');
            return;
        }

        $lastResentAt = session('admin_email_login_last_resent_at');
        if ($lastResentAt && now()->timestamp - $lastResentAt < 60) {
            $this->statusMessage = 'Please wait 60 seconds before requesting a new code.';
            return;
        }

        $otp = PhoneVerificationOtp::generateUniqueOtp();

        $user = User::find($loginData['user_id']);
        if (! $user) {
            $this->addError('code', 'User not found.');
            return;
        }

        $accessKey = $this->createAccessKey($user);

        $otpRecord = PhoneVerificationOtp::create([
            'user_id' => $loginData['user_id'],
            'otp' => $otp,
            'email' => $loginData['email'],
            'phone' => '',
            'country' => '',
            'is_verified' => false,
            'expires_at' => now()->addMinutes(10),
            'sms_status' => null,
        ]);

        $loginData['otp_id'] = $otpRecord->id;
        $loginData['access_key_id'] = $accessKey->id;
        session()->put('admin_email_login', $loginData);
        session()->put('admin_email_login_last_resent_at', now()->timestamp);

        try {
            Mail::to($loginData['email'])->send(new AdminOtpEmail($otp, $accessKey->access_key));
        } catch (\Throwable $exception) {
            Log::error('Admin email OTP resend failed', [
                'user_id' => $loginData['user_id'],
                'email' => $loginData['email'],
                'error' => $exception->getMessage(),
            ]);
            $this->addError('code', __('Unable to send the verification code. Please try again later.'));
            return;
        }

        if ($this->showOtpForDebug) {
            session()->put('admin_email_login_otp_display', $otp);
        }

        $this->statusEmail = $loginData['email'];
        $this->statusMessage = __('A new verification code has been sent to :email. Check your inbox and spam folder.', [
            'email' => $this->statusEmail,
        ]);

        Log::info('Admin email login OTP resent', [
            'user_id' => $loginData['user_id'],
            'email' => $loginData['email'],
            'otp_id' => $otpRecord->id,
        ]);
    }

    protected function createAccessKey(User $user): AdminAccessKey
    {
        AdminAccessKey::where('user_id', $user->id)->update(['is_active' => false]);

        return AdminAccessKey::create([
            'user_id' => $user->id,
            'access_key' => hash('sha256', random_bytes(32)),
            'is_active' => true,
            'expires_at' => now()->addMonths(6),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.auth.admin-phone-login');
    }
}
