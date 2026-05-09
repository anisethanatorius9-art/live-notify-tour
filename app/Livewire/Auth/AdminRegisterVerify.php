<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AdminRegisterVerify extends Component
{
    public $code = '';
    public ?string $phone = null;
    public ?string $country = null;
    public ?string $formattedPhone = null;
    public ?string $statusMessage = null;

    protected $callingCodes = [
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
        // Add more as needed
    ];

    public function mount()
    {
        $registration = session('admin_registration');

        if (! $registration) {
            return redirect()->route('admin.register');
        }

        $this->phone = $registration['phone'] ?? null;
        $this->country = $registration['country'] ?? null;
        $this->formattedPhone = $this->formatPhone($this->phone, $this->country);
        $this->statusMessage = session('status');
    }

    public function verify()
    {
        $this->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $otp = session('admin_registration_otp');
        $expiresAt = session('admin_registration_otp_expires_at');

        if (! $otp || $otp !== $this->code) {
            $this->addError('code', 'The verification code is invalid.');
            return;
        }

        if ($expiresAt && now()->timestamp > $expiresAt) {
            $this->addError('code', 'The verification code has expired. Please resend the code.');
            return;
        }

        $registration = session('admin_registration');

        if (! $registration) {
            return redirect()->route('admin.register');
        }

        $user = User::create($registration);
        Auth::login($user);

        session()->forget([
            'admin_registration',
            'admin_registration_otp',
            'admin_registration_otp_expires_at',
        ]);

        session()->regenerate();

        return redirect()->route('dashboard.admin');
    }

    protected function formatPhone(?string $phone, ?string $country): string
    {
        $callingCode = $this->callingCodes[$country] ?? '';
        if ($callingCode && $phone && !str_starts_with($phone, $callingCode)) {
            return $callingCode . ' ' . $phone;
        }
        return $phone ?? '';
    }

    public function resend()
    {
        $registration = session('admin_registration');

        if (! $registration) {
            return redirect()->route('admin.register');
        }

        $otp = (string) random_int(100000, 999999);
        session()->put('admin_registration_otp', $otp);
        session()->put('admin_registration_otp_expires_at', now()->addMinutes(15)->timestamp);
        $this->statusMessage = 'A new verification code has been sent via SMS.';

        Log::info('Admin verification SMS resent', [
            'phone' => $registration['phone'],
            'code' => $otp,
        ]);
    }

    public function render()
    {
        return view('livewire.auth.admin-register-verify');
    }
}
