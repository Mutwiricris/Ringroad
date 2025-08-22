<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user first
        User::factory()->create([
            'name' => 'Ring Road Admin',
            'email' => 'admin@ringroadcosmetics.com',
        ]);

        // Run seeders in dependency order
        $this->call([
            CategorySeeder::class,
            LocationSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            CustomerSeeder::class,
            InventorySeeder::class,
            OrderSeeder::class, // This will create orders, order items, and payments
        ]);
    }
}
