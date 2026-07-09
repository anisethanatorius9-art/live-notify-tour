<?php

namespace Tests\Feature\Admin;

use App\Livewire\Dashboard\AdminDashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminDashboardDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_delete_a_user_from_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $targetUser = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($admin);

        Livewire::test(AdminDashboard::class)
            ->call('deleteUser', $targetUser->id);

        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
