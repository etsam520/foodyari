<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionOrderDetails extends Model
{
    use HasFactory;

    protected $fillable = ['id','customer_id',	'status','mess_id','total',	'payment_details_id','meal_collection','special_note','delivery_address','coordinates','cancel_reason'];


    public function orderItems(){
        return $this->hasMany(SubscriptionOrderItems::class,'order_id');
    }

    public function paymentDetail()
    {
        return $this->hasOne(PaymentDetails::class,'subscription_order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function package()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function mess()
    {
        return $this->belongsTo(VendorMess::class, 'mess_id');
    }



}
