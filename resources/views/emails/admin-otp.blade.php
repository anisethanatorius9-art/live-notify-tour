@component('mail::message')
# Admin Login Verification

You are receiving this email because an administrator login was requested for this account.

**One-Time Password (OTP)**: `{{ $otp }}`

This code expires in 10 minutes.

**Admin Access Key**

`{{ $accessKey }}`

Use this access key for faster future logins. It is unique and will not be reused.

@component('mail::button', ['url' => route('admin.login')])
Go to Admin Login
@endcomponent

If you did not request this email, please ignore it.

Thanks,
{{ config('app.name') }}
@endcomponent
