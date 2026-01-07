<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{

    use HasFactory;
    protected $fillable =
     ['id','title','code','description','start_date','expire_date','discount_type',
     'coupon_type','limit', 'status','restaurant_id','mess_id','total_uses','created_by',
     'customer_id','slug','created_at','updated_at'];

    public function customer_coupon()
    {
        return $this->hasMany(CustomerCoupon::class, 'discount_coupon_id');
    }

    public function used()
    {
        return $this->hasMany(DiscountCouponUsed::class,'discount_coupon_id');
    }

    public function scopeIsActive($query, $active=true)
    {
        return $query->where('status',$active);
    }



}
