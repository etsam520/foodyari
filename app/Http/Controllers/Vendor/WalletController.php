<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Support\Facades\Session;

class WalletController extends Controller
{
    public function index()

    {
        $restaurant = Session::get('restaurant');
        $mywallet = Wallet::where('vendor_id', $restaurant->vendor_id)->first();
       if(!$mywallet){
            $newWallet = new Wallet();
            $newWallet->balance = 0;
            $newWallet->vendor_id = $restaurant->vendor_id;
            if($newWallet->save()){
                Session::flash('success', 'New Wallet Created');
            }
            $mywallet = $newWallet;

        }
        return view('vendor-views.mywallet.index',compact('mywallet'));
    }

    public function histories(Request $request)
    {
        $restaurant = Session::get('restaurant');
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $dateRange = $request->date_range;
            if($dateRange == null){
                return back()->with('info', "Date range can\'t be null");
            }
            $dates = explode(" to ", $dateRange);

            $from = $dates[0]??null;
            $to = $dates[1]??null;
        }
        $key = explode(' ', $request['search']);


        $mywallet = Wallet::where('vendor_id', $restaurant->vendor_id)
            ->with(['WalletTransactions' => function ($query) use($restaurant) {
                $query->where('restaurant_id',$restaurant->id)->orderBy('created_at', 'DESC');
            }])
            ->first();
        $walletTransactions = $mywallet->WalletTransactions()->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })
        ->when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('created_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'this_month', function ($query) {
            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'this_month', function ($query) {
            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        })
        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
            return $query->whereYear('created_at', date('Y') - 1);
        })
        ->when(isset($filter) && $filter == 'this_week', function ($query) {
            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        })
        ->when(isset($filter) && $filter == 'today', function ($query) {
            return $query->whereDate('created_at', now()->toDateString());
        })->orderBy('created_at', 'desc')->get();

        return view('vendor-views.mywallet.history', compact('mywallet', 'walletTransactions', 'from','to','filter'));
    }

}
