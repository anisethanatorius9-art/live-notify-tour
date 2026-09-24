<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

     'slack' => [
         'notifications' => [
             'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
             'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
         ],
     ],

     'google' => [
         'client_id' => env('GOOGLE_CLIENT_ID'),
         'client_secret' => env('GOOGLE_CLIENT_SECRET'),
         'redirect' => env('GOOGLE_REDIRECT_URI'),
     ],

     'gemini' => [
         'key' => env('GEMINI_API_KEY'),
         'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
     ],

     'selcom' => [
         'base_url' => env('SELCOM_BASE_URL', 'https://apigw.selcommobile.com'),
         'vendor' => env('SELCOM_VENDOR'),
         'api_key' => env('SELCOM_API_KEY'),
         'api_secret' => env('SELCOM_API_SECRET'),
     ],

     'paypal' => [
         'base_url' => env('PAYPAL_BASE_URL', 'https://api-m.sandbox.paypal.com'),
         'client_id' => env('PAYPAL_CLIENT_ID'),
         'client_secret' => env('PAYPAL_CLIENT_SECRET'),
         'currency' => env('PAYPAL_CURRENCY', 'USD'),
         'tzs_to_usd_rate' => env('PAYPAL_TZS_TO_USD_RATE'),
     ],

     'twilio' => [
         'account_sid' => env('TWILIO_ACCOUNT_SID'),
         'auth_token' => env('TWILIO_AUTH_TOKEN'),
         'from_number' => env('TWILIO_PHONE_NUMBER'),
         'from_whatsapp' => env('TWILIO_WHATSAPP_FROM'),
     ],

     'sms' => [
         'provider' => env('SMS_PROVIDER', 'log'),
     ],

 ];
