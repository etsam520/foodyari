<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSubscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'price' => 'float',
        'package_id' => 'integer',
        'restaurant_id' => 'integer',
        'validity' => 'integer',
        'expiry_date' => 'datetime',
        'max_order' => 'string',
        'max_product' => 'string',
        'mobile_app' => 'integer',
        'pos' => 'integer',
        'chat' => 'integer',
        'review' => 'integer',
        'status' => 'integer',
        'default' => 'integer',
        'total_package_renewed' => 'integer',
        'self_delivery' => 'integer',	
    ];
}
