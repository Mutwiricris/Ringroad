<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;

class ProductVariantStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        
        // Today's sales data
        $todaysSales = OrderItem::whereHas('order', function ($query) {
            $query->whereDate('created_at', today())
                  ->where('status', 'completed');
        })->get();

        $todaysUnits = $todaysSales->sum('quantity');
        $todaysRevenue = $todaysSales->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // This week's sales
        $weekSales = OrderItem::whereHas('order', function ($query) {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                  ->where('status', 'completed');
        })->get();

        $weekUnits = $weekSales->sum('quantity');
        $weekRevenue = $weekSales->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Total inventory value
        $totalStockValue = ProductVariant::with('inventory')->get()->sum(function ($variant) {
            $totalQty = $variant->inventory->sum('quantity');
            return $totalQty * $variant->selling_price;
        });

        // Low stock items (less than 10 units)
        $lowStockCount = ProductVariant::with('inventory')->get()->filter(function ($variant) {
            return $variant->inventory->sum('quantity') < 10;
        })->count();

        // Total products and variants
        $totalVariants = ProductVariant::count();
        $activeVariants = ProductVariant::whereHas('product', function ($query) {
            $query->where('is_active', true);
        })->count();

        // Best selling product today
        $bestSellerToday = OrderItem::select('product_variant_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function ($query) {
                $query->whereDate('created_at', today())
                      ->where('status', 'completed');
            })
            ->groupBy('product_variant_id')
            ->orderBy('total_sold', 'desc')
            ->with('productVariant.product')
            ->first();

        return [
            Stat::make('Today\'s Units Sold', $todaysUnits)
                ->description('Units sold today')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success')
                ->chart([7, 12, 8, 15, 9, 18, $todaysUnits]),

            Stat::make('Today\'s Revenue', 'KSh ' . number_format($todaysRevenue, 2))
                ->description('Revenue generated today')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('This Week\'s Units', $weekUnits)
                ->description('Units sold this week')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([45, 52, 38, 67, 43, 89, $weekUnits]),

            Stat::make('Week\'s Revenue', 'KSh ' . number_format($weekRevenue, 2))
                ->description('Weekly revenue')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('info'),

            Stat::make('Total Stock Value', 'KSh ' . number_format($totalStockValue, 2))
                ->description('Current inventory value')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('warning'),

            Stat::make('Low Stock Items', $lowStockCount)
                ->description('Items with < 10 units')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Active Variants', $activeVariants . ' / ' . $totalVariants)
                ->description('Active product variants')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('primary'),

            Stat::make('Best Seller Today', $bestSellerToday ? $bestSellerToday->productVariant->product->name : 'No sales yet')
                ->description($bestSellerToday ? $bestSellerToday->total_sold . ' units sold' : 'Start selling!')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('success'),
        ];
    }

    protected function getColumns(): int
    {
        return 4; // Display 4 cards per row
    }
}
