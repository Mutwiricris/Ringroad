<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('order_number')
                    ->required(),
                TextInput::make('customer_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('location_id')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['awaiting_payment' => 'Awaiting payment', 'completed' => 'Completed', 'refunded' => 'Refunded'])
                    ->default('awaiting_payment')
                    ->required(),
                TextInput::make('total_amount')
                    ->required()
                    ->numeric(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
