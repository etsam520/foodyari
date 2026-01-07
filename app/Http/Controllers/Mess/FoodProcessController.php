<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\DietCoupon;
use App\Models\MessFoodProcessing;
use App\Models\MessQR;
use App\Models\WeeklyChart;
use App\Notifications\FirebaseNotification;
use Carbon\Carbon;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FoodProcessController extends Controller
{
    public function process(Request $request)
    {
        
        try {
            $date = Carbon::now();
            $weekNumber =(int) ceil($date->day / 7);
            $dayName = Helpers::getDayname($date->format('D'));
            $messId = Session::get('mess')->id;
            $todayOfWeeklyChart = WeeklyChart::where('day', $dayName)
                                    ->where('week', $weekNumber) ->where('mess_id',$messId)->first();
            

            $service = $request->query('service');
            $processName = $request->query('process_name');
            if(empty($service) || empty($processName)){
                throw new Error('Ruquest Fields Can\'t be Null');
            }
            $attendanceData =  self::attendanceData($service,$messId,$processName == 'readyToDeliver'?$processName:null);
            if($attendanceData[$service]['total'] < 1){
                throw new Error('You Don\'t have any Attendances Today to make Food' );
            }
            

            if($processName == 'processed')
            {
                DB::beginTransaction();
                $foodProcess = new MessFoodProcessing();
                $foodProcess->service = $service;
                $foodProcess->speciality = $todayOfWeeklyChart[$service];
                $foodProcess->steps = $processName;
                $foodProcess->mess_id = $messId;
                $foodProcess->data = json_encode($attendanceData);
                $foodProcess->dine_in = $attendanceData[$service]['dine_in'];
                $foodProcess->delivery = $attendanceData[$service]['delivery'];
                $foodProcess->save();

                $customers = Customer::whereIn('id',$attendanceData[$service]['customer_ids'])->get();
                foreach($customers as $customer){
                    $title = "Dear $customer->f_name your Meal is being prepared";
                    $customer->notify(new FirebaseNotification($title, null, null, [],200));
                }
                DB::commit();
                return response()->json(['message' => 'Status Changed',
                                        'link' => route('mess.dashboard')],200);
                
            
            }elseif($processName == 'readyToDeliver')
            {
                DB::beginTransaction();
                
                $foodProcess = new MessFoodProcessing();
                $foodProcess->service = $service;
                $foodProcess->speciality = $todayOfWeeklyChart[$service];
                $foodProcess->steps = $processName;
                $foodProcess->mess_id = $messId;
                $foodProcess->data = json_encode($attendanceData);
                $foodProcess->dine_in = $attendanceData[$service]['dine_in'];
                $foodProcess->delivery = $attendanceData[$service]['delivery'];
                $foodProcess->save();
                $deliveryMen = DeliveryMan::where('mess_id', $messId)->where('status', 1)->get();
                foreach($deliveryMen as $dm)
                {
                    $title = "New Orders Made Grab it";
                    $dm->notify(new FirebaseNotification($title, null, null, [],200));
                }
                DB::commit();
                return response()->json(['message' => 'Status Changed',
                'link' => route('mess.dashboard')],200);
                
            }elseif($processName == 'delivered'){
                DB::beginTransaction();
                $foodProcess = new MessFoodProcessing();
                $foodProcess->service = $service;
                $foodProcess->speciality = $todayOfWeeklyChart[$service];
                $foodProcess->steps = $processName;
                $foodProcess->mess_id = $messId;
                $foodProcess->data = json_encode($attendanceData);
                $foodProcess->dine_in = $attendanceData[$service]['dine_in'];
                $foodProcess->delivery = $attendanceData[$service]['delivery'];
                $foodProcess->save();
                DB::commit();
                return response()->json(['message' => 'Status Changed',
                'link' => route('mess.dashboard')],200);

            }else{
                throw new Error('Values Not Assigned');
            }
            
        } catch (\Throwable $th) {
            DB::rollBack();
            // dd($th->getMessage());
           return response()->json(['message'=> $th->getMessage()],500);
        }
        
    }


    public static function attendanceData($serviceName,$messId,$processName=null) {
        $today = Carbon::now()->toDateString();
        
        // Eager load related models to avoid N+1 problem
        $attendances = Attendance::with(['checklist' => function($query) use ($serviceName) {
            $query->where('service', $serviceName);
        }])->whereDate('created_at', $today)->where('mess_id',$messId )->get();
        
        $attCounts = [
            $serviceName => [
                'total' => 0,
                'dine_in' => 0,
                'delivery' => 0,
                'attendance_ids' => [],
                'customer_ids' => [],
            ]
        ];
        
        foreach($attendances as $att) {

            foreach($att->checklist as $chk_list) {
                $attCounts[$serviceName]['total']++;

                $coupon = DietCoupon::with('customerSubscriptionTxns')->find($chk_list->coupon_id);
                if($coupon->customerSubscriptionTxns->delivery == 1){
                $attCounts[$serviceName]['delivery']++;
                }elseif ($coupon->customerSubscriptionTxns->delivery == 0) {
                $attCounts[$serviceName]['dine_in']++;
                }

                if($processName == 'readyToDeliver')
                {
                    $qr = MessQR::create(
                        [
                            'customer_id' => $att->customer_id,
                            'attendance_checklist_id' => $chk_list->id,
                            'diet_coupon_id' => $coupon->id,
                            'mess_id' => $messId,
                            'encrypted_code' => md5(json_encode([
                                'customer_id' => $att->customer_id,
                                'attendance_checklist_id' => $chk_list->id,
                                'diet_coupon_id' => $coupon->id,
                            ])),
                            'otp' => mt_rand(100000, 999999), // Generating a random OTP
                        ]
                    );
                    if(!$qr)
                    {
                        Log::info("Qr FAiled To generate $qr");
                    }
                }
            }
            
            $attCounts[$serviceName]['attendance_ids'][] = $att->id;
            $attCounts[$serviceName]['customer_ids'][] = $att->customer_id;
        }
        
        return $attCounts;
    }
    
}
