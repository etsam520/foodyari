<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{

    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));
        return back();
    }

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
        $transactions = OrderTransaction::with('order')->where('restaurant_id',Session::get('restaurant')->id)
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
        return view('vendor-views.report.order', compact('transactions','from','to','filter'));
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
        $transactions = OrderTransaction::with('order')->where('restaurant_id',Session::get('restaurant')->id)
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
        return view('vendor-views.report.tax', compact('transactions','from','to','filter'));
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
        $transactionsOrderIds = OrderTransaction::where('restaurant_id',Session::get('restaurant')->id)
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
        })
        ->orderBy('created_at', 'desc')
        ->pluck('order_id')
        ->toArray();


        $productsWithOrderDetails = OrderDetail::with('order')  // Eager load the related 'order' model
        ->whereIn('order_id', $transactionsOrderIds)  // Filter by the order IDs
        ->get()  // Fetch all records
        ->groupBy('food_id');
        // dd($productsWithOrderDetails);



        $filterdResult = self::getFoodReport_process_func($productsWithOrderDetails);
        $productItems = $filterdResult['productItems'];
        $sumtotal = $filterdResult['sumtotal'];
        $sumquantity = $filterdResult['sumquantity'];
        $sumcost = $filterdResult['sumcost'];


        return view('vendor-views.report.product', compact('productItems','sumtotal','sumquantity','sumcost','filter'));
    }

    public static function get_varient(array $product_variations, array $variations)
    {
        $result = [];

        foreach($variations as $k=> $variation){
            foreach($product_variations as  $product_variation){
                if( isset($variation['values'])  && isset($product_variation->values) && $product_variation->name == $variation['option']  ){
                    // dd($product_variation);
                    foreach($product_variation->values as  $option){
                        foreach($variation['values'] as &$value){
                            if($option->label == $value['label']){
                                $value['optionPrice'] = $option->optionPrice;
                                $value['total'] = ($option->optionPrice * $value['qty']);
                                break;
                            }

                        }

                    }
                }
                $result[$k] = $variation;
            }

        }
        return $result;
    }

    public static function getFoodReport_process_func($productsWithOrderDetails){
        $nonVariationDetails = [];
        $variationDetails = [];
        $addonDetails = [];
        $productItems = [];

        // dd($productsWithOrderDetails);
        foreach ($productsWithOrderDetails as $key => $ordersDetails) {
            foreach ($ordersDetails as $key => $orderDetails) {
                $foodDetails = json_decode($orderDetails->food_details);
                $foodVariations = json_decode($foodDetails->variations);

                $addons = json_decode($orderDetails->add_ons);

                if($foodVariations != null){
                    // Process ordered variations
                    $orderedVarations = json_decode($orderDetails->variation, true);
                    $orderedVarations = self::get_varient($foodVariations, $orderedVarations);

                    foreach ($orderedVarations as $ord_variation) {
                        // Normalize the name (case-insensitive and fix typos if needed)
                        $normalizedName = ucfirst(strtolower(trim($ord_variation['option'])));

                        // Find if the variation already exists
                        $names = array_column($variationDetails, 'name');
                        $optionValues = [];

                        if (($index = array_search($normalizedName, $names)) !== false) {
                            // If the variation name exists, get its values
                            $optionValues = $variationDetails[$index]['values'];
                        } else {
                            // If not, add a new variation for the ordered one
                            $variationDetails[] = ['name' => $normalizedName, 'values' => []];
                            $index = count($variationDetails) - 1;  // Get the index of the newly added variation
                        }

                        // Process each value of the ordered variation
                        foreach ($ord_variation['values'] as $value) {
                            $existingOptionIndex = false;

                            // Check if this option already exists in optionValues by matching both label and optionPrice
                            foreach ($optionValues as $optIndex => $option) {
                                if ($option['label'] === $value['label'] && $option['optionPrice'] == $value['price']) {
                                    $existingOptionIndex = $optIndex;
                                    break;
                                }
                            }

                            // If a match is found, update the quantity and total
                            if ($existingOptionIndex !== false) {
                                $optionValues[$existingOptionIndex]['quantity'] += $value['qty'];
                                $optionValues[$existingOptionIndex]['total'] += ($value['price'] * $value['qty']);
                            } else {
                                // Otherwise, add a new entry
                                $optionValues[] = [
                                    'optionPrice' => $value['price'],
                                    'label' => $value['label'],
                                    'foodname' => ucfirst($foodDetails->name) . ' (' . $value['label'] . ')',
                                    'quantity' => $value['qty'],
                                    'total' => ($value['price'] * $value['qty']),
                                ];
                            }
                        }

                        // Update the variationDetails with the new option values
                        $variationDetails[$index]['values'] = $optionValues;
                    }
                }else{
                    $optionValues = [];
                    $names = array_column($nonVariationDetails, 'name');
                    $normalizedName = ucfirst(strtolower(trim($foodDetails->name)));
                    if (($index = array_search($normalizedName, $names)) !== false) {
                        // If the variation name exists, get its values
                        $optionValues = $nonVariationDetails[$index]['values'];
                    } else {
                        // If not, add a new variation for the ordered one
                        $nonVariationDetails[] = ['name' => $normalizedName, 'values' => []];
                        $index = count($nonVariationDetails) - 1;  // Get the index of the newly added variation
                    }


                    $existingOptionIndex = false;

                    // Check if this option already exists in optionValues by matching both label and optionPrice
                    $optionPrice =  $foodDetails->restaurant_price??0 ;
                    foreach ($optionValues as $optIndex => $option) {
                        if($optionPrice > 0){
                            if ($option['label'] === $foodDetails->name && $option['optionPrice'] == $optionPrice) {
                                $existingOptionIndex = $optIndex;
                                break;
                            }
                        }
                    }

                    // If a match is found, update the quantity and total
                    if ($existingOptionIndex !== false) {
                        $optionValues[$existingOptionIndex]['quantity'] += $orderDetails->quantity;
                        $optionValues[$existingOptionIndex]['total'] += ($optionPrice * $orderDetails->quantity);
                    } else {
                        // Otherwise, add a new entry
                        if($optionPrice > 0){
                            $optionValues[] = [
                                'optionPrice' => $optionPrice,
                                'label' => $foodDetails->name,
                                'foodname' => ucfirst($foodDetails->name),
                                'quantity' => $orderDetails->quantity,
                                'total' => ($optionPrice * $orderDetails->quantity),
                            ];
                        }
                    }
                    $nonVariationDetails[$index]['values'] = $optionValues;
                }

                if($addons != null){
                    foreach($addons as $addon){
                        $optionValues = [];
                        $names = array_column($addonDetails, 'name');
                        $normalizedName = ucfirst(strtolower(trim($addon->name)));
                        if (($index = array_search($normalizedName, $names)) !== false) {
                            // If the variation name exists, get its values
                            $optionValues = $addonDetails[$index]['values'];
                        } else {
                            // If not, add a new variation for the ordered one
                            $addonDetails[] = ['name' => $normalizedName, 'values' => []];
                            $index = count($addonDetails) - 1;  // Get the index of the newly added variation
                        }


                        $existingOptionIndex = false;

                        // Check if this option already exists in optionValues by matching both label and optionPrice
                        foreach ($optionValues as $optIndex => $option) {
                            if ($option['label'] === $addon->name && $option['optionPrice'] == $addon->price) {
                                $existingOptionIndex = $optIndex;
                                break;
                            }
                        }

                        // If a match is found, update the quantity and total
                        if ($existingOptionIndex !== false) {
                            $optionValues[$existingOptionIndex]['quantity'] += $addon->qty;
                            $optionValues[$existingOptionIndex]['total'] += ($addon->price * $addon->qty);
                        } else {
                            // Otherwise, add a new entry
                            $optionValues[] = [
                                'optionPrice' => $addon->price,
                                'label' => $addon->name,
                                'foodname' => ucfirst($addon->name).' (addons)',
                                'quantity' => $addon->qty,
                                'total' => ($addon->price * $addon->qty),
                            ];
                        }
                        $addonDetails[$index]['values'] = $optionValues;
                    }


                    // dd($addonDetails);
                }

            }
        }

        $mergedDetails = array_merge($nonVariationDetails, $variationDetails, $addonDetails);
        $sumtotal=$sumquantity=$sumcost = 0;
        foreach($mergedDetails as $details){
            foreach($details['values'] as $d_value){
                $sumcost += (float) $d_value['optionPrice'];
                $sumquantity += (int) $d_value['quantity'];
                $sumtotal += (float) $d_value['total'];
                $productItems[] = $d_value;
            }
        }

        return ['productItems'=> $productItems, 'sumtotal' => $sumtotal, 'sumquantity' => $sumquantity, 'sumcost' =>$sumcost  ];
    }




    // public function expense_export(Request $request)
    // {
    //     $from =  null;
    //     $to = null;
    //     $filter = $request->query('filter', 'all_time');
    //     if($filter == 'custom'){
    //         $from = $request->from ?? null;
    //         $to = $request->to ?? null;
    //     }
    //     $key = explode(' ', $request['search']);
    //     $expense = Expense::with('order')->where('created_by','vendor')->where('restaurant_id',Helpers::get_restaurant_id())
    //     ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
    //         return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
    //     })
    //     ->when(isset($filter) && $filter == 'this_year', function ($query) {
    //         return $query->whereYear('created_at', now()->format('Y'));
    //     })
    //     ->when(isset($filter) && $filter == 'this_month', function ($query) {
    //         return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
    //     })
    //     ->when(isset($filter) && $filter == 'this_month', function ($query) {
    //         return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
    //     })
    //     ->when(isset($filter) && $filter == 'previous_year', function ($query) {
    //         return $query->whereYear('created_at', date('Y') - 1);
    //     })
    //     ->when(isset($filter) && $filter == 'this_week', function ($query) {
    //         return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
    //     })
    //     ->when( isset($key), function($query) use($key){
    //         $query->where(function ($q) use ($key) {
    //             foreach ($key as $value) {
    //                 $q->orWhere('type', 'like', "%{$value}%")->orWhere('order_id', 'like', "%{$value}%");
    //             }
    //         });
    //     })
    //     ->orderBy('created_at', 'desc')
    //     ->get();

    //     if ($request->type == 'excel') {
    //         return (new FastExcel(Helpers::export_expense_wise_report($expense)))->download('ExpenseReport.xlsx');
    //     } elseif ($request->type == 'csv') {
    //         return (new FastExcel(Helpers::export_expense_wise_report($expense)))->download('ExpenseReport.csv');
    //     }
    // }

}
