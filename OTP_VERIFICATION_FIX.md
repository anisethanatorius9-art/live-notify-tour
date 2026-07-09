# User OTP Verification Code - Complete Fix Guide

## Problem Summary

Users couldn't receive OTP verification codes due to two issues:

1. **Email OTP Failed**: Network error connecting to Gmail SMTP on port 465
2. **SMS OTP Not Implemented**: Phone login didn't actually send SMS, just stored OTP in session

## Solutions Implemented

### 1. Email OTP - Fixed (.env)

Changed from Gmail SMTP (which fails on local dev) to Mailhog:

```env
MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=test@ltn.test
```

### 2. SMS OTP - Now Implemented

**New File**: [app/Services/SmsService.php](app/Services/SmsService.php)
- Sends SMS via Twilio
- Sends WhatsApp via Twilio WhatsApp API
- Falls back to logging for development

**Updated**: [app/Livewire/Auth/UserPhoneLogin.php](app/Livewire/Auth/UserPhoneLogin.php)
- Now actually sends SMS using SmsService
- Stores OTP in database (PhoneVerificationOtp)
- Tracks SMS status (pending/sent/failed)
- Rate-limits resend requests (60 seconds)
- Better error handling

**Updated**: [config/services.php](config/services.php)
- Added Twilio configuration

---

## Setup Instructions

### For Local Development

#### Option 1: Using Mailhog (Recommended)

1. **Install Mailhog**:
   ```bash
   # Windows (using Chocolatey)
   choco install mailhog

   # Or download from: https://github.com/mailhog/MailHog/releases
   ```

2. **Run Mailhog**:
   ```bash
   mailhog
   ```

3. **Access Web UI**:
   - Open `http://localhost:8025` in your browser
   - All emails sent by the app will appear here

4. **Test Email OTP**:
   - Go to `/login/email` on your app
   - Enter any email address
   - Check Mailhog for the OTP (should appear immediately)

#### Option 2: Using Log Driver (For Quick Testing)

Your `.env` is already configured with Mailhog, but if you want to see emails in logs:

```env
MAIL_MAILER=log
```

Then check `/storage/logs/laravel.log` for OTP codes.

#### Option 3: Using MailPit (Alternative to Mailhog)

```bash
# Install MailPit
choco install mailpit

# Run it
mailpit

# Access at http://localhost:8025
```

---

### For SMS/WhatsApp OTP

#### Using Twilio (Production)

1. **Get Twilio Credentials**:
   - Sign up at https://www.twilio.com
   - Get your Account SID, Auth Token
   - Get a Twilio phone number for SMS

2. **Update .env**:
   ```env
   SMS_PROVIDER=twilio
   TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxx
   TWILIO_AUTH_TOKEN=your_auth_token
   TWILIO_PHONE_NUMBER=+1234567890
   ```

3. **For WhatsApp (Premium Twilio)**:
   ```env
   SMS_PROVIDER=twilio_whatsapp
   TWILIO_WHATSAPP_FROM=+1234567890
   ```

#### For Local Development (Logs)

Keep this configuration:
```env
SMS_PROVIDER=log
TWILIO_ACCOUNT_SID=
TWILIO_AUTH_TOKEN=
TWILIO_PHONE_NUMBER=
```

Then check `/storage/logs/laravel.log` for:
```
OTP (development/test mode) {"phone":"+255712345678","otp":"123456"}
```

---

## Testing the OTP Flow

### Email OTP Test

1. Ensure Mailhog is running: `http://localhost:8025`
2. Go to `/login/email`
3. Enter test email: `user@example.com`
4. Click "Send Code"
5. Check Mailhog web UI for the email with OTP
6. Enter OTP on the verification page

### Gmail SMTP with App Password

If you want to send real emails through Gmail, use a Gmail App Password and do not include spaces or quotes in `MAIL_PASSWORD`.

