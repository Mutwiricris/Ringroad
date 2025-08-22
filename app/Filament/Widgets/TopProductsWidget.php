<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\ProductVariant;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class TopProductsWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Top Selling Products (Last 30 Days)';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ProductVariant::query()
                    ->select([
                        'product_variants.*',
                        DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_sold'),
                        DB::raw('COALESCE(SUM(order_items.quantity * order_items.price), 0) as total_revenue')
                    ])
                    ->leftJoin('order_items', 'product_variants.id', '=', 'order_items.product_variant_id')
                    ->leftJoin('orders', function ($join) {
                        $join->on('order_items.order_id', '=', 'orders.id')
                             ->where('orders.status', '=', 'completed')
                             ->where('orders.created_at', '>=', now()->subDays(30));
                    })
                    ->with(['product.category', 'inventory'])
                    ->groupBy('product_variants.id')
                    ->orderBy('total_sold', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Variant')
                    ->searchable(),

                Tables\Columns\TextColumn::make('product.category.name')
                    ->label('Category')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Units Sold')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->total_sold ?? 0),

                Tables\Columns\TextColumn::make('total_revenue')
                    ->label('Revenue')
                    ->money('KES')
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->total_revenue ?? 0),

                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Unit Price')
                    ->money('KES')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock_quantity')
                    ->label('In Stock')
                    ->getStateUsing(function ($record) {
                        return $record->inventory->sum('quantity');
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
            ])
            ->defaultSort('total_sold', 'desc')
            ->paginated(false);
    }
}
