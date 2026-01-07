<?php

namespace App\Http\Controllers\Mess;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DietCoupon;
use App\Models\MessQR;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QRController extends Controller
{
    protected $time ;

    public function __construct()
    {
        $this->time = Carbon::now()->toTimeString();
    }
    public function varify(Request $request){
        try {
            $encrypted_code = $request->get('encrypted_code');
            $otp = $request->get('otp');
            $today = Carbon::today()->toDateString();
        
            if (!empty($encrypted_code)) {
                $qrdata = MessQR::with([
                    'coupon.customerSubscriptionTxns',
                    'coupon.customer'
                ])->where('encrypted_code', $encrypted_code)->first();
            } elseif (!empty($otp)) {
                $qrdata = MessQR::with([
                    'coupon.customerSubscriptionTxns',
                    'coupon.customer'
                ])->where('otp', $otp)->first();
            } else {
                throw new \Exception('Service Not Available');
            } 
    
            if ($qrdata) {
                if($qrdata->coupon->customerSubscriptionTxns->delivery == 1 && !$qrdata->mess_deliveryman_id ){
                    throw new \Exception("No delivery person has picked up Mr/Mrs. {$qrdata->coupon->customer->f_name} 
                    {$qrdata->coupon->customer->l_name} Please ask the delivery person to pick up the order or assign the order manually to a delivery person.");
                }elseif($qrdata->coupon->customerSubscriptionTxns->delivery == 1 && $qrdata->mess_deliveryman_id){
                    $deliveryman = DeliveryMan::find($qrdata->mess_deliveryman_id);
                    $deliveryman->messOrderAccept()->updateOrCreate(
                        [
                            'checkList_id' => $qrdata->attendance_checklist_id,
                            'dm_id' => $deliveryman->id,
                            'mess_qrId' => $qrdata->id,
                        ],
                        [
                            'status' => 'delivered',
                        ]
                    );
                }
               
                $coupon = DietCoupon::find($qrdata->diet_coupon_id);
                if($coupon->state === "redeem"){
                    throw new \Exception('Coupon Is Already Used !!');
                }
                $coupon->state = "redeem";
                $coupon->save();

                $qrdata->checked_at = now(); 
                if ($qrdata->save()) {
                    $message = ucfirst($qrdata->coupon->customer->f_name).' '.ucfirst($qrdata->coupon->customer->l_name).'Meal Dine In/ Delevry Success!!';
                    return response()->json(['success' => $message]);
                } else {
                    throw new \Exception('Something Going Wrong');
                }
            } else {
                throw new \Exception('Invalid Authentication');
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
}
