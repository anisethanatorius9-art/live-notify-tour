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

        // Create locations across East Africa
        $eastAfricaLocations = [
            'Nairobi',
            'Mombasa',
            'Zanzibar',
            'Kigali',
            'Dar es Salaam',
            'Entebbe',
            'Arusha',
            'Serengeti',
        ];

        foreach ($eastAfricaLocations as $locationName) {
            Location::firstOrCreate(['name' => $locationName]);
        }

        // Create sample services with photos and strong East Africa categories
        $sampleServices = [
            [
                'name' => 'Nairobi City Culture Tour',
                'description' => 'Explore Nairobi’s markets, museums, and local neighborhoods with an expert guide.',
                'category' => 'Tour',
                'location' => 'Nairobi',
                'image_url' => 'https://images.unsplash.com/photo-1545579620-8d4f8db6f9c9?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Mombasa Beach Resort Experience',
                'description' => 'Relax on white-sand beaches with ocean views, fresh seafood, and resort comforts.',
                'category' => 'Accommodation',
                'location' => 'Mombasa',
                'image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Zanzibar Spice Tour and Dinner',
                'description' => 'Visit spice farms, taste local herbs, and enjoy a Swahili dinner under the stars.',
                'category' => 'Dining',
                'location' => 'Zanzibar',
                'image_url' => 'https://images.unsplash.com/photo-1495121605193-b116b5b9c5b2?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Kigali Food Walk',
                'description' => 'Taste Rwandan specialties and learn about Kigali’s vibrant culinary scene.',
                'category' => 'Dining',
                'location' => 'Kigali',
                'image_url' => 'https://images.unsplash.com/photo-1466637574441-749b8f19452f?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Arusha Safari Lodge Stay',
                'description' => 'Stay in a comfortable lodge near Tanzanian safari parks with guided game drives.',
                'category' => 'Accommodation',
                'location' => 'Arusha',
                'image_url' => 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Dar es Salaam City Highlights',
                'description' => 'Discover coastal markets, beaches, and the best local restaurants in Dar es Salaam.',
                'category' => 'Tour',
                'location' => 'Dar es Salaam',
                'image_url' => 'https://images.unsplash.com/photo-1483683804023-6ccdb62f86ef?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Serengeti Photo Safari',
                'description' => 'Capture iconic wildlife scenes in the Serengeti, guided by an experienced naturalist.',
                'category' => 'Tour',
                'location' => 'Serengeti',
                'image_url' => 'https://images.unsplash.com/photo-1518143196405-0c315b4c7f84?auto=format&fit=crop&w=1200&q=80',
            ],
            // Additional hotels and dining options across East Africa
            [
                'name' => 'Nairobi Boutique Hotel',
                'description' => 'Chic boutique hotel in central Nairobi with rooftop dining and pool.',
                'category' => 'Accommodation',
                'location' => 'Nairobi',
                'image_url' => 'https://images.unsplash.com/photo-1501117716987-c8e0e3b67b32?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Nairobi Street Bites (Local Foods)',
                'description' => 'A curated food tour sampling nyama choma, mandazi and local sweets.',
                'category' => 'Dining',
                'location' => 'Nairobi',
                'image_url' => 'https://images.unsplash.com/photo-1546069901-eacef0df6022?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Mombasa Deluxe Beach Hotel',
                'description' => 'Oceanfront hotel with sea-facing suites and seafood restaurant.',
                'category' => 'Accommodation',
                'location' => 'Mombasa',
                'image_url' => 'https://images.unsplash.com/photo-1501117716987-c8e0e3b67b35?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Mombasa Seafood Platter Experience',
                'description' => 'Fresh catch seafood tasting with coastal Swahili sauces.',
                'category' => 'Dining',
                'location' => 'Mombasa',
                'image_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Zanzibar Beach Resort',
                'description' => 'Luxury beach resort with private dhow excursions and spice dinners.',
                'category' => 'Accommodation',
                'location' => 'Zanzibar',
                'image_url' => 'https://images.unsplash.com/photo-1501117716987-c8e0e3b67b36?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Zanzibar Fresh Fish Dinner',
                'description' => 'Romantic beachside grilled fish dinner with local spices.',
                'category' => 'Dining',
                'location' => 'Zanzibar',
                'image_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc839?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Kigali Hills Boutique Lodge',
                'description' => 'Comfortable lodge overlooking Kigali with local coffee tasting.',
                'category' => 'Accommodation',
                'location' => 'Kigali',
                'image_url' => 'https://images.unsplash.com/photo-1528909514045-2fa4ac7a08ba?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Kigali Traditional Dinner',
                'description' => 'Rwandan specialties served family-style with storytelling.',
                'category' => 'Dining',
                'location' => 'Kigali',
                'image_url' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc831?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Dar es Salaam Seafront Hotel',
                'description' => 'Modern hotel near the harbour with rooftop bar and pool.',
                'category' => 'Accommodation',
                'location' => 'Dar es Salaam',
                'image_url' => 'https://images.unsplash.com/photo-1501117716987-c8e0e3b67b37?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Dar Street Food Crawl',
                'description' => 'An evening of coastal snacks, samosas and coconut treats.',
                'category' => 'Dining',
                'location' => 'Dar es Salaam',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Entebbe Lakeside Hotel',
                'description' => 'Relaxing hotel on Lake Victoria with boat trips and birding tours.',
                'category' => 'Accommodation',
                'location' => 'Entebbe',
                'image_url' => 'https://images.unsplash.com/photo-1501117716987-c8e0e3b67b38?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'name' => 'Entebbe Fresh Market Dining',
                'description' => 'A selection of local Ugandan dishes and fresh produce tastings.',
                'category' => 'Dining',
                'location' => 'Entebbe',
                'image_url' => 'https://images.unsplash.com/photo-1544025162-d76694265948?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($sampleServices as $item) {
            Service::updateOrCreate(
                ['name' => $item['name']],
                [
                    'provider_id' => $providerUser->id,
                    'location_id' => Location::where('name', $item['location'])->first()->id,
                    'description' => $item['description'],
                    'price' => fake()->randomFloat(2, 70, 450),
                    'category' => $item['category'],
                    'status' => 'active',
                    'rating' => fake()->numberBetween(4, 5),
                    'image_url' => $item['image_url'],
                ]
            );
        }
    }
}
