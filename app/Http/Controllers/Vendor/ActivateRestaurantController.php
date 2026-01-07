<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminToRestaurantSubscriptonPackage;
use App\Models\Restaurant;
use App\Models\RestaurantSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ActivateRestaurantController extends Controller
{
    public function activate(Request $request)
    {   
        try {
            //code...
            $request->validate([
                'subscription_type' =>"required",
            'package_id' => 'required_if:subscription_type,subscription'
            ]);
            $restaurant = Restaurant::find(Session::get('restaurant')->id);
            DB::beginTransaction();
            $restaurant->subscription_type = $request->subscription_type;
            $restaurant->save();
            if($request->package_id){

                $subscription = AdminToRestaurantSubscriptonPackage::find($request->package_id);
                $subscription_txn = $subscription->transactions()->create([
                'package_details' => json_encode([
                    'pos'=>$subscription->pos,
                    'mobile_app'=>'integer',
                    'chat'=> $subscription->chat,
                    'review'=>$subscription->review,
                    'self_delivery'=>$subscription->self_delivery,
                ]),
                'package_id'=>$subscription->id,
                'restaurant_id'=>$restaurant->id,
                'price'=>$subscription->price,
                'validity'=>$subscription->validity,
                'payment_method'=>'cash',
                'reference' => null,
                'paid_amount'=>$subscription->price,
                'discount' => 0 ,
                'created_by' => 'restaurant',
                
                ]);

                $subscribed = RestaurantSubscription::create([
                    'pos'=>$subscription->pos,
                    'mobile_app'=>$subscription->mobile_app,
                    'package_id'=>$subscription->id,
                    'chat'=> $subscription->chat,
                    'review'=>$subscription->review,
                    'self_delivery'=>$subscription->self_delivery,
                    'txn_id' => $subscription_txn->id,
                    'restaurant_id'=>$restaurant->id,
                    'status'=>1,
                    'max_order'=>$subscription->max_order,
                    'max_product'=>$subscription->max_product,
                    'payment_method'=>'cash',
                    'paid_amount'=>$subscription->price,
                    'validity'=>$subscription->validity,
                    'expiry_date' =>Helpers::getDateAfterDays($subscription->validity),
                ]);

                if(!$subscribed){
                    throw new \Error('Package Coud\'t be Subscribed' );
                }
                
            }
            DB::commit();
            Session::flash('success','Subscription Package Activated');
            return redirect()->route('vendor.dashboard');
        } catch (\Exception $th) {
            DB::rollBack();
            dd($th->getMessage());
            return back()->with('error', $th->getMessage());
        }
    }
}
