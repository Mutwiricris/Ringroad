<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Models\Product;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Select Product')
                    ->options(Product::query()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU'),
                TextInput::make('supplier_code'),
                TextInput::make('selling_price')
                    ->required()
                    ->numeric(),
                TextInput::make('cost_price')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
