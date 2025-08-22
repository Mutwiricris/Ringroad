<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Lipsticks
            ['name' => 'Matte Liquid Lipstick', 'category' => 'Lipsticks'],
            ['name' => 'Glossy Lip Balm', 'category' => 'Lipsticks'],
            ['name' => 'Classic Red Lipstick', 'category' => 'Lipsticks'],

            // Foundation
            ['name' => 'Full Coverage Foundation', 'category' => 'Foundation'],
            ['name' => 'BB Cream', 'category' => 'Foundation'],
            ['name' => 'Tinted Moisturizer', 'category' => 'Foundation'],

            // Eyeshadow
            ['name' => 'Neutral Eyeshadow Palette', 'category' => 'Eyeshadow'],
            ['name' => 'Smokey Eye Palette', 'category' => 'Eyeshadow'],
            ['name' => 'Shimmer Eyeshadow', 'category' => 'Eyeshadow'],

            // Mascara
            ['name' => 'Volumizing Mascara', 'category' => 'Mascara'],
            ['name' => 'Waterproof Mascara', 'category' => 'Mascara'],

            // Skincare
            ['name' => 'Vitamin C Serum', 'category' => 'Skincare'],
            ['name' => 'Hyaluronic Acid Moisturizer', 'category' => 'Skincare'],
            ['name' => 'Gentle Face Cleanser', 'category' => 'Skincare'],

            // Perfumes
            ['name' => 'Floral Eau de Parfum', 'category' => 'Perfumes'],
            ['name' => 'Woody Cologne', 'category' => 'Perfumes']
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            Product::create([
                'name' => $productData['name'],
                'category_id' => $category->id,
                'is_active' => true
            ]);
        }
    }
}
