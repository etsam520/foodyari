<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WalletController extends Controller
{
    public function index()

    {
        $deliveryman =Session::get('deliveryMan');
        // dd($deliveryman);
        $mywallet = Wallet::where('deliveryman_id', $deliveryman->id)->first();
       if(!$mywallet){
            $newWallet = new Wallet();
            $newWallet->balance = 0;
            $newWallet->deliveryman_id = $deliveryman->id;
            if($newWallet->save()){
                Session::flash('success', 'New Wallet Created');
            }
            $mywallet = $newWallet;

        }
        return view('deliveryman.mywallet.index',compact('mywallet'));
    }



    public function histories(Request $request)
    {
        $deliveryman = Session::get('deliveryMan');

        $mywallet = Wallet::where('deliveryman_id', $deliveryman->id)
            ->with(['WalletTransactions' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }])
            ->first();

        if (!$mywallet) {
            return redirect()->back()->with('error', 'No wallet transactions found.');
        }

        // Filtering based on user selection (day, month, year)
        $filterType = $request->query('filter', 'day'); // Default: By Day

        $query = $mywallet->WalletTransactions()->latest();

        if ($filterType === 'day') {
            $walletTransactions = $query->whereDate('created_at', Carbon::today())->get();
        } elseif ($filterType === 'month') {
            $walletTransactions = $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->get();
        } elseif ($filterType === 'year') {
            $walletTransactions = $query->whereYear('created_at', Carbon::now()->year)->get();
        } else {
            $walletTransactions = $query->get(); // No filter applied
        }

        // Group transactions by date
        $groupedTransactions = $walletTransactions->groupBy(function ($txn) {
            return Carbon::parse($txn->created_at)->format('Y-m-d');
        });

        // Process transactions: add if "received", subtract if "paid"
        $formattedData = $groupedTransactions->map(function ($transactions, $date) {
            $total = 0;

            foreach ($transactions as $txn) {
                if ($txn->type === 'received') {
                    $total += $txn->amount; // Add amount
                } else {
                    $total -= $txn->amount; // Deduct amount
                }
            }

            return [
                'date' => $date,
                'total' => $total,
                'transactions' => $transactions
            ];
        });

        // dd($formattedData->toArray());

        return view('deliveryman.mywallet.history', compact('mywallet', 'formattedData', 'filterType'));
    }

}
