<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Expr\FuncCall;

class Subscription extends Model
{
    use HasFactory;
   protected $table = 'subscription';
   protected $fillable = ['title','validity','speciality','veg','diets','discount','discount_type','price','mess_id'];	
  

   public function dietCoupons()
   {
     return $this->hasMany(DietCoupon::class);
   }

   public function mess()
   {
     return $this->belongsTo(VendorMess::class);
   }

   public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function customerSubscriptionTransactions()
    {
        return $this->hasMany(CustomerSubscriptionTransactions::class);
    }
    
    public function orderItems()
    {
        return $this->hasMany(SubscriptionOrderItems::class);
    }
    public function scopeMessAndCustomers($query)
    {
        return $query->with(['mess', 'customers' => function ($query2) {
            $query2->where('status', 1)
                  ->where('diet_status', 1)
                  ->where('expiry', '>=', Carbon::now()->format('Y-m-d'));
        }])->where('status', 1);
    }

    public function scopeFindMess($query)
    {
        return $query->with('mess')->where('status', 1);
    }
   
   
}
