<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

      protected $casts = [
        'photos' => 'array',
    ];

    protected $fillable = [
        'name',
        'brand_id',
        'model',
        'photos',
        'status',
        'warranty',
    ];
    public function latestPurchase()
    {
        return $this->hasOne(Purchase::class)->latestOfMany();
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function inventory()
{
    return $this->hasOne(Inventory::class);
}

public function inventoryItems()
{
    return $this->hasMany(InventoryItem::class);
}

public function purchases()
{
    return $this->hasMany(Purchase::class);
}
}