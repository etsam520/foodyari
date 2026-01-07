<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Models\Vendor;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\SubscriptionPackage;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Http;
use App\Models\RestaurantSubscription;
use App\Models\VendorMess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest:vendor', ['except' => 'logout']);
    }

    public function login()
    {
        if (Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.dashboard');
        }

        return view('vendor-views.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);


        if (Auth::guard('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $vendor = Auth::guard('vendor')->user();
            $restaurant =  $vendor->restaurants[0];
            $cookieName = 'active_store';

            $cookieValue = [
                'name' => $restaurant->name,
                'type' => 'restaurant',
                'id' => $restaurant->id
            ];
            if (isset($_COOKIE['My_FCM_Token'])) {
                $restaurant->fcm_token = $_COOKIE['My_FCM_Token'];
                $restaurant->save();
            }

            if ($request->filled('remember')) {
                $vendor = Auth::guard('vendor')->user();

                $flag_to_generate_token = empty($vendor->remember_token) ? true : false;

                if ($flag_to_generate_token) {
                    $vendor->remember_token = Str::random(60);
                    $vendor->remember_token_created_at = now();
                }
                $vendor->save();
                // Set a cookie for the remember token (valid for 1 month)
                Cookie::queue('remb_t_vendor', $vendor->remember_token, 259200); // 43200 minutes = 1 month
            }
            Cookie::queue($cookieName, json_encode($cookieValue), 60*24*365);
            Session::put('restaurant', $restaurant);
            return redirect()->route('vendor.dashboard');

        }

        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->with(['error' => __('The provided credentials do not match our records.')]);
    }

    public function dashboardChanger(Request $request)
    {
        try{
            $cookieName = 'active_store';
            if(Auth::guard('vendor')->check() && Cookie::has($cookieName)){
                $type = $request->query('type');
                $id = $request->query('id');
                $name = $request->query('name');
                if(empty($type) || empty($id) || empty($name)){
                    throw new \Exception('Requested Parameters can\'t be null');
                }

                $cookieValue = json_decode(Cookie::get($cookieName), true);

                if($type == 'restaurant'){
                    $restaurant = Restaurant::find($id);
                    if(!$restaurant){
                        throw new \Exception('Store Not Found');
                    }
                    $cookieValue = [
                        'name' => $restaurant->name,
                        'type' => 'restaurant',
                        'id' => $restaurant->id
                    ];
                    if (isset($_COOKIE['My_FCM_Token'])) {
                        $restaurant->fcm_token = $_COOKIE['My_FCM_Token'];
                        $restaurant->save();
                    }
                    Session::put('restaurant', $restaurant);
                    $route ='vendor.dashboard';
                }elseif($type == 'mess'){
                    $mess = VendorMess::find($id);
                    if(!$mess){
                        throw new \Exception('Store Not Found');
                    }
                    $cookieValue = [
                        'name' => $mess->name,
                        'type' => 'mess',
                        'id' => $mess->id
                    ];
                    if (isset($_COOKIE['My_FCM_Token'])) {
                        $mess->fcm_token = $_COOKIE['My_FCM_Token'];
                        $mess->save();
                    }
                    Session::put('mess', $mess);
                    $route = 'mess.dashboard';

                }else{
                    throw new \Exception('Process Desabled');
                }
                Cookie::queue($cookieName, json_encode($cookieValue), 60*24*365);
                return redirect()->route($route);
            }else{
                throw new \Exception('Unauthorised Access');
            }
        }catch(\Throwable $th){
        return back()->with('warning', $th->getMessage());
        }



    }



    public function logout(Request $request)
    {
        $vendor = auth()->guard('vendor')->user();

        if ($vendor) {
            $vendor->remember_token = null;
            $vendor->save();

            Cookie::queue(Cookie::forget('remb_t_vendor'));
        }
        $request->session()->invalidate();
        auth()->guard('vendor')->logout();
        return redirect()->route('vendor.auth.login');
    }
}
