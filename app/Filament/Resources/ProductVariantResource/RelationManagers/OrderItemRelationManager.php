<?php

namespace App\Filament\Resources\ProductVariantResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Schemas\Schema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class OrderItemRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_number')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cost_at_time_of_sale')
                    ->label('Cost at Sale')
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('order.order_number')
            ->columns([
                TextColumn::make('order.order_number')->label('Order Number'),
                TextColumn::make('quantity')->numeric(),
                TextColumn::make('price')->money('KES'),
                TextColumn::make('order.created_at')->label('Order Date')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make(),
            ])
            ->actions([
                // EditAction::make(),
            ])
            ->bulkActions([
                // BulkActionGroup::make([
                //     DeleteAction::make(),
                // ]),
            ]);
    }
}
