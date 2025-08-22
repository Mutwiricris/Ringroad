<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use Filament\Actions;
use Filament\Resources\Pages\Page;

class ListReports extends Page
{
    protected static string $resource = ReportResource::class;
    
    public function getView(): string
    {
        return 'filament.resources.reports.pages.list-reports';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('inventory-report')
                ->label('Inventory Report')
                ->icon('heroicon-o-archive-box')
                ->color('success')
                ->url(static::$resource::getUrl('inventory-report')),
            Actions\Action::make('profit-margin-report')
                ->label('Profit Margin Report')
                ->icon('heroicon-o-currency-dollar')
                ->color('info')
                ->url(static::$resource::getUrl('profit-margin-report')),
        ];
    }
}
