<?php

namespace App\Http\Controllers\User\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DietCoupon;
use App\Models\MessSubscritionPackageTransaction;
use App\Models\Subscription;
use App\Models\SubscriptionOrderDetails;
use App\Models\SubscriptionOrderItems;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\Throw_;

class SubscribedPackageController extends Controller
{
    public function list()
    {
        $orders = SubscriptionOrderDetails::where('customer_id',Session::get('userInfo')->id)
                    ->where('status','confirmed')->latest()
                    ->paginate(5);
        
        return view('user-views.mess.subscriptionPackage.list',compact('orders'));
    }

    public function listItems(Request $request)
    {
        try {
            $orderId = $request->get('order_id');
         
            $order = SubscriptionOrderDetails::with(['orderItems','paymentDetail'])->find($orderId);
            // dd($order);
            $items = SubscriptionOrderItems::where('order_id',$orderId)->get();
            if(!$items)
            {
                throw new Error('Items not found');
            }
     
            return response()->json([ 
                'success' => true,
                'view'   =>view('user-views.mess.subscriptionPackage.partials._list-item',compact('items','order'))->render(),
            ]);

        } catch (\Exception $th) {
        //   dd($th);
            return response()->json([
                'error' => $th->getMessage(),
            ]);
        }
    }


    public function activatePackage(Request $request)
    {
        try {
            if (empty($request->order_id)) {
                throw new \Exception('Order ID can\'t be empty');
            } elseif (empty($request->product_id)) {
                throw new \Exception('Product ID can\'t be empty');
            }

            $customer = Customer::find(Session::get('userInfo')->id);
            $subscriptionPackage = Subscription::find($request->product_id);
            $orderDetails = SubscriptionOrderDetails::find($request->order_id) ;
            // dd($orderDetails);  
            $today = Carbon::today()->toDateString();
            $expiry = Helpers::getDateAfterDays($subscriptionPackage->validity);
            $orderItem = SubscriptionOrderItems::where('order_id',$request->order_id)->where('product_id',$subscriptionPackage->id)->latest()->first();
            

            $whetherSubscribed = CustomerSubscriptionTransactions::with(['dietCoupons' => function($query){
              return  $query->where('state', 'active');}])
                ->where('customer_id', $customer->id)
                ->where('expiry', '>', $today)
                ->latest()
                ->first();
                
                
                if ($whetherSubscribed && $whetherSubscribed->dietCoupons->count() > 0) {
              
                throw new \Exception('You have an active subscription. You can activate another package after ' .
                    Helpers::daysUntilExpiry($whetherSubscribed->expiry) . ' days.');
            }

            DB::beginTransaction();

            $packageTXN = MessSubscritionPackageTransaction::updateOrCreate(
                [
                    'order_id' => $request->order_id,
                    'product_id' => $subscriptionPackage->id,
                    'mess_id' => $orderDetails->mess_id,
                ],
                [
                    'special_note' => $orderDetails->special_note,
                    'state' => 'enable',
                    'customer_id' => $customer->id,
                    'payment_details_id' => $orderDetails->payment_details_id,
                    'delivery_address' => $orderDetails->delivery_address,
                    'coordinates' => $orderDetails->coordinates
                ]
            );

            $subscriptionTxn = $customer->subscription()->create([
                'subscription_id' => $subscriptionPackage->id,
                'start' => $today,
                'expiry' => $expiry,
                'mess_id' => $subscriptionPackage->mess_id,
                'mess_package_txn_id' => $packageTXN->id,
                'delivery_address' => $orderDetails->delivery_address,
                'coordinates' => $orderDetails->coordinates
            ]);

            if (!$subscriptionTxn) {
                throw new \Exception('Subscription could not be added');
            }

            // Create diet coupons for the customer
            $dietCoupons = DietCoupon::createCustomerCoupons($subscriptionPackage->id, $customer->id,$subscriptionTxn->id);

            if (!$dietCoupons) {
                throw new \Exception('Coupons could not be created');
            }

            $orderItem->status = 'active';
            $orderItem->expiring = $expiry;
            $orderItem->save();

            DB::commit();
            return response()->json([
                'success' => "Package Activate",
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'error' => $exception->getMessage(),
            ]);
        }
    }

}
