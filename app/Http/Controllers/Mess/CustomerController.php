<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attendace;
use App\Models\Customer;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DietCoupon;
use App\Models\MessService;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        return view('mess-views.customer.add');
    }

    public function view($id)
    {
        $today = Carbon::now();

        $customerView = Customer::whereHas('subscription', function ($query) use ($today) {
                $query->where('expiry', '>', $today); // Corrected the comparison operator
            })
            ->with('subscription.subscription')
            ->find($id);

        // dd($customerView);
        
        return view('mess-views.customer.view', compact('customerView'));
    }  

    public function submit(Request $request)
    {
   
        $validator = Validator::make($request->all(), [
            "subscription_id" => 'nullable|numeric',
            "f_name" => 'required|string',
            "l_name" => 'nullable|string',
            "street" => 'required|string',
            "phone" => 'required|numeric|digits:10',
            "email" => 'required|email|unique:users',
            "pincode" => 'required|digits:6',
            "city" => "required|string",
            "start" => "required|date",
            "expiry" => "required|date",
            "password" => "required|min:6", 
            "c_password" => "required|same:password", 
            'type' => 'required',
            'image' => 'required|mimes:jpeg,jpg,png', 
        ],[
            'f_name' => 'First Name is Required',
            'street' => 'Address is Required',
            'phone' => 'Phone is Required',
            'email' => 'Email is Required',
            'email.unique' => 'Email already exists',
            "start" => "Starting Date is Required",
            "expiry" => "Ending Date is Required",
            'pincode' => 'Pincode is Required',
            'city' => 'City is Required',
            'type' => 'Meal Type is Required',
            'password' => 'Password is Required',
            'c_password' => 'Password not matched',
            'image' => 'Image Required',
        ]);

        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
        
            // Retrieve user role
            $role = UserRole::where('role', 'customer')->first();
            $mess_id = Session::get('mess')->id;
            
            if(!$role) {
                throw new \Exception('Role not found');
            }
        
            $customer = Customer::create([
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'image' => Helpers::uploadFile($request->image, 'customers'),
                'password' => bcrypt($request->password),
                'role_id' => $role->id,
                'status' => 1,
                'address' => json_encode([
                    'street' => $request->street,
                    'city' => $request->city,
                    'pincode' => $request->pincode
                ]),
            ]);
            
            if (!$customer) {
                throw new \Exception('Failed to Add Customer');
            }
            // Create customer subscription transaction
            $subscriptionTxn = $customer->subscription()->create([
                'subscription_id' => $request->subscription_id,
                'start' => $request->start,
                'expiry' => $request->expiry,
                'mess_id' => $mess_id,
            ]);
            
            if (!$subscriptionTxn) {

                throw new \Exception('Subscription Couldn\'t Be Added');
            }
        
            // Create diet coupons for the customer
            $dietCoupons = DietCoupon::createCustomerCoupons($request->subscription_id, $customer->id);

            if(!$dietCoupons){
                throw new \Exception('Coupons Coudn\'t created');
            }
        

            $messWallet = Wallet::where('mess_id', $mess_id)->first();
            $subscription = Subscription::find($request->subscription_id);

            if (!$messWallet || !$subscription) {
                throw new \Exception('Wallet or Subscription not found');
            }

            if ($subscription->discount_type == 'percent') {
                $amount = Helpers::percent_discount($subscription->price, $subscription->discount);
            } else {
                $amount = Helpers::flat_discount($subscription->price, $subscription->discount);
            }

            if ($messWallet->balance < $amount) {
                throw new \Exception('Insufficient balance in the wallet');
            }

            $messWallet->balance -= (int)$amount;
            $messWallet->save();

            $txn = $messWallet->WalletTransactions()->create([
                'amount' => $amount,
                'type' => 'Dr',
                'remarks' => "Customer Created With $subscription->name @ " . Helpers::format_currency($amount) . ", Name: $request->f_name"
            ]);

            if (!$txn) {
                throw new \Exception('Transaction could not be processed');
            }
        
            DB::commit();
            
            return response()->json(['success' => 'Customer Added Successfully']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    public function list()
    {
        $messId = Session::get('mess')->id ?? null;
        $suscriptionTransactions =  CustomerSubscriptionTransactions::where('mess_id', $messId)->with(['customer','subscription'])->orderBy('created_at','DESC')->get();

        // dd($suscriptionTransactions);

    return view('mess-views.customer.list', compact('suscriptionTransactions'));
    }

    public function getdata()
    {
        $customers = Customer::with(['user', 'attendance' => function($query) {
            $today = now()->toDateString(); 
            $query->whereDate('created_at', $today)->with('checklist');
        }])->get();
        $services = MessService::all();
        
        return response()->json(compact('customers','services'));
    }
    // public function getdata()
    // {
    //     $customers = Customer::with('user')->get();
    //     $services = MessService::all();

        
    //     // return response()->json(compact('customers','services'));
    // }

    
}
