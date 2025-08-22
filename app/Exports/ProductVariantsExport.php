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
use Illuminate\Database\Eloquent\Builder;

class ProductVariantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
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

            if (isset($this->filters['selling_price_from']) && $this->filters['selling_price_from']) {
                $query->where('selling_price', '>=', $this->filters['selling_price_from']);
            }

            if (isset($this->filters['selling_price_to']) && $this->filters['selling_price_to']) {
                $query->where('selling_price', '<=', $this->filters['selling_price_to']);
            }

            if (isset($this->filters['cost_price_from']) && $this->filters['cost_price_from']) {
                $query->where('cost_price', '>=', $this->filters['cost_price_from']);
            }

            if (isset($this->filters['cost_price_to']) && $this->filters['cost_price_to']) {
                $query->where('cost_price', '<=', $this->filters['cost_price_to']);
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
            'Variant Name',
            'SKU',
            'Supplier Code',
            'Buying Price',
            'Selling Price',
            'Stock Qty',
            'Stock Value (Cost)',
            'Stock Value (Selling)',
            'Profit Margin',
            'Created Date',
            'Updated Date'
        ];
    }

    public function map($variant): array
    {
        $stockQty = $variant->inventory->sum('quantity');
        $stockValueCost = $stockQty * $variant->cost_price;
        $stockValueSelling = $stockQty * $variant->selling_price;
        $profitMargin = $variant->selling_price > 0 ?
            (($variant->selling_price - $variant->cost_price) / $variant->selling_price) * 100 : 0;

        return [
            $variant->id,
            $variant->product->category->name ?? 'Uncategorized',
            $variant->product->name ?? '',
            $variant->name,
            $variant->sku ?? '-',
            $variant->supplier_code ?? '-',
            number_format($variant->cost_price, 2),
            number_format($variant->selling_price, 2),
            $stockQty,
            number_format($stockValueCost, 2),
            number_format($stockValueSelling, 2),
            number_format($profitMargin, 2) . '%',
            $variant->created_at->format('Y-m-d'),
            $variant->updated_at->format('Y-m-d')
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
                    'startColor' => ['rgb' => '4CAF50'], // Green header
                ],
            ],
            // Alternate row colors
            'A:N' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // #
            'B' => 15,  // Category
            'C' => 25,  // Product Name
            'D' => 20,  // Variant Name
            'E' => 15,  // SKU
            'F' => 15,  // Supplier Code
            'G' => 12,  // Buying Price
            'H' => 12,  // Selling Price
            'I' => 10,  // Stock Qty
            'J' => 15,  // Stock Value (Cost)
            'K' => 15,  // Stock Value (Selling)
            'L' => 12,  // Profit Margin
            'M' => 12,  // Created Date
            'N' => 12,  // Updated Date
        ];
    }
}
