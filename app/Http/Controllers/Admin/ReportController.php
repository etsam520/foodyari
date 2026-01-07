<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\ReportLogic;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Food as FoodModel ;
use App\Models\OrderTransaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{


    public function order_report(Request $request)
    {
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
        $transactions = OrderTransaction::with('order')
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
        })
        ->when( isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%");
                }
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();
        // dd($transactions[0]);
        // dd($transactions[0]['admin_data']);
        // dd(json_decode($transactions[0]['admin_data']),
        //     json_decode($transactions[0]['customer_data']),
        //     json_decode($transactions[0]['restaurant_data']),
        //     $transactions[0]['order']);
        return view('admin-views.report.order', compact('transactions','from','to','filter'));
    }

    public function product_report(Request $request){
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');

        if($filter == 'custom'){
            $dateRange = $request->date_range;
            if($dateRange == null){
                return redirect()->route('vendor.report.product')->with('info', "Date range can\'t be null");
            }
            $dates = explode(" to ", $dateRange);

            $from = $dates[0]??null;
            $to = $dates[1]??null;
        }
        $key = explode(' ', $request['search']);
        $transactionsOrder = OrderTransaction::with('order')
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
        })->when(isset($filter) && $filter == 'today', function ($query) {
            return $query->whereDate('created_at', now()->toDateString());
        })
        ->when(isset($filter) && $filter == 'this_week', function ($query) {
            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        })
        ->when( isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%");
                }
            });
        })->orderBy('created_at', 'desc')
        ->get();

        $transactionsOrderIds = $transactionsOrder->pluck('order_id')->toArray();

        $productsWithOrderDetails = OrderDetail::with('order')  // Eager load the related 'order' model
        ->whereIn('order_id', $transactionsOrderIds)  // Filter by the order IDs
        ->get()  // Fetch all records
        ->groupBy('food_id');

        $filterdResult = ReportLogic::getFoodReport_process_func($productsWithOrderDetails);
        $productItems = $filterdResult['productItems'];
        $sumtotal = $filterdResult['sumtotal'];
        $sumquantity = $filterdResult['sumquantity'];
        $sumcost = $filterdResult['sumcost'];

        return view('admin-views.report.product', compact('productItems','sumtotal','sumquantity','sumcost','filter'));

    }

    public function tax_report(Request $request)
    {
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
        $transactions = OrderTransaction::with('order')
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
        })
        ->when( isset($key), function($query) use($key){
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%");
                }
            });
        })
        ->orderBy('id', 'desc')
        ->get();
        return view('admin-views.report.tax', compact('transactions','from','to','filter'));
    }




}
