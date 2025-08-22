<?php

namespace App\Filament\Resources\ProductVariants\Pages;

use App\Filament\Resources\ProductVariants\ProductVariantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProductVariant extends EditRecord
{
    protected static string $resource = ProductVariantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
