<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class SalesTrendChart extends ChartWidget
{
    protected static ?int $sort = 3;

    public function getHeading(): ?string
    {
        return 'Sales Trend (Last 7 Days)';
    }

    protected function getData(): array
    {
        // Get sales data for the last 7 days
        $salesData = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('M j');

            $dailySales = OrderItem::whereHas('order', function ($query) use ($date) {
                $query->whereDate('created_at', $date->toDateString())
                      ->where('status', 'completed');
            })->sum(DB::raw('quantity * price'));

            $salesData[] = $dailySales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Daily Revenue (KSh)',
                    'data' => $salesData,
                    'borderColor' => '#10B981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "KSh " + value.toLocaleString(); }',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
