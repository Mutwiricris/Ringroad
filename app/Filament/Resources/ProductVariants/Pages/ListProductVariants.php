<?php

namespace App\Filament\Resources\ProductVariants\Pages;

use App\Filament\Resources\ProductVariants\ProductVariantResource;
use App\Filament\Widgets\ProductVariantStatsWidget;
use App\Filament\Widgets\InventoryInsightsWidget;
use App\Filament\Widgets\SalesTrendChart;
use App\Filament\Widgets\TopProductsWidget;
use App\Imports\ProductVariantImport;
use App\Exports\ProductVariantTemplateExport;
use App\Exports\ProductVariantsInventoryReport;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ListProductVariants extends ListRecords
{
    protected static string $resource = ProductVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('exportReport')
                ->label('Export Inventory Report')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(function () {
                    return Excel::download(new ProductVariantsInventoryReport(), 'inventory_report.xlsx');
                }),
            Action::make('downloadTemplate')
                ->label('Download CSV Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return Excel::download(new ProductVariantTemplateExport(), 'product_variants_template.csv');
                }),
            Action::make('import')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('CSV File')
                        ->acceptedFileTypes(['text/csv', 'application/csv', 'text/plain'])
                        ->required()
                        ->helperText('Upload a CSV file with columns: Category, Product Name, Units in Stock, Location, Buying Price, TOTAL UNITS, RRP, Selling Price')
                ])
                ->action(function (array $data) {
                    $import = new ProductVariantImport();
                    
                    try {
                        Excel::import($import, $data['file']);
                        
                        $imported = count($import->toArray($data['file'])[0] ?? []);
                        $failures = $import->failures();
                        
                        if ($failures->count() > 0) {
                            $failureMessages = $failures->map(function ($failure) {
                                return "Row {$failure->row()}: " . implode(', ', $failure->errors());
                            })->take(5)->implode("\n");
                            
                            Notification::make()
                                ->title('Import completed with errors')
                                ->body("Imported: {$imported} records\nErrors in some rows:\n{$failureMessages}")
                                ->warning()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Import successful')
                                ->body("Successfully imported {$imported} product variants")
                                ->success()
                                ->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Import failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ProductVariantStatsWidget::class,
            InventoryInsightsWidget::class,
            SalesTrendChart::class,
            TopProductsWidget::class,
        ];
    }
}
