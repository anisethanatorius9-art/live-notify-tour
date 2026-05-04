<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AdminHelp extends Component
{
    public $showModal = false;

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
    }

    public function render()
    {
        return view('livewire.admin.admin-help');
    }
}
