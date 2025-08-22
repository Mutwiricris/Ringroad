<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\ProductVariant;
use App\Models\Category;
use App\Models\Location;
use App\Models\OrderItem;

class InventoryInsightsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Most profitable category
        $categoryProfits = Category::with(['products.variants.inventory'])->get()->map(function ($category) {
            $totalProfit = 0;
            foreach ($category->products as $product) {
                foreach ($product->variants as $variant) {
                    $qty = $variant->inventory->sum('quantity');
                    $profit = ($variant->selling_price - $variant->cost_price) * $qty;
                    $totalProfit += $profit;
                }
            }
            return [
                'name' => $category->name,
                'profit' => $totalProfit
            ];
        })->sortByDesc('profit')->first();

        // Average profit margin
        $variants = ProductVariant::all();
        $avgMargin = $variants->avg(function ($variant) {
            if ($variant->selling_price > 0) {
                return (($variant->selling_price - $variant->cost_price) / $variant->selling_price) * 100;
            }
            return 0;
        });

        // Total units in stock
        $totalUnitsInStock = ProductVariant::with('inventory')->get()->sum(function ($variant) {
            return $variant->inventory->sum('quantity');
        });

        // Out of stock items
        $outOfStockCount = ProductVariant::with('inventory')->get()->filter(function ($variant) {
            return $variant->inventory->sum('quantity') == 0;
        })->count();

        // Most stocked location
        $locationStock = Location::with(['inventory.productVariant'])->get()->map(function ($location) {
            $totalValue = $location->inventory->sum(function ($inventory) {
                return $inventory->quantity * $inventory->productVariant->selling_price;
            });
            $totalUnits = $location->inventory->sum('quantity');
            return [
                'name' => $location->name,
                'value' => $totalValue,
                'units' => $totalUnits
            ];
        })->sortByDesc('value')->first();

        // Fast moving items (sold in last 7 days)
        $fastMovingCount = OrderItem::whereHas('order', function ($query) {
            $query->where('created_at', '>=', now()->subDays(7))
                  ->where('status', 'completed');
        })->distinct('product_variant_id')->count();

        // Highest value single item
        $highestValueItem = ProductVariant::with(['product', 'inventory'])
            ->get()
            ->map(function ($variant) {
                $qty = $variant->inventory->sum('quantity');
                return [
                    'variant' => $variant,
                    'total_value' => $qty * $variant->selling_price
                ];
            })
            ->sortByDesc('total_value')
            ->first();

        return [
            Stat::make('Total Units in Stock', number_format($totalUnitsInStock))
                ->description('Across all locations')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary'),

            Stat::make('Average Profit Margin', number_format($avgMargin, 1) . '%')
                ->description('Across all products')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color($avgMargin > 30 ? 'success' : ($avgMargin > 20 ? 'warning' : 'danger')),

            Stat::make('Out of Stock', $outOfStockCount)
                ->description('Items needing restock')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($outOfStockCount > 0 ? 'danger' : 'success'),

            Stat::make('Most Profitable Category', $categoryProfits['name'] ?? 'N/A')
                ->description('KSh ' . number_format($categoryProfits['profit'] ?? 0, 2) . ' potential profit')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),

            Stat::make('Top Location by Value', $locationStock['name'] ?? 'N/A')
                ->description('KSh ' . number_format($locationStock['value'] ?? 0, 2) . ' stock value')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info'),

            Stat::make('Fast Moving Items', $fastMovingCount)
                ->description('Sold in last 7 days')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success'),

            Stat::make('Highest Value Item', $highestValueItem ? $highestValueItem['variant']->product->name : 'N/A')
                ->description($highestValueItem ? 'KSh ' . number_format($highestValueItem['total_value'], 2) . ' total value' : 'No items')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
