@component('mail::message')
# Login Verification Code

You are receiving this email because a login request was made for your account.

**One-Time Password (OTP)**: `{{ $otp }}`

This code expires in 10 minutes.

@component('mail::button', ['url' => route('login.email')])
Open Login Page
@endcomponent

If you did not request this email, please ignore it.

Thanks,
{{ config('app.name') }}
@endcomponent
