<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminFundController extends Controller
{
    public function index()
    {
        $admin = Helpers::getAdmin();
        $fund = AdminFund::getFund();

        if (!isset($fund->fund_type)) {
            $fund =  AdminFund::create([
                'balance' => 0,
                'fund_tupe' => 'admin',
            ]);
            Session::flash('success', 'New Fund Created');

        }
        return view('admin-views.fund.index', compact('fund'));
    }

    public function histories(Request $request)
    {
        $admin = Helpers::getAdmin();
        $fund = AdminFund::getFund();
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

        if ($fund) {
            $fundTxns = $fund->txns()
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
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
            })->orderBy('created_at','desc')->get();
        } else {
            $fundTxns = collect();
        }

        return view('admin-views.fund.history', compact('fund', 'fundTxns','from','to','filter'));
    }
}
