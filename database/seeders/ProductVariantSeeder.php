<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Product;

class ProductVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $variants = [
            // Matte Liquid Lipstick variants
            ['product' => 'Matte Liquid Lipstick', 'name' => 'Ruby Red', 'sku' => 'MLL-RR-001', 'supplier_code' => 'SUP001', 'selling_price' => 1500.00, 'cost_price' => 800.00],
            ['product' => 'Matte Liquid Lipstick', 'name' => 'Pink Nude', 'sku' => 'MLL-PN-002', 'supplier_code' => 'SUP002', 'selling_price' => 1500.00, 'cost_price' => 800.00],
            ['product' => 'Matte Liquid Lipstick', 'name' => 'Berry Crush', 'sku' => 'MLL-BC-003', 'supplier_code' => 'SUP003', 'selling_price' => 1500.00, 'cost_price' => 800.00],
            ['product' => 'Matte Liquid Lipstick', 'name' => 'Coral Pink', 'sku' => 'MLL-CP-004', 'supplier_code' => 'SUP004', 'selling_price' => 1500.00, 'cost_price' => 800.00],

            // Glossy Lip Balm variants
            ['product' => 'Glossy Lip Balm', 'name' => 'Clear', 'sku' => 'GLB-CL-001', 'supplier_code' => 'SUP005', 'selling_price' => 700.00, 'cost_price' => 400.00],
            ['product' => 'Glossy Lip Balm', 'name' => 'Tinted Rose', 'sku' => 'GLB-TR-002', 'supplier_code' => 'SUP006', 'selling_price' => 800.00, 'cost_price' => 450.00],
            ['product' => 'Glossy Lip Balm', 'name' => 'Cherry Red', 'sku' => 'GLB-CR-003', 'supplier_code' => 'SUP007', 'selling_price' => 800.00, 'cost_price' => 450.00],

            // Classic Red Lipstick variants
            ['product' => 'Classic Red Lipstick', 'name' => 'Matte Finish', 'sku' => 'CRL-MF-001', 'supplier_code' => 'SUP008', 'selling_price' => 1200.00, 'cost_price' => 700.00],
            ['product' => 'Classic Red Lipstick', 'name' => 'Satin Finish', 'sku' => 'CRL-SF-002', 'supplier_code' => 'SUP009', 'selling_price' => 1200.00, 'cost_price' => 700.00],

            // Full Coverage Foundation variants
            ['product' => 'Full Coverage Foundation', 'name' => 'Light Beige', 'sku' => 'FCF-LB-001', 'supplier_code' => 'SUP010', 'selling_price' => 2800.00, 'cost_price' => 1800.00],
            ['product' => 'Full Coverage Foundation', 'name' => 'Medium Tan', 'sku' => 'FCF-MT-002', 'supplier_code' => 'SUP011', 'selling_price' => 2800.00, 'cost_price' => 1800.00],
            ['product' => 'Full Coverage Foundation', 'name' => 'Deep Caramel', 'sku' => 'FCF-DC-003', 'supplier_code' => 'SUP012', 'selling_price' => 2800.00, 'cost_price' => 1800.00],
            ['product' => 'Full Coverage Foundation', 'name' => 'Honey Beige', 'sku' => 'FCF-HB-004', 'supplier_code' => 'SUP013', 'selling_price' => 2800.00, 'cost_price' => 1800.00],

            // BB Cream variants
            ['product' => 'BB Cream', 'name' => 'Fair', 'sku' => 'BBC-FA-001', 'supplier_code' => 'SUP014', 'selling_price' => 2000.00, 'cost_price' => 1200.00],
            ['product' => 'BB Cream', 'name' => 'Medium', 'sku' => 'BBC-MD-002', 'supplier_code' => 'SUP015', 'selling_price' => 2000.00, 'cost_price' => 1200.00],
            ['product' => 'BB Cream', 'name' => 'Dark', 'sku' => 'BBC-DK-003', 'supplier_code' => 'SUP016', 'selling_price' => 2000.00, 'cost_price' => 1200.00],

            // Tinted Moisturizer variants
            ['product' => 'Tinted Moisturizer', 'name' => 'Light Coverage', 'sku' => 'TM-LC-001', 'supplier_code' => 'SUP017', 'selling_price' => 1800.00, 'cost_price' => 1100.00],
            ['product' => 'Tinted Moisturizer', 'name' => 'Medium Coverage', 'sku' => 'TM-MC-002', 'supplier_code' => 'SUP018', 'selling_price' => 1800.00, 'cost_price' => 1100.00],

            // Neutral Eyeshadow Palette variants
            ['product' => 'Neutral Eyeshadow Palette', 'name' => '12 Shades', 'sku' => 'NEP-12S-001', 'supplier_code' => 'SUP019', 'selling_price' => 3500.00, 'cost_price' => 2200.00],
            ['product' => 'Neutral Eyeshadow Palette', 'name' => '18 Shades', 'sku' => 'NEP-18S-002', 'supplier_code' => 'SUP020', 'selling_price' => 4200.00, 'cost_price' => 2800.00],

            // Smokey Eye Palette variants
            ['product' => 'Smokey Eye Palette', 'name' => '10 Shades', 'sku' => 'SEP-10S-001', 'supplier_code' => 'SUP021', 'selling_price' => 3200.00, 'cost_price' => 2000.00],

            // Shimmer Eyeshadow variants
            ['product' => 'Shimmer Eyeshadow', 'name' => 'Gold Rush', 'sku' => 'SE-GR-001', 'supplier_code' => 'SUP022', 'selling_price' => 900.00, 'cost_price' => 500.00],
            ['product' => 'Shimmer Eyeshadow', 'name' => 'Silver Moon', 'sku' => 'SE-SM-002', 'supplier_code' => 'SUP023', 'selling_price' => 900.00, 'cost_price' => 500.00],
            ['product' => 'Shimmer Eyeshadow', 'name' => 'Bronze Glow', 'sku' => 'SE-BG-003', 'supplier_code' => 'SUP024', 'selling_price' => 900.00, 'cost_price' => 500.00],

            // Volumizing Mascara variants
            ['product' => 'Volumizing Mascara', 'name' => 'Black', 'sku' => 'VM-BL-001', 'supplier_code' => 'SUP025', 'selling_price' => 1600.00, 'cost_price' => 1000.00],
            ['product' => 'Volumizing Mascara', 'name' => 'Brown', 'sku' => 'VM-BR-002', 'supplier_code' => 'SUP026', 'selling_price' => 1600.00, 'cost_price' => 1000.00],
            ['product' => 'Volumizing Mascara', 'name' => 'Blue', 'sku' => 'VM-BL-003', 'supplier_code' => 'SUP027', 'selling_price' => 1600.00, 'cost_price' => 1000.00],

            // Waterproof Mascara variants
            ['product' => 'Waterproof Mascara', 'name' => 'Black', 'sku' => 'WM-BL-001', 'supplier_code' => 'SUP028', 'selling_price' => 1800.00, 'cost_price' => 1100.00],
            ['product' => 'Waterproof Mascara', 'name' => 'Brown', 'sku' => 'WM-BR-002', 'supplier_code' => 'SUP029', 'selling_price' => 1800.00, 'cost_price' => 1100.00],

            // Vitamin C Serum variants
            ['product' => 'Vitamin C Serum', 'name' => '30ml', 'sku' => 'VCS-30ML-001', 'supplier_code' => 'SUP030', 'selling_price' => 3200.00, 'cost_price' => 2000.00],
            ['product' => 'Vitamin C Serum', 'name' => '50ml', 'sku' => 'VCS-50ML-002', 'supplier_code' => 'SUP031', 'selling_price' => 4800.00, 'cost_price' => 3000.00],

            // Hyaluronic Acid Moisturizer variants
            ['product' => 'Hyaluronic Acid Moisturizer', 'name' => '50ml', 'sku' => 'HAM-50ML-001', 'supplier_code' => 'SUP032', 'selling_price' => 2800.00, 'cost_price' => 1800.00],
            ['product' => 'Hyaluronic Acid Moisturizer', 'name' => '100ml', 'sku' => 'HAM-100ML-002', 'supplier_code' => 'SUP033', 'selling_price' => 4200.00, 'cost_price' => 2700.00],

            // Gentle Face Cleanser variants
            ['product' => 'Gentle Face Cleanser', 'name' => '150ml', 'sku' => 'GFC-150ML-001', 'supplier_code' => 'SUP034', 'selling_price' => 1800.00, 'cost_price' => 1100.00],
            ['product' => 'Gentle Face Cleanser', 'name' => '250ml', 'sku' => 'GFC-250ML-002', 'supplier_code' => 'SUP035', 'selling_price' => 2500.00, 'cost_price' => 1600.00],

            // Floral Eau de Parfum variants
            ['product' => 'Floral Eau de Parfum', 'name' => '30ml', 'sku' => 'FEP-30ML-001', 'supplier_code' => 'SUP036', 'selling_price' => 4500.00, 'cost_price' => 3000.00],
            ['product' => 'Floral Eau de Parfum', 'name' => '50ml', 'sku' => 'FEP-50ML-002', 'supplier_code' => 'SUP037', 'selling_price' => 6500.00, 'cost_price' => 4200.00],
            ['product' => 'Floral Eau de Parfum', 'name' => '100ml', 'sku' => 'FEP-100ML-003', 'supplier_code' => 'SUP038', 'selling_price' => 9500.00, 'cost_price' => 6200.00],

            // Woody Cologne variants
            ['product' => 'Woody Cologne', 'name' => '50ml', 'sku' => 'WC-50ML-001', 'supplier_code' => 'SUP039', 'selling_price' => 5800.00, 'cost_price' => 3800.00],
            ['product' => 'Woody Cologne', 'name' => '100ml', 'sku' => 'WC-100ML-002', 'supplier_code' => 'SUP040', 'selling_price' => 8800.00, 'cost_price' => 5800.00]
        ];

        foreach ($variants as $variantData) {
            $product = Product::where('name', $variantData['product'])->first();
            if ($product) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'supplier_code' => $variantData['supplier_code'],
                    'selling_price' => $variantData['selling_price'],
                    'cost_price' => $variantData['cost_price']
                ]);
            }
        }
    }
}
