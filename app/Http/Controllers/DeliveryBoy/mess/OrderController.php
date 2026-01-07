<?php

namespace App\Http\Controllers\DeliveryBoy\mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\AttendaceCheckList;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\DietCoupon;
use App\Models\MessDeliverymanOrderAccept;
use App\Models\MessFoodProcessing;
use App\Models\MessQR;
use App\Models\MessTiffin;
use App\Notifications\FirebaseNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;

class OrderController extends Controller
{
    public function getOrders() {
        try {
            $today = Carbon::now()->toDateString();
            $deliveryMan = auth('delivery_men')->user();
            $orders = [];
    
            // Check if there's a food process ready to deliver
            $foodProcess = MessFoodProcessing::whereDate('created_at', $today)
                                              ->where('mess_id', $deliveryMan->mess_id)
                                              ->where('steps', 'readyToDeliver')
                                              ->first();
    
          
    
            // Get attendances with specific criteria
            $attendances = Attendance::with('checklist.coupon.customerSubscriptionTxns.customer')
                                     ->whereDate('created_at', $today)
                                     ->whereHas('checklist', function($query) {
                                         $query->where('sign_to_delivery', 0);
                                     })
                                     ->where('mess_id', $deliveryMan->mess_id)
                                     ->get();
            $dmRejectedOrder = MessDeliverymanOrderAccept::whereDate('created_at', $today)->where('dm_id', $deliveryMan->id)
                                        ->where('status', 'rejected')->first();

            foreach ($attendances as $attendance) {
                foreach ($attendance->checklist as $checklist) {

                    if (!empty($dmRejectedOrder) && ($checklist->id == $dmRejectedOrder->checkList_id)) {
                        continue;
                    }
                    $coupon = $checklist->coupon;
                    if ($coupon->customerSubscriptionTxns->delivery == 1) {
                        
                        $orders[] = $coupon;
                    }
                }
            }
    
            return response()->json([
                'view' =>view('deliveryman.mess.order.request', compact('orders'))->render(),
                'currentOrders' => count($orders),
            ], 200);

    
        } catch (\Throwable $exception) {
            Log::error('Error fetching orders: ' . $exception->getMessage());
            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    public function confirmOrder(Request $request) {
        $today = Carbon::now()->toDateString();
        $deliveryman = DeliveryMan::find(auth('delivery_men')->user()->id) ;
        $couponId = $request->query('coupon_id');
        $status = $request->query('status');
    
        
        try {
            DB::beginTransaction();
            $checklist = AttendaceCheckList::with(['coupon.customerSubscriptionTxns.customer'])
            ->whereDate('created_at', $today)
            ->where('coupon_id', $couponId)->first();
            if (!$checklist) {
                return response()->json(['message' => 'Attendance for today not found'], 404);
            }
            $qr = MessQR::where('attendance_checklist_id', $checklist->id)->first();
            if (!$qr) {
                return response()->json(['message' => 'QR not Generated Yet'], 404);
            }
            
            if ($status === 'accept') {
                $putStatus = 'accepted';
                $usedTiffinIds = MessQR::whereDate('created_at', $today)
                ->where('mess_id', $deliveryman->mess_id)->pluck('tiffin_id')->toArray();
            
                $tiffin = MessTiffin::whereNotIn('id', $usedTiffinIds)->where('mess_id',$deliveryman->mess_id)
                        ->where('visible',true)->latest()->first();
               
                $qr->mess_deliveryman_id = $deliveryman->id;
                $qr->tiffin_id = $tiffin->id;
                $qr->save();
                $checklist->sign_to_delivery = 1;
                $checklist->save();
            }elseif ($status === 'reject') {
                $putStatus = 'rejected';
            }elseif ($status === 'pickedUp') {
                $putStatus = 'pickedUp';
                $customer = Customer::find($qr->customer_id);
                $title = "Your Food is Picked up To deliver";
                $customer->notify(new FirebaseNotification($title, null, null, [],200));
            }else{
                throw new \Exception('You can\'t Proceed This order Further');
            }
            $deliveryman->messOrderAccept()->updateOrCreate(
                [
                    'customer_id' => $checklist->coupon->customer_id,
                    'checkList_id' => $checklist->id,
                    'dm_id' => $deliveryman->id,
                    'mess_qrId' => $qr->id,
                ],
                [
                    'status' => $putStatus,
                    'delivery_address' => $checklist->coupon->customerSubscriptionTxns->delivery_address,
                    'coordinates' => $checklist->coupon->customerSubscriptionTxns->coordinates,
                    'accepted_at' => $putStatus === 'accept' ? Carbon::now()->toDateTimeString() : null,
                ]
            );
       
            DB::commit();
    
            return response()->json(['message' => 'Order status updated successfully'], 200);
        } catch (\Throwable $exception) {
            DB::rollBack();
            // dd()
            Log::error('Error confirming order: ' . $exception->getMessage());
            return response()->json(['message' => 'An error occurred while updating the order status'], 500);
        }
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
