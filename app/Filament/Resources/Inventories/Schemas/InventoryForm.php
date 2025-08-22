<?php

namespace App\Filament\Resources\Inventories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('product_variant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('location_id')
                    ->required()
                    ->numeric(),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
