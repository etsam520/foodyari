<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use App\Models\DeliveryManCashInHand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CashController extends Controller
{
    public function _histories()
    {
        $deliveryman =Session::get('deliveryMan');
        $cashInHand = DeliveryManCashInHand::where('deliveryman_id', $deliveryman->id)->first();
        $cashTxns = $cashInHand->cashTxns()->latest()->get();
        dd($cashTxns->toArray());
        return view('deliveryman.cash.history', compact('cashInHand', 'cashTxns'));

    }

    public function histories(Request $request)
    {
        $deliveryman = Session::get('deliveryMan');
        $cashInHand = DeliveryManCashInHand::where('deliveryman_id', $deliveryman->id)->first();

        if (!$cashInHand) {
            return redirect()->back()->with('error', 'No cash transactions found.');
        }

        $query = $cashInHand->cashTxns()->latest();

        // Filtering based on user selection (day, month, year)
        $filterType = $request->query('filter', 'day'); // Default: By Day

        if ($filterType === 'day') {
            $cashTxns = $query->whereDate('created_at', Carbon::today())->get();
        } elseif ($filterType === 'month') {
            $cashTxns = $query->whereMonth('created_at', Carbon::now()->month)
                            ->whereYear('created_at', Carbon::now()->year)
                            ->get();
        } elseif ($filterType === 'year') {
            $cashTxns = $query->whereYear('created_at', Carbon::now()->year)->get();
        } else {
            $cashTxns = $query->get(); // No filter applied
        }

        // Group transactions by date
        $groupedTransactions = $cashTxns->groupBy(function ($txn) {
            return Carbon::parse($txn->created_at)->format('Y-m-d');
        });

        // Process transactions: add if "received", subtract if "paid"
        $formattedDataTxns = $groupedTransactions->map(function ($transactions, $date) {
            $total = 0;

            foreach ($transactions as $txn) {
                if ($txn->txn_type === 'received') {
                    $total += $txn->amount; // Add amount
                } elseif ($txn->txn_type === 'paid') {
                    $total -= $txn->amount; // Deduct amount
                }
            }
            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $transactions
            ];
        });

        $fomattedSettlementTxns = $groupedTransactions->map(function ($transactions, $date) {
            $total = 0;

            $new_arr = array_filter($transactions->toArray(), function ($txn) use (&$total) {
                if($txn['txn_type'] === 'paid') {
                    $total -= $txn['amount']; // Deduct amount
                    return $txn['txn_type'] === 'paid';
                }
            });

            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $new_arr
            ];
        });

        return view('deliveryman.cash.history', compact('cashInHand', 'formattedDataTxns','fomattedSettlementTxns', 'filterType'));
    }
}
