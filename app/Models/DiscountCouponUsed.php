<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCouponUsed extends Model
{
    use HasFactory;

    protected $table = 'discount_coupon_useds';
    protected $fillable = [
        'discount_coupon_id',
        'order_id',
        'used_at'
        ];

    public function discount_coupon()
    {
        return $this->belongsTo(DiscountCoupon::class,'discount_coupon_id');
    }

    public function orders() {
        return $this->belongsTo(Order::class,'order_id');
    }
}
