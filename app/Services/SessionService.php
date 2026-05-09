<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SessionService
{
    /**
     * Perform complete logout with all session cleanup
     */
    public static function completeLogout(): void
    {
        // Get current user before logout
        /** @var User|null $user */
        $user = Auth::user();

        // Clear remember token
        if ($user instanceof User) {
            $user->update(['remember_token' => null]);
        }

        // Clear all sessions for this user from database (if using database driver)
        if (config('session.driver') === 'database') {
            DB::table(config('session.table'))
                ->where('user_id', optional($user)->id)
                ->delete();
        }

        // Standard Laravel logout
        Auth::guard('web')->logout();

        // Invalidate and regenerate session
        Session::invalidate();
        Session::regenerateToken();
    }

    /**
     * Invalidate a specific user's all sessions (useful for admin actions)
     */
    public static function invalidateAllUserSessions(int $userId): void
    {
        if (config('session.driver') === 'database') {
            DB::table(config('session.table'))
                ->where('user_id', $userId)
                ->delete();
        }
    }

    /**
     * Clean up expired sessions
     */
    public static function cleanupExpiredSessions(): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $lifetimeInSeconds = config('session.lifetime') * 60;
        $expiresAt = now()->timestamp - $lifetimeInSeconds;

        DB::table(config('session.table'))
            ->where('last_activity', '<', $expiresAt)
            ->delete();
    }
}
