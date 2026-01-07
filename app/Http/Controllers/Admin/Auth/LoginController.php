<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    public function __construct()
    {
        // $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function login(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin-views.auth.login');
    }

    public function submit(Request $request)
    {
        // dd($request);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Check if the "remember me" option is selected
            $admin = auth('admin')->user();
            if ($request->filled('remember')) {
                // Initialize flag to determine whether a new token needs to be generated
                $flag_to_generate_token = empty($admin->remember_token) ? true : false;


                // Generate a new token if needed
                if ($flag_to_generate_token) {
                    $admin->remember_token = Str::random(60);
                    $admin->remember_token_created_at = now();
                }

                // Set a cookie for the remember token (valid for 1 month = 43200 minutes)
                Cookie::queue('remb_t_ad', $admin->remember_token, 259200);
            }
            if (isset($_COOKIE['My_FCM_Token'])) {
                $admin->fcm_token = $_COOKIE['My_FCM_Token'];
            }
            $admin->save(); // Save the updated remember token and creation date


            // Redirect to the admin dashboard if authentication is successful
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->with('error','credentials_does_not_match');
    }

    public function logout(Request $request)
    {

        Cookie::queue(Cookie::forget('remb_t_ad'));
        $request->session()->invalidate();
        auth()->guard('admin')->logout();
        return redirect()->route('admin.auth.login');
    }
}
