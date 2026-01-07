<?php

namespace App\Console\Commands;

use App\CentralLogics\Helpers;
use App\Models\Attendace;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DietCoupon;
use App\Models\MessService;
use App\Models\Subscription;
use App\Models\VendorMess;
use App\Models\WeeklyChart;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

// class MakeBreakFastAttendance extends Command
// {
//     /**
//      * The name and signature of the console command.
//      *c
//      * @var string
//      */
//     protected $signature = 'attendance:makeBreakfast';

//     /**
//      * The console command description.
//      *
//      * @var string
//      */
//     protected $description = 'Make Break Fast Attendance';

//     /**
//      * Execute the console command.
//      */
//     public function handle()
//     {
//         try{

//             $today = Carbon::now('Asia/Kolkata')->toDateString(); 
//             $timenow = Carbon::now('Asia/Kolkata');
//             $day = strtolower($timenow->format('l'));
//             $week = $timenow->weekOfMonth;
//             $diet_name = Helpers::getService('B');

//             $weeklychart = WeeklyChart::where('week',$week )->where('day', $day)->first();
//             $speciality = $weeklychart[$diet_name];
//             if($speciality == Helpers::getSpeciality('O')){
//                 throw new \Exception("Service Off for ".strtoupper($diet_name));
//             }


//             foreach(VendorMess::latest()->get() as $mess) {
//                 $mess_id = $mess->id;
                
//                 $messService = MessService::where('mess_id', $mess_id)
//                     ->where('name', $diet_name)
//                     ->first();
                    
//                 if($messService && $messService->status == 1) {
//                     $endTime = Carbon::parse($messService->available_time_starts)->addMinutes(30);
//                     if (!$endTime->isPast()) {
//                         $customerSubscriptionTxns = CustomerSubscriptionTransactions::haveDietCoupons($speciality!="special"?$diet_name: null)
//                                                                                     ->where('mess_id', $mess->id)
//                                                                                     ->where('expiry', '>=', $today)
//                                                                                     ->where('diet_status' , true)
//                                                                                     ->get();
//                         // dd($customerSubscriptionTxns);
//                         if($customerSubscriptionTxns->isNotEmpty()) {
                            
//                             foreach ($customerSubscriptionTxns as $customerSubscriptionTxn) {
//                                 $coupons = $customerSubscriptionTxn->dietCoupons;
//                                 if ($coupons->isNotEmpty()) {
//                                     foreach ($coupons as $coupon) {
//                                         DB::beginTransaction();
//                                         $coupnData = DietCoupon::find($coupon->id);
//                                         $attendance = Attendance::whereDate('created_at', $today)
//                                             ->where('customer_id', $coupon->customer_id)
//                                             ->where('subscription_id',$customerSubscriptionTxn->subscription_id)
//                                             ->firstOrNew();
                            
//                                         $attendance->customer_id = $coupon->customer_id;
//                                         $attendance->subscription_id = $customerSubscriptionTxn->subscription_id;
//                                         $attendance->save();
                                       
                            
//                                         if ($speciality == Helpers::getSpeciality('S')) {
//                                             $coupnData->diet_name = $diet_name;
//                                         } 
                            
//                                         $checklist = $attendance->checklist()->Create([
//                                                             'service' => $diet_name,
//                                                             'attendance_date' => $today,
//                                                             'attendance_time' => $timenow->toTimeString(),
//                                                             'checked' => 1,
//                                                             'coupon_id' => $coupnData->id
//                                                         ]);
                            
//                                         if ($checklist) {
//                                             $coupnData->state = 'pending';
//                                             $coupnData->save();
//                                             DB::commit();
//                                             dd('success');
//                                         } else {
//                                             DB::rollBack();
//                                         }
//                                     }
//                                 }
//                             }  
//                         }
//                     }
//                 }
//             }
//          }catch(\Exception $ex){
//           return $ex->getMessage();
//         }
//     }

// }
