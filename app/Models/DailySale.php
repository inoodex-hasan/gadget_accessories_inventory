<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySale extends Model
{
    use HasFactory;
    protected $fillable = ['date', 'description', 'card_amount', 'cash_amount', 'others_amount', 'total_amount', 'assigned_person_id'];
}
