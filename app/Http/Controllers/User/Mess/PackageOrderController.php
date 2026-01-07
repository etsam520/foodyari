<?php

namespace App\Http\Controllers\User\Mess;

use App\CentralLogics\Helpers;
use App\CentralLogics\MessSubscriptionBilling;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\BusinessSetting;
use App\Models\GatewayPayment;
use App\Models\Order;
use App\Models\PaymentDetails;
use App\Models\Subscription;
use App\Models\SubscriptionOrderDetails;
use App\Models\VendorEmployee;
use App\Models\VendorMess;
use App\Models\Wallet;
use App\Services\Payments\PaymentGatewayFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PackageOrderController extends Controller
{
    public function order(Request $request)
    {

        try {

            $request->validate([
                'meal_collection' => 'required',
                'message' => 'nullable|string',
                "address" => 'required_if:meal_collection,delivery,',
                "latitude1" => 'required_if:meal_collection,delivery,',
                "longitude1" => 'required_if:meal_collection,delivery,',
            ]);
            // dd($request->post());

            $mealCollectionType = $request->meal_collection;
            $specialNote = $request->message; // Changed 'message' to 'special_note'

            $billing =new MessSubscriptionBilling();

            $customer = Auth::guard('customer')->user();

            DB::beginTransaction();
            $orderDetail = SubscriptionOrderDetails::create([
                'customer_id' => $customer->id,
                'special_note' => $specialNote ?? null,
                'meal_collection' => $mealCollectionType,
                'total' => $billing->total(),
                'status' => 'pending',
                "delivery_address" =>json_encode([
                    'position' => ["latitude" => $request->latitude1,"longitude" => $request->longitude1],
                    'stringAddress' => $request->address,
                    'landmark' =>null,
                    'type' => null,
                    ]) ,
                'coordinates' => $request->address ? json_encode(["latitude" => $request->latitude1,"longitude" => $request->longitude1]): null
            ]);

            $cartItems = CartHelper::getCart();
            foreach ($cartItems as $item) {
                $package = $item['package_data'];
                if (!empty($package)) {
                    if(empty($orderDetail->mess_id)){
                        $orderDetail->mess_id = Subscription::find($package['id'])->mess_id;

                    }
                    $orderDetail->orderItems()->create([
                        'price' => Helpers::food_discount($package['price'], $package['discount'], $package['discount_type']),
                        'product_id' => $package['id'],
                    ]);
                }
            }

            $paymentDetail = $orderDetail->paymentDetail()->create([
                'subtotal' => $billing->subtotal,
                'total' => $billing->total(),
                'tax' => $billing->tax,
                'customer_id' => $customer->id,
                'coupon_discount' => $billing->couponDiscount,
                'custom_discount' => $billing->customDiscount,
                'discount' => $billing->discount,
                'delivery_charge' => $billing->deliveryCharge,
                'other_charges' => json_encode([]), // Changed 'otherCharges' to 'other_charges'
                'status' => 'unpaid',
                'method' =>  null,

            ]);

            if ($paymentDetail) {

                $orderDetail->payment_details_id = $paymentDetail->id;

                $orderDetail->save();
                CartHelper::clearCart();
                DB::commit();
                return redirect()->route('user.mess.payment_options',['order_id'=> $orderDetail->id]);
            } else {
                throw new \Error("Failed to process Order. Try Later");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function orderPaymentOnline(Request $request)
    {
        try {
            $request->validate([
                'orderAmount' => 'required|numeric|min:1',
                'gateway' => 'required_if:amount,notnull|in:phonepe,gpay,paytm'
            ]);
            $user = Session::get('userInfo');

            DB::beginTransaction();

            // Create the order using the payment gateway
            $order =  self::placeOrder_via_onlineOrWallet($request);


            $orderPaymentDetails = [
                'merchant_txn_id' =>  call_user_func_array(
                    config('payment.merchant_txn_id'),
                    [$request->input('gateway'), "O"]
                ),
                'amount' =>  1,
                // 'amount' =>  $order->paymentDetail->online,
                'email' => $user->email,
                'phone' => $user->phone,
                'gateway' => $request->input('gateway'),
            ];



            Cache::put('last_order', $order);

            GatewayPayment::create([
                // 'amount' =>  $order->paymentDetail->online,
                'amount' =>  1,
                'merchant_txn_id' => $orderPaymentDetails['merchant_txn_id'],
                'gateway' => $orderPaymentDetails['gateway'],
                'assosiate' => 'customer',
                'assosiate_id' => $user->id,
                'payload' => json_encode($orderPaymentDetails),
                'details' => json_encode($order->toArray()),
            ]);

            $paymentGateway = PaymentGatewayFactory::make($request->input('gateway')); // 'cashfree', 'phonepe', etc.
            $queryString = array_filter($orderPaymentDetails, function ($key) {
                return in_array($key, ['gateway', 'merchant_txn_id']);
            }, ARRAY_FILTER_USE_KEY);
            $queryString = http_build_query($queryString);
            $orderPaymentDetails['returnUrl'] = route('user.mess.handle-order-payment-online-callback', $queryString);


            $response = $paymentGateway->createOrder($orderPaymentDetails);
            DB::commit();
            return json_encode($response);

            // Handle the response
            if ($response->status === "OK") {
                return redirect($response->paymentLink);
            } else {
                return back()->withErrors(['error' => $response->message]);
            }
        } catch (\Throwable $th) {
            Session::remove('success');
            DB::rollBack();
            $message = $th->getMessage();
            return view('user-views.Error.errorhandle-page', compact('message'));
        }
    }

    public function placeOrder_via_cashOrWallet(Request $request)
    {
        try {
            $order_id = $request->query('order_id')??null;
            $order = SubscriptionOrderDetails::with(['paymentDetail', 'orderItems'=>function($query){
                return $query->with('package');
            }])->find($order_id);


            if (!$order) {
                throw new \Exception(__('Subscription Package Not Found'));
            }

            $payment_method = 'cash';
            $wallet = filter_var($request->wallet ?? 0, FILTER_VALIDATE_FLOAT);

            $cash = $request->cash ?? 0;
            $onlinePayment = $request->online ?? 0;
            $user = Session::get('userInfo');


            DB::beginTransaction();

             $cash_to_collect = $order->total ?? 0;


            if (round($cash + $wallet) !=  round($order->total)) {
                throw new \Exception('Amount Mismatched');
            }

            if ($onlinePayment > 0) {
                return redirect()->route('onlinepay'); // Set online URL
            } elseif ($wallet > 0) {
                $customerWallet = Wallet::where('customer_id', $user->id)->first();
                $adminFund = AdminFund::getFund();

                if ($wallet > $customerWallet->balance) {  // Checking wallet available balance
                    throw new \Exception('Insufficient Wallet Amount');
                } elseif ($wallet > $order->total) {
                    throw new \Exception('Selected Amount Can\'t be more of Order Amount');
                }
                $customerWallet->balance -= $wallet; // Deducting wallet balance
                $adminFund->balance += $wallet; // Adding it to admin fund
                $cash_to_collect -= $wallet; // Deducting cash to collect by wallet amount
                $payment_method =  $cash > 0 ? 'cash&wallet' : 'wallet';
                $customerWallet->save();
                $adminFund->save();

            }

            $order = self::updateOrderProcess($order, $payment_method, $cash_to_collect, $digitalAmount = 0 );

            if ($wallet > 0) {
                $customerWallet->walletTransactions()->create([
                    'amount' => $wallet,
                    'type' => 'paid',
                    'paid_to' => 'admin',
                    'customer_id' => $user->id,
                    'remarks' => "Amount deducted For the Mess Order No. #{$order->id}",
                ]);

                $adminFund->txns()->create([
                    'amount' => $wallet,
                    'txn_type' => 'received',
                    'received_from' => 'customer',
                    'customer_id' => $user->id,
                    'remarks' => "Amount received from Mr/Mrs. {$user->f_name} wallet for the Mess order no: #{$order->id}",
                ]);

            }
            $order->save();
            DB::commit();
            Session::flash('success', __('messages.order_placed_successfully'));
            return redirect()->route('user.dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function placeOrder_via_onlineOrWallet(Request $request)
    {
        try {
            $order_id = $request->query('order_id')??null;
            $order = SubscriptionOrderDetails::with(['paymentDetail', 'orderItems'=>function($query){
                return $query->with('package');
            }])->find($order_id);


            if (!$order) {
                throw new \Exception(__('Subscription Package Not Found'));
            }

            $payment_method = 'online';
            $wallet = filter_var($request->wallet ?? 0, FILTER_VALIDATE_FLOAT);


            $online= $request->online ?? 0;
            $user = Session::get('userInfo');


            DB::beginTransaction();

             $digitalAmount = $order->total ?? 0;


            if (round($online +$wallet) !=  round($order->total)) {
                throw new \Exception('Amount Mismatched');
            }

            if ($wallet > 0) {
                $customerWallet = Wallet::where('customer_id', $user->id)->first();
                $adminFund = AdminFund::getFund();

                if ($wallet > $customerWallet->balance) {  // Checking wallet available balance
                    throw new \Exception('Insufficient Wallet Amount');
                } elseif ($wallet > $order->total) {
                    throw new \Exception('Selected Amount Can\'t be more of Order Amount');
                }
                $customerWallet->balance -= $wallet; // Deducting wallet balance
                $adminFund->balance += $wallet; // Adding it to admin fund
                $digitalAmount -= $wallet; // Deducting amount to collect by wallet amount
                $payment_method =  $online > 0 ? 'online&wallet' : 'wallet';
                $customerWallet->save();
                $adminFund->save();

            }

            $order = self::updateOrderProcess($order, $payment_method, 0, $digitalAmount);


            if ($wallet > 0) {
                $customerWallet->walletTransactions()->create([
                    'amount' => $wallet,
                    'type' => 'paid',
                    'paid_to' => 'admin',
                    'customer_id' => $user->id,
                    'remarks' => "Amount deducted For the Mess Order No. #{$order->id}",
                ]);

                $adminFund->txns()->create([
                    'amount' => $wallet,
                    'txn_type' => 'received',
                    'received_from' => 'customer',
                    'customer_id' => $user->id,
                    'remarks' => "Amount received from Mr/Mrs. {$user->f_name} wallet for the Mess order no: #{$order->id}",
                ]);

            }
            $order->save();
            DB::commit();
            Session::flash('success', __('messages.order_placed_successfully'));
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return back()->with('error', $e->getMessage());
        }
    }

    public static function updateOrderProcess(SubscriptionOrderDetails $order, $payment_method="cash", $cash_to_collect=0,$digitalAmount =0)
    {

        try {
            $user = Session::get('userInfo');
            DB::beginTransaction();

            $order->paymentDetail->cash_to_collect = $cash_to_collect;

            $order->paymentDetail->method = $payment_method;
            // preg_match($package )
            if (preg_match('/online/i', $payment_method)) {
                $order->paymentDetail->online = $digitalAmount;
            }
            if (preg_match('/cash/i', $payment_method)) {
                $order->paymentDetail->cash = $cash_to_collect;
            }
            if (preg_match('/wallet/i', $payment_method)) {
                $order->paymentDetail->wallet = $order->paymentDetail->total - ($cash_to_collect + $digitalAmount);
            }



            if (strpos($payment_method, 'online') !== false) {
                $order->paymentDetail->status = 'unpaid';
            }

            $order->paymentDetail->status = $cash_to_collect == 0 ? 'paid' : 'unpaid';
            // $order->tax_status = 'excluded';

            // $tax_included = BusinessSetting::where(['key' => 'tax_included'])->first() ?  BusinessSetting::where(['key' => 'tax_included'])->first()->value : 0;
            // if ($tax_included ==  1) {
            //     $order->tax_status = 'included';
            // }

                // self::sendOrderNotification($order, $billing);


            if($order->paymentDetail->save()){
                DB::commit();
                return $order;
            } else {
                throw new \Error(__('messages.failed_to_place_order'));
            }
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            throw $e;
        }
    }

    public function handleOrderPaymentOnlineCallback(Request $request)
    {
        try {

            $user = Session::get('userInfo');
            $paymentGateway = PaymentGatewayFactory::make($request->input('gateway')); // 'cashfree', 'phonepe', etc.

            $response = $paymentGateway->handleCallback($request->all());
            $order = Cache::get('last_order', null);


            if ($response->payment_status == 'success') {
                self::online_txn_sattlement($response, $order);
                Session::flash('success', "Order Placed SuccessFully");
                return redirect()->route('user.dashboard');
            } elseif ($response->payment_status == 'failed') {
                throw new \Error($response->responseCode);
            } elseif ($response->payment_status == 'pending') {
                return response()->route('user.dashboard')->with('error', 'Process Pending Please Contact Our Support Team');
            }
        } catch (\Throwable $th) {

            // dd($th);
            Session::remove('success');
            $message = $th->getMessage();
            return view('user-views.Error.errorhandle-page', compact('message'));
        }
    }

    public static function online_txn_sattlement($paidTxn, $order)
    {
        try {
            $order = Order::find($order->id);
            $user = Session::get('userInfo');
            $amount = $paidTxn->amount;


            $adminFundRemarks =  Helpers::format_currency($amount) . " Received From {$user->f_name}, For the Mess Order no: #{$order->id} , Transaction No : {$paidTxn->txn_id} , using " . strtoupper($paidTxn->gateway);

            $adminFund = AdminFund::getFund();
            $adminFund->balance += $amount ; // Adding it to admin fund

            $adminFund->txns()->create([
                'amount' => $amount,
                'txn_type' => 'received',
                'received_from' => 'customer',
                'customer_id' => $user->id,
                'remarks' => $adminFundRemarks,
            ]);
            $adminFund->save();

            $order->payment_status = 'paid';
            $order->save();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function paymentOptions(Request $request)
    {
        $now = Carbon::now();
        $user = Session::get('userInfo');
        $order = SubscriptionOrderDetails::find($request->query('order_id'));
        // dd($billing);
        if($order->total == 0){
            $order->delete();
            return redirect(route('user.dashboard'));
        }
        $wallet = Wallet::firstOrCreate(['customer_id' => $user->id]);
        // dd($wallet);
        return view('user-views.mess.payment-options', compact('order', 'wallet'));
    }

    public function list ($status)
    {
        $customer = Auth::guard('customer')->user();
        // $mess = VendorMess::find(2);
        $orders = SubscriptionOrderDetails::with(['paymentDetail', 'orderItems'])
            ->when($status == 'confirmed', function ($query) {
                return $query->where('status', 'confirmed');
            })
            ->when($status == 'pending', function ($query) {
                return $query->where('status', 'pending');
            })
            ->when($status == 'canceled', function ($query) {
                return $query->where('status', 'canceled');
            })
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();
        // $orders = SubscriptionOrderDetails::where('status', $status)->get();

        $status = __('messages.'.$status);
        // dd($orders);
        return view('user-views.mess.orders.list', compact('orders', 'status','customer'));
        // Helpers::format_time()
        // Subscription::find()
    }

    public function myorder($order_id)
    {
        $order = SubscriptionOrderDetails::with(['paymentDetail', 'orderItems'=>function($query){
            return $query->with('package');
        }])->find($order_id);
        // dd($order);
        if(!$order)
        {
            return back()->with('warning', "No Order Available Now");;
        }
        return view('user-views.mess.orders.myorder', compact('order'));
    }

}
