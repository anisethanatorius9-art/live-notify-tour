<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class AdminRegister extends Component
{
    // Hizi variables lazima ziwe public ili blade izione
    public $selectedCountry = 'TZ';
    public $phone = '+255';
    public $admin_code = '';

    // Data ya nchi na codes zake
    public $countryData = [
        'TZ' => ['name' => 'Tanzania', 'code' => '+255'],
        'KE' => ['name' => 'Kenya', 'code' => '+254'],
        'UG' => ['name' => 'Uganda', 'code' => '+256'],
        'RW' => ['name' => 'Rwanda', 'code' => '+250'],
        'US' => ['name' => 'United States', 'code' => '+1'],
        'GB' => ['name' => 'United Kingdom', 'code' => '+44'],
    ];

    /**
     * Hii function inarun kila admin anapobadilisha nchi kwenye dropdown
     */
    public function updatedSelectedCountry($value)
    {
        if (isset($this->countryData[$value])) {
            $this->phone = $this->countryData[$value]['code'];
        }
    }

    public function resendCode()
    {
        // SMS logic hapa
        session()->flash('status', 'A new verification code has been sent.');
    }

    public function register()
    {
        $this->validate([
            'admin_code' => 'required|digits:6',
            'phone' => 'required',
            'selectedCountry' => 'required',
        ]);

        // Hapa sasa unahifadhi data zako
        // mfano: User::create([...]);
    }

    public function render()
    {
        // Hapa tunaunganisha na file la muonekano (Blade)
        return view('livewire.auth.admin-register');
    }
}
