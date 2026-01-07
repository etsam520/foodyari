<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class DeliveryMan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('delivery_men')->check()) {

            return $next($request);
        }

        $rememberToken = Cookie::get('remb_t_dm', null);
        // dd($rememberToken);
        if (!empty($rememberToken)) {
            $deliveryMan = \App\Models\DeliveryMan::where('remember_token', $rememberToken)->first();
            if ($deliveryMan) {
                // Attempt to log in the deliveryMan using their ID
                if (auth('delivery_men')->loginUsingId($deliveryMan->id)) {
                    Session::put('deliveryMan',$deliveryMan);
                    return $next($request); // Allow the request to continue
                }
            }
        }

        if($request->routeIs('deliveryman.auth.forgot-password')){
            return $next($request);
        }


        if ($request->routeIs('deliveryman.auth.login')) {
            return $next($request);
        }
        if ($request->routeIs('deliveryman.auth.login-submit')) {
            return $next($request);
        }
        return redirect()->route('deliveryman.auth.login');
    }
}
