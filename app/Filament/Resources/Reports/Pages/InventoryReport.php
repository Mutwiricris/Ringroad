<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\Inventory;
use App\Models\Category;
use App\Models\Location;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryReport extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = ReportResource::class;
    
    public function getView(): string
    {
        return 'filament.resources.reports.pages.inventory-report';
    }
    
    public ?array $data = [];
    public $reportData = null;
    public $summaryStats = null;

    public function mount(): void
    {
        $this->form->fill([
            'date_from' => now()->startOfMonth(),
            'date_to' => now(),
            'category_id' => null,
            'location_id' => null,
        ]);
        
        $this->generateReport();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)
                    ->schema([
                        Forms\Components\DatePicker::make('date_from')
                            ->label('Date From')
                            ->default(now()->startOfMonth()),
                        Forms\Components\DatePicker::make('date_to')
                            ->label('Date To')
                            ->default(now()),
                        Forms\Components\Select::make('category_id')
                            ->label('Category')
                            ->options(Category::pluck('name', 'id'))
                            ->placeholder('All Categories'),
                        Forms\Components\Select::make('location_id')
                            ->label('Location')
                            ->options(Location::pluck('name', 'id'))
                            ->placeholder('All Locations'),
                    ])
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('filter')
                ->label('Apply Filters')
                ->icon('heroicon-o-funnel')
                ->color('primary')
                ->action('generateReport'),
            Action::make('export')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->action('exportPdf'),
        ];
    }

    public function generateReport(): void
    {
        $query = Inventory::with([
            'productVariant.product.category',
            'location'
        ]);

        // Apply filters
        if ($this->data['date_from']) {
            $query->whereDate('created_at', '>=', $this->data['date_from']);
        }
        
        if ($this->data['date_to']) {
            $query->whereDate('created_at', '<=', $this->data['date_to']);
        }

        if ($this->data['category_id']) {
            $query->whereHas('productVariant.product', function ($q) {
                $q->where('category_id', $this->data['category_id']);
            });
        }

        if ($this->data['location_id']) {
            $query->where('location_id', $this->data['location_id']);
        }

        $this->reportData = $query->get();

        // Calculate summary statistics
        $this->summaryStats = [
            'total_items' => $this->reportData->count(),
            'total_quantity' => $this->reportData->sum('quantity'),
            'total_locations' => $this->reportData->pluck('location_id')->unique()->count(),
            'total_value_cost' => $this->reportData->sum(function ($item) {
                return $item->quantity * $item->productVariant->cost_price;
            }),
            'total_value_selling' => $this->reportData->sum(function ($item) {
                return $item->quantity * $item->productVariant->selling_price;
            }),
            'categories' => $this->reportData->pluck('productVariant.product.category.name')->unique()->count(),
        ];
    }

    public function exportPdf()
    {
        // Get filter names for display
        $categoryName = null;
        $locationName = null;
        
        if ($this->data['category_id']) {
            $categoryName = Category::find($this->data['category_id'])?->name;
        }
        
        if ($this->data['location_id']) {
            $locationName = Location::find($this->data['location_id'])?->name;
        }
        
        $filters = [
            'date_from' => $this->data['date_from'],
            'date_to' => $this->data['date_to'],
            'category_name' => $categoryName,
            'location_name' => $locationName,
        ];
        
        $pdf = \PDF::loadView('reports.pdf.inventory-report', [
            'reportData' => $this->reportData,
            'summaryStats' => $this->summaryStats,
            'filters' => $filters,
        ]);
        
        $filename = 'inventory-report-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function getStats(): array
    {
        if (!$this->summaryStats) {
            return [];
        }

        return [
            Stat::make('Total Items', number_format($this->summaryStats['total_items']))
                ->description('Inventory items')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            Stat::make('Total Quantity', number_format($this->summaryStats['total_quantity']))
                ->description('Units in stock')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('success'),
            Stat::make('Locations', $this->summaryStats['total_locations'])
                ->description('Storage locations')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('warning'),
            Stat::make('Categories', $this->summaryStats['categories'])
                ->description('Product categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('info'),
            Stat::make('Cost Value', '$' . number_format($this->summaryStats['total_value_cost'], 2))
                ->description('Total cost value')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),
            Stat::make('Selling Value', '$' . number_format($this->summaryStats['total_value_selling'], 2))
                ->description('Total selling value')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('gray'),
        ];
    }
}
