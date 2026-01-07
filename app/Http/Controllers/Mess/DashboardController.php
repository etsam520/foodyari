<?php

namespace App\Http\Controllers\Mess;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Customer;
use App\Models\DietCoupon;
use App\Models\MessService;
use App\Models\Vendor;
use App\Models\VendorMess;
use App\Models\WeeklyChart;
use App\Models\Zone;
use App\Notifications\FirebaseNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    // public function dashboard()
    // {
        
    //         $title = 'Thank NOw Your raddi';
    //         $body = 'sldsfkjd dsldfjkalf asd';
    //         $data = [
    //             'link' => "http://localhost:8080/foodyari-user2/checkout.php",
    //             'image' => "https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/DotOnline_gTLD_logo.svg/330px-DotOnline_gTLD_logo.svg.png"
    //         ];
    
    //         $users = Customer::find(35);
    //         $users->notify(new FirebaseNotification($title, $body, null, $data));
    
    //         // dd($users);
    //         // foreach ($users as $user) {
    //         //     $user->notify(new FirebaseNotification($title, $body, $data));
    //         // }
    
    //         return response()->json(['status' => 'Notification sent successfully!']);
        
    // }
    public function dashboard() {
        
        $today = Carbon::now()->toDateString();
        $messId = Session::get('mess')->id;
        /**************** //toaday\'s attendance start //********** */ 
        $attendences = Attendance::with(['checklist'])
                        ->whereDate('created_at', $today)
                       ->where('mess_id',$messId)->get();
        $attCounts =[
            'breakfast' => [
                'total' =>0,
                'dine_in' =>0,
                'delivery' =>0
            ],
            'lunch'  => [
                'total' =>0,
                'dine_in' =>0,
                'delivery' =>0
            ],
            'dinner' => [
                'total' =>0,
                'dine_in' =>0,
                'delivery' =>0
            ],
        ];
        foreach($attendences as $att)
        {
            foreach($att->checklist as $chk_list){
                if($chk_list->service == 'breakfast'){
                   $attCounts['breakfast']['total'] ++; 
                   $coupon = DietCoupon::with('customerSubscriptionTxns')->find($chk_list->coupon_id);
                   if($coupon->customerSubscriptionTxns->delivery == 1){
                    $attCounts['breakfast']['delivery']++;
                   }elseif ($coupon->customerSubscriptionTxns->delivery == 0) {
                    $attCounts['breakfast']['dine_in']++;
                   }
                }elseif($chk_list->service == 'lunch'){
                    $attCounts['lunch']['total'] ++; 
                    $coupon = DietCoupon::with('customerSubscriptionTxns')->find($chk_list->coupon_id);
                    if($coupon->customerSubscriptionTxns->delivery == 1){
                        $attCounts['lunch']['delivery']++;
                    }elseif ($coupon->customerSubscriptionTxns->delivery == 0) {
                        $attCounts['lunch']['dine_in']++;
                    }
                }elseif($chk_list->service == 'dinner'){
                    $attCounts['dinner']['total'] ++; 
                    $coupon = DietCoupon::with('customerSubscriptionTxns')->find($chk_list->coupon_id);
                    if($coupon->customerSubscriptionTxns->delivery == 1){
                        $attCounts['dinner']['delivery']++;
                    }elseif ($coupon->customerSubscriptionTxns->delivery == 0) {
                        $attCounts['dinner']['dine_in']++;
                    }
                }
            }
        }
         /**************** //toaday\'s attendance end //********** */ 
          /**************** //weekle chart start //********** */ 
          $timenow = Carbon::now('Asia/Kolkata');
            $day = strtolower($timenow->format('l'));
            $week = $timenow->weekOfMonth;
            
            $weeklychart = WeeklyChart::where('week',$week )->where('mess_id',$messId)->where('day', $day)->first();
            if(!$weeklychart)
            {
                Session::flash('error','Set Today\'s Weekly Chart');
            }else{
                $diets = Helpers::getService();
                // dd($weeklychart);
                foreach($diets as $diet){
                    $speciality = $weeklychart[$diet];
                    if($speciality == Helpers::getSpeciality('O')){
                        Session::flash('error',"Service Off for ".strtoupper($diet)." of the week no : $weeklychart->week ");
                    }
                } 
            }
            /**************** //weekle chart end //********** */ 
            

        
        return view('mess-views.dashboard', compact('attCounts','attendences'));
    }

    public function profileUpdate() {
        $mess =  VendorMess::find(Session::get('mess')->id);
        $zones = Zone::select('name','id')->get();
        return view('mess-views.info.profile',compact('zones','mess'));;
    }
    
    public function profileUpdateStore(Request $request) {
        $rules = [
            'name' => 'required|string|max:191',
            'description' => 'required|string|max:500',
            'street' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'email' => 'required|email',
            'pincode' => 'required|numeric|digits:6',
            'radius' => 'required|numeric|max:180',
            'badge_one' => 'required|string|max:100',
            'badge_two' => 'required|string|max:100',
            'diet_cost_normal' => 'required|numeric|max:1000',  
            'diet_cost_special' => 'required|numeric|max:1000',
            'latitude' => 'required|numeric|min:-90|max:90',
            'longitude' => 'required|numeric|min:-180|max:180',
        ];
        
        //
        
        $validator = Validator::make($request->all(), $rules); 
        
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $vendorMess = VendorMess::find(Session::get('mess')->id);
            $vendorMess->name = $request->name;
            $vendorMess->email = $request->email;
            $vendorMess->description = $request->description;
            if($request->file('logo')){
                $vendorMess->logo = Helpers::updateFile($request->file('logo'), 'vendorMess',$vendorMess->logo);
            }
            if($request->file('cover_photo')){
                $vendorMess->cover_photo = Helpers::updateFile($request->file('cover_photo'), 'vendorMess/cover/',$vendorMess->cover_photo);
            }
            $vendorMess->radius = $request->radius;
            $vendorMess->address = json_encode([
                                        'street' => $request->street,
                                        'city' => $request->city,
                                        'pincode' => $request->pincode,
                                    ]);

            $vendorMess->diet_cost = json_encode([
                'normal' => $request->diet_cost_normal,
                'special' => $request->diet_cost_special,
            ]);
                                
            $vendorMess->badges = json_encode(['b1' => $request->badge_one, 'b2' => $request->badge_two]);
            $vendorMess->zone_id = $request->zone_id;
            $vendorMess->save();

            return redirect()->back()->with('success', __('Mess Updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function attendanceTiming(Request $request)
    {
        // dd($request->post());
         $request->validate([
            "breakfast_start" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "breakfast_end" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "lunch_start" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "lunch_end" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "dinner_start" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
            "dinner_end" => ['required', 'regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/'],
        ]);
    
        try {
           
            $messId = Session::get('mess')->id;
            
          
            $breakfast = Helpers::getService('B');
            $lunch = Helpers::getService('L'); 
            $dinner = Helpers::getService('D');
    
           
          $ff =  MessService::updateOrCreate(
                ["name" => $breakfast, "mess_id" => $messId],
                ["available_time_starts" => $request->breakfast_start, "available_time_ends" => $request->breakfast_end]
            );
            dd($ff);
            MessService::updateOrCreate(
                ["name" => $lunch, "mess_id" => $messId],
                ["available_time_starts" => $request->lunch_start, "available_time_ends" => $request->lunch_end]
            );
            MessService::updateOrCreate(
                ["name" => $dinner, "mess_id" => $messId],
                ["available_time_starts" => $request->dinner_start, "available_time_ends" => $request->dinner_end]
            );
    
            return back()->with('success', __('Timing Saved'));
        } catch (\Exception $e) {
            
            return back()->with('error', $e->getMessage());
        }
    }
    
    



    
}

