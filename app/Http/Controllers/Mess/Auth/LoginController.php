<?php

namespace App\Http\Controllers\Mess\Auth;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Vendor;
use App\Models\VendorMess;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function login()
    {
        return view('mess-views.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $mess = VendorMess::with('vendor')->where('email', $request->email)->first();

        if ($mess && Hash::check($request->password, $mess->password)) {
            Auth::guard('vendor')->login($mess->vendor);
            Session::put('mess', $mess);
            
            return redirect()->route('mess.dashboard');
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
        auth()->guard('vendor')->logout();
        return redirect()->route('mess.auth.login');
    }

    
}
