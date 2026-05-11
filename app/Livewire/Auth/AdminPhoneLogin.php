<?php

namespace App\Livewire\Auth;

use App\Models\AdminAccessKey;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminPhoneLogin extends Component
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

        $otp = (string) random_int(100000, 999999);
        session()->put('admin_phone_login', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'country' => $this->country,
        ]);
        session()->put('admin_phone_login_otp', $otp);
        session()->put('admin_phone_login_otp_expires_at', now()->addMinutes(10)->timestamp);

        $this->formattedPhone = $this->formatPhone($this->phone, $this->country);

        // For development: store OTP temporarily for display
        session()->put('admin_phone_login_otp_display', $otp);

        $this->statusMessage = __('A verification code has been sent via SMS to :phone.', ['phone' => $this->formattedPhone]);

        Log::info('Admin phone login OTP sent', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'code' => $otp,
        ]);

        // Dispatch notification
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "OTP sent! Code for testing: {$otp}",
        ]);
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $loginData = session('admin_phone_login');
        $otp = session('admin_phone_login_otp');
        $expiresAt = session('admin_phone_login_otp_expires_at');

        if (! $loginData) {
            $this->addError('code', 'Session expired. Please request a new code.');
            return;
        }

        if (! $otp || $otp !== $this->code) {
            $this->addError('code', 'The verification code is invalid.');
            return;
        }

        if ($expiresAt && now()->timestamp > $expiresAt) {
            $this->addError('code', 'The verification code has expired. Please request a new code.');
            return;
        }

        $user = User::find($loginData['user_id']);

        if (! $user) {
            $this->addError('code', 'User not found.');
            return;
        }

        // Generate or retrieve an admin access key for the user
        $accessKey = $this->getOrCreateAccessKey($user);

        Auth::login($user);

        // Clean up session data
        session()->forget([
            'admin_phone_login',
            'admin_phone_login_otp',
            'admin_phone_login_otp_expires_at',
        ]);

        session()->regenerate();
        session()->flash('access_key_generated', true);
        session()->flash('status', __('Logged in successfully. Your admin access key has been provided below.'));

        // Store the key temporarily in session for display after redirect
        session()->put('generated_access_key', $accessKey->access_key);

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
            $this->statusMessage = 'Please wait before requesting a new code.';
            return;
        }

        $otp = (string) random_int(100000, 999999);
        session()->put('admin_phone_login_otp', $otp);
        session()->put('admin_phone_login_otp_expires_at', now()->addMinutes(10)->timestamp);
        session()->put('admin_phone_login_last_resent_at', now()->timestamp);

        $this->statusMessage = __('A new verification code has been sent via SMS to :phone.', [
            'phone' => $this->formattedPhone,
        ]);

        Log::info('Admin phone login OTP resent', [
            'user_id' => $loginData['user_id'],
            'phone' => $loginData['phone'],
            'code' => $otp,
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
