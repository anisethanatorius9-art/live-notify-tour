<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneVerificationOtp extends Model
{
    protected $table = 'phone_verification_otps';

    protected $fillable = [
        'user_id',
        'otp',
        'email',
        'phone',
        'country',
        'is_verified',
        'expires_at',
        'attempt_count',
        'last_attempt_at',
        'sms_status',
        'sms_response',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_attempt_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Get the user associated with this OTP.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if OTP is still valid (not expired and verified).
     */
    public function isValid(): bool
    {
        return !$this->is_verified && now()->isBefore($this->expires_at);
    }

    /**
     * Check if OTP has expired.
     */
    public function hasExpired(): bool
    {
        return now()->isAfter($this->expires_at);
    }

    /**
     * Mark OTP as verified.
     */
    public function markAsVerified(): void
    {
        $this->update(['is_verified' => true]);
    }

    /**
     * Get the latest unverified OTP for a user.
     */
    public static function getLatestActiveOtp(int $userId)
    {
        return static::where('user_id', $userId)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->latest('created_at')
            ->first();
    }

    /**
     * Generate a unique OTP.
     */
    public static function generateUniqueOtp(): string
    {
        do {
            $otp = (string) random_int(100000, 999999);
        } while (static::where('otp', $otp)->exists());

        return $otp;
    }

    /**
     * Record an attempt to verify the OTP.
     */
    public function recordAttempt(): void
    {
        $this->increment('attempt_count');
        $this->update(['last_attempt_at' => now()]);
    }

    /**
     * Check if too many verification attempts have been made (>5 attempts).
     */
    public function hasTooManyAttempts(): bool
    {
        return $this->attempt_count >= 5;
    }
}
