<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = ['food_id',	'order_id',	'price',	'food_details',	'variation',	'discount_on_food',	'tax_amount',	'variation_price',	'add_on_ids',	'discount_type',	'item_campaign_id',	'quantity',	'addon_price'];





    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }




}
