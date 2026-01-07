<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Mess\MainController as MessDashboardController;
use App\Http\Controllers\User\Restaurant\DashboardController as RestaurantDashboardController;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\VendorMess;
use App\Models\WeeklyMenu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Str;

use function PHPUnit\Framework\returnSelf;

class DashboardController extends Controller
{
    public function home(Request $request)
    {
      
        $restaurantController = new RestaurantDashboardController();

        $this->authCheckGaurd();

        $isAuthenticated = auth('customer')->check();
        $hasRedisLocation = false;

        if ($isAuthenticated) {
            $user = auth('customer')->user();

            $redis = new RedisHelper();
            $userRedisLocation = $redis->get("user:{$user->id}:user_location");
            $hasRedisLocation = $userRedisLocation !== null;

            $shouldShowIndex = ($isAuthenticated && $hasRedisLocation);
        } else {
            $shouldShowIndex = Helpers::guestCheck();
        }

        $view = $shouldShowIndex
            ? $restaurantController->index()
            : $restaurantController->userLocationSet();

        if ($isAuthenticated && isset($_COOKIE['My_FCM_Token'])) {
            $customer = auth('customer')->user();
            $customer->fcm_token = $_COOKIE['My_FCM_Token'];
            $customer->save();

            // keep previous lifetime
            setcookie('mqtt_client_user_id', $customer->id, time() + (60 * 60 * 30 * 60), "/");
        }

        return $view;
    }



    private function authCheckGaurd() : void {

        // $data = [
        //     'is_userLogin' => false,
        //     'is_guest' => false,
        // ];
        Helpers::syncPassKey(request: request() , canCreatePassKey: true);
        $rememberToken = Cookie::get('remb_t_cus', null);
        if (!empty($rememberToken)) {
            $customer = Customer::where('remember_token', $rememberToken)->first();
            if ($customer) {
                if (auth('customer')->loginUsingId($customer->id)) {
                    Session::put('userInfo', $customer);
                    // $data['is_userLogin'] = true;
                    // $data['is_guest'] = false;
                }
            }
        }
      
        if (empty($_COOKIE['guest_token'])) {
            $token = Str::uuid()->toString();
            setcookie('guest_token', $token, time() + (60 * 60 * 24 * 7), "/");
            $_COOKIE['guest_token'] = $token;
            // $data['is_guest'/] = true;   
        }

        // return $data;

    }

    public function mess()
    {
        $messController = new MessDashboardController();
        return $messController->home();
    }



    public function getWeeklyMenuDay(Request $request)
    {
        // Helpers::getDayname()
        $messId = $request->get('mess_id');
        $type = $request->get('type');
        // $messWeeklyMenu = WeeklyMenu::where('mess_id', $messId)->where('type', Helpers::getFoodType(Str::upper($type)))->get();
        return view('user-views.mess.partials._menu-days', compact('type', 'messId'))->render();
    }
    public function getWeeklyMenu(Request $request)
    {
        // Helpers::getDayname()
        $messId = $request->get('mess_id');
        $type = $request->get('type');
        $dayKey = $request->get('day');

        return view('user-views.mess.partials._menu-list', compact('messId', 'type', 'dayKey'))->render();
    }
}
