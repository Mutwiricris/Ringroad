<?php

namespace App\Filament\Resources\ProductVariants\Pages;

use App\Filament\Resources\ProductVariants\ProductVariantResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Inventory;
use App\Models\Location;

class CreateProductVariant extends CreateRecord
{
    protected static string $resource = ProductVariantResource::class;

    protected function afterCreate(): void
    {
        // Get the quantity from the created variant
        $quantity = $this->record->quantity;

        // If a quantity was provided, create an initial inventory record
        if ($quantity > 0) {
            // Use the first location as the default
            $location = Location::first();

            // Only proceed if a location exists
            if ($location) {
                Inventory::create([
                    'product_variant_id' => $this->record->id,
                    'location_id' => $location->id,
                    'quantity' => $quantity,
                ]);
            }
        }
    }
}
