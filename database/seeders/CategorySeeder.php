<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Lipsticks',
            'Foundation',
            'Eyeshadow',
            'Mascara',
            'Blush',
            'Concealer',
            'Eyeliner',
            'Bronzer',
            'Highlighter',
            'Nail Polish',
            'Skincare',
            'Perfumes',
            'Hair Products',
            'Body Lotions'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
