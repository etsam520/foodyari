<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Models\Order;
use App\Models\Category;
use App\Models\Food;
use App\Models\OrderDetail;
use App\Models\Admin;
use App\Models\RestaurantWallet;
use App\Models\AdminWallet;
use App\Models\ItemCampaign;
use App\Models\BusinessSetting;
use App\Models\DeliveryMan;
use App\Models\ZoneBusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function list(Request $request, $status)
    {

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
        $orders = Order::with(['orderCalculationStmt'])

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
            })->when(isset($filter) && $filter == 'this_month', function ($query) {
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
            ->where('restaurant_id', Session::get('restaurant')->id)
            ->orderBy('schedule_at', 'desc')
            ->get();
        $statusKey = $status;
        $status = __('messages.' . $status);
        // dd($orders);

        return view('vendor-views.order.list', compact('orders', 'status', 'from', 'to', 'filter', 'statusKey'));
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
            'view' => view('vendor-views.order.partials._table', compact('orders'))->render()
        ]);
    }

    public function details(Request $request, $id)
    {
        // OrderLogic::create_subscription_log($id);
        $order = Order::with(['restaurant', 'orderCalculationStmt', 'customer' => function ($query) {
            return $query->withCount('orders');
        }, 'delivery_man' => function ($query) {
            return $query->withCount('orders');
        }])->where(['id' => $id, 'restaurant_id' => Session::get('restaurant')->id])->first();
        $delivery_man = Helpers::deliverymen_list_formatting(DeliveryMan::all());
        // dd($order);
        if (isset($order)) {
            return view('vendor-views.order.order-view', compact('order', 'delivery_man'));
        } else {
            return back()->with('info', 'No more orders!');
        }
    }

    public function order_status_update(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'order_status' => 'required|in:confirmed,pending,processing,handover,delivered,canceled',
                'cancel_reason' => 'required_if:order_status,canceled',
            ], [
                'id.required' => 'Order id is required!'
            ]);
            // dd('slksd');

            $order = Order::with('customer')->where('id', $request->id)->where('restaurant_id', Session::get('restaurant')->id)->first();

            if ($order->delivered != null) {
                return back()->with('warning', __('messages.cannot_change_status_after_delivered'));
            }

            if ($request['order_status'] == 'canceled' && $order['order_status'] == 'canceled') {
                return back()->with('warning', 'Order already Cancelled');
            }

            if ($request['order_status'] == 'canceled' && $order->confirmed) {
                return back()->with('warning', __('messages.you_can_not_cancel_after_confirm'));
            }
            if ($request['order_status'] == 'pending' && $order->order_status != "pending") {
                return back()->with('warning', __('You Can\'t make order Pending After Confirmation'));
            }

            if ($request['order_status'] == 'delivered' && $order->order_type != 'take_away') {
                return back()->with('warning', __('messages.you_can_not_delivered_delivery_order'));
            }

            $notification = [
                'type' => 'Manual',
                'subject' => null,
                'message' => "Order no  #$order->id",
            ];
            if ($request->order_status == 'delivered') {

                if ($order->transaction  == null || isset($order->subscription_id)) {
                    if ($order->payment_method == 'cash_on_delivery') {
                        $ol = OrderLogic::create_transaction($order, 'restaurant', null);
                    } else {
                        $ol = OrderLogic::create_transaction($order, 'admin', null);
                    }


                    if (!$ol) {
                        return back()->with('warning', __('messages.faield_to_create_order_transaction'));
                    }
                }

                $order->payment_status = 'paid';

                $order->details->each(function ($item, $key) {
                    if ($item->food) {
                        $item->food->increment('order_count');
                    }
                });
                $order->customer ?  $order->customer->increment('order_count') : '';
            }
            if ($request->order_status == 'canceled' || $request->order_status == 'delivered') {
                if ($order->delivery_man) {
                    $dm = $order->delivery_man;
                    $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                    $dm->save();
                }
            }

            if ($request->order_status == 'canceled') {
                $order->cancellation_reason = $request->reason;
                $order->canceled_by = 'restaurant';
                $order->canceled = now();
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('order_cancled_message', $order->getZoneId()) ?? "Order Canceled";
                $notification['body'] = $request->reason;


                if ($order->customer) {
                    Helpers::sendOrderNotification($order->customer, $notification);
                }
            }
            if ($request->order_status == 'delivered') {
                $notification['message'] = ZoneBusinessSetting::getSettingValue('admin_order_delivered_message', $order->getZoneId()) ?? "Order Delivered";

                $order->restaurant->increment('order_count');
                if ($order->delivery_man) {
                    $order->delivery_man->increment('order_count');
                }
            }

            $order->order_status = $request->order_status;
            if ($request->order_status == "processing") {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_processing_message', $order->getZoneId()) ?? "Order Processing";
                Helpers::sendOrderNotification($order->customer, $notification);
                $order->processing_time = $request->processing_time;
            }

            if ($request->order_status == "handover") {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('admin_order_handovered_message', $order->getZoneId()) ?? "Order Handovered";
                Helpers::sendOrderNotification($order->customer, $notification);
            }

            if ($request->order_status == "confirmed") {
                $notification['subject'] = ZoneBusinessSetting::getSettingValue('order_confirm_message', $order->getZoneId()) ?? "Order Confirmed";
                Helpers::sendOrderNotification($order->customer, $notification);
            }

            $order[$request['order_status']] = now();
            $order->save();

            return back()->with('success', __('messages.order') . ' ' . __('messages.status_updated'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
            dd($th);
        }
    }

    public function order_dm_assign_manually(Request $request, OrderLogic $orderLogic)
    {
        $delivery_man_id = $request->get('delivery_man_id');
        $order_id = $request->get('order_id');
        if ($delivery_man_id == 0 || $delivery_man_id == '' || $delivery_man_id == null) {
            return response()->json(['message' => __('messages.deliveryman') . ' ' . __('messages.not_found')], 404);
        }
        $order = Order::find($order_id);
        $deliveryman = DeliveryMan::where('id', $delivery_man_id)->first();
        if ($order->delivery_man_id == $delivery_man_id) {
            return response()->json(['message' => __('messages.order_already_assign_to_this_deliveryman')], 400);
        }
        if ($deliveryman) {
            if ($deliveryman->current_orders >= $orderLogic->dmMaxOrders) {
                return response()->json(['message' => __('messages.dm_maximum_order_exceed_warning')], 400);
            }
            $cash_in_hand = isset($deliveryman->wallet) ? $deliveryman->wallet->amount : 0;
            $dm_max_cash = $orderLogic->dm_max_cash_in_hand;
            $value = $dm_max_cash ?? 0;
            if ($order->payment_method == "cash_on_delivery" && (($cash_in_hand + $order->order_amount) >= $value)) {
                return response()->json(['message' => __('delivery man max cash in hand exceeds')], 400);
            }
            if ($order->delivery_man) {
                $dm = $order->delivery_man;
                $dm->current_orders = $dm->current_orders > 1 ? $dm->current_orders - 1 : 0;
                // $dm->decrement('assigned_order_count');
                $dm->save();

                $data = [
                    'title' => __('messages.order_push_title'),
                    'description' => __('messages.you_are_unassigned_from_a_order'),
                    'order_id' => '',
                    'image' => '',
                    'type' => 'assign'
                ];
                // Helpers::send_push_notif_to_device($dm->fcm_token, $data);

                // DB::table('user_notifications')->insert([
                //     'data' => json_encode($data),
                //     'delivery_man_id' => $dm->id,
                //     'created_at' => now(),
                //     'updated_at' => now()
                // ]);
            }
            $order->delivery_man_id = $delivery_man_id;
            $order->order_status = in_array($order->order_status, ['pending', 'confirmed']) ? 'accepted' : $order->order_status;
            $order->accepted = now();
            $order->save();

            $deliveryman->current_orders = $deliveryman->current_orders + 1;
            $deliveryman->save();
            $deliveryman->increment('assigned_order_count');
            $value = Helpers::order_status_update_message('accepted');
            // try {
            //     if ($value && $order->customer) {
            //         // $fcm_token = $order->customer->cm_firebase_token;
            //         $data = [
            //             'title' => __('messages.order_push_title'),
            //             'description' => $value,
            //             'order_id' => $order['id'],
            //             'image' => '',
            //             'type' => 'order_status'
            //         ];
            //         // Helpers::send_push_notif_to_device($fcm_token, $data);

            //         // DB::table('user_notifications')->insert([
            //         //     'data' => json_encode($data),
            //         //     'user_id' => $order->customer->id,
            //         //     'created_at' => now(),
            //         //     'updated_at' => now()
            //         // ]);
            //     }
            //     $data = [
            //         'title' => __('messages.order_push_title'),
            //         'description' => __('messages.you_are_assigned_to_a_order'),
            //         'order_id' => $order['id'],
            //         'image' => '',
            //         'type' => 'assign'
            //     ];
            //     // Helpers::send_push_notif_to_device($deliveryman->fcm_token, $data);
            //     // DB::table('user_notifications')->insert([
            //     //     'data' => json_encode($data),
            //     //     'delivery_man_id' => $deliveryman->id,
            //     //     'created_at' => now(),
            //     //     'updated_at' => now()
            //     // ]);
            // } catch (\Exception $e) {
            //     info($e);
            //     return response()->json(['error' =>__('messages.push_notification_faild') ], 200);
            // }
            return response()->json([], 200);
        }
        return response()->json(['message' => __('Deliveryman not available!')], 400);
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
        $order = Order::with('orderCalculationStmt')->where(['id' => $id])->first();
        return view('vendor-views.order.invoice', compact('order'));
    }

    public function generate_KOT($id)
    {
        $order = Order::find($id);
        return view('vendor-views.order._KOT', compact('order'));
    }

    // public function add_payment_ref_code(Request $request, $id)
    // {
    //     Order::where(['id' => $id, 'restaurant_id' => Helpers::get_restaurant_id()])->update([
    //         'transaction_reference' => $request['transaction_reference']
    //     ]);

    //     Toastr::success('Payment reference code is added!');
    //     return back();
    // }
}
