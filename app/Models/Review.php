<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $table = 'reviews';
    protected $fillable = ['order_id', 'customer_id','deliveryman_id','restaurant_id', 'rating', 'review','review_to'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function orderDeatails()
    {
        return $this->belongsTo(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function deliveryman()
    {
        return $this->belongsTo(DeliveryMan::class,'deliveryman_id');
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class,'restaurant_id');
    }

}
