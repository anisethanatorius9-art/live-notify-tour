<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\UserManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_management_filters_providers(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'tourist']);
        User::factory()->create(['role' => 'provider']);

        $this->actingAs($admin);

        Livewire::test(UserManagement::class)
            ->set('filterRole', 'provider')
            ->assertSet('filterRole', 'provider')
            ->assertSee('Provider');
    }

    public function test_admin_can_change_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($admin);

        Livewire::test(UserManagement::class)
            ->call('updateUserRole', $user->id, 'provider')
            ->assertSet('filterRole', '');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'provider',
        ]);
    }
}
