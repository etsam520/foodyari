<?php

namespace App\Http\Middleware;

use App\CentralLogics\Helpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ModulePermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   
    public function handle($request, Closure $next, $module)
    {
        
        try {
            if (Auth::guard('admin')->check()  ) {
                Helpers::module_permission_check($module);
                return $next($request);
            }
            else if (Auth::guard('vendor')->check()) {
    
                if(Session::has('restaurant'))
                {
                    // dd($module);
                    return Helpers::module_restaurant_permission_check($module, $next($request)) ; 
                }elseif(Session::has('vendor') && Session::get('mess')->id){
                    Helpers::module_mess_permission_check($module);
                }else{
                    throw new \Exception('Access Denied');
                }
                 return $next($request);
                
            }else if(Auth::guard('customer')->check()){
                return $next($request);
            }
            return $next($request);
        } catch (\Exception $th) {
            Session::flash('warning', $th->getMessage());
            return back();
        }   

        
    }
}
