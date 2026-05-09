<?php

namespace App\Livewire\Actions;

use App\Services\SessionService;

class Logout
{
    /**
     * Log the current user out of the application.
     */
    public function __invoke()
    {
        SessionService::completeLogout();

        return redirect('/');
    }
}
