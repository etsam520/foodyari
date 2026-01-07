<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Admin\appartus\ZoneHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\DeliveryManJoineeForm;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Restaurant;
use App\Models\RestaurantJoineeForm;
use App\Models\Review;
use App\Models\VendorMess;
use Carbon\Carbon;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use ParagonIE\Sodium\Core\Curve25519\H;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // $user = null;
        // $isStaff = !Helpers::isAdmin();
        // if($isStaff)
        //     $user = Helpers::getStaff();
        // else
        //     $user = Helpers::getAdmin();

        // ZoneHelper::setOrderZone($user->zone_id ?? 'all');
        // $zone = ZoneHelper::getOrderZone();

        


        // // return $user;
        // $from =  null;
        // $to = null;
        // $filter = $request->query('filter', 'this_month');

        // if($filter == 'custom'){
        //     $dateRange = $request->date_range;
        //     if($dateRange == null){
        //         return redirect()->route('vendor.report.product')->with('info', "Date range can\'t be null");
        //     }
        //     $dates = explode(" to ", $dateRange);

        //     $from = $dates[0]??null;
        //     $to = $dates[1]??null;
        // }
        // $key = explode(' ', $request['search']);
        // $transactionsOrder = OrderTransaction::with('order.restaurant')
        // ->when($isStaff, function($query) use($user){
        //     return $query->whereHas('order', function($q) use($user){
        //         $q->where('zone_id', $user->zone_id);
        //     });
        // })
        // ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
        //     return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        // })
        // ->when(isset($filter) && $filter == 'this_year', function ($query) {
        //     return $query->whereYear('created_at', now()->format('Y'));
        // })
        // ->when(isset($filter) && $filter == 'this_month', function ($query) {
        //     return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        // })
        // ->when(isset($filter) && $filter == 'this_month', function ($query) {
        //     return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        // })
        // ->when(isset($filter) && $filter == 'previous_year', function ($query) {
        //     return $query->whereYear('created_at', date('Y') - 1);
        // })->when(isset($filter) && $filter == 'today', function ($query) {
        //     return $query->whereDate('created_at', now()->toDateString());
        // })
        // ->when(isset($filter) && $filter == 'this_week', function ($query) {
        //     return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        // })
        // ->when( isset($key), function($query) use($key){
        //     $query->where(function ($q) use ($key) {
        //         foreach ($key as $value) {
        //             $q->orWhere('order_id', 'like', "%{$value}%");
        //         }
        //     });
        // })
        // ->orderBy('created_at', 'desc')
        // ->get();

    $user = null;
    $isStaff = !Helpers::isAdmin();
    if ($isStaff)
        $user = Helpers::getStaff();
    else
        $user = Helpers::getAdmin();

    ZoneHelper::setOrderZone($user->zone_id ?? 'all');
    $zone = ZoneHelper::getOrderZone();

    $from = null;
    $to = null;
    $filter = $request->query('filter', 'this_month');

    // Use start_date and end_date for custom filter
    if ($filter == 'custom') {
        $from = Helpers::parseDateToFormat($request->query('start_date'),inputFormat: 'd-m-Y', outputFormat: 'Y-m-d');
        $to = Helpers::parseDateToFormat($request->query('end_date'),inputFormat: 'd-m-Y', outputFormat: 'Y-m-d');
        if (!$from || !$to) {
            return redirect()->route('vendor.report.product')->with('info', "Start and End date can't be null");
        }
    }

    $key = explode(' ', $request['search']);
    $transactionsOrder = OrderTransaction::with('order.restaurant')
        ->when($isStaff, function ($query) use ($user) {
            return $query->whereHas('order', function ($q) use ($user) {
                $q->where('zone_id', $user->zone_id);
            });
        })
        ->when($filter == 'custom' && $from && $to, function ($query) use ($from, $to) {
            return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
        })
        ->when($filter == 'this_year', function ($query) {
            return $query->whereYear('created_at', now()->format('Y'));
        })
        ->when($filter == 'this_month', function ($query) {
            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
        })
        ->when($filter == 'previous_year', function ($query) {
            return $query->whereYear('created_at', date('Y') - 1);
        })
        ->when($filter == 'today', function ($query) {
            return $query->whereDate('created_at', now()->toDateString());
        })
        ->when($filter == 'this_week', function ($query) {
            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
        })
        ->when(isset($key), function ($query) use ($key) {
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('order_id', 'like', "%{$value}%");
                }
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
        $earning = 0;
        $collection = 0;
        $topRestaurant = [];

        foreach ($transactionsOrder as $txn) {
            $restaurantId = $txn->order->restaurant_id;

            // Aggregate restaurant revenue and other details in a single step
            if (!isset($topRestaurant[$restaurantId])) {
                // Initialize the restaurant details if not already present
                $topRestaurant[$restaurantId] = [
                    'name' => $txn->order->restaurant->name,
                    'revenue' => 0,
                    'orders' => 0
                ];
            }

            // Increment revenue for the restaurant
            $topRestaurant[$restaurantId]['revenue'] += $txn->admin_earning;
            $topRestaurant[$restaurantId]['orders'] ++;

            // Update total collection and earnings
            $collection += $txn->order_amount;
            $earning += $txn->admin_earning;
        }

        // Optional: If you need the restaurants sorted by revenue in descending order
        uasort($topRestaurant, function ($a, $b) {
            return $b['orders'] <=> $a['orders'];
        });
        $count = [
            'totalOrders' =>  count($transactionsOrder),
            'currentOrder' => Order::whereDate('created_at', Carbon::today())->when($isStaff, function($query) use($user){
                return $query->where('zone_id', $user->zone_id);
            })->count(),
            'scheduledOrders' => Order::where('order_status', 'scheduled')->when($isStaff, function($query) use($user){
                return $query->where('zone_id', $user->zone_id);
            })->count(),
            'restaurants' => Restaurant::when($isStaff, function($query) use($user){
                return $query->where('zone_id', $user->zone_id);
            })->count(),
            'messes' => VendorMess::count(),
            'customers' => Customer::count(),
            'collection' => $collection ,
            'earning' => $earning ,
        ];

        if (isset($_COOKIE['My_FCM_Token'])) {
            $admin = Admin::find(auth('admin')->id());
            if ($admin) {
                $admin->fcm_token = $_COOKIE['My_FCM_Token'];
                $admin->save();
                setcookie('mqtt_client_admin_id', $admin->id, time() + (60*60*30* 60), "/");
            }
        }


        $oneWeekAgo = Carbon::now()->subDays(7);

        // Fetch pending restaurant signups from the last 7 days (limit 5)
        $restaurantSignUp = RestaurantJoineeForm::where('status', 'pending')
            ->where('created_at', '>=', $oneWeekAgo)
            ->latest()
            ->limit(5)
            ->get();

        // Fetch pending delivery boy signups from the last 7 days
        $deliveryBoySignUp = DeliveryManJoineeForm::where('status', 'pending')
            ->where('created_at', '>=', $oneWeekAgo)
            ->latest()
            ->get();
        $reviews = Review::with(['customer','deliveryman','restaurant'])->latest()->limit(3)->get();
        
        // Chart data for orders, payments, ratings, and earnings
        try {
            $chartData = $this->getChartsData($filter, $from, $to, $isStaff, $user);
        } catch (\Exception $e) {
            // Fallback chart data if there's an error
            $chartData = [
                'orders' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [12, 19, 3, 5, 2, 3]
                ],
                'payments' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [1200, 1900, 300, 500, 200, 300]
                ],
                'ratings' => [
                    'labels' => ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                    'data' => [5, 10, 15, 25, 45]
                ],
                'earnings' => [
                    'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    'data' => [120, 190, 30, 50, 20, 30]
                ]
            ];
            Log::error('Chart data generation failed: ' . $e->getMessage());
        }

        return view('admin-views.dashboard', compact('count','filter','topRestaurant','restaurantSignUp','deliveryBoySignUp'
            ,'reviews', 'chartData'));

    }


    public function profile(Request $request)
    {
        try {
            $admin = auth('admin')->user();
            return view('admin-views.info.profile', compact('admin'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function profileEdit(Request $request)
    {
        try {
            $admin = auth('admin')->user();

            return view('admin-views.info.edit', compact('admin'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function profileUpdateStore(Request $request) {
        $request->validate([
            'f_name' => 'required|string|max:255',
            'l_name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . auth('admin')->id(),
            'phone' => 'required|numeric|digits:10|unique:admins,phone,' . auth('admin')->id(),
            'password' => 'nullable|confirmed|min:8',
        ]);

        $admin = Admin::find(auth('admin')->id());
        $admin->f_name = $request->input('f_name');
        $admin->l_name = $request->input('l_name');
        $admin->email = $request->input('email');
        $admin->phone = $request->input('phone');

        // If password is provided, hash and save it
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->input('password'));
        }

        $admin->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    private function getChartsData($filter, $from, $to, $isStaff, $user)
    {

        // Base query conditions
        $baseQuery = function($query) use ($isStaff, $user) {
            if ($isStaff) {
                return $query->where('zone_id', $user->zone_id);
            }
            return $query;
        };

        // Date range conditions
        $dateCondition = function($query) use ($filter, $from, $to) {
            if ($filter == 'custom' && $from && $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            } elseif ($filter == 'today') {
                return $query->whereDate('created_at', now()->toDateString());
            } elseif ($filter == 'this_week') {
                return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($filter == 'this_month') {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            } elseif ($filter == 'this_year') {
                return $query->whereYear('created_at', now()->format('Y'));
            } elseif ($filter == 'previous_year') {
                return $query->whereYear('created_at', date('Y') - 1);
            }
            return $query;
        };


        // Orders Chart Data
        $ordersData = $this->getOrdersChartData($baseQuery, $dateCondition, $filter);
        
        // Payments Chart Data
        $paymentsData = $this->getPaymentsChartData($baseQuery, $dateCondition, $filter);
        
        // Ratings Chart Data
        $ratingsData = $this->getRatingsChartData($baseQuery, $dateCondition, $filter);
        
        // Earnings Chart Data
        $earningsData = $this->getEarningsChartData($baseQuery, $dateCondition, $filter);

        return [
            'orders' => $ordersData,
            'payments' => $paymentsData,
            'ratings' => $ratingsData,
            'earnings' => $earningsData
        ];
    }

    private function getOrdersChartData($baseQuery, $dateCondition, $filter)
    {
        // dd($filter);
        try {
            if ($filter == 'today') {
                // dd('here');
                $labels = [];
                $data = [];

                for ($i = 0; $i < 24; $i += 2) {
                    $hourLabel = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00 ';
                    $labels[] = $hourLabel;

                    $count = OrderTransaction::query()
                        ->where(function ($q) use ($baseQuery) {
                            $baseQuery($q);
                        })
                        ->whereDate('created_at', now()->toDateString())
                        ->whereRaw('HOUR(created_at) >= ? AND HOUR(created_at) < ?', [$i, $i + 2])
                        //d
                        ->count();

                    $data[] = $count;
                }
            } elseif ($filter == 'this_week') {
                // Daily data for this week
                $labels = [];
                $data = [];
                $startOfWeek = now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i);
                    $labels[] = $date->format('D');
                    
                    $count = OrderTransaction::query()
                        ->where(function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->whereDate('created_at', $date->toDateString())
                        ->count();
                    $data[] = $count;
                }
             } elseif( $filter == 'this_month') {
                // Daily data for this month
                $labels = [];
                $data = [];
                $daysInMonth = now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i+= 3) {
                    $startDay = $i;$endDay = min($i + 2, $daysInMonth);
                    $labels[] = str_pad($startDay, 2, '0', STR_PAD_LEFT) . '-' . str_pad($endDay, 2, '0', STR_PAD_LEFT);
                    
                    $count = OrderTransaction::query()
                        ->where(function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->where(function($q) use ($dateCondition) { return $dateCondition($q); })
                        ->whereBetween(DB::raw('DAY(created_at)'), [$startDay, $endDay])
                        ->count();
                    $data[] = $count;
                }
            } else {
                // Monthly data for other filters
                $labels = [];
                $data = [];
                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = Carbon::create()->month($i)->format('M');
                    
                    $count = OrderTransaction::query()
                        ->where(function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->where(function($q) use ($dateCondition) { return $dateCondition($q); })
                        ->whereMonth('created_at', $i)
                        ->count();
                    $data[] = $count;
                }
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Orders chart data error: ' . $e->getMessage());
            // Return fallback data
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [12, 19, 3, 5, 2, 3]
            ];
        }
    }

    private function getPaymentsChartData($baseQuery, $dateCondition, $filter)
    {
        try {
            if ($filter == 'today') {
                $labels = [];
                $data = [];
                for ($i = 0; $i < 24; $i += 2) {
                    $hour = str_pad($i, 2, '0', STR_PAD_LEFT) . ':00';
                    $labels[] = $hour;
                    
                    $amount = OrderTransaction::query()
                        ->whereHas('order', function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->whereDate('created_at', now()->toDateString())
                        ->whereRaw('HOUR(created_at) >= ? AND HOUR(created_at) < ?', [$i, $i + 2])
                        ->sum('order_amount');
                    $data[] = $amount ?? 0;
                }
            } elseif ($filter == 'this_week') {
                $labels = [];
                $data = [];
                $startOfWeek = now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i);
                    $labels[] = $date->format('D');
                    
                    $amount = OrderTransaction::query()
                        ->whereHas('order', function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->whereDate('created_at', $date->toDateString())
                        ->sum('order_amount');
                    $data[] = $amount ?? 0;
                }
            } else {
                $labels = [];
                $data = [];
                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = Carbon::create()->month($i)->format('M');
                    
                    $amount = OrderTransaction::query()
                        ->whereHas('order', function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->where(function($q) use ($dateCondition) { return $dateCondition($q); })
                        ->whereMonth('created_at', $i)
                        ->sum('order_amount');
                    $data[] = $amount ?? 0;
                }
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Payments chart data error: ' . $e->getMessage());
            // Return fallback data
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [0, 0, 0, 0, 0, 0]
            ];
        }
    }

    private function getRatingsChartData($baseQuery, $dateCondition, $filter)
    {
        try {
            // Rating distribution
            $labels = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
            $data = [];
            
            for ($rating = 1; $rating <= 5; $rating++) {
                $count = Review::query()
                    ->where('rating', $rating)
                    ->where(function($q) use ($dateCondition) { return $dateCondition($q); })
                    ->count();
                $data[] = $count;
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Ratings chart data error: ' . $e->getMessage());
            // Return fallback data
            return [
                'labels' => ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'],
                'data' => [5, 10, 15, 25, 45]
            ];
        }
    }

    private function getEarningsChartData($baseQuery, $dateCondition, $filter)
    {
        try {
            if ($filter == 'today') {
                $labels = [];
                $data = [];
                // 2 hours interval
                for ($i = 0; $i < 24; $i+=2) {
                    $hour = str_pad($i , 2, '0', STR_PAD_LEFT) . ':00';
                    $labels[] = $hour;
                    
                    $earnings = OrderTransaction::query()
                        ->whereHas('order', function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->whereDate('created_at', now()->toDateString())
                        ->whereRaw('HOUR(created_at) >= ? AND HOUR(created_at) < ?', [$i, $i + 2])
                        ->sum('admin_earning');
                    $data[] = $earnings ?? 0;
                }
            } elseif ($filter == 'this_week') {
                $labels = [];
                $data = [];
                $startOfWeek = now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i);
                    $labels[] = $date->format('D');
                    
                    $earnings = OrderTransaction::query()
                        ->whereHas('order', function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->whereDate('created_at', $date->toDateString())
                        ->sum('admin_earning');
                    $data[] = $earnings ?? 0;
                }
            } else {
                $labels = [];
                $data = [];
                for ($i = 1; $i <= 12; $i++) {
                    $labels[] = Carbon::create()->month($i)->format('M');
                    
                    $earnings = OrderTransaction::query()
                        ->whereHas('order', function($q) use ($baseQuery) { return $baseQuery($q); })
                        ->where(function($q) use ($dateCondition) { return $dateCondition($q); })
                        ->whereMonth('created_at', $i)
                        ->sum('admin_earning');
                    $data[] = $earnings ?? 0;
                }
            }

            return [
                'labels' => $labels,
                'data' => $data
            ];
        } catch (\Exception $e) {
            Log::error('Earnings chart data error: ' . $e->getMessage());
            // Return fallback data
            return [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [0, 0, 0, 0, 0, 0]
            ];
        }
    }

}
