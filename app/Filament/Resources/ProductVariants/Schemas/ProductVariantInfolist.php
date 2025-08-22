<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductVariantInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('product_id')
                    ->numeric(),
                TextEntry::make('name'),
                TextEntry::make('sku')
                    ->label('SKU'),
                TextEntry::make('supplier_code'),
                TextEntry::make('selling_price')
                    ->numeric(),
                TextEntry::make('cost_price')
                    ->numeric(),
                TextEntry::make('quantity')
                    ->numeric()
                    ->default(0)
                    ->label('Quantity in Stock'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
