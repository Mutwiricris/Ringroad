<?php

namespace App\Filament\Resources\Reports;

use App\Filament\Resources\Reports\Pages;
use App\Models\Report;
use Filament\Resources\Resource;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;
    
    protected static ?string $navigationLabel = 'Reports';

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'inventory-report' => Pages\InventoryReport::route('/inventory'),
            'profit-margin-report' => Pages\ProfitMarginReport::route('/profit-margin'),
        ];
    }
}
