<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DmCurrentLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'dm_id',
        'mess_id',
        'admin_id',
        'restaurant_id',
        'active',
        'current_orders',
        'last_location',
        'updated_at'
    ];
}
