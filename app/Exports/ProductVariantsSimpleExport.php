<?php

namespace App\Exports;

use App\Models\ProductVariant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ProductVariantsSimpleExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = ProductVariant::with(['product.category', 'inventory']);

        // Apply filters if provided
        if (!empty($this->filters)) {
            if (isset($this->filters['product_id']) && $this->filters['product_id']) {
                $query->where('product_id', $this->filters['product_id']);
            }
            if (isset($this->filters['created_from']) && $this->filters['created_from']) {
                $query->whereDate('created_at', '>=', $this->filters['created_from']);
            }
            if (isset($this->filters['created_until']) && $this->filters['created_until']) {
                $query->whereDate('created_at', '<=', $this->filters['created_until']);
            }
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Category',
            'Product Name',
            'Buying Price',
            'Selling Price',
            'Units Sold',
            'Amount',
            'Units Sold',
            'Amount',
            'Units Sold',
            'Amount',
            'TOTAL',
            'Amount'
        ];
    }

    public function map($variant): array
    {
        $stockQty = $variant->inventory->sum('quantity');

        return [
            $variant->id,
            $variant->product->category->name ?? 'Accessories',
            $variant->product->name ?? $variant->name,
            $variant->cost_price,
            $variant->selling_price,
            '-', // Units Sold (placeholder)
            '-', // Amount (placeholder)
            '-', // Units Sold (placeholder)
            '-', // Amount (placeholder)
            '-', // Units Sold (placeholder)
            '-', // Amount (placeholder)
            '-', // TOTAL (placeholder)
            '-'  // Amount (placeholder)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling - Green background like in your image
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4CAF50'], // Green header
                ],
            ],
            // Yellow highlighting for certain columns (like in your image)
            'K:M' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'], // Yellow background
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // #
            'B' => 12,  // Category
            'C' => 25,  // Product Name
            'D' => 12,  // Buying Price
            'E' => 12,  // Selling Price
            'F' => 10,  // Units Sold
            'G' => 10,  // Amount
            'H' => 10,  // Units Sold
            'I' => 10,  // Amount
            'J' => 10,  // Units Sold
            'K' => 10,  // Amount
            'L' => 10,  // TOTAL
            'M' => 10,  // Amount
        ];
    }
}
