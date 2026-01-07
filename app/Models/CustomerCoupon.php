<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'discount_coupon_id',
        'purchased',
        'times_used'
    ];

    public function discount_coupon()
    {
        return $this->belongsTo(DiscountCoupon::class);
    }
}
