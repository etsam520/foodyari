<?php

namespace App\Http\Controllers\vendorOwner\auth;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        return view('vendor-owner-views.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        $vendor = Vendor::where('email', $request->email)->first();
        if ($vendor ) {
            if (auth('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
                // $mess = $vendor->messes->first();
                // Session::put('vendor', ['mess_name' => $mess->name, 'mess_logo' => $mess->logo, 'mess_id' => $mess->id]);
                return redirect()->route('vendorOwner.dashboard');
            }
            
            return redirect()->back()->withInput($request->only('email', 'remember'))->with('error','credentials_does_not_match');
        }
        
        return redirect()->back()->withInput($request->only('email', 'remember'))->with('error','You Don\'t have any Account');
    }
    public function logout(Request $request)
    {
        auth()->guard('vendor')->logout();
        return redirect()->route('vendorOwner.auth.login');
    }
}
