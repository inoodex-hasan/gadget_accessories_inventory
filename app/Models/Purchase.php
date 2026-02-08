<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    // Optional: define table name if not following conventions
    protected $table = 'purchases';

    // Mass assignable fields
    protected $fillable = [
        'product_id',
        'vendor_id',
        'quantity',
        'unit_price',
        'sub_price',
        'total_price',
        'payment',
        'due',
        'vendor_id',
        'created_by',
        'updated_by',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
     public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }
}
