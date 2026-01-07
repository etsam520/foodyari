<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    use HasFactory;

    protected $fillable = ['id','subscription_order_id','subtotal','tax','coupon_discount','custom_discount','delivery_charge','otherCharges',
    'status','method','wallet_id','created_at','updated_at','payment_id','total','customer_id','discount','other_charges', ];




    public function messSubsciptionOrders()
    {
        return $this->belongsTo(SubscriptionOrderDetails::class ,'payment_details_id');
    }


}
