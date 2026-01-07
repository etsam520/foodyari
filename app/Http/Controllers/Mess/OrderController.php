<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Middleware\mess;
use App\Models\DeliveryMan;
use App\Models\MessSubscritionPackageTransaction;
use App\Models\PaymentDetails;
use App\Models\SubscriptionOrderDetails;
use App\Models\VendorMess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function list($status)
    {

        $orders = SubscriptionOrderDetails::with(['customer','paymentDetail', 'orderItems'])
        ->when($status == 'confirmed', function ($query) {
            return $query->where('status', 'confirmed');
        })
        ->when($status == 'pending', function ($query) {
            return $query->where('status', 'pending');
        })
        ->when($status == 'canceled', function ($query) {
            return $query->where('status', 'canceled');
        })
        ->where('mess_id', Session::get('mess')->id)
        ->orderBy('created_at', 'desc')
        ->paginate(15);

        $status = __('messages.'.$status);

        // dd($orders);
       

        return view('mess-views.subscriptionPackageOrder.list', compact('orders', 'status',));
    }

    public function search(Request $request){
        $key = explode(' ', $request['search']);
        $orders=SubscriptionOrderDetails::where(['mess_id'=>Helpers::get_restaurant_id()])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->Notpos()->limit(100)->get();
        return response()->json([
            'view'=>view('vendor-views.order.partials._table',compact('orders'))->render()
        ]);
    }

    public function details(Request $request,$id)
    {
        // OrderLogic::create_subscription_log($id);
        $order = SubscriptionOrderDetails::with([
            'paymentDetail',
            'orderItems',
            'customer' => function($query) {
                $query->with([
                    'customerAddress' => function($q) {
                        $q->latest()->limit(1);
                    }
                ])->withCount('orders');
            }
        ])->find($id);

        $delivery_man = Helpers::deliverymen_list_formatting(DeliveryMan::all());
        // dd($order);
        if (isset($order)) {
            return view('mess-views.subscriptionPackageOrder.order-view', compact('order','delivery_man'));
        } else {
            return back()->with('info' ,'No more orders!');
        }
    }

    public function status(Request $request)
{
    $request->validate([
        'id' => 'required',
        'order_status' => 'required|in:confirmed,pending,canceled',
        'reason' =>'required_if:order_status,canceled',
    ], [
        'id.required' => 'Order id is required!'
    ]);

    
    $order = SubscriptionOrderDetails::with(['paymentDetail', 'orderItems', 'customer'])
        ->where(['id' => $request->id, 'mess_id' => Session::get('mess')->id])
        ->first();

    if (!$order) {
        return back()->with('error', 'Order not found.');
    }

    if ($order->status == 'confirmed') {
        return back()->with('warning', 'Can\'t change the status because it is already confirmed.');
    }
    if ($order->status == 'canceled') {
        return back()->with('warning', 'Can\'t change the status because it is already Cancelled.');
    }

    if ($request->order_status == 'canceled' && $request->order_status != $order->status) {
        $order->cancel_reason = $request->reason . "\n \n \t Canceled by: Mess";
        $order->status = 'canceled';
        $order->save();
        return back()->with('success', 'Order Cancelled.');
    }
    

    if ($request->order_status == 'confirmed') {
        $payment = PaymentDetails::find($order->payment_details_id);
        if($payment->method == 'cash'){
            $payment->status = 'paid';
            $payment->save();
        }
        
    }

    $order->status = $request->order_status;
    $order->save();

    return back()->with('success', __('messages.order').' '.__('messages.status_updated'));
}


    public function order_dm_assign_manually(Request $request, OrderLogic $orderLogic)
    {
        $delivery_man_id = $request->get('delivery_man_id');
        $order_id = $request->get('order_id');
        if ($delivery_man_id == 0 ||$delivery_man_id == '' || $delivery_man_id == null) {
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
            $dm_max_cash =$orderLogic->dm_max_cash_in_hand;
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
        Toastr::success('Delivery address updated!');
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::where(['id' => $id, 'restaurant_id' => Helpers::get_restaurant_id()])->first();
        return view('vendor-views.order.invoice', compact('order'));
    }

    public function add_payment_ref_code(Request $request, $id)
    {
        Order::where(['id' => $id, 'restaurant_id' => Helpers::get_restaurant_id()])->update([
            'transaction_reference' => $request['transaction_reference']
        ]);

        Toastr::success('Payment reference code is added!');
        return back();
    }
}
