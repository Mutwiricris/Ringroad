<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\Location;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = ProductVariant::all();
        $locations = Location::all();

        foreach ($variants as $variant) {
            foreach ($locations as $location) {
                // Create inventory for each variant at each location
                // Some locations might have more stock than others
                $baseQuantity = rand(5, 50);

                // Kitengela Shop (main shop) has more stock
                if ($location->name === 'Kitengela Shop') {
                    $quantity = $baseQuantity + rand(20, 80);
                } else {
                    $quantity = rand(0, $baseQuantity); // Other locations might be out of stock sometimes
                }

                Inventory::create([
                    'product_variant_id' => $variant->id,
                    'location_id' => $location->id,
                    'quantity' => $quantity
                ]);
            }
        }
    }
}
