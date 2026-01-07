<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Admin\appartus\ZoneHelper;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\DeliveryMan;
use App\Models\DeliveryManCashInHand;
use App\Models\LovedOneWithOrder;
use App\Models\Order;
use App\Models\Zone;
use App\Models\ZoneBusinessSetting;
use App\Services\JsonDataService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
 
    public function list(Request $request, $status)
    {
        $zone = ZoneHelper::getOrderZone();
        
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'this_month');
        if ($filter == 'custom') {
            $dateRange = $request->date_range;
            if ($dateRange == null) {
                return back()->with('info', "Date range can\'t be null");
            }
            $dates = explode(" to ", $dateRange);

            $from = $dates[0] ?? null;
            $to = $dates[1] ?? null;
        }
        $key = explode(' ', $request['search']);

        $orders = Order::whereHas('restaurant', function ($query) use ($zone) {
                $query->when(!in_array($zone, [null, 'all']), function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });
            })
            ->with(['customer', 'details', 'restaurant', 'lovedOne'])
            ->when($status == 'confirmed', function ($query) {
                return $query->whereIn('order_status', ['confirmed', 'accepted'])->whereNotNull('confirmed');
            })
            ->when($status == 'pending', function ($query) {
                return $query->Pending();
            })
            ->when($status == 'accepted', function ($query) {
                return $query->AccepteByDeliveryman();
            })
            ->when($status == 'processing', function ($query) {
                return $query->Preparing();
            })
            ->when($status == 'food_on_the_way', function ($query) {
                return $query->FoodOnTheWay();
            })
            ->when($status == 'delivered', function ($query) {
                return $query->Delivered();
            })
            ->when($status == 'handover', function ($query) {
                return $query->Handovered();
            })
            ->when($status == 'canceled', function ($query) {
                return $query->Canceled();
            })
            ->when($status == 'failed', function ($query) {
                return $query->failed();
            })
            ->when($status == 'requested', function ($query) {
                return $query->Refund_requested();
            })
            ->when($status == 'rejected', function ($query) {
                return $query->Refund_request_canceled();
            })
            ->when($status == 'refunded', function ($query) {
                return $query->Refunded();
            })
            ->when($status == 'requested', function ($query) {
                return $query->Refund_requested();
            })
            ->when($status == 'rejected', function ($query) {
                return $query->Refund_request_canceled();
            })
            ->when($status == 'scheduled', function ($query) {
                return $query->Scheduled();
            })
            ->when($status == 'on_going', function ($query) {
                return $query->Ongoing();
            })->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
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
            ->orderBy('schedule_at', 'desc')
            ->get();

        $statusKey = $status;
        $status = __('messages.' . $status);


        return view('admin-views.order.list', compact('orders', 'status', 'from', 'to', 'filter', 'statusKey'));
    }

    public function search(Request $request)
    {
        $key = explode(' ', $request['search']);
        $orders = Order::where(['restaurant_id' => Helpers::get_restaurant_id()])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->Notpos()->limit(100)->get();
        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render()
        ]);
    }

    public function details(Request $request, $id)
    {
        // OrderLogic::create_subscription_log($id);
        $order = Order::with(['delivery_man','orderCalculationStmt', 'restaurant', 'lovedOne', 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }])->where(['id' => $id])->first();
        $delivery_man = null;
        // if($order->delivery_man_id == null){
        $delivery_man = Helpers::adminDeliveryMan($order->restaurant->zone_id);
        $delivery_man = Helpers::deliverymen_list_formatting($delivery_man);
        // }

        //  return $delivery_man;
            // return $order;
        // dd();
        if (isset($order)) {
            return view('admin-views.order.order-view', compact('order', 'delivery_man'));
        } else {
            return back()->with('info', 'No more orders!');
        }
    }

    public function order_status_update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'order_status' => 'required|in:confirmed,pending,processing,handover,delivered,canceled,order_on_way,arrived_at_door,dm_at_restaurant',
                'cancel_reason' => 'required_if:order_status,canceled',
            ], [
                'id.required' => 'Order id is required!'
            ]);
            // dd('slksd');

            $order = Order::with(['customer', 'lovedOne'])->where(['id' => $request->id])->first();
            $zoneId = $order->zone_id;
            if($zoneId == null){
               $zoneId = Order::leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
                ->where('orders.id', $order->id)
                ->value('restaurants.zone_id');
            }


            if ($order->delivered != null) {
                return back()->with('warning', __('messages.cannot_change_status_after_delivered'));
            }

            if ($request['order_status'] == 'canceled' && $order['order_status'] == 'canceled') {
                return back()->with('warning', 'Order already Cancelled');
            }

            // if($request['order_status']=='canceled' && $order->confirmed )
            // {
            //     return back()->with('warning',__('messages.you_can_not_cancel_after_confirm'));
            // }
            if ($request['order_status'] == 'pending' && $order->order_status != "pending") {
                return back()->with('warning', __('You Can\'t make order Pending After Confirmation'));
            }

            // if($request['order_status']=='delivered' && $order->order_type != 'take_away')
            // {
            //     return back()->with('warning',__('messages.you_can_not_delivered_delivery_order'));
            // }
            $notification = [
                'type' => 'Manual',
                'subject' => null,
                'message' => "Order no  #$order->id",
            ];

            if ($request->order_status == 'delivered') {

                $ol = OrderLogic::order_transaction($order->id, null, "admin");

                if (!$ol) {
                    return back()->with('warning', __('messages.faield_to_create_order_transaction'));
                }

                $order->payment_status = 'paid';
                // $order->details->each(function($item, $key){
                //     if($item->food)
                //     {
                //         $item->food->increment('order_count');
                //     }
                // });
                // $order->customer ?  $order->customer->increment('order_count') : '';
            }



            if ($request->order_status == 'canceled') {
                $order->cancellation_reason = $request->reason;
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_cancel_message', $zoneId);
                $notification['body'] = $request->reason;
                $order->canceled_by = 'admin';
                $order->canceled = now();
                $order->cancellation_reason = $request->cancel_reason??"No reason provided";
                if ($order->customer) {
                    Helpers::sendOrderNotification($order->customer, $notification);
                }
            }
            if ($request->order_status == 'delivered') {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_delivered_message', $zoneId);
                Helpers::sendOrderNotification($order->customer, $notification);
                
                // Fire OrderDelivered event for referral processing
                event(new \App\Events\OrderDelivered($order));
            }
            if ($request->order_status == "processing") {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_processing_message', $zoneId);
                Helpers::sendOrderNotification($order->customer, $notification);
                $order->processing_time = $request->processing_time;
            }

            if ($request->order_status == "handover") {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_handovered_message', $zoneId);
                Helpers::sendOrderNotification($order->customer, $notification);
            }

            if ($request->order_status == "confirmed") {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_confirmed_message', $zoneId);
                $order->confirmed = now();
                Helpers::sendOrderNotification($order->customer, $notification);
            }


            $order[$request->order_status] = now();
            $order->order_status = $request->order_status;
            $order->save();
            return back()->with('success', __('messages.order') . ' ' . __('messages.status_updated'));
        } catch (\Throwable $th) {
            throw $th;
            // dd($th);
        }
    }

    public function order_dm_assign_manually(Request $request)
    {
        try {

            $delivery_man_id = $request->query('dm_id');
            $order_id = $request->get('order_id');
            if ($delivery_man_id == 0 || $delivery_man_id == '' || $delivery_man_id == null) {
                return response()->json(['message' => __('messages.deliveryman') . ' ' . __('messages.not_found')], 404);
            }
            $order = Order::with(['customer', 'delivery_man', 'lovedOne'])->find($order_id);
            $zoneId = $order->zone_id;
            if($zoneId == null){
                $zoneId = Order::leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
                 ->where('orders.id', $order->id)
                 ->value('restaurants.zone_id');
             }
            $deliveryman = DeliveryMan::with('wallet')->where('id', $delivery_man_id)->first();

            if ($order->delivery_man_id == $delivery_man_id) {
                return response()->json(['message' => __('messages.order_already_assign_to_this_deliveryman')], 400);
            }
            $dmData = new JsonDataService($deliveryman->id);
            $dmData = $dmData->readData();
            if (!$dmData->active) {
                throw new \Exception('Deliveryman Offline');
            }
            if ($deliveryman) {
                // if ($deliveryman->current_orders >= $orderLogic->dmMaxOrders) {
                //     return response()->json(['message' => __('messages.dm_maximum_order_exceed_warning')], 400);
                // }
                $cashInhand = DeliveryManCashInHand::where('deliveryman_id', $deliveryman->id)->first();

                $cashInhandLimit = ZoneBusinessSetting::getSettingValue('dm_max_cash_in_hand', $zoneId);
                $cashInhand->balance = $cashInhand->balance + $order->cash_to_collect;

                if ($cashInhand->balance > $cashInhandLimit) {
                    throw new \Error('Cash To Collect Touched the max limit of Cash In hand. Please settle down the cash in hand amount.');
                }

                $order->delivery_man_id = $delivery_man_id;
                if ($order->accepted == null) {
                    $order->order_status = in_array($order->order_status, ['pending', 'confirmed']) ? 'accepted' : $order->order_status;
                    $order->accepted = now();
                }

                $order->save();
                $this->createDmOrderProcess($order, $dmData);

                $notification = [
                    'type' => 'Manual',
                    'subject' => null,
                    'message' => "Order no  #$order->id",
                    'subject' =>  '',
                ];

                if ($order->delivery_man) {
                    $notification['subject'] = "Assigned Order Removed From Your Target";
                    Helpers::sendOrderNotification($order->delivery_men, $notification);
                }
                if ($deliveryman) {
                    $notification['subject'] = ZoneBusinessSetting::getSettingValue('dm_order_placed_message', $zoneId);
                    Helpers::sendOrderNotification($order->delivery_men, $notification);
                }
                return response()->json([], 200);
            }
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => $th->getMessage()], 400);
        }
    }
    private function createDmOrderProcess($order, $dmData)
    {
        $restaurantLocation = json_decode($order->restaurant->coordinates, true);
        $origin = $dmData->last_location['lat'] . "," . $dmData->last_location['lng'];
        $destination = $restaurantLocation['latitude'] . "," . $restaurantLocation['longitude'];

        $googleDirections = Helpers::googleDirections($origin, $destination);

        $orderProcess = \App\Models\DmOrderProcess::updateOrCreate([
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

    public function update_shipping(Request $request, $id)
    {
        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
        ]);

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'floor' => $request->floor,
            'road' => $request->road,
            'house' => $request->house,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('customer_addresses')->where('id', $id)->update($address);
        // Toastr::success('Delivery address updated!');
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::with(['orderCalculationStmt', 'restaurant', 'customer', 'lovedOne'])
            ->findOrfail($id);

        return view('admin-views.order.invoice', compact('order'));
    }

    public function generate_KOT($id)
    {
        $order = Order::find($id);
        return view('admin-views.order._KOT', compact('order'));
    }

    // public function add_payment_ref_code(Request $request, $id)
    // {
    //     Order::where(['id' => $id, 'restaurant_id' => Helpers::get_restaurant_id()])->update([
    //         'transaction_reference' => $request['transaction_reference']
    //     ]);

    //     Toastr::success('Payment reference code is added!');
    //     return back();
    // }

    public function topOrders(Request $request)
    {
        
        $zone = ZoneHelper::getOrderZone();
        $today = Carbon::now();

        $orders = Order::whereDate('created_at', $today)
            ->whereHas('restaurant', function ($query) use ($zone) {
                $query->when($zone != 'all', function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });
            })
            ->with(['customer', 'details', 'restaurant'])

            ->orderBy('schedule_at', 'desc')
            ->limit(15)->get();

        return response()->json([
            'view' => view('admin-views.dashboard-partials.latest-orders', compact('orders'))->render(),
        ]);
    }
}
