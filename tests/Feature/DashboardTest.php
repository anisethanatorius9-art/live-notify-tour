<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_users_are_redirected_to_their_respective_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));


        $response->assertRedirect('/dashboard/tourist');
    }

    public function test_tourist_users_can_visit_the_tourist_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => 'tourist',
            'email_verified_at' => now(),
        ]);
        $this->actingAs($user);


        $response = $this->get('/dashboard/tourist');

        $response->assertStatus(200);
    }
}
