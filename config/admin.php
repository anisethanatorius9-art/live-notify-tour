<?php

return [
    /**
     * Code required to register a new admin user.
     * Set ADMIN_REGISTRATION_CODE in your .env file.
     */
    'registration_code' => env('ADMIN_REGISTRATION_CODE', 'live-notify-admin-2026'),

    /**
     * Optional admin login key for extra protection.
     * Set ADMIN_LOGIN_KEY in your .env file when you want to enforce a second secret.
     */
    'login_key' => env('ADMIN_LOGIN_KEY', null),
];
