<?php

namespace App\Http\Controllers\User\Restaurant;

use App\CentralLogics\Helpers;
use App\CentralLogics\DeliveryTime\DeliveryTimer;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\JsonDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function list ($status)
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::with('restaurant')
            ->when($status === 'confirmed', function ($query) {
                return $query->where('order_status', 'confirmed');
            })
            ->when($status === 'pending', function ($query) {
                return $query->where('order_status', 'pending');
            })
            ->when($status === 'canceled', function ($query) {
                return $query->where('order_status', 'canceled');
            })
            ->when($status === 'delivered', function ($query) {
                return $query->where('order_status', 'delivered');
            })
            ->when($status === 'scheduled', function ($query) {
                return $query->where('order_status', 'scheduled');
            })
            ->when($status === 'all', function ($query) {
                return $query->whereNotNull('order_status');
            })->when($status === 'live', function ($query) {
                return $query->whereNotIn('order_status', ['delivered','canceled', 'scheduled'])->whereDate('created_at', now());
            })
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $status = __('messages.'.$status);
        return view('user-views.restaurant.orders.list', compact('orders', 'status','customer'));
    }


    public function orderTrace(Request $request)
    {
        $orderId = $request->query('order_id');
        if(empty($orderId)){
            return back();
        }

        $order = Order::with(['orderCalculationStmt','restaurant','customer','delivery_man'])->find($orderId);
        if(!$order){
            return back()->with('info', 'Order Not Found');
        }

        // Initialize delivery timing data
        $deliveryData = null;
        $etaInfo = null;
        $dmLastLocation = null;
        $processingInfo = null;

        // Get delivery timing information if delivery man is assigned
        if ($order->delivery_man_id && !in_array($order->order_status, ['delivered', 'canceled'])) {
            try {
                // Initialize DeliveryTimer
                $deliveryTimer = new DeliveryTimer($order->delivery_man_id);
                
                // Get delivery time estimation
                $deliveryData = $deliveryTimer->getDeliveryTime($order->id, $order);
                $dmLastLocation = $deliveryTimer->getDmLastLocation();
                //  $dmLastLocation->currentLocation->lat;
                // $dmLastLocation->currentLocation->lng;

                // Get restaurant arrival ETA if not picked up yet
                if (!$order->picked_up) {
                    $etaInfo = $deliveryTimer->getResturantReachOutTime($order->id, $order);
                }

                // Calculate processing time information
                $processingInfo = $this->calculateProcessingTime($order, $deliveryData);
                // dd($processingInfo);

            } catch (\Exception $e) {
                dd($e->getMessage());
                Log::error('Error calculating delivery times for order ' . $order->id . ': ' . $e->getMessage());
            }
        }


        return view('user-views.restaurant.orders.trace.tracking', compact('order', 'deliveryData', 'dmLastLocation', 'etaInfo', 'processingInfo'));
    }

    /**
     * Calculate processing time information
     */
    private function calculateProcessingTime($order, $deliveryData)
    {
        $processingInfo = [
            'is_processing' => false,
            'processing_time' => 0,
            'extra_cooking_time' => 0,
            'total_time' => 0,
            'elapsed_time' => 0,
            'remaining_time' => 0,
            'completion_percentage' => 0,
            'estimated_completion' => null,
            'status' => 'pending'
        ];

        // Check if order is in processing state or related states
        $processingStates = ['confirmed', 'processing', 'handover', 'accepted'];
        // dd($order->order_status);
        if (in_array($order->order_status, $processingStates)) {
            $processingInfo['is_processing'] = true;
           
            // $processingInfo['extra_cooking_time'] = floatval($order->extra_cooking_time) ?? 0;
            
            // Determine the processing start time based on order status
            $processingStart = null;
            if ($order->processing) {
                $processingStart = Carbon::parse($order->processing);
            } elseif ($order->confirmed) {
                $processingStart = Carbon::parse($order->confirmed);
            } else {
                $processingStart = Carbon::parse($order->created_at);
            }
            
            $now = Carbon::now();
            $processingInfo['processing_time'] =  $deliveryData['breakdown']['processing_estimated']; // Default 15 minutes
            $processingInfo['elapsed_time'] = $deliveryData['breakdown']['elapsed_processing'];
            $processingInfo['remaining_time'] = max(0, $deliveryData['breakdown']['processing_remaining']);
            $processingInfo['total_time']   = $processingInfo['processing_time'] + $processingInfo['extra_cooking_time'];
            // Calculate completion percentage
            if ($processingInfo['total_time'] > 0) {
                $processingInfo['completion_percentage'] = min(100, ($processingInfo['elapsed_time'] / $processingInfo['total_time']) * 100);
            } else {
                $processingInfo['completion_percentage'] = 0;
            }
            
            $processingInfo['estimated_completion'] = $processingStart->copy()->addMinutes($processingInfo['total_time']);
            
            // Determine status based on order state and timing
            if ($order->order_status == 'handover' || $order->handover) {
                $processingInfo['status'] = 'ready';
                $processingInfo['completion_percentage'] = 100;
            } elseif ($processingInfo['remaining_time'] <= 0) {
                $processingInfo['status'] = 'overdue';
                $processingInfo['completion_percentage'] = 100;
            } elseif ($order->order_status == 'processing') {
                $processingInfo['status'] = 'cooking';
            } elseif ($order->order_status == 'confirmed') {
                $processingInfo['status'] = 'preparing';
            } else {
                $processingInfo['status'] = 'cooking';
            }
        }

        return $processingInfo;
    }

    public function liveOrder(Request $request)
    {
        $customer = Session::get('userInfo')??Auth::guard('customer')->user();
        $order = Order::with('restaurant')
        ->whereDate('created_at', now())->where('customer_id', $customer->id)
        ->whereTime('created_at', '<=', now()->addMinutes(120))
        ->whereNotIn('order_status', ['delivered', 'canceled'])
        ->latest()->first();
        if(!$order || $order == null){
            return response()->json([]);
        }
        $deliverby = null;
        if($order->picked_up != null){
            $deliverby =  $order->dmOrderProcess->deliver_by;
            $deliverby =  now()->diffInSeconds($deliverby) ;
            // dd($deliverby);
        }elseif($order->processing != null && $order->delivery_man_id != null){

            $restaurantLocation = json_decode($order->restaurant->coordinates, true) ;
            $deliveryAddress = json_decode($order->delivery_address , true);

            $origin = $restaurantLocation['latitude'].",".$restaurantLocation['longitude'];
            $destination = $deliveryAddress['position']['lat'].",".$deliveryAddress['position']['lon'];
            $googleDirections = Helpers::googleDirections($origin, $destination);

            $durationInTraffic = $googleDirections['duration_in_traffic_value'] ?? $googleDirections['duration_value'];

            $processing_time = now()->diffInSeconds($order->processing??null, false);

            $processingTime = $order->processing_time??0; // in minutes
            $processingAt = Carbon::parse($order->processing); // a Carbon instance or parsed datetime
            $processingEnd = $processingAt->copy()->addMinutes($processingTime);

            $processing_time = now()->diffInSeconds($processingEnd, false);
            if($processing_time < 0){
                $processing_time = 0;
            }
            $processing_time = ((float)  $processing_time ) + $durationInTraffic + (5 * 60);
            // $deliverby = Carbon::parse($order->processing)->addSeconds($processing_time);
            // $deliverby =  now()->diffInSeconds($durationInTraffic) ;
            $deliverby = $processing_time ;

        }

        return response()->json([
            "view" => view('user-views.restaurant.orders.trace._live-order',compact('order'))->render(),
            // "countdownMinutes" =>now()->diffInSeconds($deliverby, false),
            "countdownMinutes" =>$deliverby,
        ]);
    }



    public function shareOrder(Request $request)
    {
        $share_token = $request->query('share_token')??null;
        if(empty($share_token)){
            return redirect()->route('/');
        }

        $order = Order::with(['details','restaurant','customer','delivery_man'])->where('share_token', $share_token)->first();
        if(!$order){
            return redirect()->route('/');
        }
        return view('user-views.restaurant.orders.trace.share',compact('order'));
    }

    public function dmPostion(Request $request)
{
    if (!$request->order_id) {
        return response()->json(['error' => 'Order ID is null'], 400);
    }

    $order = Order::find($request->order_id);

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

     // Assuming a relationship exists between Order and Deliveryman

    // Check if deliveryman exists
    if (!$order->delivery_man_id) {
        return response()->json(['error' => 'Deliveryman not assigned'], 404);
    }

    // Retrieve deliveryman position data using JsonDataService
    $dmDataService = new JsonDataService($order->delivery_man_id);
    $dmData = $dmDataService->readData();
    $dmData->last_location['name'] = $dmData->name;

    // Return the deliveryman position data in JSON format
    return response()->json($dmData->last_location);
}

    /**
     * Display scheduled orders list
     */
    public function scheduledOrders(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        
        $orders = Order::with(['restaurant', 'details'])
            ->where('customer_id', $customer->id)
            ->where('order_status', 'scheduled')
            ->orderBy('schedule_at', 'asc')
            ->paginate(10);

        return view('user-views.restaurant.orders.scheduled-list', compact('orders', 'customer'));
    }

    /**
     * Display scheduled order details
     */
    public function scheduledOrderDetails(Request $request, $orderId)
    {
        $customer = Auth::guard('customer')->user();
        
        $order = Order::with(['restaurant', 'details'])
            ->where('customer_id', $customer->id)
            ->where('id', $orderId)
            ->where('order_status', 'scheduled')
            ->first();

        if (!$order) {
            return redirect()->route('user.restaurant.scheduled-orders')
                ->with('error', 'Scheduled order not found.');
        }

        return view('user-views.restaurant.orders.scheduled-details', compact('order', 'customer'));
    }

    /**
     * Cancel a scheduled order
     */
    public function cancelScheduledOrder(Request $request, $orderId)
    {
        $customer = Auth::guard('customer')->user();
        
        $order = Order::where('customer_id', $customer->id)
            ->where('id', $orderId)
            ->where('order_status', 'scheduled')
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Scheduled order not found.'
            ], 404);
        }

        // Check if the order can still be cancelled (e.g., at least 1 hour before scheduled time)
        $now = Carbon::now();
        $scheduledTime = Carbon::parse($order->schedule_at);
        
        if ($scheduledTime->diffInHours($now) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel order less than 1 hour before scheduled time.'
            ], 422);
        }

        // Update order status to cancelled
        $order->update([
            'order_status' => 'canceled',
            'canceled' => $now,
            'cancellation_reason' => 'Cancelled by customer',
            'canceled_by' => 'customer'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scheduled order cancelled successfully.'
        ]);
    }

}
