<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Location;
use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductVariantImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty(trim($row['category'] ?? '')) || empty(trim($row['product_name'] ?? ''))) {
            return null;
        }

        // Find or create category
        $category = Category::firstOrCreate(
            ['name' => trim($row['category'])],
            ['name' => trim($row['category'])]
        );

        // Find or create product based on product name and category
        $product = Product::firstOrCreate(
            [
                'name' => trim($row['product_name']),
                'category_id' => $category->id
            ],
            [
                'name' => trim($row['product_name']),
                'category_id' => $category->id,
                'is_active' => true
            ]
        );

        // Create product variant
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => trim($row['product_name']), // Use product name as variant name
            'sku' => $this->generateSku($row['product_name']),
            'cost_price' => (float) ($row['buying_price'] ?? 0),
            'selling_price' => (float) ($row['selling_price'] ?? 0),
            'quantity' => (int) ($row['total_units'] ?? 0)
        ]);

        // Handle location and inventory if provided
        if (!empty($row['location'])) {
            $location = Location::firstOrCreate(
                ['name' => trim($row['location'])],
                ['name' => trim($row['location'])]
            );

            // Create inventory record
            Inventory::create([
                'product_variant_id' => $variant->id,
                'location_id' => $location->id,
                'quantity' => (int) ($row['units_in_stock'] ?? $row['total_units'] ?? 0)
            ]);
        }

        return $variant;
    }

    /**
     * Generate SKU from product name
     */
    private function generateSku(string $productName): string
    {
        $base = strtoupper(Str::slug($productName, ''));
        $base = substr($base, 0, 10); // Limit to 10 characters
        
        // Check if SKU exists and append number if needed
        $counter = 1;
        $sku = $base;
        
        while (ProductVariant::where('sku', $sku)->exists()) {
            $sku = $base . $counter;
            $counter++;
        }
        
        return $sku;
    }

    /**
     * Validation rules for each row
     */
    public function rules(): array
    {
        return [
            'category' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'units_in_stock' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'buying_price' => 'required|numeric|min:0',
            'total_units' => 'nullable|numeric|min:0',
            'rrp' => 'nullable|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'category.required' => 'Category is required',
            'product_name.required' => 'Product name is required',
            'buying_price.required' => 'Buying price is required',
            'buying_price.numeric' => 'Buying price must be a number',
            'selling_price.required' => 'Selling price is required',
            'selling_price.numeric' => 'Selling price must be a number',
        ];
    }
}