Update your `.env` like this:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your_app_password_without_spaces
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="Live and Notify Tourism"
```

Then clear config cache:

```bash
php artisan config:clear
php artisan cache:clear
```

Test sending a sample OTP email via Tinker:

```bash
php artisan tinker
```

Then run in Tinker:

```php
Mail::mailer(config('mail.default'))->to('yourtest@example.com')->send(new App\Mail\UserOtpEmail('123456'));
```

If the send fails, check `/storage/logs/laravel.log` for the exception and make sure your network allows outbound SMTP on port 587.

### SMS OTP Test

1. Go to `/login/phone`
2. Select country (e.g., TZ for Tanzania)
3. Enter phone number (e.g., `712345678`)
4. Click "Send Code"
5. Check Laravel logs: `/storage/logs/laravel.log`
   - Look for: `OTP (development/test mode) {"phone":"+255712345678"...}`
6. Enter OTP on the verification page

---

## Key Changes Made

### Database

- Created `phone_verification_otps` table (already exists)
- Tracks SMS status: `sms_status` field (pending/sent/failed)
- Tracks attempts: `attempt_count` field

### Code

**Before**: Phone login just stored OTP in session
```php
session()->put('user_phone_login_otp', $otp);
// SMS was never sent!
```

**After**: Phone login sends actual SMS
```php
$otpRecord = PhoneVerificationOtp::create([...]);
$smsSent = SmsService::sendOtp($phone, $otp, $country);
$otpRecord->update(['sms_status' => $smsSent ? 'sent' : 'failed']);
```

---

## Troubleshooting

### Email OTP Still Not Working?

1. **Check Mailhog is running**: `http://localhost:8025` should load
2. **Check .env MAIL_MAILER**: Should be `smtp`
3. **Check logs**: `/storage/logs/laravel.log` for errors
4. **Clear cache**: `php artisan config:cache`

### SMS OTP Not Working?

1. **Check SMS_PROVIDER**: Should be `log` for development
2. **Check logs**: `/storage/logs/laravel.log` for `OTP (development/test mode)`
3. **For Twilio**: Verify Account SID, Auth Token in .env
4. **Check phone format**: Should include country code (e.g., +255712345678)

### "Unable to send the verification code. Please try again later."

This error means:
- **For Email**: Mail::send() failed (check network, Mailhog running, mail config)
- **For SMS**: SmsService::sendOtp() failed (check logs for details)

---

## Differences: User OTP vs Admin OTP

| Aspect | User OTP | Admin OTP |
|--------|----------|----------|
| Email Login | ✅ UserEmailLogin | ✅ AdminPhoneLogin |
| Phone/SMS | ✅ UserPhoneLogin | ❌ Not for admins |
| Storage | Database (PhoneVerificationOtp) | Database (AdminAccessKey) |
| User Type | Regular users/tourists | Admins only |

Users can use either email or phone for login. Admins can only use email.

---

## Files Modified

- ✅ `.env` - Changed mail config to Mailhog, updated SMS config
- ✅ [app/Services/SmsService.php](app/Services/SmsService.php) - NEW SMS service
- ✅ [app/Livewire/Auth/UserPhoneLogin.php](app/Livewire/Auth/UserPhoneLogin.php) - Now sends actual SMS
- ✅ [config/services.php](config/services.php) - Added Twilio config

---

## Next Steps for Production

1. **Email**: Use cloud email service (Mailgun, SendGrid, Amazon SES)
   ```env
   MAIL_MAILER=mailgun
   MAILGUN_DOMAIN=your-domain.com
   MAILGUN_SECRET=your-secret
   ```

2. **SMS**: Get real Twilio credentials
   ```env
   SMS_PROVIDER=twilio
   TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxx
   TWILIO_AUTH_TOKEN=your_auth_token
   TWILIO_PHONE_NUMBER=+1234567890
   ```

3. **Monitoring**: Log all OTP attempts for audit trail
