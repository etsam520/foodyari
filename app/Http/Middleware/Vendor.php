<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use App\Models\VendorMess;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('vendor')->check()) {
            return $next($request);
        }
        $rememberToken = Cookie::get('remb_t_vendor', null);
        if (!empty($rememberToken)) {
            $vendor = \App\Models\Vendor::where('remember_token', $rememberToken)->first();
            if ($vendor) {
                if (auth('vendor')->loginUsingId($vendor->id)) {
                    $activeStore = json_decode(Cookie::get('active_store'));
                    if($activeStore->type == 'restaurant'){
                        Session::put('restaurant',Restaurant::find($activeStore->id));
                    }else {
                        Session::put('mess',VendorMess::find($activeStore->id));
                    }
                    return $next($request); // Allow the request to continue
                }
            }
        }

        if ($request->routeIs('vendor.auth.login')) {

            return $next($request); // Allow the login route through without checks
        }
        $request->session()->put('url.intended', $request->fullUrl());
        return redirect()->route('vendor.auth.login');
    }
}
