<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse('admin');
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }
        $rememberToken = Cookie::get('remb_t_ad', null);
        if (!empty($rememberToken)) {
            // Find the admin by the remember token
            $admin = \App\Models\Admin::where('remember_token', $rememberToken)->first();

            if ($admin) {
                        // Attempt to log in the admin using their ID
                if (auth('admin')->loginUsingId($admin->id)) {
                    return $next($request); // Allow the request to continue
                }
            }
        }
        if ($request->routeIs('admin.auth.login-post')) {
            return $next($request);
        }
        if ($request->routeIs('admin.auth.login')) {
            return $next($request);
        }
        return redirect()->route('admin.auth.login');
    }
}
