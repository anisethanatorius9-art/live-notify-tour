<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Tour', 'Activity', 'Accommodation', 'Transport', 'Dining', 'Entertainment'];

        return [
            'provider_id' => User::factory(),
            'location_id' => Location::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'category' => $this->faker->randomElement($categories),
            'status' => 'active',
            'rating' => $this->faker->numberBetween(0, 5),
        ];
    }
}
