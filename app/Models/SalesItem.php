<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'product_id',
        'unit_price',
        'qty',
        'total_price',
        'warranty',
    ];
}
