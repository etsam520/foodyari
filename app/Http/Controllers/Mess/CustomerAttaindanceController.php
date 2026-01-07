<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceCheckList;
use App\Models\Customer;
use App\Models\CustomerSubscriptionTransactions;
use App\Models\DietCoupon;
use App\Models\MessMenu;
use App\Models\MessService;
use App\Models\Subscription;
use App\Models\VendorMess;
use App\Models\WeeklyChart;
use App\Models\WeeklyMenu;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Header;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerAttaindanceController extends Controller
{
    public function index()
    
    {

        $today = Carbon::now('Asia/Kolkata')->toDateString(); 

        $attendances = Attendance::whereDate('created_at', $today)
            ->with(['checklist', 'customers'])
            ->get();

        return view('mess-views.attaindace.index',compact('attendances'));
    }
   

    public function attendanceBySingle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
            'service_id' => 'required|integer',
            'attendance_checked' => 'required|integer',
        ]);

        try {
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }
        
            $today = Carbon::now('Asia/Kolkata')->toDateString(); // Set timezone to Indian Standard Time
            $timenow = Carbon::now('Asia/Kolkata');
        
            $service = MessService::find($request->service_id);
            $endTime = Carbon::parse($service->available_time_starts)->addMinutes(30);
        
            if ($endTime->isPast()) {
                throw new \Exception('Time For Attendance is End Now');
            }
        
            $attendance = Attendance::whereDate('created_at', $today)
                ->where('customer_id', $request->customer_id)
                ->firstOrNew();
            $attendance->customer_id = $request->customer_id;
            $attendance->save();
        
             // Using relationships to handle checklist
            $checklist = $attendance->checklist()->updateOrCreate(
                ['attendance_date' => $today, 'service_id' => $service->id],
                ['attendance_time' => $timenow->toTimeString(), 'checked' => $request->attendance_checked]
            );

            if ($checklist->checked == 1) { // Accessing checked property from the $checklist object
                $resp = 'Attendance marked successfully';
            } else {
                throw new \Exception('Attendance Unmarked');
            }
        
            return response()->json(['success' => $resp]);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 422);
        }
    }
    
    public function attendanceByAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|integer',
            'attendance_checked' => 'required|integer',
        ]);

        try {
            if ($validator->fails()) {
                throw new \Exception($validator->errors()->first());
            }

            $customers = Customer::where('mess_id', Session::get('mess')->id)->where('status', 1)->get(); 

            $today = Carbon::now('Asia/Kolkata')->toDateString(); 
            $timenow = Carbon::now('Asia/Kolkata');

            $service = MessService::find($request->service_id);
            $endTime = Carbon::parse($service->available_time_starts)->addMinutes(30);

            if ($endTime->isPast()) {
                throw new \Exception('Time For Attendance is End Now');
            }

            $marker = true;

            foreach ($customers as $customer) {
                $attendance = Attendance::whereDate('created_at', $today)
                    ->where('customer_id', $customer->id)
                    ->firstOrNew();
                $attendance->customer_id = $customer->id;
                $attendance->save();

                // Using relationships to handle checklist
                $checklist = $attendance->checklist()->updateOrCreate(
                    ['attendance_date' => $today, 'service_id' => $service->id],
                    ['attendance_time' => $timenow->toTimeString(), 'checked' => $request->attendance_checked]
                );

                if ($checklist->checked != 1) { 
                    $marker = false;
                }
            }

            if ($marker) {
                $resp = 'Attendance marked successfully';
            } else {
                throw new \Exception('Attendance Unmarked');
            }
            return response()->json(['success' => $resp]);
        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 422);
        }
    }


}
