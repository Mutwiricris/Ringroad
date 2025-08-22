<?php

namespace App\Filament\Resources\Reports\Pages;

use App\Filament\Resources\Reports\ReportResource;
use App\Models\ProductVariant;
use App\Models\Category;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Colors\Color;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid as InfoGrid;
use Filament\Infolists\Components\TextEntry;

class ProfitMarginReport extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = ReportResource::class;
    
    public function getView(): string
    {
        return 'filament.resources.reports.pages.profit-margin-report';
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
            'min_margin' => null,
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
                        Forms\Components\Select::make('min_margin')
                            ->label('Minimum Margin %')
                            ->options([
                                '0' => '0%+',
                                '10' => '10%+',
                                '20' => '20%+',
                                '30' => '30%+',
                                '50' => '50%+',
                            ])
                            ->placeholder('All Margins'),
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
        // Get product variants with their profit margins
        $query = ProductVariant::with(['product.category'])
            ->where('selling_price', '>', 0)
            ->where('cost_price', '>', 0);

        if ($this->data['category_id']) {
            $query->whereHas('product', function ($q) {
                $q->where('category_id', $this->data['category_id']);
            });
        }

        $variants = $query->get();

        $this->reportData = $variants->map(function ($variant) {
            // Get sales data for this variant
            $salesQuery = $variant->orderItems()
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.status', 'completed');

            if ($this->data['date_from']) {
                $salesQuery->where('orders.created_at', '>=', $this->data['date_from']);
            }

            if ($this->data['date_to']) {
                $salesQuery->where('orders.created_at', '<=', $this->data['date_to']);
            }

            $salesData = $salesQuery->selectRaw('
                SUM(order_items.quantity) as units_sold,
                SUM(order_items.quantity * order_items.price) as total_revenue,
                SUM(order_items.quantity * order_items.cost_at_time_of_sale) as total_cost
            ')->first();

            $unitsSold = $salesData->units_sold ?? 0;
            $totalRevenue = $salesData->total_revenue ?? 0;
            $totalCost = $salesData->total_cost ?? 0;
            $totalProfit = $totalRevenue - $totalCost;

            // Use ProductVariant's profit margin calculation
            $profitMargin = $variant->profit_margin;

            return (object) [
                'id' => $variant->id,
                'category' => $variant->product->category->name ?? 'Uncategorized',
                'product_name' => $variant->product->name,
                'sku' => $variant->sku,
                'units_sold' => $unitsSold,
                'cost_price' => $variant->cost_price,
                'selling_price' => $variant->selling_price,
                'total_revenue' => $totalRevenue,
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit,
                'profit_margin' => $profitMargin,
                'profit_per_unit' => $variant->profit,
            ];
        })->filter(function ($item) {
            // Only include items with sales or show all if no date filter
            if ($this->data['date_from'] || $this->data['date_to']) {
                return $item->units_sold > 0;
            }
            return true;
        })->filter(function ($item) {
            if ($this->data['min_margin'] !== null) {
                return $item->profit_margin >= (float) $this->data['min_margin'];
            }
            return true;
        })->sortByDesc('profit_margin');

        $this->summaryStats = [
            'total_products' => $this->reportData->count(),
            'total_units_sold' => $this->reportData->sum('units_sold'),
            'total_revenue' => $this->reportData->sum('total_revenue'),
            'total_cost' => $this->reportData->sum('total_cost'),
            'total_profit' => $this->reportData->sum('total_profit'),
            'average_margin' => $this->reportData->avg('profit_margin'),
            'highest_margin' => $this->reportData->max('profit_margin'),
            'lowest_margin' => $this->reportData->min('profit_margin'),
        ];
    }

    public function exportPdf(): void
    {
        $this->js('alert("PDF export functionality will be implemented next")');
    }

    public function getStats(): array
    {
        if (!$this->summaryStats) {
            return [];
        }

        return [
            Stat::make('Total Products', $this->summaryStats['total_products'])
                ->description('Products sold')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),
            Stat::make('Units Sold', number_format($this->summaryStats['total_units_sold']))
                ->description('Total quantity sold')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),
            Stat::make('Total Revenue', '$' . number_format($this->summaryStats['total_revenue'], 2))
                ->description('Gross sales')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Total Cost', '$' . number_format($this->summaryStats['total_cost'], 2))
                ->description('Cost of goods sold')
                ->descriptionIcon('heroicon-m-minus-circle')
                ->color('warning'),
            Stat::make('Total Profit', '$' . number_format($this->summaryStats['total_profit'], 2))
                ->description('Net profit earned')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('success'),
            Stat::make('Average Margin', number_format($this->summaryStats['average_margin'], 1) . '%')
                ->description('Overall profit margin')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }

    public function getTableData(): array
    {
        if (!$this->reportData) {
            return [];
        }

        return $this->reportData->toArray();
    }
}
