<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['product_variant_id', 'location_id', 'quantity'];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
