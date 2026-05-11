<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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

        $otp = (string) random_int(100000, 999999);
        session()->put('user_phone_login', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'country' => $this->country,
        ]);
        session()->put('user_phone_login_otp', $otp);
        session()->put('user_phone_login_otp_expires_at', now()->addMinutes(10)->timestamp);

        $this->formattedPhone = $this->formatPhone($this->phone, $this->country);
        $this->statusMessage = __('A verification code has been sent via SMS to :phone.', ['phone' => $this->formattedPhone]);

        Log::info('User phone login OTP sent', [
            'user_id' => $user->id,
            'phone' => $this->phone,
            'code' => $otp,
        ]);
    }

    public function verifyOtp(): void
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $loginData = session('user_phone_login');
        $otp = session('user_phone_login_otp');
        $expiresAt = session('user_phone_login_otp_expires_at');

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

        Auth::login($user);

        // Clean up session data
        session()->forget([
            'user_phone_login',
            'user_phone_login_otp',
            'user_phone_login_otp_expires_at',
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
            $this->statusMessage = 'Please wait before requesting a new code.';
            return;
        }

        $otp = (string) random_int(100000, 999999);
        session()->put('user_phone_login_otp', $otp);
        session()->put('user_phone_login_otp_expires_at', now()->addMinutes(10)->timestamp);
        session()->put('user_phone_login_last_resent_at', now()->timestamp);

        $this->formattedPhone = $this->formatPhone($loginData['phone'], $loginData['country']);
        $this->statusMessage = __('A new verification code has been sent via SMS to :phone.', [
            'phone' => $this->formattedPhone,
        ]);

        Log::info('User phone login OTP resent', [
            'user_id' => $loginData['user_id'],
            'phone' => $loginData['phone'],
            'code' => $otp,
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
