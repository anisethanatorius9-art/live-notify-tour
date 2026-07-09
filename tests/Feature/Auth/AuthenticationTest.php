<?php

namespace Tests\Feature\Auth;

use App\Mail\AdminOtpEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Features;
use Livewire\Livewire;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->withoutTwoFactor()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrorsIn('email');

        $this->assertGuest();
    }

    public function test_users_can_authenticate_with_mixed_case_email(): void
    {
        $user = User::factory()->withoutTwoFactor()->create(['email' => 'user@example.com']);

        $response = $this->post(route('login.store'), [
            'email' => 'USER@EXAMPLE.COM',
            'password' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_email_login_sends_otp_to_the_normalized_email_input(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'Admin@Example.com']);

        Livewire::test(\App\Livewire\Auth\AdminPhoneLogin::class)
            ->set('email', 'Admin@Example.com')
            ->call('sendOtp');

        Mail::assertSent(AdminOtpEmail::class, function (AdminOtpEmail $mail) {
            return $mail->hasTo('admin@example.com');
        });
    }

    public function test_existing_user_receives_otp_at_their_registered_email_address(): void
    {
        Mail::fake();

        DB::table('users')->insert([
            'name' => 'Existing User',
            'email' => 'User@Example.COM',
            'password' => bcrypt('password'),
            'role' => 'tourist',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::whereRaw('LOWER(email) = ?', ['user@example.com'])->firstOrFail();

        Livewire::test(\App\Livewire\Auth\UserEmailLogin::class)
            ->set('email', 'user@example.com')
            ->call('sendOtp');

        Mail::assertSent(\App\Mail\UserOtpEmail::class, function (\App\Mail\UserOtpEmail $mail) use ($user): bool {
            return $mail->hasTo($user->email);
        });
    }

    public function test_new_user_receives_otp_at_the_email_they_entered(): void
    {
        Mail::fake();

        Livewire::test(\App\Livewire\Auth\UserEmailLogin::class)
            ->set('email', 'newuser@example.com')
            ->call('sendOtp');

        Mail::assertSent(\App\Mail\UserOtpEmail::class, function (\App\Mail\UserOtpEmail $mail): bool {
            return $mail->hasTo('newuser@example.com');
        });
    }

    public function test_admin_users_cannot_authenticate_using_regular_login_screen(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrorsIn('email');

        $this->assertGuest();
    }

    public function test_users_with_two_factor_enabled_are_redirected_to_two_factor_challenge(): void
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            $this->markTestSkipped('Two-factor authentication is not enabled.');
        }

        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
        ]);

        $user = User::factory()->create();

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('two-factor.login'));
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));

        $this->assertGuest();
    }
}
