<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Models\GatewayPayment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentHistoryController extends Controller
{
    public function online(Request $request)
    {

        $status = $request->status??null;
        $user = Session::get('userInfo');
        $txns = GatewayPayment::where('assosiate', 'customer')
        ->when(isset($status), function ($query) use($status){
           return $query->where('payment_status',$status);
        })
        ->where('assosiate_id',$user->id)
        ->latest()->paginate(20);
        // dd($txns);
        if($txns){
            return view('user-views.payments.online.history',compact('txns'));
        }else{
            return back()->with('waring', 'No Transaction Found');
        }

    }

    public function cash(Request $request)
    {
        $status = $request->status??null;
        $user = Session::get('userInfo');
        $txns = CashTransaction::where('assosiate', 'customer')
        ->when(isset($status), function ($query) use($status){
           return $query->where('payment_status',$status);
        })
        ->where('customer_id',$user->id)
        ->latest()->paginate(20);
        // dd($txns);
        if($txns){
            return view('user-views.payments.online.history',compact('txns'));
        }else{
            return back()->with('waring', 'No Transaction Found');
        }
    }

    public function histories(Request $request)
    {
        // dd($request->json());
        $txn_method = $request->json('txn_method', true);
        $txn_type = $request->json('txn_type', true);

        $cashTxns = [];
        $WalletTxns = [];
        $OnlineTxns = [];
        $referalTxns = [];

        if($txn_method['all']){
            $cashTxns = self::cash_txns($txn_type);
            $OnlineTxns = self::online_txns($txn_type);
            $WalletTxns = self::wallet_txns($txn_type);
        }elseif($txn_method['cash']){
            $cashTxns = self::cash_txns($txn_type);
        }elseif($txn_method['online']){
            $OnlineTxns = self::online_txns($txn_type);
        }elseif ($txn_method['wallet']) {
            $WalletTxns = self::wallet_txns($txn_type);
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

    private static function cash_txns($txn_type){
        $user = Session::get('userInfo');
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

    private static function online_txns($txn_type)
    {

        $user = Session::get('userInfo');
        $online_txns = GatewayPayment::where('assosiate', 'customer')
        ->where('payment_status','success')
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

    private static function wallet_txns($txn_type){
        $customer = Session::get('userInfo');

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

}
