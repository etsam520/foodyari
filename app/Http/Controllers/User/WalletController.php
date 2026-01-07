<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\GatewayPayment;
use App\Models\Wallet;
use App\Services\Payments\PaymentGatewayFactory;
use Carbon\Carbon;
use Illuminate\Cache\RedisTagSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class WalletController extends Controller
{
    public function index()
    {
        $customer = Session::get('userInfo');
        $wallet = Wallet::where('customer_id', $customer->id)->first();
        if (!$wallet) {
            $wallet = Wallet::create([
                'balance' => 0,
                'customer_id' => $customer->id
            ]);
        }

        return view('user-views.payments.wallet.index', compact('wallet'));
    }

    public function histories()
    {
        $customer = Session::get('userInfo');

        $mywallet = Wallet::where('customer_id', $customer->id)
            ->with(['WalletTransactions' => function ($query) use ($customer) {
                $query->where('customer_id', $customer->id)->orderBy('created_at', 'DESC');
            }])
            ->first();
        $walletTransactions = $mywallet->WalletTransactions()->paginate(20);

        return view('user-views.payments.wallet.history', compact('mywallet', 'walletTransactions'));
    }

    public function topUP(Request $request)
    {

        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'gateway' => 'required_if:amount,notnull|in:phonepe,gpay,paytm'
            ]);
            $user = Session::get('userInfo');
            $wallet = Wallet::where('customer_id', $user->id)->first();

            if (!$wallet) {
                Wallet::create([
                    'customer_id' => $user->id
                ]);
            }
            $orderDetails = [
                'merchant_txn_id' =>  call_user_func_array(
                    config('payment.merchant_txn_id'),
                    [$request->input('gateway'), 'W']
                ),
                'amount' => $request->input('amount'),
                'email' => $user->email,
                'phone' => $user->phone,
                'gateway' => $request->input('gateway'),
            ];
            DB::beginTransaction();
            GatewayPayment::create([
                'amount' => $request->input('amount'),
                'merchant_txn_id' => $orderDetails['merchant_txn_id'],
                'gateway' => $orderDetails['gateway'],
                'assosiate' => 'customer',
                'assosiate_id' => $user->id,
                'payload' => json_encode($orderDetails),
                'details' => null,
            ]);

            $paymentGateway = PaymentGatewayFactory::make($request->input('gateway')); // 'cashfree', 'phonepe', etc.
            $queryString = array_filter($orderDetails, function ($key) {
                return in_array($key, ['gateway', 'merchant_txn_id']);
            }, ARRAY_FILTER_USE_KEY);
            $queryString = http_build_query($queryString);
            $orderDetails['returnUrl'] = route('user.wallet.top-up-handle', $queryString);


            // Create the order using the payment gateway
            $response = $paymentGateway->createOrder($orderDetails);
            DB::commit();

            // Handle the response
            if ($response->status === "OK") {
                return redirect($response->paymentLink);
            } else {
                return back()->withErrors(['error' => $response->message]);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = $th->getMessage();
            return view('user-views.Error.errorhandle-page', compact('message'));
        }
    }

    public function topUPHandle(Request $request)
    {
        try {

            $user = Session::get('userInfo');
            $paymentGateway = PaymentGatewayFactory::make($request->input('gateway')); // 'cashfree', 'phonepe', etc.

            $response = $paymentGateway->handleCallback($request->all());
            if ($response->payment_status == 'success') {
                self::topUP_txn_sattlement($response);
                if (Cookie::has('cart')) {
                    return redirect()->route('user.restaurant.check-out');
                }
                return redirect()->route('user.wallet.get');
            } elseif ($response->payment_status == 'failed') {
                throw new \Error($response->responseCode);
            } elseif ($response->payment_status == 'pending') {
                return response()->route('user.wallet.history')->with('error', 'Process Pending Please Contact Our Support Team');
            }
        } catch (\Throwable $th) {

            // dd($th);
            $message = $th->getMessage();
            return view('user-views.Error.errorhandle-page', compact('message'));
        }
    }

    public static function topUP_txn_sattlement($paidTxn)
    {
        try {

            $user = Session::get('userInfo');
            $amount = $paidTxn->amount;

            DB::beginTransaction();
            $customerWallet = Wallet::where('customer_id', $user->id)->first();
            $adminFund = AdminFund::getFund();

            $customerWallet->balance += $amount; // Adding wallet balance
            $adminFund->balance += $amount; // Adding it to admin fund


            $customerWallet->walletTransactions()->create([
                'amount' => $amount,
                'type' => 'received',
                'customer_id' => $user->id,
                'remarks' => "Top-Up : " . Helpers::format_currency($amount) . " Added, Transaction No : {$paidTxn->txn_id} , using " . strtoupper($paidTxn->gateway),
            ]);

            $adminFund->txns()->create([
                'amount' => $amount,
                'txn_type' => 'received',
                'received_from' => 'customer',
                'customer_id' => $user->id,
                'remarks' => Helpers::format_currency($amount) . " Received of Top Up From {$user->f_name}, Transaction No : {$paidTxn->txn_id} , using " . strtoupper($paidTxn->gateway),
            ]);
            $customerWallet->save();
            $adminFund->save();


            DB::commit();
            $message =  __(Helpers::format_currency($amount) . ' Top-UP Done');
            Session::flash('success', $message);
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
}
