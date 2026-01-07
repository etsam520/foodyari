<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BusinessSettingsController extends Controller
{

    private $restaurant;

    public function restaurant_index()
    {
        $restaurant =Restaurant::with('schedules')->find(Session::get('restaurant')->id) ;
        return view('vendor-views.business-settings.business-index', compact('restaurant'));
       
        // 
    }
    public function temp_off( Request $request)
    {
        $restaurant =Restaurant::with('schedules')->find(Session::get('restaurant')->id) ;
        $restaurant->temp_close = $request->json('tempOff');
        if($restaurant->save()){
            return response()->json(['message'=>'Status Updated']);
        }else{
            return response()->json(['message'=>'Status Error'],403);

        }
    }
    public function restaurant_setup(Request $request)
    {   
        try{

            $request->validate([
                'gst' => 'nullable|required_if:gst_status,1',
                'per_km_delivery_charge' => 'required_with:minimum_delivery_charge|numeric|between:0,999999999999.99',
                'minimum_delivery_charge' => 'required_with:per_km_delivery_charge|numeric|between:0,999999999999.99',
                'maximum_shipping_charge' => [
                    'nullable',
                    'numeric',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value < $request->minimum_delivery_charge) {
                            $fail(__('messages.maximum_shipping_charge_must_be_greater_or_equal'));
                        }
                    },
                ],
            ], [
                'gst.required_if' => __('messages.gst_can_not_be_empty'),
            ]);
            
            $restaurant = Restaurant::find(Session::get('restaurant')->id);
            // dd($request->all());
            
            $off_day =  json_encode($request->off_day)??null;
            $restaurant->minimum_order = $request->minimum_order;
            $restaurant->opening_time = Carbon::parse($request->opening_time)->toTimeString();
            $restaurant->closeing_time = Carbon::parse($request->closeing_time)->toTimeString();
            $restaurant->type = Helpers::getFoodType($request->type);
            $restaurant->off_day = $off_day;
            $restaurant->tax = $request->gst_status?$request->gst :0;
            $restaurant->ready_to_handover = $request->ready_to_handover =="on"?1: 0;
            // dd($restaurant->ready_to_handover);

            $restaurant->minimum_shipping_charge = $request->minimum_delivery_charge;
            $restaurant->per_km_shipping_charge = $request->per_km_delivery_charge;
            $restaurant->maximum_shipping_charge = $request->maximum_shipping_charge ?? null;

            $restaurant->save();
            return back()->with('success',__('messages.restaurant_settings_updated'));
        }catch(Throwable $th)
        {
            return back()->with('error', $th->getMessage());
        }

    }

    // public function restaurant_status(Restaurant $restaurant, Request $request)
    // {
    //     if($request->menu == "schedule_order" && !Helpers::schedule_order())
    //     {
    //         Toastr::warning(__('messages.schedule_order_disabled_warning'));
    //         return back()->;
    //     }

    //     if((($request->menu == "delivery" && $restaurant->take_away==0) || ($request->menu == "take_away" && $restaurant->delivery==0)) &&  $request->status == 0 )
    //     {
    //         Toastr::warning(__('messages.can_not_disable_both_take_away_and_delivery'));
    //         return back();
    //     }

    //     if((($request->menu == "veg" && $restaurant->non_veg==0) || ($request->menu == "non_veg" && $restaurant->veg==0)) &&  $request->status == 0 )
    //     {
    //         Toastr::warning(__('messages.veg_non_veg_disable_warning'));
    //         return back();
    //     }

    //     if($request->menu == 'free_delivery' &&

    //     ($restaurant->restaurant_model == 'subscription' && isset($rest_sub) && $rest_sub->self_delivery == 0) || ($restaurant->restaurant_model == 'unsubscribed')

    //     ){
    //         Toastr::error(__('your_subscription_plane_does_not_have_this_feature'));
    //         return back();

    //     }

    //     $restaurant[$request->menu] = $request->status;
    //     $restaurant->save();
    //     Toastr::success(__('messages.Restaurant settings updated!'));
    //     return back();
    // }

    // public function active_status(Request $request)
    // {
    //     $restaurant = Helpers::get_restaurant_data();
    //     $restaurant->active = $restaurant->active?0:1;
    //     $restaurant->save();
    //     return response()->json(['message' => $restaurant->active?__('messages.restaurant_opened'):__('messages.restaurant_temporarily_closed')], 200);
    // }

    // public function add_schedule(Request $request)
    // {
    //     $validator = Validator::make($request->all(),[
    //         'start_time'=>'required|date_format:H:i',
    //         'end_time'=>'required|date_format:H:i|after:start_time',
    //     ],[
    //         'end_time.after'=>__('messages.End time must be after the start time')
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)]);
    //     }
    //     $restaurant = Session::get('restaurant');
    //     $temp = RestaurantSchedule::where('day', 'LIKE', '%'.$request->day.'%')->where('restaurant_id',$restaurant->id)
    //     ->where(function($q)use($request){
    //         return $q->where(function($query)use($request){
    //             return $query->where('opening_time', '<=' , $request->start_time)->where('closing_time', '>=', $request->start_time);
    //         })->orWhere(function($query)use($request){
    //             return $query->where('opening_time', '<=' , $request->end_time)->where('closing_time', '>=', $request->end_time);
    //         });
    //     })
    //     ->first();


    //     if(isset($temp))
    //     {
    //         return response()->json(['errors' => [
    //             ['code'=>'time', 'message'=>__('messages.schedule_overlapping_warning')]
    //         ]]);
    //     }
        
    //     $restaurant_schedule = RestaurantSchedule::insert(['restaurant_id'=>$restaurant->id,'day'=>$request->day,'opening_time'=>$request->start_time,'closing_time'=>$request->end_time]);
    //     $restaurant = Restaurant::with('schedules')->find(Session::get('restaurant')->id);
    //     return response()->json([
    //         'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
    //     ]);
    // }


    public function add_schedule(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'start_time'=>'required|date_format:H:i',
            'end_time'=>'required|date_format:H:i|after:start_time',
        ],[
            'end_time.after'=>__('messages.End time must be after the start time')
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $restaurant = Session::get('restaurant');

        // Overlap check: detect any existing schedule that truly intersects the new interval.
        // Use strict comparisons so adjacent intervals (existing.closing_time == new.start_time) are allowed.
        $temp = RestaurantSchedule::where('day', 'LIKE', '%'.$request->day.'%')
            ->where('restaurant_id', $restaurant->id)
            ->where(function($q) use ($request) {
                $q->where('opening_time', '<', $request->end_time)
                  ->where('closing_time', '>', $request->start_time);
            })
            ->first();

        if(isset($temp))
        {
            return response()->json(['errors' => [
                ['code'=>'time', 'message'=>__('messages.schedule_overlapping_warning')]
            ]]);
        }
        
        // use create() so model events and $fillable are respected
        $restaurant_schedule = RestaurantSchedule::create([
            'restaurant_id' => $restaurant->id,
            'day' => $request->day,
            'opening_time' => $request->start_time,
            'closing_time' => $request->end_time
        ]);

        $restaurant = Restaurant::with('schedules')->find(Session::get('restaurant')->id);
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
        ]);
    }


    public function remove_schedule($restaurant_schedule)
    {
        $restaurant = Session::get('restaurant');
        $schedule = RestaurantSchedule::where('restaurant_id', $restaurant->id)->find($restaurant_schedule);
        if(!$schedule)
        {
            return response()->json([],404);
        }
        $schedule->delete();
        $restaurant = Restaurant::with('schedules')->find(Session::get('restaurant')->id);
        return response()->json([
            'view' => view('vendor-views.business-settings.partials._schedule', compact('restaurant'))->render(),
        ]);
    }

    // public function site_direction_vendor(Request $request){
    //     session()->put('site_direction_vendor', ($request->status == 1?'ltr':'rtl'));
    //     return response()->json();
    // }

}
