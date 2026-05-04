<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class AdminSettings extends Component
{
    public $showModal = false;
    public $maintenanceMode = false;
    public $maxUploadSize = 10;
    public $sessionTimeout = 120;
    public $emailNotifications = true;

    public function mount()
    {
        $this->maintenanceMode = Cache::get('app.maintenance_mode', false);
        $this->maxUploadSize = Cache::get('app.max_upload_size', 10);
        $this->sessionTimeout = Cache::get('app.session_timeout', 120);
        $this->emailNotifications = Cache::get('app.email_notifications', true);
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }

    public function saveSettings()
    {
        Cache::put('app.maintenance_mode', $this->maintenanceMode, 86400 * 365);
        Cache::put('app.max_upload_size', $this->maxUploadSize, 86400 * 365);
        Cache::put('app.session_timeout', $this->sessionTimeout, 86400 * 365);
        Cache::put('app.email_notifications', $this->emailNotifications, 86400 * 365);

        $this->dispatch('notify', message: __('Settings saved successfully'), type: 'success');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.admin.admin-settings');
    }
}
