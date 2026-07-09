<?php

namespace Tests\Feature\Services;

use App\Livewire\Services\ServiceForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ServiceFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_form_renders_without_flux_button_variant_error(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin);

        Livewire::test(ServiceForm::class)
            ->assertOk();
    }
}
