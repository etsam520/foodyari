<?php

namespace App\Http\Controllers\User\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\Subscription;
use App\Models\VendorMess;
use App\Models\WeeklyMenu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MealController extends Controller
{
    public function index() 
    {  
        try {
           
            Helpers::get_restaurant_data();
            $customer = Customer::activeSubscription()->find(Session::get('userInfo')->id);
            $today = Carbon::now('Asia/Kolkata')->toDateString(); 
            $timenow = Carbon::now('Asia/Kolkata');
            $day = strtolower($timenow->format('l'));
            $customerSubscriptionTxns = CustomerSubscriptionTransactions::with('package')
            ->where('customer_id', auth('customer')->user()->id)
            ->where('expiry', '>=', $today)
            ->where('diet_status' , true)
            ->first();
         
            if(!$customerSubscriptionTxns){
                throw new \Exception('You Don\'t have subscribed any package yet.');
            }
            $subscribedPackage = $customerSubscriptionTxns->package->first();
            $todayMenu = WeeklyMenu::where('mess_id', $subscribedPackage->mess_id)->where('day', $day)->where('type',$subscribedPackage->type)->get();
            $mess = VendorMess::find($subscribedPackage->mess_id);
            $attendance = Attendance::with('checklist')->whereDate('created_at', $today)
            ->where('customer_id', $customerSubscriptionTxns->customer_id)
            ->where('subscription_id',$customerSubscriptionTxns->subscription_id)
            ->first();
            
            return view('user-views.mess.meal.index',compact('mess','customerSubscriptionTxns','attendance','subscribedPackage','customer','todayMenu'));
        } catch (\Throwable $th) {
            return back()->with('info', $th->getMessage());
        } 
    }
}
