<?php

namespace App\Http\Middleware;

use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class mess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (Auth::guard('vendor')->check()) {
            $mess = Auth::guard('vendor')->user()->withCount('messes')->first();
            if($mess->messes_count > 0){
                 return $next($request);
            }
           
        }
        return redirect()->route('mess.auth.login');
    }
}
