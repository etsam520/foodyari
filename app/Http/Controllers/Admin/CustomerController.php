<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\CashTransaction;
use App\Models\Customer;
use App\Models\GatewayPayment;
use App\Models\Order;
use App\Models\Review;
use App\Models\UserRole;
use App\Models\Wallet;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class CustomerController extends Controller
{

    public function index()
    {
        return view('admin-views.customer.add');
    }

    public function view($id)
    {
        $customer = Customer::with([
            'wallet',
            'loyaltyPointTransactions' => function($query) {
                $query->latest()->limit(10);
            },
            'referralRewards' => function($query) {
                $query->with(['referral', 'sponsor:id,f_name,l_name'])
                      ->latest()->limit(10);
            },
            'sponsorRewards' => function($query) {
                $query->with(['referral', 'user:id,f_name,l_name'])
                      ->latest()->limit(10);
            },
            'sponsoredReferrals' => function($query) {
                $query->latest()->limit(10);
            },
            'referrer:id,f_name,l_name,email,phone',
            'referredUsers:id,f_name,l_name,email,phone,image,status,successful_orders,created_at,referred_by'
        ])->find($id);

        if (isset($customer)) {
            $orders = Order::with(['lovedOne', 'customer:id,f_name,l_name'])
                ->latest()
                ->where(['customer_id' => $id])
                ->paginate(15);
            
            // Get cached customer statistics for better performance
            $cachedStats = $this->getCachedCustomerStats($id);
            
            $loyaltyStats = $cachedStats['loyalty'] ?? [
                'total_earned' => 0,
                'total_redeemed' => 0,
                'total_expired' => 0,
            ];

            $referralStats = $cachedStats['referral'] ?? [
                'total_referrals' => 0,
                'successful_referrals' => 0,
                'total_rewards_earned' => 0,
                'claimed_rewards' => 0,
                'pending_rewards' => 0,
            ];

            $walletBalance = $cachedStats['wallet_balance'] ?? 0;

            return view('admin-views.customer.customer-view', compact(
                'customer', 'orders', 'loyaltyStats', 'referralStats', 'walletBalance'
            ));
        }
        Session::flash('error',__('messages.customer_not_found'));
        return back();
    }

    public function submit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "f_name" => 'required|string',
            "l_name" => 'nullable|string',
            "street" => 'required|string',
            "phone" => 'required|numeric|digits:10|unique:customers',
            "email" => 'required|email|unique:customers',
            "pincode" => 'required|digits:6',
            "city" => "required|string",
            "password" => "required|min:6",
            "c_password" => "required|same:password",
            'image' => 'required|mimes:jpeg,jpg,png',
        ],[
            'f_name' => 'First Name is Required',
            'street' => 'Address is Required',
            'phone' => 'Phone is Required',
            'email' => 'Email is Required',
            'email.unique' => 'Email already exists',
            'pincode' => 'Pincode is Required',
            'city' => 'City is Required',
            'password' => 'Password is Required',
            'c_password' => 'Password not matched',
            'image' => 'Image Required',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $role = UserRole::where('role', 'customer')->first();

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

             Wallet::create([
                'customer_id' => $customer->id,
            ]);
            DB::commit();

            return response()->json(['success' => 'Customer Added Successfully']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    public function list(Request $request)
    {
        // Optimize query with proper eager loading and pagination for better performance
        $query = Customer::select([
            'id', 'f_name', 'l_name', 'email', 'phone', 'image', 
            'status', 'loyalty_points', 'referral_code', 'created_at'
        ])
        ->with([
            'wallet:customer_id,balance',
        ])
        ->withCount([
            'orders as total_orders_count',
            'orders as delivered_orders_count' => function($query) {
                $query->where('order_status', 'delivered');
            }
        ]);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('f_name', 'like', "%{$search}%")
                  ->orWhere('l_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $customers = $query->orderBy('delivered_orders_count', 'desc')
                    ->get();
                        //   ->paginate(50)
                        //   ->appends($request->query()); // Maintain query parameters in pagination

        return view('admin-views.customer.list', compact('customers'));
    }


     public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin-views.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validator = Validator::make($request->all(), [
            "f_name" => 'required|string',
            "l_name" => 'nullable|string',
            "street" => 'required|string',
            "phone" => 'required|numeric|digits:10|unique:customers,phone,' . $id,
            "email" => 'required|email|unique:customers,email,' . $id,
            "pincode" => 'required|digits:6',
            "city" => "required|string",
            "password" => "nullable|min:6",
            "c_password" => "nullable|same:password",
            'image' => 'nullable|mimes:jpeg,jpg,png',
        ], [
            'f_name' => 'First Name is Required',
            'street' => 'Address is Required',
            'phone' => 'Phone is Required',
            'email' => 'Email is Required',
            'email.unique' => 'Email already exists',
            'phone.unique' => 'Phone already exists',
            'pincode' => 'Pincode is Required',
            'city' => 'City is Required',
            'password' => 'Password must be at least 6 characters',
            'c_password' => 'Password not matched',
            'image' => 'Image must be jpeg, jpg, or png',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $updateData = [
                'f_name' => $request->f_name,
                'l_name' => $request->l_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => json_encode([
                    'street' => $request->street,
                    'city' => $request->city,
                    'pincode' => $request->pincode
                ]),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = bcrypt($request->password);
            }

            if ($request->hasFile('image')) {
                $updateData['image'] = Helpers::uploadFile($request->image, 'customers');
            }

            $customer->update($updateData);

            DB::commit();

            return response()->json(['success' => 'Customer Updated Successfully']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['errors' => $ex->getMessage()], 422);
        }
    }

    public function getdata()
    {
        $customers = Customer::latest()->get();
        return response()->json(compact('customers'));
    }

    public function access($id)
    {
        $user = Customer::findorFail($id);

        Auth::guard('customer')->login($user);
        Session::put('userInfo', $user);

        $user = Customer::with(['customerAddress' => function ($query) {
            $query->where('is_default', 1)->latest();
        }])->find($user->id);
                // dd($user);
        if(!empty($user->customerAddress[0])){
            $address = $user->customerAddress[0];
            $data = [
                'lat' => $address->latitude??null,
                'lng' =>$address->longitude??null,
                'phone' => $address->phone??null,
                'address' => $address->address??null,
                'landmark' => $address->landmark??null,
                'type' => $address->type??null
            ];
            $redis = new RedisHelper();
            $redis->set("user:{$user->id}:user_location", $data, 3600, true);  
        }

        return redirect()->route('user.dashboard');
    }



    public function status(Request $req , Customer $customer)
    {

        $customer = $customer->find($req->query('id'));

        $customer->status = filter_var( $req->query('status'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;

        $customer->save();

        return back()->with('success', 'Status Changed');
    }

    public function addWalletFund(Request $request)
    {
        try {
        $request->validate([
            'amount'  =>'required|numeric',
            'customer_id'  =>'required|exists:customers,id',
            'transaction_type'  =>'required|in:debit,credit',
            'referance'  =>'nullable|string',
        ]);

            $amount = $request->amount;

            DB::beginTransaction();
            $customerWallet = Wallet::firstOrNew(['customer_id' => $request->customer_id]);
            if(!$customerWallet){
                throw new \Exception("Customer Not Found");
            }
            if($request->transaction_type == 'debit'){
                throw new \Exception('You Can\'t Direct Deduct From Customer Wallet');
            }
            // dd($customerWallet);
                $adminFund = AdminFund::getFund();
                $user = Customer::find($customerWallet->customer_id);

                $customerWallet->balance += $amount; // Adding wallet balance
                $adminFund->balance -= $amount; // Adding it to admin fund


                $customerWallet->walletTransactions()->create([
                    'amount' => $amount,
                    'type' => $request->transaction_type == 'credit' ?  'received' : 'paid',
                    'customer_id' => $customerWallet->customer_id,
                    'remarks' => "Top-Up : ".Helpers::format_currency($amount)." Added Through Admin !!",
                ]);

                $adminFund->txns()->create([
                    'amount' => $amount,
                    'txn_type' => $request->transaction_type == 'debit' ?  'received' : 'paid',
                    'paid_to' => 'customer',
                    'received_from' => null,
                    'customer_id' => $customerWallet->customer_id,
                    'remarks' => $request->referance ??Helpers::format_currency($amount)." Send For Top Up  {$user->f_name}",
                ]);
                $customerWallet->save();
                $adminFund->save();


            DB::commit();
            $message =  __(Helpers::format_currency($amount).' Top-UP Done');
            Session::flash('success',$message);
           return back();

        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return back()->with('error', $e->getMessage());
        }
    }


    public function histories(Request $request)
    {
        // dd($request->json());
        $txn_method = $request->json('txn_method', true);
        $txn_type = $request->json('txn_type', true);
        $customerId = $request->query('customer_id');
        $customer = Customer::find($customerId);

        $cashTxns = [];
        $WalletTxns = [];
        $OnlineTxns = [];
        $referalTxns = [];

        if($txn_method['all']){
            $cashTxns = self::cash_txns($txn_type, $customer);
            $OnlineTxns = self::online_txns($txn_type, $customer);
            $WalletTxns = self::wallet_txns($txn_type , $customer);
        }elseif($txn_method['cash']){
            $cashTxns = self::cash_txns($txn_type, $customer);
        }elseif($txn_method['online']){
            $OnlineTxns = self::online_txns($txn_type, $customer);
        }elseif ($txn_method['wallet']) {
            $WalletTxns = self::wallet_txns($txn_type, $customer);
        }
        $txns =array_merge($cashTxns, $WalletTxns, $OnlineTxns) ;
        $txns = self::txnByDate($txns);

       $txns = array_filter($txns, function ($txn) use ($txn_type) {
            if ($txn_type['all']) {
                return true; // Include all transactions
            } elseif ($txn_type['received']) {
                return $txn['type'] === "received";
            } elseif ($txn_type['paid']) {
                return $txn['type'] === "paid";
            }
            return false; // Exclude the transaction if no condition matches
        });
        // dd($txns);
        return response()->json($txns);
    }

    private static function cash_txns($txn_type, $user){
        // $user = Session::get('userInfo');
        $cashTxns = CashTransaction::where('customer_id',$user->id)
        ->latest()->get()->toArray();
        $txns = [];
        foreach($cashTxns as $t){
            $txns[] = [
                'amount' => $t['amount'],
                'date' => $t['created_at'],
                'remarks' => $t['remarks'],
                'status' => 'success',
                'type' => $t['received_from'] === 'customer'? 'paid': ($t['paid_to'] === 'customer'? 'received': null),
            ];
        }


        return $txns ;
    }

    private static function online_txns($txn_type , $user)
    {

        // $user = Session::get('userInfo');
        $online_txns = GatewayPayment::where('assosiate', 'customer')
        // ->when(isset($status), function ($query) use($status){
        //    return $query->where('payment_status',$status);
        // })
        ->where('assosiate_id',$user->id)
        ->latest()->get()->toArray();
        $txns = [];
        foreach($online_txns as $t){
            $txns[] = [
                'amount' => $t['amount'],
                'date' => $t['created_at'],
                'remarks' => "Txn id: {$t['txn_id']}, GateWay : {$t['gateway']}" ,
                'status' => $t['payment_status'],
                'type' => 'paid',
            ];
        }

        return $txns ;
    }

    private static function wallet_txns($txn_type , $customer){
        // $customer = Session::get('userInfo');

        $mywallet = Wallet::where('customer_id', $customer->id)
            ->with(['WalletTransactions' => function ($query) use($customer) {
                $query->where('customer_id',$customer->id)->orderBy('created_at', 'DESC');
            }])
            ->first();
        $cashTxns = $mywallet->WalletTransactions()->get()->toArray();
        $txns = [];
        foreach($cashTxns as $t){
            $txns[] = [
                'amount' => $t['amount'],
                'date' => $t['created_at'],
                'remarks' => $t['remarks'],
                'status' => 'success',
                'type' => $t['type'],
            ];
        }

        return $txns;

    }

    private static function txnByDate($txns, $orderBy = "desc")
    {
        // Base case: If there's one or no transactions, return the array.
        if (count($txns) <= 1) {
            return $txns;
        }

        // Recursive case: Split the array into two halves
        $middle = (int) floor(count($txns) / 2);
        $left = array_slice($txns, 0, $middle);
        $right = array_slice($txns, $middle);

        // Recursively sort both halves
        $sortedLeft = self::txnByDate($left, $orderBy);
        $sortedRight = self::txnByDate($right, $orderBy);

        // Merge the sorted halves
        return self::mergeByDate($sortedLeft, $sortedRight, $orderBy);
    }

    private static function mergeByDate($left, $right, $orderBy)
    {
        $sorted = [];
        while (count($left) > 0 && count($right) > 0) {
            $leftDate = strtotime($left[0]['date']);
            $rightDate = strtotime($right[0]['date']);

            if ($orderBy === "asc") {
                if ($leftDate <= $rightDate) {
                    $sorted[] = array_shift($left);
                } else {
                    $sorted[] = array_shift($right);
                }
            } else { // Descending orderBy
                if ($leftDate >= $rightDate) {
                    $sorted[] = array_shift($left);
                } else {
                    $sorted[] = array_shift($right);
                }
            }
        }

        // Append any remaining elements
        return array_merge($sorted, $left, $right);
    }

    public function rating(){
        // Redirect to the new grouped reviews page
        return redirect()->route('admin.reviews.grouped-list');
    }

    /**
     * Get cached customer statistics for better performance
     */
    private function getCachedCustomerStats($customerId)
    {
        return Cache::remember("customer_stats_{$customerId}", 300, function () use ($customerId) {
            $customer = Customer::find($customerId);
            
            if (!$customer) {
                return null;
            }

            $loyaltyStats = [
                'total_earned' => $customer->loyaltyPointTransactions()
                    ->where('type', 'earned')->sum('points') ?? 0,
                'total_redeemed' => $customer->loyaltyPointTransactions()
                    ->where('type', 'redeemed')->sum('points') ?? 0,
                'total_expired' => $customer->loyaltyPointTransactions()
                    ->where('type', 'expired')->sum('points') ?? 0,
            ];

            $referralStats = [
                'total_referrals' => $customer->sponsoredReferrals()->count(),
                'successful_referrals' => $customer->sponsoredReferrals()->count(),
                'total_rewards_earned' => $customer->sponsorRewards()
                    ->where('is_unlocked', true)->sum('sponsor_reward_value') ?? 0,
                'claimed_rewards' => $customer->sponsorRewards()
                    ->where('is_sponsor_claimed', true)->sum('sponsor_reward_value') ?? 0,
                'pending_rewards' => $customer->sponsorRewards()
                    ->where('is_unlocked', true)
                    ->where('is_sponsor_claimed', false)->sum('sponsor_reward_value') ?? 0,
            ];

            return [
                'loyalty' => $loyaltyStats,
                'referral' => $referralStats,
                'wallet_balance' => $customer->wallet->balance ?? 0
            ];
        });
    }

    /**
     * Clear customer statistics cache
     */
    public function clearCustomerStatsCache($customerId)
    {
        Cache::forget("customer_stats_{$customerId}");
        return response()->json(['success' => true]);
    }

    /**
     * Search customer orders
     */
    public function orderSearch(Request $request)
    {
        $customerId = $request->get('id');
        $search = $request->get('search');
        
        $query = Order::with(['lovedOne', 'customer:id,f_name,l_name'])
            ->where('customer_id', $customerId);
            
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('f_name', 'like', "%{$search}%")
                                   ->orWhere('l_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lovedOne', function($lovedOneQuery) use ($search) {
                      $lovedOneQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(15);
        
        return response()->json([
            'view' => view('admin-views.customer.partial._order-table-list', compact('orders'))->render(),
            'total' => $orders->total()
        ]);
    }
}
