<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Models\RestaurantMenu;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('customer')->check()) {
            return $next($request);
        }else{
            if (empty($_COOKIE['guest_token'])) {
                $token = Str::uuid()->toString();
                setcookie('guest_token', $token, time() + (60 * 60 * 24 * 7), "/");
                $_COOKIE['guest_token'] = $token;
            }
        }

        $rememberToken = Cookie::get('remb_t_cus', null);
        if (!empty($rememberToken)) {
            $customer = Customer::where('remember_token', $rememberToken)->first();
            if ($customer) {
                if (auth('customer')->loginUsingId($customer->id)) {
                    Session::put('userInfo', $customer);
                    return $next($request); // Allow the request to continue
                }
            }
        }

        if($request->routeIs('user.restaurant.check-out') || $request->routeIs('user.restaurant.billing-summery') ) {
            return $next($request);
        }

        // Store the requested URL in the session
        $request->session()->put('url.intended', $request->fullUrl());
        if ($request->routeIs('user.auth.login')) {

            return $next($request); // Allow the login route through without checks
        }

        return redirect()->route('user.auth.login');
    }


}


