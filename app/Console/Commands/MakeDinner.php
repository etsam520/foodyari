<?php

namespace App\Console\Commands;

use App\CentralLogics\Helpers;
use App\Models\Attendace;
use App\Models\Attendance;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DietCoupon;
use App\Models\MessService;
use App\Models\Subscription;
use App\Models\VendorMess;
use App\Models\WeeklyChart;
use Carbon\Carbon;
use Error;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MakeDinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:makeDinner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Dinner Attaindance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{

            $today = Carbon::now('Asia/Kolkata')->toDateString(); 
            $timenow = Carbon::now('Asia/Kolkata');
            $day = strtolower($timenow->format('l'));//
            $week = $timenow->weekOfMonth;
            $diet_name = Helpers::getService('D');
            
            foreach(VendorMess::latest()->get() as $mess) {
                $mess_id = $mess->id;
                
                $weeklychart = WeeklyChart::where('week',$week )->where('mess_id',$mess_id)->where('day', $day)->first();
                if(!$weeklychart)
                {
                    // Log::info('weekly chart '.$weeklychart);
                    continue;
                }
                $speciality = $weeklychart[$diet_name];
                if($speciality == Helpers::getSpeciality('O')){
                    Log::info("Service Off for ".strtoupper($diet_name));
                    continue;
                }
                
                
                $messService = MessService::where('mess_id', $mess_id)
                    ->where('name', $diet_name)
                    ->first();
                // dd($messService);
                    
                if($messService && $messService->status == 1) {
                    $endTime = Carbon::parse($messService->available_time_starts)->addMinutes(30);
                    // if (!$endTime->isPast()) {
                        $customerSubscriptionTxns = CustomerSubscriptionTransactions::where('mess_id', $mess->id)
                                                                                    ->where('expiry', '>=', $today)
                                                                                    ->where('diet_status' , true)
                                                                                    ->get();
                        if($customerSubscriptionTxns->isNotEmpty()) {
                            
                            foreach ($customerSubscriptionTxns as $customerSubscriptionTxn) {
                                $coupons = CustomerSubscriptionTransactions::haveDietCoupons($speciality!="special"?$diet_name: null)->find($customerSubscriptionTxn->id)->dietCoupons;
                                Log::info($coupons);
                                
                                if ($coupons->isNotEmpty()) {
                                    foreach ($coupons as $coupon) {
                                        DB::beginTransaction();
                                        $coupnData = DietCoupon::find($coupon->id);
                                        $attendance = Attendance::whereDate('created_at', $today)
                                                                ->where('customer_id', $coupon->customer_id)
                                                                ->where('subscription_id', $customerSubscriptionTxn->subscription_id)
                                                                ->where('mess_id', $mess->id)
                                                                ->firstOrNew();

                                        // Set the attributes
                                        $attendance->customer_id = $coupon->customer_id;
                                        $attendance->subscription_id = $customerSubscriptionTxn->subscription_id;
                                        $attendance->mess_id = $mess->id;

                                        // Save the instance
                                        $attendance->save();

                                        Log::info('Customer attendance saved: ' . json_encode($attendance->toArray()));
                                       
                            
                                        if ($speciality == Helpers::getSpeciality('S')) {
                                            $coupnData->diet_name = $diet_name;
                                            $coupnData->speciality = $speciality;
                                        } 
                            
                                        $checklist = $attendance->checklist()->Create([
                                                            'service' => $diet_name,
                                                            'attendance_date' => $today,
                                                            'attendance_time' => $timenow->toTimeString(),
                                                            'checked' => 1,
                                                            'mess_id' => $mess_id,
                                                            'coupon_id' => $coupnData->id
                                                        ]);
                            
                                        if ($checklist) {
                                            $coupnData->state = 'pending';
                                            $coupnData->save();
                                            DB::commit();
                                            Log::info('attendance success for cusId :'.$attendance->customer_id);
                                        } else {
                                            DB::rollBack();
                                        }
                                    }
                                }else{
                                    Log::error('Coupons not available');
                                }
                            }  
                        }
                    // }else{
                    //     Log::error("time ended");
                    // }
                }
            }
            return "attendance success for dinner";
         }catch(\Exception $ex){
          Log::error($ex->getMessage()) ;
        } 
    }
}
