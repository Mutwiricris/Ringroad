<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductVariantTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        return [
            [
                'Electronics',
                'iPhone 15 Pro',
                '50',
                'Store A',
                '999.00',
                '100',
                '1299.00',
                '1199.00'
            ],
            [
                'Clothing',
                'Nike Air Max',
                '25',
                'Store B',
                '89.99',
                '50',
                '179.99',
                '149.99'
            ],
            [
                'Books',
                'Laravel Guide',
                '10',
                'Warehouse',
                '25.00',
                '20',
                '49.99',
                '39.99'
            ]
        ];
    }

    /**
     * @return array
     */
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '4CAF50']]],
        ];
    }
}
