<?php

namespace App\Filament\Resources\ProductVariants\Tables;


use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class ProductVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('supplier_code')
                    ->searchable(),
                TextColumn::make('cost_price')
                    ->label('Buying Price (KSh)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'KSh ' . number_format($state, 2)),
                TextColumn::make('selling_price')
                    ->label('Selling Price (KSh)')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'KSh ' . number_format($state, 2)),
                TextColumn::make('stock_quantity')
                    ->label('Stock Qty')
                    ->getStateUsing(function ($record) {
                        return $record->inventory->sum('quantity');
                    })
                    ->numeric()
                    ->sortable(),
                TextColumn::make('stock_value')
                    ->label('Stock Value')
                    ->getStateUsing(function ($record) {
                        $qty = $record->inventory->sum('quantity');
                        return 'KSh ' . number_format($qty * $record->selling_price, 2);
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Date filters
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_at')
                            ->label('Created Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['created_at'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', $date),
                        );
                    }),

                Filter::make('created_date_range')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Created From'),
                        DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                    //product category filter
                // SelectFilter::make('category_id')
                // ->lable('category_id')
                // ->relationship('product','name')
                // ->searchable()
                // ->preload(),

                // Product filter
                SelectFilter::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),

                // Price range filters
                Filter::make('selling_price_range')
                    ->form([
                        TextInput::make('selling_price_from')
                            ->label('Selling Price From')
                            ->numeric()
                            ->prefix('KSh'),
                        TextInput::make('selling_price_to')
                            ->label('Selling Price To')
                            ->numeric()
                            ->prefix('KSh'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['selling_price_from'],
                                fn (Builder $query, $price): Builder => $query->where('selling_price', '>=', $price),
                            )
                            ->when(
                                $data['selling_price_to'],
                                fn (Builder $query, $price): Builder => $query->where('selling_price', '<=', $price),
                            );
                    }),

                Filter::make('cost_price_range')
                    ->form([
                        TextInput::make('cost_price_from')
                            ->label('Buying Price From')
                            ->numeric()
                            ->prefix('KSh'),
                        TextInput::make('cost_price_to')
                            ->label('Buying Price To')
                            ->numeric()
                            ->prefix('KSh'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cost_price_from'],
                                fn (Builder $query, $price): Builder => $query->where('cost_price', '>=', $price),
                            )
                            ->when(
                                $data['cost_price_to'],
                                fn (Builder $query, $price): Builder => $query->where('cost_price', '<=', $price),
                            );
                    }),

                // Days filter (last X days)
                SelectFilter::make('days')
                    ->label('Created in Last')
                    ->options([
                        '7' => 'Last 7 days',
                        '30' => 'Last 30 days',
                        '60' => 'Last 60 days',
                        '90' => 'Last 90 days',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value']) && $data['value']) {
                            return $query->where('created_at', '>=', now()->subDays((int) $data['value']));
                        }
                        return $query;
                    }),

                // SKU filter
                Filter::make('has_sku')
                    ->label('Has SKU')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('sku')),

                // Supplier code filter
                Filter::make('has_supplier_code')
                    ->label('Has Supplier Code')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('supplier_code')),
            ])
            ->headerActions([
                ExportAction::make('export_detailed')
                    ->label('Export Detailed Report')
                    ->color('success')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'product-variants-detailed-' . now()->format('Y-m-d'))
                            ->withColumns([
                                Column::make('id')->heading('#'),
                                Column::make('product.category.name')->heading('Category'),
                                Column::make('product.name')->heading('Product Name'),
                                Column::make('name')->heading('Variant Name'),
                                Column::make('sku')->heading('SKU'),
                                Column::make('supplier_code')->heading('Supplier Code'),
                                Column::make('cost_price')->heading('Buying Price (KSh)'),
                                Column::make('selling_price')->heading('Selling Price (KSh)'),
                                Column::make('stock_quantity')
                                    ->heading('Stock Quantity')
                                    ->formatStateUsing(function ($record) {
                                        return $record->inventory->sum('quantity') ?? 0;
                                    }),
                                Column::make('stock_value_cost')
                                    ->heading('Stock Value (Cost)')
                                    ->formatStateUsing(function ($record) {
                                        $qty = $record->inventory->sum('quantity') ?? 0;
                                        return 'KSh ' . number_format($qty * $record->cost_price, 2);
                                    }),
                                Column::make('stock_value_selling')
                                    ->heading('Stock Value (Selling)')
                                    ->formatStateUsing(function ($record) {
                                        $qty = $record->inventory->sum('quantity') ?? 0;
                                        return 'KSh ' . number_format($qty * $record->selling_price, 2);
                                    }),
                                Column::make('profit_margin')
                                    ->heading('Profit Margin %')
                                    ->formatStateUsing(function ($record) {
                                        if ($record->selling_price > 0) {
                                            $margin = (($record->selling_price - $record->cost_price) / $record->selling_price) * 100;
                                            return number_format($margin, 2) . '%';
                                        }
                                        return '0%';
                                    }),
                                Column::make('created_at')
                                    ->heading('Created Date')
                                    ->formatStateUsing(fn ($state) => $state->format('Y-m-d')),
                                Column::make('updated_at')
                                    ->heading('Updated Date')
                                    ->formatStateUsing(fn ($state) => $state->format('Y-m-d')),
                            ])
                    ]),

                ExportAction::make('export_simple')
                    ->label('Export Sales Report')
                    ->color('primary')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'product-variants-sales-' . now()->format('Y-m-d'))
                            ->withColumns([
                                Column::make('id')->heading('#'),
                                Column::make('product.category.name')->heading('Category'),
                                Column::make('product.name')->heading('Product Name'),
                                Column::make('cost_price')->heading('Buying Price'),
                                Column::make('selling_price')->heading('Selling Price'),
                                Column::make('inventory_quantity')
                                    ->heading('Stock Quantity')
                                    ->formatStateUsing(function ($record) {
                                        return $record->inventory->sum('quantity') ?? 0;
                                    }),
                                Column::make('stock_value')
                                    ->heading('Stock Value')
                                    ->formatStateUsing(function ($record) {
                                        $qty = $record->inventory->sum('quantity') ?? 0;
                                        return number_format($qty * $record->selling_price, 2);
                                    }),
                                Column::make('profit_margin')
                                    ->heading('Profit Margin %')
                                    ->formatStateUsing(function ($record) {
                                        if ($record->selling_price > 0) {
                                            $margin = (($record->selling_price - $record->cost_price) / $record->selling_price) * 100;
                                            return number_format($margin, 2) . '%';
                                        }
                                        return '0%';
                                    }),
                            ])
                    ]),
            ])

;
    }
}
