<?php

namespace App\Exports;

use App\Models\ProductVariant;
use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductVariantsInventoryReport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    private $totalUnitsCache = [];

    public function __construct()
    {
        // Pre-calculate total units for all variants to avoid N+1 queries
        $this->totalUnitsCache = Inventory::select('product_variant_id', DB::raw('SUM(quantity) as total'))
            ->groupBy('product_variant_id')
            ->pluck('total', 'product_variant_id')
            ->toArray();
    }

    public function collection()
    {
        // Optimized single query with all necessary joins
        return Inventory::with([
            'productVariant:id,product_id,cost_price,selling_price',
            'productVariant.product:id,name,category_id',
            'productVariant.product.category:id,name',
            'location:id,name'
        ])
        ->select('id', 'product_variant_id', 'location_id', 'quantity')
        ->limit(5000) // Limit to prevent timeout
        ->get();
    }

    public function headings(): array
    {
        return [
            'Category',
            'Product Name',
            'Units in Stock',
            'Location',
            'Buying Price',
            'TOTAL UNITS',
            'RRP',
            'Selling Price'
        ];
    }

    public function map($inventory): array
    {
        $variant = $inventory->productVariant;
        $product = $variant->product;
        $category = $product->category;
        
        // Get total units from pre-calculated cache
        $totalUnits = $this->totalUnitsCache[$variant->id] ?? 0;
        
        // RRP calculation with 20% markup on selling price
        $rrp = $variant->selling_price * 1.2;

        return [
            $category->name ?? 'Uncategorized',
            $product->name ?? 'Unknown Product',
            $inventory->quantity, // Units in stock at this location
            $inventory->location->name ?? 'Unknown Location',
            number_format($variant->cost_price, 2),
            $totalUnits, // Total units across all locations
            number_format($rrp, 2),
            number_format($variant->selling_price, 2)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2E7D32'], // Dark green header
                ],
            ],
            // All data cells
            'A:H' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Category
            'B' => 25,  // Product Name
            'C' => 12,  // Units in Stock
            'D' => 15,  // Location
            'E' => 12,  // Buying Price
            'F' => 12,  // TOTAL UNITS
            'G' => 10,  // RRP
            'H' => 12,  // Selling Price
        ];
    }
}
