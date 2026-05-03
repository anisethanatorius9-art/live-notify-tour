<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Location;
use App\Models\Service;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update test user (tourist)
        $touristUser = User::updateOrCreate(
            ['email' => 'tourist@example.com'],
            [
                'name' => 'Aniseth Anatorius',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'tourist',
            ]
        );

        // Create provider user
        $providerUser = User::updateOrCreate(
            ['email' => 'provider@example.com'],
            [
                'name' => 'Service Provider',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
                'role' => 'provider',
            ]
        );

        // Create locations
        $locations = Location::firstOrCreate(['name' => 'Nairobi']);
        Location::firstOrCreate(['name' => 'Mombasa']);
        Location::firstOrCreate(['name' => 'Kisumu']);
        Location::firstOrCreate(['name' => 'Nakuru']);

        // Create sample services
        $categories = ['Tour', 'Activity', 'Accommodation', 'Transport', 'Dining', 'Entertainment'];
        $serviceNames = [
            'City Tour Experience',
            'Safari Adventure',
            'Beach Relaxation Package',
            'Mountain Hiking',
            'Cultural Experience',
            'Water Sports',
            'Wildlife Photography',
            'Local Cuisine Tour',
        ];

        foreach ($serviceNames as $index => $name) {
            Service::updateOrCreate(
                ['name' => $name],
                [
                    'provider_id' => $providerUser->id,
                    'location_id' => Location::inRandomOrder()->first()->id,
                    'description' => 'Experience the best ' . strtolower($name) . ' with our professional guides and amenities.',
                    'price' => fake()->randomFloat(2, 50, 500),
                    'category' => $categories[$index % count($categories)],
                    'status' => 'active',
                    'rating' => fake()->numberBetween(3, 5),
                ]
            );
        }
    }
}
