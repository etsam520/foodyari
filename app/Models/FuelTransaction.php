<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuelTransaction extends Model
{
    use HasFactory;

    protected $table = 'fuel_transactions';
    protected $guarded = [];
    protected $fillable = [
        'dm_id',
        'type', // ['add', 'deduct'] enum
        'amount',
        'distance',
        'rate',
        'note',
        'attendance_id'
    ];
}
