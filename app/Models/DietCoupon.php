<?php

namespace App\Models;

use App\CentralLogics\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietCoupon extends Model
{
    use HasFactory;
    protected $table = 'diet_coupons';
    protected $fillable = ['id','diet_name','subscription_id','mess_id','customer_id','coupon_no','state','speciality','customer_subscription_txn_id'];
    
    public $timestamps = false;

    public static function generateCouponNo($mess_id)
    {
        $mess = VendorMess::find($mess_id);
        $cno = self::where('mess_id', $mess_id)->orderBy('id', 'DESC')->first();

        if ($cno) {
            preg_match('/(\d+)$/', $cno->coupon_no, $matches);
            $numericPart = isset($matches[1]) ? $matches[1] : '';
            $incrementedNumericPart = (int)$numericPart + 1;
            $formattedNumericPart = str_pad($incrementedNumericPart, strlen($numericPart), '0', STR_PAD_LEFT);
    
            $couponNO = preg_replace('/\d+$/', $formattedNumericPart, $cno->coupon_no);
        } else {
            $couponNO = substr($mess->name, 0, 4) . dechex(date('dym')) . '0000000001';
        }

        return strtoupper($couponNO);
    }

    public  static function createCustomerCoupons($subscriptionId, $customerId, $customer_subscription_txn_id)
    {
        $subscription = Subscription::find($subscriptionId);
        // dd($subscription);
        $serviceDiets = json_decode($subscription->diets, true);

        
        foreach ($serviceDiets as $key => $value) {
            for ($i = 0; $i < (int)$value; $i++) {
                $dietFirest = ucfirst(strtolower((trim($key)[0])));
                $dietName = null;
                $speciality = Helpers::getSpeciality('N');
                if(Helpers::getService($dietFirest)){
                    $dietName = Helpers::getService($dietFirest);
                }else if(Helpers::getSpeciality($dietFirest)){
                    $speciality = Helpers::getSpeciality($dietFirest);
                }
                
                $saveStatus = self::create([
                    'diet_name' => $dietName,
                    'mess_id' => $subscription->mess_id,
                    'subscription_id' => $subscriptionId,
                    'customer_id' => $customerId,
                    'coupon_no' => self::generateCouponNo($subscription->mess_id),
                    'speciality' => $speciality,
                    'customer_subscription_txn_id' => $customer_subscription_txn_id,
                ]);

                if(!$saveStatus){
                    throw new \Exception("Invalid diet or speciality: $dietName");
                }
            }
        }
        return true;
    }
    public static function countCoupon($customer_subscription_txn_id,$state =['active']){
       return self::where('customer_subscription_txn_id' , $customer_subscription_txn_id)->where('state',$state)->count()  ;   
    }
    public static function getCustomerCoupons($subscriptionId, $customerId){
        return self::where('subscription_id', $subscriptionId)->get();
    }

    public static function getCustomerCoupon($subscriptionId, $customerId, $state = 'active') {
        return self::where('subscription_id', $subscriptionId)
                    ->where('customer_id',$customerId)
                    ->where('state', $state)
                    ->first();
            
    }

    public function subscription()
    {
        $this->belongsTo(Subscription::class);
    }

    public function checklist()
    {
        $this->hasOne(AttendaceCheckList::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function customerSubscriptionTxns()
    {
        return $this->belongsTo(CustomerSubscriptionTransactions::class,'customer_subscription_txn_id');
    }
}
