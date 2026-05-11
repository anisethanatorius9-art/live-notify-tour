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

    /**
     * Phone-based OTP login settings.
     * Enable phone login for admins by setting ADMIN_PHONE_LOGIN_ENABLED=true.
     * OTP codes are valid for the specified number of minutes.
     */
    'phone_login' => [
        'enabled' => env('ADMIN_PHONE_LOGIN_ENABLED', false),
        'otp_expiry_minutes' => env('ADMIN_OTP_EXPIRY_MINUTES', 10),
        'resend_cooldown_seconds' => env('ADMIN_OTP_RESEND_COOLDOWN', 60),
        'max_attempts' => env('ADMIN_OTP_MAX_ATTEMPTS', 5),
    ],

    /**
     * Access key settings.
     * Access keys are auto-generated when an admin verifies via phone OTP.
     * Keys expire after the specified number of months.
     */
    'access_key' => [
        'expiry_months' => env('ADMIN_ACCESS_KEY_EXPIRY_MONTHS', 6),
    ],
];
