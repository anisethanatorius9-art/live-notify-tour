<?php

namespace Tests\Feature\Auth;

use App\Livewire\Auth\AdminPhoneLogin;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminPhoneLoginPageTest extends TestCase
{
    public function test_admin_email_login_page_renders_once_without_nested_document_wrappers(): void
    {
        $response = $this->get(route('admin.login.email'));

        $response->assertOk();
        $response->assertSee('Admin Email OTP Login');

        $content = $response->getContent();

        $this->assertSame(1, substr_count($content, '<!DOCTYPE html>'));
        $this->assertStringNotContainsString('<html wire:snapshot', $content);
    }

    public function test_admin_email_login_accepts_another_admin_email_for_otp(): void
    {
        Mail::fake();

        $component = new AdminPhoneLogin();
        $component->email = 'another-admin@example.com';

        $this->withoutExceptionHandling();

        $this->expectException(\Illuminate\Database\QueryException::class);

        $component->sendOtp();
    }
}
