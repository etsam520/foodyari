<?php

namespace App\Http\Controllers\DeliveryBoy\restaurant;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\DeliveryHistory;
use App\Models\DeliveryMan;
use App\Models\LovedOneWithOrder;
use App\Models\Order;
use App\Services\JsonDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function getOrders() {
        try {
            $today = Carbon::now()->toDateString();
            $deliveryMan = auth('delivery_men')->user();
            $orders = Order::where('restaurant_id', $deliveryMan->restaurant_id)->whereIn('order_status',['accept','confirmed'])->get();

            // $dmRejectedOrder = MessDeliverymanOrderAccept::whereDate('created_at', $today)->where('dm_id', $deliveryMan->id)
            //                             ->where('status', 'rejected')->first();

            $dmRejectedOrder =[];

                    // if (!empty($dmRejectedOrder) && ($checklist->id == $dmRejectedOrder->checkList_id)) {
                    //     continue;
                    // }



            return response()->json([
                'view' =>view('deliveryman.restaurant.order.request', compact('orders'))->render(),
                'currentOrders' => count($orders),
            ], 200);


        } catch (\Throwable $exception) {
            Log::error('Error fetching orders: ' . $exception->getMessage());
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }


    public function get_latest_orders(Request $request)
    {
        $dm = DeliveryMan::find(auth('delivery_men')->user()->id);

        // Fetch orders, excluding rejected ones
        $orders = Order::with(['customer', 'lovedOne'])
            ->where('restaurant_id', $dm->restaurant_id)
            ->whereNotIn('id', Session::get('dmOrderRejectIds') ?? [])
            ->whereIn('order_status', ['pending', 'confirmed'])
            ->orderBy('schedule_at', 'desc')
            ->get();

        return response()->json([
            'view' => view('deliveryman.restaurant.order.request', compact('orders'))->render(),
            'currentOrders' => $orders->count(),
        ], 200);
    }


    public function confirmOrder(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
            $dmData = new JsonDataService($dm->id);
            $dmData = $dmData->readData();
            if(!$dmData->active)
            throw new \Exception('You Are Offline');
            // Check if the order status is "reject"
            if ($request->query('status') == "reject") {
                $reject = Session::get('dmOrderRejectIds');
                if ($reject) {
                    $reject[] = $orderId;
                    Session::put('dmOrderRejectIds', $reject);
                } else {
                    Session::put('dmOrderRejectIds', [$orderId]);
                }
                return response()->json(['message' => 'Order Rejected'], 200);

            }

            // Fetch the order with specified conditions
            $order = Order::with('customer')
            ->where('id', $orderId)
            ->whereNull('delivery_man_id')
            ->whereIn('order_status', ['pending', 'confirmed'])
            ->first();


            // If order not found, return a 403 response
            if (!$order) {
                return response()->json(['message' => 'Order Not Found'], 403);
            }

            // Fetch the delivery man

            // Update order status and assign delivery man
            $order->order_status = in_array($order->order_status, ['pending', 'confirmed']) ? 'accepted' : $order->order_status;
            $order->delivery_man_id = $dm->id;
            $order->accepted = now();
            $order->save();

            // Update delivery man's current orders count
            $dm->current_orders += 1;
            $dm->save();

            // Increment assigned order count
            $dm->increment('assigned_order_count');

            // Send notification to customer
            $fcm_token = $order->customer->cm_firebase_token;
            $data = [
                'title' => __('messages.order_push_title'),
                'description' => '',
                'order_id' => $order['id'],
                'image' => '',
                'type' => 'order_status'
            ];
            Helpers::send_notification($fcm_token, $data);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }

        return response()->json(['message' => 'Order accepted successfully'], 200);
    }



    public function record_location_data(Request $request)
    {
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        DB::table('delivery_histories')->insert([
            'delivery_man_id' => $dm['id'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude'],
            'time' => now(),
            'location' => $request['location'],
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return response()->json(['message' => 'location recorded'], 200);
    }

    public function get_order_history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $history = DeliveryHistory::where(['order_id' => $request['order_id'], 'delivery_man_id' => $dm['id']])->get();
        return response()->json($history, 200);
    }

    public function update_order_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
            'status' => 'required|in:confirmed,canceled,picked_up,delivered',
            'reason' =>'required_if:status,canceled',
        ]);

        $validator->sometimes('otp', 'required', function ($request) {
            return (Config::get('order_delivery_verification')==1 && $request['status']=='delivered');
        });

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        $order = Order::where('id', $request['order_id'])->first();
        if(!isset($order)){
            return response()->json([_('messages.order_not_found')], 403);
        }

        if($request['status'] =="confirmed" && config('order_confirmation_model') == 'restaurant')
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order-confirmation-model', 'message' => __('messages.order_confirmation_warning')]
                ]
            ], 403);
        }

        if($request['status'] == 'canceled' && !config('canceled_by_deliveryman'))
        {
            return response()->json([
                'errors' => [
                    ['code' => 'status', 'message' => __('messages.you_can_not_cancel_a_order')]
                ]
            ], 403);
        }

        if(isset($order->confirmed ) && $request['status'] == 'canceled')
        {
            return response()->json([
                'errors' => [
                    ['code' => 'delivery-man', 'message' => __('messages.order_can_not_cancle_after_confirm')]
                ]
            ], 403);
        }

        if(Config::get('order_delivery_verification')==1 && $request['status']=='delivered' && $order->otp != $request['otp'])
        {
            return response()->json([
                'errors' => [
                    ['code' => 'otp', 'message' => 'Not matched']
                ]
            ], 406);
        }
        if ($request->status == 'delivered' || isset($order->subscription_id))
        {
            if($order->transaction == null)
            {
                $reveived_by = $order->payment_method == 'cash_on_delivery'?($dm->type != 'zone_wise'?'restaurant':'deliveryman'):'admin';

                if(OrderLogic::create_transaction($order,$reveived_by, null))
                {
                    $order->payment_status = 'paid';
                }
                else
                {
                    return response()->json([
                        'errors' => [
                            ['code' => 'error', 'message' => __('messages.faield_to_create_order_transaction')]
                        ]
                    ], 406);
                }
            }
            if($order->transaction)
            {
                $order->transaction->update(['delivery_man_id'=>$dm->id]);
            }

            $order->details->each(function($item, $key){
                if($item->food)
                {
                    $item->food->increment('order_count');
                }
            });
            $order->customer ?  $order->customer->increment('order_count') : '';

            $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
            $dm->save();

            $dm->increment('order_count');
            $order->restaurant->increment('order_count');

        }
        else if($request->status == 'canceled')
        {
            if($order->delivery_man)
            {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders>1?$dm->current_orders-1:0;
                $dm->save();
            }


            if(!isset($order->confirmed) && isset($order->subscription_id)){
                $order->subscription()->update(['status' => 'canceled']);
                if($order->subscription->log){
                    $order->subscription->log()->update([
                        'order_status' => $request->status,
                        'canceled' => now(),
                        ]);
                }
            }

            $order->cancellation_reason = $request->reason;
            $order->canceled_by = 'deliveryman';
        }

        if($request->status == 'confirmed' &&  $order->delivery_man_id == null){
            $order->delivery_man_id = $dm->id;
        }
        // dd($request['status']);
        $order->order_status = $request['status'];
        $order[$request['status']] = now();
        $order->save();
        try {
            Helpers::send_order_notification($order);
        } catch (\Exception $th) {
            info($th);
        }

        OrderLogic::update_subscription_log($order);
        return response()->json(['message' => 'Status updated'], 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        // OrderLogic::create_subscription_log($request->order_id);
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();
        $order = Order::with(['details'])->where('id',$request['order_id'])->where(function($query) use($dm){
            $query->whereNull('delivery_man_id')
                ->orWhere('delivery_man_id', $dm['id']);
        })->Notpos()->first();
        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 404);
        }
        $details = Helpers::order_details_data_formatting($order->details);
        return response()->json($details, 200);
    }

    public function get_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $order = Order::with(['customer', 'restaurant','details', 'lovedOne'])->where(['delivery_man_id' => $dm['id'], 'id' => $request['order_id']])->Notpos()->first();
        if(!$order)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'order', 'message' => translate('messages.not_found')]
                ]
            ], 204);
        }
        return response()->json(Helpers::order_data_formatting($order), 200);
    }

    public function get_all_orders(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required',
            'offset' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $dm = DeliveryMan::where(['auth_token' => $request['token']])->first();

        $paginator = Order::with(['customer', 'restaurant', 'lovedOne'])
        ->where(['delivery_man_id' => $dm['id']])
        ->whereIn('order_status', ['delivered','canceled','refund_requested','refunded','refund_request_canceled','failed'])
        ->HasSubscriptionInStatus(['delivered','canceled','refund_requested','refunded','refund_request_canceled','failed'])
        ->orderBy('schedule_at', 'desc')
        ->Notpos()
        ->paginate($request['limit'], ['*'], 'page', $request['offset']);
        $orders= Helpers::order_data_formatting($paginator->items(), true);
        $data = [
            'total_size' => $paginator->total(),
            'limit' => $request['limit'],
            'offset' => $request['offset'],
            'orders' => $orders
        ];
        return response()->json($data, 200);
    }

    public function get_last_location(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $last_data = DeliveryHistory::whereHas('delivery_man.orders', function($query) use($request){
            return $query->where('id',$request->order_id);
        })->latest()->first();
        return response()->json($last_data, 200);
    }


    public function orderList(Request $request, $state )
    {
        // $= $r?equest->query('state');
        $today = Carbon::today()->toDateString();

        $orders = MessDeliverymanOrderAccept::with('customer','checklist.coupon.customerSubscriptionTxns')->where('dm_id', auth('delivery_men')->user()->id)
            ->when($state == 'newly', function ($query) use($today) {
                return $query->whereDate('created_at', $today)->latest()->paginate(10);
            })
            ->when($state == 'all', function($query) {
                return $query->latest()->paginate(10);
            })
            ->when($state == 'accepted', function($query) use($today) {
                return $query->whereDate('created_at', $today)
                            ->where('status', 'accepted')->latest()->paginate(10);
            })
            ->when($state == 'rejected', function($query) {
                return $query->where('status', 'rejected')->latest()->paginate(10);
            })
            ->when($state == 'pickedUp', function($query) use($today) {
                return $query->whereDate('created_at', $today)
                            ->where('status', 'pickedUp')->latest()->paginate(10);
            })
            ->when($state == 'delivered', function($query) use($today) {
                return $query->where('status', 'delivered')->latest()->paginate(10);
            });
            // ->get();
            // dd($orders);
            // Helpers::format_time()

        return view('deliveryman.mess.order.list', compact('orders', 'state'));
    }

    public function orderTrack($dmOrderAcceptId)
    {
       $order =  MessDeliverymanOrderAccept::with('customer','checklist.coupon.customerSubscriptionTxns')->find($dmOrderAcceptId);
       return view('deliveryman.order.track', compact('order'));
    }

    public function varifyQR(Request $request){
        try {
            $encrypted_code = $request->get('encrypted_code');
            $otp = $request->get('otp');
            $today = Carbon::today()->toDateString();
            $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);

            if (!empty($encrypted_code)) {
                $qrdata = MessQR::where('encrypted_code', $encrypted_code)->first();
            } elseif (!empty($otp)) {
                $qrdata = MessQR::where('otp', $otp)->whereDate('created_at', $today)->first();
            } else {
                throw new \Exception('Service Not Available');
            }

            if ($qrdata) {
                $coupon = DietCoupon::find($qrdata->diet_coupon_id);
                if($coupon->state === "redeem"){
                    throw new \Exception('Coupon Is Already Used !!');
                }
                $coupon->state = "redeem";
                $coupon->save();


                $deliveryman->messOrderAccept()->updateOrCreate(
                    [
                        'checkList_id' => $qrdata->attendance_checklist_id,
                        'dm_id' => $deliveryman->id,
                        'mess_qrId' => $qrdata->id,
                    ],
                    [
                        'status' => 'delivered',
                    ]
                );

                $qrdata->checked_at = now();
                if ($qrdata->save()) {
                    return response()->json(['success' => 'Delivery Success']);
                } else {
                    throw new \Exception('Something Going Wrong');
                }
            } else {
                throw new \Exception('Invalid Authentication');
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
}
