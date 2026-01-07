<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminToMessSubscriptionTXN extends Model
{
    use HasFactory;
    protected $casts = [
        'package_details' => 'array',
        'id'=> 'string',
        'chat'=>'integer',
        'review'=>'integer',
        'package_id'=>'integer',
        'restaurant_id'=>'integer',
        'status'=>'integer',
        'self_delivery'=>'integer',
        'max_order'=>'string',
        'max_product'=>'string',
        'payment_method'=>'string',
        'paid_amount'=>'float',
        'validity'=>'integer',

    ];

    public function mess()
    {
        return $this->hasOne(VendorMess::class);
    }
    public function package()
    {
        return $this->belongsTo(AdminToMessSubscriptionTXN::class, 'package_id', 'id');
    }
}
