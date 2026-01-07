<?php

namespace App\Http\Controllers\DeliveryBoy\Admin;

use App\CentralLogics\DeliveryTime\DeliveryTimer;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Models\AdminFund;
use App\Models\BusinessSetting;
use App\Models\CashTransaction;
use App\Models\CustomerAddress;
use App\Models\DeliveryMan;
use App\Models\DeliveryManCashInHand;
use App\Models\DmOrderProcess;
use App\Models\LovedOneWithOrder;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Restaurant;
use App\Models\Wallet;
use App\Models\Zone;
use App\Models\ZoneBusinessSetting;
use App\Services\JsonDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Redis as FacadesRedis;
// use Redis;
use Illuminate\Support\Facades\Session;
use Predis\Client as RedisClient;


class OrderController extends Controller
{
    public function getOrders()
    {
        try {
            $today = Carbon::now()->toDateString();
            $deliveryMan = auth('delivery_men')->user();
            $orders = Order::where('delivery_man_id', $deliveryMan->id)->whereIn('order_status', ['accept', 'confirmed'])->get();

            // $dmRejectedOrder = MessDeliverymanOrderAccept::whereDate('created_at', $today)->where('dm_id', $deliveryMan->id)
            //                             ->where('status', 'rejected')->first();

            $dmRejectedOrder = [];

            // if (!empty($dmRejectedOrder) && ($checklist->id == $dmRejectedOrder->checkList_id)) {
            //     continue;
            // }



            return response()->json([
                'view' => view('deliveryman.restaurant.order.request', compact('orders'))->render(),
                'currentOrders' => count($orders),
            ], 200);
        } catch (\Throwable $exception) {
            Log::error('Error fetching orders: ' . $exception->getMessage());
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function orderDeliveredList(Request $request)
    {
        $filterType = $request->query('filter', 'month');
        $query = Order::with(['customer', 'restaurant', 'details', 'lovedOne'])
            ->where('delivery_man_id', auth('delivery_men')->user()->id)
            ->where('order_status', 'delivered');

        if ($filterType == 'day') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filterType == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        } elseif ($filterType == 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $orders = $query->latest()->get();
        $state = $filterType;


        return view('deliveryman.admin.order.delivered', compact('orders', 'state'));
    }




    public function getOrder(Request $request)
    {
        $orderId = $request->order_id ?? null;
        // dd($request->all());
        if (empty($orderId)) {
            return back()->with('info', "Order Id Can\'t be Null");
        }
        $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
        $today = Carbon::now()->toDateString();
        $dmData = new JsonDataService($dm->id);
        $dmData = $dmData->readData();
        // dd($dmData->last_location);
        $lat = $dmData->last_location['lat'] ?? 0;
        $lng = $dmData->last_location['lng'] ?? 0;



        $order = Order::whereDate('created_at', $today)->with([
            'customer',
            'details',
            'lovedOne',
            'restaurant' => function ($query) use ($lat, $lng) {
                $query->nearby($lat, $lng);
            }
        ])
            ->whereNotIn('id', Session::get('dmOrderRejectIds') ?? [])
            // ->whereIn('order_status', ['pending', 'confirmed'])
            ->orderBy('schedule_at', 'desc')
            ->find($orderId);
        if (empty($order)) {
            return back();
        }

        $timer = new DeliveryTimer($dm->id);
        $restaurantReachOutTimer = $timer->getResturantReachOutTime($order->id, $order);
        $customerReachOutTimer = $timer->getDeliveryTime($order->id, $order);

        // dd($restaurantReachOutTimer, '---', $customerReachOutTimer);
        $location = json_decode($order->delivery_address);
        if (isset($location)) {
            $userPosition = ['lat' => $location->position->lat, 'lon' => $location->position->lon];
            // dd($distance);
            $dmPosition = ['lat' => $lat, 'lon' => $lng];
            $distance = Helpers::haversineDistance($userPosition, $dmPosition);
            $order->distance = $distance;
        } else {
            $order = null;
        }


        return view('deliveryman.admin.order.index', compact('order', 'userPosition', 'dmPosition', 'restaurantReachOutTimer', 'customerReachOutTimer'));
    }

    public function get_latest_orders(Request $request)
    {
        $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
        $attendance = $dm->attendances()->first();
        if ($attendance == null || $attendance->is_online == 0) {
            return response()->json([], 500);
        }
        $today = Carbon::now()->toDateString();
        $dmData = new JsonDataService($dm->id);
        $dmData = $dmData->readData();
        $lat = $dmData->last_location['lat'];
        $lng = $dmData->last_location['lng'];
        $restaurant_ids = Restaurant::nearby($lat, $lng)->where('zone_id', $dm->zone_id)->get('id')->toArray();
        $filter = $request->query('filter') ?? 'all';

        $orders = Order::whereDate('created_at', $today)->with([
            'customer',
            'lovedOne',
            'restaurant' => function ($query) use ($lat, $lng) {
                $query->nearby($lat, $lng);
            }
        ])
            ->whereIn('restaurant_id', $restaurant_ids)
            ->whereNotIn('id', Session::get('dmOrderRejectIds') ?? [])
            ->when($filter == "delivered", function ($query) {
                $query->whereNotNull('delivered');
            })->when($filter == "accepted", function ($query) {
                $query->whereNull('delivered');
            })->when($filter == 'all', function ($query) {
                $query->whereNotIn('order_status', ['delivered', 'canceled']);
            })
            ->where(function ($query) use ($dm) {
                $query->whereNull('delivery_man_id')
                    ->orWhere('delivery_man_id', $dm->id);
            })
            ->orderBy('id', 'desc')
            ->get();


        foreach ($orders as $key => &$ord) {

            $location = json_decode($ord->delivery_address);

            if (isset($location)) {
                $userPosition = ['lat' => $location->position->lat, 'lon' => $location->position->lon];
                $dmPosition = ['lat' => $lat, 'lon' => $lng];
                $distance = Helpers::haversineDistance($userPosition, $dmPosition);
                $ord->distance = $distance;
            } else {
                unset($orders[$key]);
            }
        }

        if ($filter == "all") {
            $current_time = time();
            $midnight = mktime(0, 0, 0, date("n"), date("j") + 1, date("Y"));
            $seconds_until_midnight = $midnight - $current_time;
            setcookie('current_orders_count', count($orders), $current_time + $seconds_until_midnight, "/");
        }

        return response()->json([
            'view' => view('deliveryman.admin.order.request', compact('orders', 'filter'))->render(),
            'currentOrders' => $orders->count(),
        ], 200);
    }


    public function confirmOrder(Request $request)
    {
        try {
            $orderId = $request->query('order_id');
            $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
            if (empty($orderId)) {
                throw new \Error('info', "Order Id Can\'t be Null");
            }
            if (!$dm) {
                throw new \Exception('Delivery not login');
            }
            DB::beginTransaction();

            $dmData = new JsonDataService($dm->id);
            $dmData = $dmData->readData();
            if (!$dmData->active) throw new \Exception('You Are Offline');
            // Check if the order status is "reject"
            if ($request->query('status') == "reject") {
                $reject = Session::get('dmOrderRejectIds');
                if ($reject) {
                    $reject[] = $orderId;
                    Session::put('dmOrderRejectIds', $reject);
                } else {
                    Session::put('dmOrderRejectIds', [$orderId]);
                }

                throw new \Exception('Order Rejected');
                // return response()->json(['message' => 'Order Rejected'], 200);
            }


            // Fetch the order with specified conditions
            $order = Order::with(['customer', 'lovedOne'])
                ->whereNull('delivery_man_id')
                // ->whereIn('order_status', ['pending', 'confirmed'])
                ->find($orderId);



            // If order not found, return a 403 response
            if (!$order) {
                throw new \Exception('Order not found or already assigned');
            }

            // Update order status and assign delivery man
            $order->order_status = in_array($order->order_status, ['pending', 'confirmed']) ? 'accepted' : $order->order_status;
            $order->delivery_man_id = $dm->id;
            $order['accepted'] = now();
            $order['confirmed'] = now();
            $order->save();
            $this->createDmOrderProcess($order, $dmData); // creating Order Process for dm

            $dm->save();

            $notification = [
                'type' => 'Manual',
                'subject' => null,
                'message' => "Order no  #$order->id",
                'subject' => ZoneBusinessSetting::getSettingValue('dm_order_accepted_message', $order->getZoneId()) ?? "Order Confirmed"
            ];
            Helpers::sendOrderNotification($order->customer, $notification);

            // if ($request->ajax()) {
            //     return response()->json(['message' => 'Order accepted successfully'], 200);
            // }
            DB::commit();
            return redirect()->route('deliveryman.admin.order', ['order_id' => $orderId])->with('success', "Order accepted successfully");
        } catch (\Exception $e) {
            DB::rollBack();
            // if ($request->ajax()) {
            //     return response()->json(['message' => $e->getMessage()], 403);
            // }
            return redirect()->route('deliveryman.dashboard')->with('error', $e->getMessage());
        }
    }
    private function createDmOrderProcess($order, $dmData)
    {
        $restaurantLocation = json_decode($order->restaurant->coordinates, true);
        $origin = $dmData->last_location['lat'] . "," . $dmData->last_location['lng'];
        $destination = $restaurantLocation['latitude'] . "," . $restaurantLocation['longitude'];

        $googleDirections = Helpers::googleDirections($origin, $destination);

        $orderProcess = DmOrderProcess::updateOrCreate([
            'order_id' => $order->id,
            'dm_id' => $dmData->dm_id,
        ], [
            'start_time' => now(),
            'start_langitude' => $dmData->last_location['lat'] ?? 0,
            'start_longitude' => $dmData->last_location['lng'] ?? 0,
            'start_address' => $googleDirections['start_address'] ?? '',
            'avg_distance' =>  $googleDirections['distance_value'] ?? 0,

        ]);
        return true;
    }

    public function OrderStageChanger(Request $request)
    {

        $stage = $request->query('stage');
        $orderId = $request->query('order_id');
        $today = Carbon::now()->toDateString();
        // $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id) ;
        try {
            if (!$stage) {
                throw new \Exception('Stage Not Defined');
            }
            $dm = DeliveryMan::find(auth('delivery_men')->user()->id);

            $dmData = new JsonDataService($dm->id);
            $dmData = $dmData->readData();
            if (!$dmData->active)
                throw new \Exception('You Are Offline');

            DB::beginTransaction();

            $order = Order::with(['customer', 'restaurant', 'lovedOne'])
                ->where('id', $orderId)
                ->whereNotNull('delivery_man_id')
                ->whereIn('order_status', ['accepted', 'processing', 'dm_at_restaurant', 'handover', 'picked_up', 'arrived_at_door', 'order_on_way'])
                ->first();
            // dd($order);
            if (!$order) {
                throw new \Exception('Something went wrong');
            }

            if ($stage === 'picked_up') {
                if ($order->restaurant->ready_to_handover) {
                    if ($order->order_status != 'handover') {
                        throw new \Exception('Order doesn\'n handover yet');
                    }
                }
                $order->picked_up = now();
                $this->dmOrderProcessPickUp($order, $dmData);

                $order->order_status = $stage;
                if ($order->save()) {
                    $notification = [
                        'type' => 'Manual',
                        'subject' => 'Your Order is Out to Deliver',
                        'message' => "Order no  #$order->id",
                    ];
                    Helpers::sendOrderNotification($order->customer, $notification);
                }
            } elseif ($stage === 'arrived_at_door') {
                if (!in_array($order->order_status, ['picked_up', 'order_on_way'])) {
                    throw new \Exception('Order isn\'t picked up or on the way yet.');
                }
                $order->arrived_at_door = now();
                $order->order_status = $stage;
                if ($order->save()) {

                    $notification = [
                        'type' => 'Manual',
                        'subject' => 'Delivery Man at Your Door Step',
                        'message' => "Order no  #$order->id",
                    ];
                    Helpers::sendOrderNotification($order->customer, $notification);
                }
            } elseif ($stage === 'collect_cash') {
                return redirect()->route('deliveryman.admin.order-payment-option');
            } else {
                throw new \Exception('You can\'t no method available yet');
            }

            DB::commit();

            return back()->with('success', "Order status updated successfully");
        } catch (\Throwable $exception) {
            DB::rollBack();
            // dd($exception);
            return back()->with('info', $exception->getMessage());
        }
    }

    public function dmOrderProcessPickUp($order, $dmData)
    {
        $redis = new RedisClient();
        $customers = [];

        $restaurantLocation = json_decode($order->restaurant->coordinates, true);
        $deliveryAddress = json_decode($order->delivery_address, true);

        $origin = $restaurantLocation['latitude'] . "," . $restaurantLocation['longitude'];
        $destination = $deliveryAddress['position']['lat'] . "," . $deliveryAddress['position']['lon'];
        $googleDirections = Helpers::googleDirections($origin, $destination);
        $durationInTraffic = $googleDirections['duration_in_traffic_value'] ?? $googleDirections['duration_value'] ?? 0;

        $processing_time = now()->diffInSeconds($order->processing ?? null, false);

        $processingTime = $order->processing_time ?? 0; // in minutes
        $processingAt = Carbon::parse($order->processing); // a Carbon instance or parsed datetime
        $processingEnd = $processingAt->copy()->addMinutes($processingTime);

        $processing_time = now()->diffInSeconds($processingEnd, false);
        if ($processing_time < 0) {
            $processing_time = 0;
        }
        $processing_time =  $processing_time + $durationInTraffic + (5 * 60);

        $deliverby = Carbon::now()->addSeconds($processing_time);

        $order->dmOrderProcess()->update([
            'picked_up_time'     => $order->picked_up->toDateTimeString(),
            'picked_up_langitude' => $restaurantLocation['latitude'],
            'picked_up_longitude' => $restaurantLocation['longitude'],
            'end_langitude'       => $deliveryAddress['position']['lat'],
            'end_longitude'      => $deliveryAddress['position']['lon'],
            'end_address'        => $googleDirections['end_address'] ?? null,
            'avg_distance'       => $order->dmOrderProcess->avg_distance + ($googleDirections['distance_value'] ?? 0),
            'deliver_by'         => $deliverby->toDateTimeString(),
        ]);

        // $redis->set("deliveryman:{$dm->id}:has_order");
        $dm_has_order = $redis->get("deliveryman:{$dmData->dm_id}:has_order") ?? null;
        if ($dm_has_order === '1') {
            $customers = $redis->get("deliveryman:{$dmData->dm_id}:order_customer_ids");
            $customers = json_decode($customers, true);
        }

        if (!in_array($order->customer_id, $customers)) {
            $customers[] = $order->customer_id;
        }
        $redis->set("deliveryman:{$dmData->dm_id}:has_order", 1);
        $redis->set("deliveryman:{$dmData->dm_id}:order_customer_ids", json_encode($customers));
        return true;
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
    
    public function orderList(Request $request, $state)
    {
        // $= $r?equest->query('state');.
        $today = Carbon::today()->toDateString();

        $orders = Order::with(['customer', 'restaurant', 'details', 'lovedOne'])->where('delivery_man_id', auth('delivery_men')->user()->id)
            ->when($state == 'newly', function ($query) use ($today) {
                return $query->whereDate('created_at', $today)->latest()->paginate(10);
            })
            ->when($state == 'all', function ($query) {
                return $query->latest()->paginate(10);
            })
            ->when($state == 'accepted', function ($query) use ($today) {
                return $query->whereDate('created_at', $today)
                    ->whereIn('order_status', ['accepted', 'processing', 'handover'])->latest()->paginate(10);
            })
            ->when($state == 'rejected', function ($query) {
                return $query->where('order_status', 'rejected')->latest()->paginate(10);
            })
            ->when($state == 'pickedUp', function ($query) use ($today) {
                return $query->whereDate('created_at', $today)
                    ->where('order_status', 'picked_up')->latest()->paginate(10);
            })
            ->when($state == 'delivered', function ($query) use ($today) {
                return $query->where('order_status', 'delivered')->latest()->paginate(10);
            });
        // dd($orders);
        // Helpers::format_time()

        return view('deliveryman.admin.order.list', compact('orders', 'state'));
    }

    public function orderTrack($dmOrderAcceptId)
    {
        $today = Carbon::now()->toDateString();
        $dm = auth('delivery_men')->user();
        $order =  Order::with('customer')->find($dmOrderAcceptId);
        $dmData = new JsonDataService($dm->id);
        $dmData = $dmData->readData();
        $dmPosition = ['lat' => $dmData->last_location['lat'], 'lon' => $dmData->last_location['lng']];

        $location = json_decode($order->delivery_address);
        $userPosition = ['lat' => $location->position->lat, 'lon' => $location->position->lon];
        $distance = Helpers::haversineDistance($userPosition, $dmPosition);
        $order->distance = $distance;
        return view('deliveryman.admin.order.track', compact('order'));
    }

    public function varifyQR(Request $request)
    {
        try {
            $encrypted_code = $request->get('encrypted_code');
            $otp = $request->get('otp');
            $orderId = $request->query('order_id') ?? null;
            $today = Carbon::today()->toDateString();
            $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);
            $order = Order::with('customer')->where('otp', $otp)->where('delivery_man_id', $deliveryman->id)->whereDate('created_at', $today)->find($orderId);

            if ($order) {

                Cache::put('varified_order', $order, $second = 60);
                return response()->json([
                    'success' => 'Order Varified',
                    'link' => route('deliveryman.admin.order-deliver') . "?payment_type={$order->payment_method}&order_id={$order->id}",
                ]);
            } else {
                throw new \Exception('Invalid OTP');
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    public function paymentOption()
    {
        $order = Cache::get('varified_order', null);
        // dd($order);
        return view('deliveryman.admin.order.payment-option', compact('order'));
    }

    public function order_deliver(Request $request)
    {

        // dd($request);
        $orderId = $request->order_id ?? null;
        $order = Order::with('restaurant')->find($orderId);
        $zone = Zone::find($order->restaurant->zone_id);
        $varificationRequired = $zone->delivery_verification;
        $paymentMethod = $request->payment_type ?? $order->payment_method;

        $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);
        try {
            DB::beginTransaction();
            if ($varificationRequired) {
                $varifiedOrder = Cache::get('varified_order', null);
                if (empty($varifiedOrder)) {
                    throw new \Exception('Order Not Verified');
                }
            }
            //    dd( $order );
            if ($paymentMethod == 'online') {
                // handle online process
            } elseif ($paymentMethod == 'cash') {

                $cashInhand = DeliveryManCashInHand::where('deliveryman_id', $deliveryman->id)->first();
                $cashInhandLimit = ZoneBusinessSetting::getSettingValue('dm_max_cash_in_hand', $zone->id);
                $cashInhand->balance = $cashInhand->balance + $order->cash_to_collect;

                if ($cashInhand->balance > $cashInhandLimit) {
                    throw new \Error('Cash To Collect Touched the max limit of Cash In hand. Please settle down the cash in hand amount.');
                }

                if ($order->cash_to_collect > 0) {
                    $cashInhand->cashTxns()->create([
                        'amount' => $order->cash_to_collect,
                        'txn_type' => 'received',
                        'received_from' => 'customer',
                        'paid_to' => 'deliveryman',
                        'customer_id' => $order->customer_id,
                        'remarks' => "Cash Collect for Order : #" . $order->id,
                    ]);
                    $cashInhand->save();
                }
            } else {
                if ($order->payment_status != 'paid') {
                    throw new \Error('Order is Unpaid');
                }
            }

            $ol = OrderLogic::order_transaction($order->id, null);

            if (!$ol) {
                return throw new \Exception(__('messages.faield_to_create_order_transaction'));
            }


            $order->order_status = 'delivered';
            $order->delivered = now();
            $order->payment_status = 'paid';
            $order->dmOrderProcess()->update(['end_time' => now()]);
            $order->save();

            DB::commit();
            Cache::delete('varified_order');
            $notification = [
                'type' => 'Manual',
                'subject' => ZoneBusinessSetting::getSettingValue('dm_order_delivered_message', $order->getZoneId()) ?? "Order Delivered",
                'message' => "Order no  #$order->id",
            ];
            Helpers::sendOrderNotification($order->customer, $notification);
             // Fire OrderDelivered event for referral processing
            event(new \App\Events\OrderDelivered($order));

            return redirect()->route('deliveryman.dashboard')->with('success', 'Order Delivered Successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th);
            return redirect()->back()->with('info', $th->getMessage());
        }
    }

    /*
    public function varidfdffyQR(Request $request){
        try {
            $encrypted_code = $request->get('encrypted_code');
            $otp = $request->get('otp');
            $today = Carbon::today()->toDateString();
            $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id);


                $order = Order::where('otp', $otp)->where('delivery_man_id', $deliveryman->id)->whereDate('created_at', $today)->first();

            if ($order) {
                Helpers::order_transaction_process_by_deliveryMan($order->id);
                $order->order_status = 'delivered';
                $order->delivered = now();
                $order->payment_status = 'paid';
                if ($order->save()) {
                    return response()->json(['success' => 'Delivery Success']);
                } else {
                    throw new \Exception('Something Going Wrong');
                }
            } else {
                throw new \Exception('Invalid OTP');
            }
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }*/

    /* this is avilable in $ol = OrderLogic::order_transaction($order, null);


    public static function order_transaction($orderId, $received_by = null)
    {
        try {
            DB::beginTransaction();
            // Fetch the order with related restaurant and details
            $order = Order::with(['restaurant', 'details', 'delivery_man'])->find($orderId);
            $deliveryMan = $order->delivery_man;
            $ADMIN = Helpers::getAdmin();

            // Initialize variables
            $admin_amount = 0;
            $restaurant_amount = 0;
            $discountByRestaurant = 0;
            $dmTips = 0;
            $adminCommissionByRestaurant = 0;
            $commissionPercent = 0;
            $total_food_price = 0;
            $balance_match = 0;

            // Calculate total food price and restaurant expense
            foreach ($order->details as $detailedItem) {
                $foodPrice = $detailedItem->price * $detailedItem->quantity;
                $discountByRestaurant += $detailedItem->discount_on_food * $detailedItem->quantity;
                $foodPrice += $detailedItem->addon_price + $detailedItem->variation_price;
                $total_food_price += $foodPrice;
            }

            $total_food_price -= $discountByRestaurant;

            // Calculate admin commission based on subscription type
            if ($order->restaurant->subscription_type == 'commission') {
                if ($order->restaurant->commission > 0) {
                    $adminCommissionByRestaurant = $order->restaurant->commission;
                } else {
                    $commissionPercent = ZoneBusinessSetting::getSettingValue('admin_commission', $order->getZoneId());
                    if ($commissionPercent > 0) {
                        $adminCommissionByRestaurant = ($total_food_price * $commissionPercent) / 100;
                    }
                }
            }

            $restaurant_amount = $total_food_price - $adminCommissionByRestaurant;
            $restaurant_amount += $order->total_tax_amount;
            $admin_amount += $adminCommissionByRestaurant;

            // Calculate delivery man expense
            $deliveryCharge = $order->delivery_charge;
            $dmTips = $order->dm_tips;
            $deliveryCommissionInPercentage = 0;
            $deliveryCommissionAmount = 0;

            if ($deliveryMan->earning == 1) { // earning 1 for freelancer and 0 for salary based
                $deliveryCommissionInPercentage = ZoneBusinessSetting::getSettingValue('delivery_charge_comission', $order->getZoneId());
                if ($deliveryCommissionInPercentage > 0) {
                    $deliveryCommissionAmount = ($deliveryCharge * $deliveryCommissionInPercentage) / 100;
                    $deliveryCharge -= $deliveryCommissionAmount;
                }
                $admin_amount += $deliveryCommissionAmount;
                $balance_match += $deliveryCharge;
            } else {
                $admin_amount += $deliveryCharge;
            }
            // dd($dmTips);
            $balance_match = $admin_amount + $restaurant_amount  + $dmTips;

            if (round($order->order_amount) != round($balance_match)) {
                throw new \Error("unmatched figure Order amount : $order->order_amount and Balcanced : $balance_match" );
            }

            // Define the data array for OrderTransaction insertion
            $data = [
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'delivery_man_id' => $order->delivery_man_id,
                'foods_amount' => $total_food_price,
                'discount_by_admin' => 0,
                'discount_by_restaurant' => $discountByRestaurant,
                'custom_discount_by_admin' => 0,
                'custom_discount_by_restaurant' => 0,
                'coupon_discount_by_admin' => 0,
                'coupon_discount_by_restaurant' => 0,
                'platform_charge' => 0,
                'free_delivery' => 0,
                'admin_commission_on_restaurant' => $adminCommissionByRestaurant,
                'admin_commission_on_deliveryman' => $deliveryCommissionAmount,
                'total_delivery_charge' => $order->delivery_charge,
                'net_delivery_charge' => $deliveryCharge,
                'dm_tips' => $order->dm_tips ?? 0,
                'received_by' => $received_by ?? $order->customer->f_name,
                'zone_id' => $order->restaurant->zone_id,
                'status' => true,
                'delivery_service_provider' => 'admin',
                'tax' => $order->total_tax_amount,
                'restaurant_amount' => $restaurant_amount,
                'admin_amount' => $admin_amount,
                'commission_percentage' => $commissionPercent,
            ];


          $orderTxn = OrderTransaction::create($data);
          DB::commit();
          return self::order_transaction_based_amount_transfer($orderTxn->id);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }


    public static function order_transaction_based_amount_transfer($order_transaction_id)
    {
        // Fetch the order transaction
        $orderTxn = OrderTransaction::find($order_transaction_id);
        if (!$orderTxn) {
            throw new \Exception('Order transaction not found');
        }

        // Fetch the order with related restaurant and customer
        $order = Order::with(['restaurant', 'customer'])->find($orderTxn->order_id);
        if (!$order) {
            throw new \Exception('Order not found');
        }

        // Fetch wallets
        $dmWallet = Wallet::where('deliveryman_id', $order->delivery_man_id)->first();
        $adminFund = AdminFund::getFund();
        $vendorWallet = Wallet::where('vendor_id', $order->restaurant->vendor_id)->first();

        $deliveryMan = DeliveryMan::find($order->delivery_man_id);
        $ADMIN = Helpers::getAdmin(); // Assuming this fetches the admin details

        if (!$dmWallet || !$adminFund || !$vendorWallet || !$deliveryMan) {
            throw new \Exception('Wallets or delivery man not found');
        }

        DB::beginTransaction();
        try {
            if ($deliveryMan->earning == 1) {
                // Transfer net delivery charge from admin fund to delivery man wallet
                $adminFund->balance -= $orderTxn->net_delivery_charge;
                $adminFund->txns()->create([
                    'amount' => $orderTxn->net_delivery_charge,
                    'txn_type' => 'paid',
                    'paid_to' => 'deliveryman',
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => "Delivery Charge Paid To {$deliveryMan->f_name} (Deliveryman) for the Order No: #{$order->id}"
                ])->save();

                $dmWallet->balance += $orderTxn->net_delivery_charge;
                $dmWallet->WalletTransactions()->create([
                    'amount' => $orderTxn->net_delivery_charge,
                    'admin_id' => $ADMIN->id,
                    'type' => 'received',
                    'remarks' => 'Delivery Charge Accepted From Admin',
                ])->save();
            }

            if ($orderTxn->dm_tips > 0) {
                // Transfer delivery man tips from admin fund to delivery man wallet
                $adminFund->balance -= $orderTxn->dm_tips;
                $adminFund->txns()->create([
                    'amount' => $orderTxn->dm_tips,
                    'txn_type' => 'paid',
                    'paid_to' => 'deliveryman',
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => "Delivery Man Tips Paid To {$deliveryMan->f_name} (Deliveryman) for the Order No: #{$order->id}"
                ])->save();

                $dmWallet->balance += $orderTxn->dm_tips;
                $dmWallet->WalletTransactions()->create([
                    'amount' => $orderTxn->dm_tips,
                    'admin_id' => $ADMIN->id,
                    'type' => 'received',
                    'remarks' => 'Delivery Tips Accepted From Admin',
                ])->save();
            }

            // Transfer restaurant amount to vendor wallet
            $adminFund->balance -= $orderTxn->restaurant_amount;
            $adminFund->txns()->create([
                'amount' => $orderTxn->restaurant_amount,
                'txn_type' => 'paid',
                'paid_to' => 'vendor',
                'vendor_id' => $order->restaurant->vendor_id,
                'restaurant_id' => $order->restaurant->id,
                'remarks' => "Restaurant \"" . ucfirst($order->restaurant->name) . "\" Order Amount paid for the Order No: #{$order->id}"
            ])->save();

            $vendorWallet->balance += $orderTxn->restaurant_amount;
            $vendorWallet->WalletTransactions()->create([
                'amount' => $orderTxn->restaurant_amount,
                'admin_id' => $ADMIN->id,
                'type' => 'received',
                'restaurant_id' =>  $order->restaurant->id,
                'remarks' => "Restaurant \"" . ucfirst($order->restaurant->name) . "\" Order Amount Accepted for the Order No: #{$order->id}",
            ])->save();
            $adminFund->save();
            $vendorWallet->save();
            $dmWallet->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
        */

    /**
     * Get updated timers for an order
     */
    public function getUpdatedTimers(Request $request)
    {
        try {
            $orderId = $request->order_id;
            
            if (empty($orderId)) {
                return response()->json(['success' => false, 'message' => 'Order ID is required'], 400);
            }

            $dm = DeliveryMan::find(auth('delivery_men')->user()->id);
            $dmData = new JsonDataService($dm->id);
            $dmData = $dmData->readData();
            
            $order = Order::with(['customer', 'details', 'lovedOne', 'restaurant'])
                ->find($orderId);

            if (empty($order)) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $timer = new DeliveryTimer($dm->id);
            $restaurantReachOutTimer = $timer->getResturantReachOutTime($order->id, $order);
            $customerReachOutTimer = $timer->getDeliveryTime($order->id, $order);

            return response()->json([
                'success' => true,
                'restaurantTimer' => $restaurantReachOutTimer,
                'customerTimer' => $customerReachOutTimer,
                'message' => 'Timers updated successfully'
            ]);

        } catch (\Throwable $exception) {
            Log::error('Error getting updated timers: ' . $exception->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to update timers'
            ], 500);
        }
    }
}
