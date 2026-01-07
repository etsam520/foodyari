<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTransaction extends Model
{
    use HasFactory;

    protected $fillable  = [
        'order_id',
        'restaurant_id',
        'delivery_man_id',
        'free_delivery',
        'order_amount',
        'gst_amount',
        'gst_percent',
        'platform_charge',
        'dm_tips',

        'delivery_charge',
        'packing_charge',
        'restaurant_earning',
        'restaurant_gst_amount',
        'restaurant_receivable_amount',
        'admin_commission_amount',
        'admin_earning',
        'admin_gst_amount',
        'admin_receivable_amount',
        'customer_data',
        'restaurant_data',
        'admin_data',
        'received_by',
        'zone_id',
        'status',
        'delivery_service_provider',
    ];


    public function order()  {
        return $this->belongsTo(Order::class, 'order_id');
    }


}


