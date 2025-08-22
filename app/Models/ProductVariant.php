<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'sku', 'supplier_code', 'selling_price', 'cost_price', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getProfitMarginAttribute()
    {
        if ($this->selling_price <= 0) {
            return 0;
        }
        
        return (($this->selling_price - $this->cost_price) / $this->selling_price) * 100;
    }

    public function getProfitAttribute()
    {
        return $this->selling_price - $this->cost_price;
    }
}
