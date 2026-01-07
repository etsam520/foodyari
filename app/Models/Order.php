<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'order_amount',
        'coupon_discount_amount',
        'coupon_discount_details',
        'payment_status',
        'order_status',
        'total_tax_amount',
        'tax_details',
        'payment_method',
        'transaction_reference',
        'delivery_instruction',
        'cooking_instruction',
        'delivery_man_id',
        'order_note',
        'order_type',
        'checked',
        'restaurant_id',
        'subscription_id',
        'delivery_charge',
        'delivery_charge_details',
        'otp',
        'pending',
        'accepted',
        'confirmed',
        'processing',
        'handover',
        'picked_up',
        'delivered',
        'canceled',
        'refund_requested',
        'refunded',
        'refund_details',
        'callback',
        'scheduled',
        'delivery_address',
        'restaurant_discount_amount',
        'custom_discount',
        'custom_discount_details',
        'adjusment',
        'zone_id',
        'dm_tips',
        'cancellation_reason',
        'canceled_by',
        'tax_status',
        'discount_on_product_by',
        'vehicle_id',
        'refund_request_canceled',
        'coupon_created_by',
        'cancellation_note',
        'free_delivery_by',
        'processing_time',
        'extra_cooking_time',
        'extra_cooking_time_updated_at',
        'cash_to_collect',
        'edited',
        'original_delivery_charge',
        'failed',
        'schedule_at',
        'distance',
        'review_id'
    ];

    protected $casts = [
        'extra_cooking_time_updated_at' => 'datetime',
        'extra_cooking_time' => 'integer',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class);
    }

    public function orderTxn()
    {
     return $this->hasOne(OrderTransaction::class);
    }

    public function orderCalculationStmt()
    {
     return $this->hasOne(OrderCalculationStatement::class);
    }


    // filteration

    public function scopePreparing($query)
    {
        return $query->whereIn('order_status', ['confirmed','processing','handover']);
    }


    //check from here
    public function scopeOngoing($query)
    {
        return $query->whereIn('order_status', ['accepted','confirmed','processing','handover','picked_up']);
    }

    public function scopeFoodOnTheWay($query)
    {
        return $query->where('order_status','picked_up');
    }

    public function scopeHandovered($query)
    {
        return $query->where('order_status','handover');
    }

    public function scopePending($query)
    {
        return $query->where('order_status','pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('order_status','failed');
    }

    public function scopeCanceled($query)
    {
        return $query->where('order_status','canceled');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status','delivered');
    }

    public function scopeRefunded($query)
    {
        return $query->where('order_status','refunded');
    }
    
    public function scopeScheduled($query)
    {
        return $query->where('order_status','scheduled');
    }
    
    public function scopeAccepteByDeliveryman($query)
    {
        return $query->where('order_status','accepted')->whereNotNull('delivery_man_id');
    }

    public function scopeRefund_requested($query)
    {
        return $query->where('order_status','refund_requested');
    }

    public function scopeRefund_request_canceled($query)
    {
        return $query->where('order_status','refund_request_canceled');
    }


    public function scopeSearchingForDeliveryman($query)
    {
        return $query->whereNull('delivery_man_id')->where('order_type', '=' , 'delivery')->whereNotIn('order_status',['delivered','failed','canceled', 'refund_requested','refund_request_canceled', 'refunded']);
    }

    public function scopeDelivery($query)
    {
        return $query->where('order_type', '=' , 'delivery');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
    public function dmOrderProcess()
    {
        return $this->hasOne(DmOrderProcess::class, 'order_id');
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function activeRefund()
    {
        return $this->hasOne(Refund::class)->whereIn('refund_status', ['pending', 'approved', 'processed']);
    }

    public function lovedOne()
    {
        return $this->hasOne(LovedOneWithOrder::class, 'order_id');
    }

    public function getZoneId()
    {
        if($this->zone_id == null){
            $zoneId = Order::leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
             ->where('orders.id', $this->id)
             ->value('restaurants.zone_id');
             return $zoneId;}
        return $this->zone_id;  
    }

}
