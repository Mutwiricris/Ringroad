<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

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

class ProductVariantRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('selling_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('cost_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->maxLength(255),
                Forms\Components\TextInput::make('supplier_code')
                    ->label('Supplier Code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('sku')->label('SKU'),
                TextColumn::make('cost_price')
                    ->label('Buying Price')
                    ->money('KES'),
                TextColumn::make('selling_price')
                    ->label('Selling Price')
                    ->money('KES'),
                TextColumn::make('stock_quantity')
                    ->label('Stock Qty')
                    ->getStateUsing(function ($record) {
                        return $record->inventory->sum('quantity');
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}