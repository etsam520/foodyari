<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\Helpers;
use App\CentralLogics\DeliveryTime\DeliveryTimer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Vendor;
use App\Models\OrderTransaction;
use App\Models\Restaurant;
use App\Models\VendorMess;
use App\Models\Zone;
use App\Services\JsonDataService;
use Arcanedev\QrCode\QrCode;
use Carbon\Carbon;
use Google\Cloud\Storage\Connection\Rest;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $restaurant = Session::get('restaurant');

        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'this_month');

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
        $transactionsOrder = OrderTransaction::with('order')->where('restaurant_id',Session::get('restaurant')->id)
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
        ->get();

        $earning = 0;
        $collection = 0;
        // dd($transactionsOrder);
        foreach($transactionsOrder as $txns){
            $collection += $txns->restaurant_receivable_amount;
            $earning += $txns->restaurant_earning;
        }

        $transactionsOrderIds = $transactionsOrder->pluck('order_id')->toArray();

        $productsWithOrderDetails = OrderDetail::with('order')  // Eager load the related 'order' model
        ->whereIn('order_id', $transactionsOrderIds)  // Filter by the order IDs
        ->get()  // Fetch all records
        ->groupBy('food_id');

        $productsWithOrderDetailsResult = ReportController::getFoodReport_process_func($productsWithOrderDetails);

        $count = [
            'totalOrders' =>  count($transactionsOrderIds),
            'currentOrder' => Order::whereDate('created_at', Carbon::today())->where('restaurant_id',$restaurant->id)->count(),
            'staff' => 0,
            // 'customers' => Customer::where('restaurant_id',$restaurant->vendor_id)->count(),
            'customers' => 0,
            'collection'=> $collection,
            'earning' => $earning,
            'sold' =>  count($transactionsOrderIds)
        ];

        $productSold = $productsWithOrderDetailsResult['productItems'];
        $restaurant = Restaurant::find(Session::get('restaurant')->id);

        $latest_orders = $this->vendor_latest_orders();

        // Add refund statistics
        $refund_stats = [];
        try {
            $refund_stats = [
                'total_refunds' => \App\Models\Refund::whereHas('order', function($query) use ($restaurant) {
                    $query->where('restaurant_id', $restaurant->id);
                })->count(),
                'pending_refunds' => \App\Models\Refund::whereHas('order', function($query) use ($restaurant) {
                    $query->where('restaurant_id', $restaurant->id);
                })->where('refund_status', 'pending')->count(),
                'total_deductions' => \App\Models\Refund::whereHas('order', function($query) use ($restaurant) {
                    $query->where('restaurant_id', $restaurant->id);
                })->where('refund_status', 'processed')->sum('restaurant_deduction_amount'),
            ];
        } catch (\Exception $e) {
            // Handle case where refund system is not yet migrated
            $refund_stats = [
                'total_refunds' => 0,
                'pending_refunds' => 0,
                'total_refund_amount' => 0,
                'total_deductions' => 0,
            ];
        }
        $this->syncFcmToken($restaurant->id);
        return view('vendor-views.dashboard',compact('count','productSold','filter', 'restaurant','latest_orders', 'refund_stats'));
    }

    private function syncFcmToken($restaurantId){
        if (isset($_COOKIE['My_FCM_Token'])) {
           Restaurant::where('id', $restaurantId)->update(['fcm_token' => $_COOKIE['My_FCM_Token']]);
        }
    }

    private function vendor_latest_orders(){
        $today = now()->toDateString();
        $vendor = auth('vendor')->user();
        $orders = Order::select(
            'orders.*',
            'restaurants.name as restaurant_name',
            'restaurants.phone as restaurant_phone',
            'restaurants.email as restaurant_email',
            'restaurants.address as restaurant_address',
            'order_stmt.restaurantData',
            DB::raw("CONCAT(dman.f_name, ' ', dman.l_name) as deliveryman_name"),
            DB::raw('dman.phone as deliveryman_phone'),
            DB::raw('dman.email as deliveryman_email'),
            DB::raw('dman.image as deliveryman_image'),
            DB::raw('dm_location.last_location as deliveryman_location'),

        )
        ->whereDate('orders.created_at', $today)
        ->whereIn('orders.restaurant_id', function ($query) use ($vendor) {
            $query->select('id')
                ->from('restaurants')
                ->where('vendor_id', $vendor->id);
        })
        ->leftJoin('restaurants', 'restaurants.id', '=', 'orders.restaurant_id')
        ->leftJoin('order_calculation_statements as order_stmt', 'order_stmt.order_id', '=', 'orders.id')
        ->leftJoin('delivery_men as dman', 'dman.id', '=', 'orders.delivery_man_id')
        ->leftJoin('dm_current_locations as dm_location', 'dm_location.dm_id', '=', 'dman.id')
        ->latest()
        ->get();
        // dd($orders);
        $orders->map(function ($order) {
            // Calculate delivery man arrival time and directions
            if($order->delivery_man_id != null && $order->picked_up == null){
                try {
                    $dmData = json_decode($order->deliveryman_location, true);
                    
                    if ($dmData && isset($dmData['lat']) && isset($dmData['lng'])) {
                        $origin = $dmData['lat'].','.$dmData['lng'];
                        $restaurantLocation = json_decode($order->restaurant->coordinates, true);
                        $restaurantLocation = $restaurantLocation['latitude'].",".$restaurantLocation['longitude'];
                        
                        // Get Google Directions for distance and time
                        $googleDirections = Helpers::googleDirections($origin, $restaurantLocation);
                        $order->gmapData = $googleDirections;
                        
                        // Calculate precise ETA using DeliveryTimer
                        $timer = new DeliveryTimer($order->delivery_man_id);
                        $restaurantETA = $timer->getResturantReachOutTime($order->id, $order);
                        
                        $order->delivery_man_arrival = [
                            'eta_minutes' => $restaurantETA['eta_minutes'] ?? 0,
                            'eta_time' => $restaurantETA['eta_time'] ?? null,
                            'distance_text' => $googleDirections['distance']['text'] ?? 'N/A',
                            'duration_text' => $googleDirections['duration']['text'] ?? 'N/A',
                            'duration_value' => $googleDirections['duration']['value'] ?? 0,
                            'factors' => $restaurantETA['factors'] ?? [],
                            'status' => $this->getDeliveryManStatus($order, $restaurantETA['eta_minutes'] ?? 0)
                        ];
                    } else {
                        $order->gmapData = null;
                        $order->delivery_man_arrival = null;
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating delivery man arrival: ' . $e->getMessage());
                    $order->gmapData = null;
                    $order->delivery_man_arrival = null;
                }
            } else {
                $order->gmapData = null;
                $order->delivery_man_arrival = null;
            }
            
            $order->deliveryman_image = Helpers::getUploadFile($order->deliveryman_image, 'deliveryman');
            return $order;
        });

        return $orders;
    }

    /**
     * Determine delivery man status based on ETA
     */
    private function getDeliveryManStatus($order, $etaMinutes)
    {
        // If delivery man hasn't been assigned yet
        if (!$order->delivery_man_id) {
            return [
                'status' => 'not_assigned',
                'label' => 'Not Assigned',
                'color' => 'secondary',
                'icon' => 'fa-clock'
            ];
        }

        // If order is already picked up
        if ($order->picked_up) {
            return [
                'status' => 'picked_up',
                'label' => 'Picked Up',
                'color' => 'success',
                'icon' => 'fa-check-circle'
            ];
        }

        // If delivery man is at restaurant
        if ($order->dm_at_restaurant) {
            return [
                'status' => 'at_restaurant',
                'label' => 'At Restaurant',
                'color' => 'success',
                'icon' => 'fa-map-marker-alt'
            ];
        }

        // Based on ETA minutes
        if ($etaMinutes <= 0) {
            return [
                'status' => 'overdue',
                'label' => 'Overdue',
                'color' => 'danger',
                'icon' => 'fa-exclamation-triangle'
            ];
        } elseif ($etaMinutes <= 2) {
            return [
                'status' => 'arriving_soon',
                'label' => 'Arriving Soon',
                'color' => 'warning',
                'icon' => 'fa-clock'
            ];
        } elseif ($etaMinutes <= 5) {
            return [
                'status' => 'nearby',
                'label' => 'Nearby',
                'color' => 'info',
                'icon' => 'fa-motorcycle'
            ];
        } else {
            return [
                'status' => 'on_way',
                'label' => 'On Way',
                'color' => 'primary',
                'icon' => 'fa-route'
            ];
        }
    }

    public function profile(Request $request)
    {
        try {
            $restaurant = Restaurant::with('vendor')->find(Session::get('restaurant')->id);
            $zones = Zone::select('name','id','coordinates')->get();

            return view('vendor-views.info.profile', compact('restaurant','zones'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function profileEdit(Request $request)
    {
        try {
            $restaurant = Restaurant::with('vendor')->find(Session::get('restaurant')->id);
            $zones = Zone::select('name','id','coordinates')->get();

            return view('vendor-views.info.edit', compact('restaurant','zones'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }


    public function profileUpdateStore(Request $request) {

        $rules = [
            'name' => 'required|string|max:191',
            'id' => 'required',
            'street' => 'required|string|max:1000',
            'city' => 'required|string|max:1000',
            'pincode' => 'required|numeric|digits:6',
            'radius' => 'required|numeric|max:180',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'badge_one' => 'nullable|string|max:1000',
            'badge_two' =>'nullable|string|max:1000',
            'badge_three' =>'nullable|string|max:1000',
            'description' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
            'email' => 'nullable|email',
        ];



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $restaurant = restaurant::find(Session::get('restaurant')->id);
            $restaurant->name = $request->name;

            $restaurant->description = $request->description?? null;
            if($request->file('logo')){
                $restaurant->logo = Helpers::updateFile($request->file('logo'), 'restaurant',$restaurant->logo);
            }
            // if($request->file('cover_photo')){
            //     $restaurant->cover_photo = Helpers::updateFile($request->file('cover_photo'), 'restaurant/cover/',$restaurant->cover_photo);
            // }
            $restaurant->radius = $request->radius;
            $restaurant->address = json_encode([
                                        'street' => $request->street,
                                        'city' => $request->city,
                                        'pincode' => $request->pincode,
                                    ]);
            $restaurant->badges = json_encode(['b1' => $request->badge_one, 'b2' => $request->badge_two,'b3' =>$request->badge_three]);
            $restaurant->zone_id = $request->zone_id;
            $restaurant->latitude = $request->latitude;
            $restaurant->tax = $request->tax ;
            $restaurant->longitude = $request->longitude;
            $restaurant->coordinates = json_encode([
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
            ]);

            $restaurant->email = $request->email;
            $restaurant->phone = $request->phone;
            $restaurant->save();

            return redirect()->back()->with('success', __('Profile Updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // public function qrGenerate()
    // {
    //     $restaurant = Restaurant::find(Session::get('restaurant')->id);

    //     $name = Str::ucfirst($restaurant->name);
    //     $url = route('user.restaurant.get-restaurant', [
    //         'name' => $restaurant->url_slug ?? Str::slug($restaurant->name)
    //     ]);
    //     $mainPath = public_path('/qrtemplate/imagePic/');
    //     // dd($mainPath);
    //     $runnerFile = $mainPath . escapeshellarg('runner.sh');
    //     $outputFile = $mainPath . 'output/output.png';

    //     $command = escapeshellcmd($runnerFile) . ' '
    //             . escapeshellarg($name) . ' '
    //             . escapeshellarg($url) . ' '
    //             . escapeshellarg($mainPath) . ' '
    //             . escapeshellarg($outputFile);

    //     // Capture both STDOUT and STDERR
    //     $fullCommand = $command . ' 2>&1';

    //     exec($fullCommand, $output, $returnVar);

    //     // Dump everything for debugging
    //     dd([
    //         'command'   => $command,
    //         'output'    => $output,     // array of lines from STDOUT+STDERR
    //         'exit_code' => $returnVar
    //     ]);

    //     if ($returnVar !== 0) {
    //         return response()->json([
    //             'error' => 'QR code generation failed',
    //             'output' => $output
    //         ], 500);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'file' => asset('qrtemplate/imagePic/output.png')
    //     ]);
    // }



    public function qrGenerate()
{
    $restaurant = Restaurant::find(Session::get('restaurant')->id);
    $restaurantName = Str::ucfirst($restaurant->name);
    $link = route('user.restaurant.get-restaurant', [
        'name' => $restaurant->url_slug ?? Str::slug($restaurant->name)
    ]);

    $mainPath = public_path('qrtemplate/imagePic');
    $runnerFile = $mainPath . '/runner.sh';

    // Build the command
    $command = sprintf(
        '%s %s %s %s',
        escapeshellcmd($runnerFile),
        escapeshellarg($restaurantName),
        escapeshellarg($link),
        escapeshellarg($mainPath)
    );

    $descriptors = [
        0 => ['pipe', 'r'],  // stdin
        1 => ['pipe', 'w'],  // stdout (binary PNG)
        2 => ['pipe', 'w'],  // stderr (logs)
    ];

    $process = proc_open($command, $descriptors, $pipes);

    // dd($process);
    Log::info($process);

    if (!is_resource($process)) {
        Log::error("Failed to start QR generation process for {$restaurantName}");
        return response()->json(['error' => 'Failed to start QR generation'], 500);
    }

    // Read PNG binary from stdout
    $pngData = stream_get_contents($pipes[1]);
    fclose($pipes[1]);

    // Read FFmpeg logs
    $ffmpegLogs = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    $returnCode = proc_close($process);

    if ($returnCode !== 0 || empty($pngData)) {
        Log::error("QR generation failed for {$restaurantName}", ['ffmpeg' => $ffmpegLogs]);
        return response()->json([
            'error' => 'QR generation failed',
            'details' => $ffmpegLogs
        ], 500);
    }

    Log::info("QR generated successfully for {$restaurantName}", ['ffmpeg' => $ffmpegLogs]);

    // Stream the PNG
    return response($pngData, 200, [
        'Content-Type' => 'image/png',
        'Cache-Control' => 'no-cache, no-store, must-revalidate',
        'Content-Disposition' => 'inline; filename="'.$restaurantName.'-qr.png"',
        'Pragma' => 'no-cache',
        'Expires' => '0',
    ]);
}



    public function qrGenerate800X800()
    {
        // Generate QR as base64 (from your helper)
        $qrBase64 = Helpers::qrGenerate(Session::get('restaurant')->name, 'http://localhost/foodyari_live/restaurant-panel/dashboard');

        // Remove "data:image/png;base64," if present
        $qrBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $qrBase64);

        // Decode to binary
        $qrImage = base64_decode($qrBase64);

        // Return as image stream
        return Response::make($qrImage, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="qr.png"',
        ]);
    }

    /**
     * Get real-time delivery man arrival updates
     */
    public function getDeliveryManArrival(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            if (!$orderId) {
                return response()->json(['success' => false, 'message' => 'Order ID is required'], 400);
            }

            $vendor = auth('vendor')->user();
            $order = Order::with(['restaurant', 'delivery_man'])
                ->whereIn('restaurant_id', function ($query) use ($vendor) {
                    $query->select('id')
                        ->from('restaurants')
                        ->where('vendor_id', $vendor->id);
                })
                ->find($orderId);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            if (!$order->delivery_man_id || $order->picked_up) {
                return response()->json([
                    'success' => true,
                    'arrival_data' => null,
                    'message' => 'Delivery man not assigned or order already picked up'
                ]);
            }

            // Get current delivery man location
            $dmLocation = DB::table('dm_current_locations')
                ->where('dm_id', $order->delivery_man_id)
                ->first();

            if (!$dmLocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery man location not available'
                ], 404);
            }

            $dmData = json_decode($dmLocation->last_location, true);
            $origin = $dmData['lat'] . ',' . $dmData['lng'];
            $restaurantLocation = json_decode($order->restaurant->coordinates, true);
            $destination = $restaurantLocation['latitude'] . "," . $restaurantLocation['longitude'];

            // Get updated directions
            $googleDirections = Helpers::googleDirections($origin, $destination);
            
            // Calculate precise ETA
            $timer = new DeliveryTimer($order->delivery_man_id);
            $restaurantETA = $timer->getResturantReachOutTime($order->id, $order);

            $arrivalData = [
                'eta_minutes' => $restaurantETA['eta_minutes'] ?? 0,
                'eta_time' => $restaurantETA['eta_time'] ?? null,
                'distance_text' => $googleDirections['distance']['text'] ?? 'N/A',
                'duration_text' => $googleDirections['duration']['text'] ?? 'N/A',
                'duration_value' => $googleDirections['duration']['value'] ?? 0,
                'factors' => $restaurantETA['factors'] ?? [],
                'status' => $this->getDeliveryManStatus($order, $restaurantETA['eta_minutes'] ?? 0),
                'delivery_man' => [
                    'name' => $order->delivery_man->f_name . ' ' . $order->delivery_man->l_name,
                    'phone' => $order->delivery_man->phone,
                    'image' => Helpers::getUploadFile($order->delivery_man->image, 'deliveryman'),
                ],
                'current_location' => [
                    'lat' => $dmData['lat'],
                    'lng' => $dmData['lng']
                ],
                'restaurant_location' => [
                    'lat' => $restaurantLocation['latitude'],
                    'lng' => $restaurantLocation['longitude']
                ]
            ];

            return response()->json([
                'success' => true,
                'arrival_data' => $arrivalData,
                'updated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting delivery man arrival: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get delivery man arrival data'
            ], 500);
        }
    }

    /**
     * Get delivery man location for map display
     */
    public function getDeliveryManLocation(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            $vendor = auth('vendor')->user();
            $order = Order::with(['restaurant', 'delivery_man'])
                ->whereIn('restaurant_id', function ($query) use ($vendor) {
                    $query->select('id')
                        ->from('restaurants')
                        ->where('vendor_id', $vendor->id);
                })
                ->find($orderId);

            if (!$order || !$order->delivery_man_id) {
                return response()->json(['success' => false, 'message' => 'Order or delivery man not found'], 404);
            }

            $dmLocation = DB::table('dm_current_locations')
                ->where('dm_id', $order->delivery_man_id)
                ->first();

            if (!$dmLocation) {
                return response()->json(['success' => false, 'message' => 'Location not available'], 404);
            }

            $dmData = json_decode($dmLocation->last_location, true);
            $restaurantLocation = json_decode($order->restaurant->coordinates, true);

            return response()->json([
                'success' => true,
                'delivery_man_location' => [
                    'lat' => $dmData['lat'],
                    'lng' => $dmData['lng'],
                    'updated_at' => $dmLocation->updated_at
                ],
                'restaurant_location' => [
                    'lat' => $restaurantLocation['latitude'],
                    'lng' => $restaurantLocation['longitude']
                ],
                'delivery_man' => [
                    'name' => $order->delivery_man->f_name . ' ' . $order->delivery_man->l_name,
                    'phone' => $order->delivery_man->phone,
                    'image' => Helpers::getUploadFile($order->delivery_man->image, 'deliveryman'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting delivery man location: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get location'], 500);
        }
    }

    /**
     * Update extra cooking time for an order
     */
    public function updateExtraCookingTime(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'extra_cooking_time' => 'required|integer|min:0|max:300', // Max 5 hours
            ]);

            $vendor = auth('vendor')->user();
            $order = Order::whereIn('restaurant_id', function ($query) use ($vendor) {
                $query->select('id')
                    ->from('restaurants')
                    ->where('vendor_id', $vendor->id);
            })->find($request->order_id);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Don't allow updating if order is already picked up or delivered
            if ($order->picked_up || $order->delivered || $order->canceled) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cannot update cooking time for completed orders'
                ], 400);
            }

            $previousExtraTime = $order->extra_cooking_time ?? 0;
            
            $order->update([
                'extra_cooking_time' => $request->extra_cooking_time,
                'extra_cooking_time_updated_at' => now(),
            ]);

            // Log the change for tracking
            Log::info("Extra cooking time updated for order {$order->id}: {$previousExtraTime} -> {$request->extra_cooking_time} minutes");

            return response()->json([
                'success' => true,
                'message' => 'Extra cooking time updated successfully',
                'data' => [
                    'order_id' => $order->id,
                    'previous_extra_time' => $previousExtraTime,
                    'new_extra_time' => $request->extra_cooking_time,
                    'total_extra_time' => $order->extra_cooking_time,
                    'updated_at' => $order->extra_cooking_time_updated_at,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating extra cooking time: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update extra cooking time'
            ], 500);
        }
    }

    /**
     * Get extra cooking time for an order
     */
    public function getExtraCookingTime(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            if (!$orderId) {
                return response()->json(['success' => false, 'message' => 'Order ID is required'], 400);
            }

            $vendor = auth('vendor')->user();
            $order = Order::whereIn('restaurant_id', function ($query) use ($vendor) {
                $query->select('id')
                    ->from('restaurants')
                    ->where('vendor_id', $vendor->id);
            })->find($orderId);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'order_id' => $order->id,
                    'extra_cooking_time' => $order->extra_cooking_time ?? 0,
                    'updated_at' => $order->extra_cooking_time_updated_at,
                    'can_update' => !$order->picked_up && !$order->delivered && !$order->canceled,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting extra cooking time: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get extra cooking time'], 500);
        }
    }

    /**
     * Start processing an order with cooking time
     */
    public function startProcessing(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
                'cooking_time' => 'required|integer|min:5|max:180', // 5 minutes to 3 hours
            ]);

            $vendor = auth('vendor')->user();
            $order = Order::whereIn('restaurant_id', function ($query) use ($vendor) {
                $query->select('id')
                    ->from('restaurants')
                    ->where('vendor_id', $vendor->id);
            })->find($request->order_id);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            // Check if order is in correct status to start processing
            if (!$order->confirmed || $order->processing || $order->picked_up || $order->delivered || $order->canceled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order cannot be processed. Check order status.'
                ], 400);
            }

            // Start processing
            $order->update([
                'processing' => now(),
                'processing_time' => $request->cooking_time,
                'order_status' => 'processing',
            ]);

            // Log the action
            Log::info("Order {$order->id} processing started with {$request->cooking_time} minutes cooking time by vendor {$vendor->id}");

            return response()->json([
                'success' => true,
                'message' => 'Order processing started successfully',
                'data' => [
                    'order_id' => $order->id,
                    'processing_started_at' => $order->processing,
                    'cooking_time' => $request->cooking_time,
                    'estimated_completion' => now()->addMinutes($request->cooking_time)->toISOString(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting order processing: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to start processing'
            ], 500);
        }
    }

    /**
     * Force mark an order as ready
     */
    public function forceReady(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
            ]);

            $vendor = auth('vendor')->user();
            $order = Order::whereIn('restaurant_id', function ($query) use ($vendor) {
                $query->select('id')
                    ->from('restaurants')
                    ->where('vendor_id', $vendor->id);
            })->find($request->order_id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or access denied'
                ], 404);
            }

            // Don't allow force ready if order is already picked up, delivered, or canceled
            if ($order->picked_up || $order->delivered || $order->canceled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot force ready - order is already picked up, delivered, or canceled'
                ], 400);
            }

            // Don't allow if order is not in processing state
            if (!$order->processing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot force ready - order is not in processing state'
                ], 400);
            }

            // Calculate total expected cooking time (base + extra)
            $baseCookingTime = $order->processing_time ?? 0; // Default 15 minutes if not set
            $extraCookingTime = $order->extra_cooking_time ?? 0;
            $totalExpectedTime = $baseCookingTime + $extraCookingTime;
            
            // Calculate actual time taken (from processing start to now)
            $processingStartTime = $order->processing;
            $actualTimeTaken = now()->diffInMinutes($processingStartTime);
            
            // Calculate time balance (positive = early, negative = late)
            $timeBalance = $totalExpectedTime - $actualTimeTaken;
            
            // Update order status to handover (ready for pickup)
            $order->update([
                'handover' => now(),
                'processing_time'=> $actualTimeTaken,
                'extra_cooking_time' => 0, // Adjust extra time if needed
                'order_status' => 'handover',
            ]);

            // Log the action for tracking with time balance information
            Log::info("Order {$order->id} was force marked as ready by vendor {$vendor->id}. Expected: {$totalExpectedTime}min, Actual: {$actualTimeTaken}min, Balance: {$timeBalance}min");

            return response()->json([
                'success' => true,
                'message' => 'Order has been force marked as ready',
                'data' => [
                    'order_id' => $order->id,
                    'handover_time' => $order->handover,
                    'expected_cooking_time' => $totalExpectedTime,
                    'actual_time_taken' => $actualTimeTaken,
                    'time_balance' => $timeBalance,
                    'balance_status' => $timeBalance > 0 ? 'early' : ($timeBalance < 0 ? 'late' : 'on_time')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error force marking order as ready: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to force mark order as ready'
            ], 500);
        }
    }

    /**
     * Mark order as handed over to delivery partner
     */
    public function handover(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|integer|exists:orders,id',
            ]);

            $vendor = auth('vendor')->user();
            $order = Order::whereIn('restaurant_id', function ($query) use ($vendor) {
                $query->select('id')
                    ->from('restaurants')
                    ->where('vendor_id', $vendor->id);
            })->find($request->order_id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found or access denied'
                ], 404);
            }

            // Don't allow handover if order is not ready or already processed
            if (!$order->handover) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot handover - order is not ready yet'
                ], 400);
            }

            if ($order->picked_up || $order->delivered || $order->canceled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot handover - order is already picked up, delivered, or canceled'
                ], 400);
            }

            // Check if delivery man is assigned
            if (!$order->delivery_man_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot handover - no delivery partner assigned yet'
                ], 400);
            }

            // Update order status to indicate it's been handed over to delivery partner
            $order->update([
                'picked_up' => now(),
                'order_status' => 'picked_up',
            ]);

            // Log the action for tracking
            Log::info("Order {$order->id} was handed over to delivery partner {$order->delivery_man_id} by vendor {$vendor->id}");

            return response()->json([
                'success' => true,
                'message' => 'Order has been handed over to delivery partner',
                'data' => [
                    'order_id' => $order->id,
                    'handover_time' => $order->handover,
                    'picked_up_time' => $order->picked_up,
                    'delivery_man_id' => $order->delivery_man_id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing handover: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process handover'
            ], 500);
        }
    }
}
