<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'opening_stock', 'current_stock', 'notes'];

    // Define relationship with Product
 public function product()
{
    return $this->belongsTo(Product::class);
}

public function items()
{
    return $this->hasMany(InventoryItem::class, 'product_id');
}

    public function inventory_item()
    {
        return $this->hasOne(InventoryItem::class, 'product_id', 'product_id');
    }

        public function inventory_items()
    {
        return $this->hasMany(InventoryItem::class, 'product_id', 'product_id');
    }
}
