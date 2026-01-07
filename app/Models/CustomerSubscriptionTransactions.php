<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PgSql\Lob;

class CustomerSubscriptionTransactions extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_id','start','expiry','mess_id','mess_package_txn_id','coordinates','delivery_address'];
 
    public function customer(){
        return $this->belongsTo(Customer::class);
    }
    public function package(){
        return $this->belongsTo(Subscription::class,'subscription_id');
    }

    public function dietCoupons()
    {
        return $this->hasMany(DietCoupon::class,'customer_subscription_txn_id');
    }
    
    public function scopeHaveDietCoupons($query,$diet_name, $qty=1)
    {
       
        return $query->with(['dietCoupons' => function($q) use ($diet_name, $qty) {
            // Log::warning($diet_name);
            $q->where('diet_name', $diet_name)
              ->where('state', 'active')
              ->limit($qty);
        }]);
    }


}
