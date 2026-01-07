<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class DeleteAccountController extends Controller
{   
    public function  index() 
    {
        return view('user-views.account.delete-account') ;
    }

    public function verify_account(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|max_digits:13',
        ]);
        $phone= $request->phone;
        if(empty($request->otp)){
            $user = Customer::where('phone', 'LIKE', '%'.$phone.'%')->first();
            if(!empty($user->phone))
            {
                $now = Carbon::now();
    
                if(Carbon::parse($user->otp_expiry)->isPast()){
                    $user->otp_expiry = $now->addMinute(5)->toDateTime();
                    $user->otp = rand(000000,999999);
                    $user->save();
                    Helpers::send_dlt_sms($user->otp, $phone);
                    $message = 'OTP has been sent to Your mobile Number : '.$user->phone;
                }else{
                    $message = "Otp has already been sent on your Number : ".$user->phone;
                }
                Cache::put('user_to_remove', $user->toArray(), 15); 
                return back()->with('succes', $message);
                
            }
        }else{
            $user = Customer::where('phone', 'LIKE', '%'.$phone.'%')->where('otp', $request->otp)->first();
            if(!empty($user->phone))
            {
                $now = Carbon::now();
    
                Cache::put('user_to_remove', $user->toArray(), 15); 
                return view('user-views.account.verify-account',compact('user'));
                
            }
        }
        return back()->with('error', 'user Not Foun');
    }

    public function destroy(Request $request, $id)
    {
        // Retrieve the user stored in the cache
        $user = Cache::get('user_to_remove')?? NULL;
        $user = $user ? Customer::where('email', $user['email'])->where('id', $id)->first() : null;

        if ($user) {
            // dd($user);
            // Update the user's information
            $user->fcm_token = null;
            $user->deleted_at = now();
            $user->status = 0;
            $user->remember_token = null;
            $user->otp = null;
            $user->save();

            // Clear the session and cookies
            Session::flush();
            Cache::forget('user_to_remove');
            Cookie::queue(Cookie::forget('cart')); // Add any specific cookies you want to remove

            // Redirect to home page with success message
            return redirect('/')->with('success', 'Account Deleted');
        }

        // If no user found, redirect to the dashboard with an error message
        return redirect()->route('user.dashboard')->with('error', 'Session Timeout');

        
    }
}
