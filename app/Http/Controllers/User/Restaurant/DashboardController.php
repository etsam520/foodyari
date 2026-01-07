<?php

namespace App\Http\Controllers\User\Restaurant;

use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    { 
        $redis = new RedisHelper();

        try {

            $guestToken = $_COOKIE['guest_token'] ?? null;
            if(auth('customer')->check()){
                $user = auth('customer')->user();
                $redis = new RedisHelper();
                $data = $redis->get("user:{$user->id}:user_location", true);
                if($data != null){
                    // Get scheduled orders count for the user
                    $scheduledOrdersCount = Order::where('customer_id', $user->id)
                        ->where('order_status', 'scheduled')
                        ->count();
                    
                    return view('user-views.restaurant.index', compact('scheduledOrdersCount'));
                }
            }else if($guestToken != null){
                // $redis = new RedisHelper();
                // $guest = $redis->get("guest:{$guestToken}:user_location", true);
                // $guest = 
                if(Helpers::guestLocationExists()){
                    return view('user-views.restaurant.index', ['scheduledOrdersCount' => 0]);
                }
            }
            
            throw new \Error('Address not found');
        } catch (\Throwable $th) {
            // dd($th);
            return $this->userLocationSplash();
        }

    }

    public function userLocationSet(){
        return view('user-views.restaurant.partials.location-page');
    }

    public function userLocationSplash(){
        return view('user-views.restaurant.partials.splash_page');
    }
}
