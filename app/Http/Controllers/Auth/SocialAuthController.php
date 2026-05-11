<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Failed to authenticate with Google. Please try again.');
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName();

        if (! $email) {
            return redirect()->route('login')->with('error', 'Google did not provide an email address. Please use another login method.');
        }

        // Try to find user by Google ID
        $user = User::where('google_id', $googleId)->first();

        // If not found, try to find by email (for linking accounts)
        if (! $user) {
            $user = User::where('email', $email)->first();
            if ($user) {
                // Link Google ID to existing account
                $user->update(['google_id' => $googleId]);
            }
        }

        // If still not found, create a new user
        if (! $user) {
            $user = User::create([
                'name' => $name ?? $email,
                'email' => $email,
                'password' => bcrypt(str()->random(24)), // Random password since Google auth
                'google_id' => $googleId,
            ]);
        }

        // Mark email as verified if not already
        if (! $user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'));
    }
}
