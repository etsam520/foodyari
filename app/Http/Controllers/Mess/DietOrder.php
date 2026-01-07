<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Middleware\mess;
use App\Models\AttendaceCheckList;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DeliveryMan;
use App\Models\DietCoupon;
use App\Models\MessDeliveryMan;
use App\Models\MessQR;
use App\Models\MessService;
use App\Models\MessTiffin;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use PgSql\Lob;
use SebastianBergmann\CodeCoverage\Driver\Selector;

class DietOrder extends Controller
{
    protected $today ;
    public function __construct()
    {
        $this->today = Carbon::now()->toDateString();
    }
    public function index() : View {
        $messdeliverman = DeliveryMan::where('mess_id',Session::get('mess')->id)->latest()->get();

        
        return view('mess-views.orders.allotment', compact('messdeliverman'));
    }

    public function getOrderedCustomers(Request $request)
    { 
        try{
            $service_key = $request->get('service_name');
           $service_name = Helpers::getService($service_key);
            if(empty($service_name)){
                throw new \Error('Empty Service Name'); 
            }
            $attendances = Attendance::with('checklist.coupon.customerSubscriptionTxns.customer')
                                     ->whereDate('created_at', $this->today)
                                     ->whereHas('checklist', function($query) use($service_name) {
                                         $query->where('sign_to_delivery', 0)->where('service', $service_name);
                                     })
                                     ->where('mess_id',Session::get('mess')->id )
                                     ->get();
           
            if($attendances->count() == 0){
                throw new \Exception('Data Not Fount');
            }

            $coupons = [];

            foreach ($attendances as $attendance) {
                foreach ($attendance->checklist as $checklist) {
                    if ($checklist->coupon->customerSubscriptionTxns->delivery == 1) {  
                        $coupons[] = $checklist->coupon;
                    }
                }
            }

            
        return response()->json(['coupons'=> $coupons]);return response()->json(['customers'=> $attendances]);
        }catch(\Exception $ex){
            return response()->json(['message'=> $ex->getMessage()],500);
        }
        
    }


    public function getTiffinNo()
    { 
        try{
            $today = Carbon::today()->toDateString();
            $usedTiffinIds = MessQR::whereDate('created_at', $today)
                ->where('mess_id',Session::get('mess')->id )
                ->pluck('tiffin_id')
                ->toArray();
            $tiffins  = MessTiffin::whereNotIn('id', $usedTiffinIds)
                        ->where('mess_id',Session::get('mess')->id)
                        ->where('visible',true)
                        ->latest()->get();
            if(!$tiffins){
                throw new \Error('Data Not Fount');
            }
        return response()->json($tiffins);
        }catch(\Exception $ex){
            return response()->json(['error', $ex->getMessage()]);
        }
        
    }

    public function orderAllotSubmit(Request $request)
    {
        try {
            
            $request->validate([
                "delivery_man" => 'required',
                "setFor" => 'required',
                "coupon_id" => 'required',
                "tiffin" => 'required',
            ]);
          
            $deliveryman = DeliveryMan::find($request->delivery_man);
            $today =Carbon::today()->toDateString(); 
            
            $checklist = AttendaceCheckList::with(['coupon.customerSubscriptionTxns.customer'])
            ->whereDate('created_at', $today)
            ->where('service', Helpers::getService($request->setFor))
            ->where('sign_to_delivery', 0)
            ->where('coupon_id', $request->coupon_id)->first();
            
            if (!$checklist) {
                return response()->json(['message' => 'Attendance for today not found'], 404);
            }
            $qr = MessQR::where('attendance_checklist_id', $checklist->id)->first();
            if (!$qr) {
                return response()->json(['message' => 'QR not Generated Yet'], 404);
            }
            
            $tiffin = MessTiffin::find($request->tiffin); 
           
            $qr->mess_deliveryman_id = $deliveryman->id;
            $qr->tiffin_id = $tiffin->id;
            $qr->save();

            $checklist->sign_to_delivery = 1;
            $checklist->save();

            $deliveryman->messOrderAccept()->updateOrCreate(
                [
                    'customer_id' => $checklist->coupon->customer_id,
                    'checkList_id' => $checklist->id,
                    'dm_id' => $deliveryman->id,
                    'mess_qrId' => $qr->id,
                ],
                [
                    'status' => 'accepted',
                    'delivery_address' => $checklist->coupon->customerSubscriptionTxns->delivery_address,
                    'coordinates' => $checklist->coupon->customerSubscriptionTxns->coordinates,
                ]
            );
            DB::commit();
            return response()->json(['success' => 'Ordered To Delivery Man']);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['error' => $ex->getMessage()]);
        }
    }

    public function listOfOrderToDeliveryMany()
    {
        $orderList = AttendaceCheckList::whereDate('created_at', $this->today)->whereIn('sign_to_delivery',[1])
            ->with(['allotTodeliveryMen' => function($query) {
                $query->withPivot(['cash_to_collect', 'tiffin_id','status','customer_id']); // Use withPivot to eager load pivot data
            }])
            ->get();
      return response()->json($orderList);
    }

    public function getCustomerById(Request $request){
        try{
            $customer_id = $request->get('customer_id');
            if(!$customer_id){
                throw new \Exception('Empty Id');
            }
            $customer = Customer::with('user')->where('id',$customer_id)->first();
            if(!$customer->id){
                throw new \Exception('Customer Not Fount');
            }
            return response()->json($customer);
        }catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
    public function getTiffinById(Request $request){
        try{
            $tiffin_id = $request->get('tiffin_id');
            if(!$tiffin_id){
                throw new \Exception('Empty Id');
            }
            $tiffin = MessTiffin::where('id',$tiffin_id)->first();
            if(!$tiffin->id){
                throw new \Exception('Tiffin Not Found');
            }
            return response()->json($tiffin);
        }catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()]);
        }
    }
}
