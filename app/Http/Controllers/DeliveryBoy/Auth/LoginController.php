<?php

namespace App\Http\Controllers\DeliveryBoy\Auth;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\DeliveryManCashInHand;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login()
    {
        if (Auth::guard('delivery_men')->check()) {
            return redirect()->route('deliveryman.dashboard');
        }
        return view('deliveryman.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $deliveryMan = DeliveryMan::where('email', $request->email)->first();
        if ($deliveryMan ) {
            if (auth('delivery_men')->attempt(['email' => $request->email, 'password' => $request->password])) {
                $flag_to_generate_token =  empty($deliveryMan->remember_token) ? true : false;

                if ($flag_to_generate_token) {
                    $deliveryMan->remember_token = Str::random(60);
                }

                if (isset($_COOKIE['My_FCM_Token'])) {
                    $deliveryMan->fcm_token = $_COOKIE['My_FCM_Token'];
                }
                $deliveryMan->save();
                Cookie::queue('remb_t_dm', $deliveryMan->remember_token, 259200);
                Session::put('deliveryMan', $deliveryMan);
                return redirect()->route('deliveryman.dashboard');
            }
            return redirect()->back()->withInput($request->only('email', 'remember'))->with('error','credentials_does_not_match');
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))->with('error','You Don\'t have any Account');
    }


    public function dashboard()
    {
        $deliveryMan = auth('delivery_men')->user(); Session::get('deliveryMan');
        $wallet = Wallet::where('deliveryman_id', $deliveryMan->id)->first();
        if(!isset($wallet->balance)){
            $wallet = Wallet::create([
                'deliveryman_id' => $deliveryMan->id,
                'balance' => 0,
            ]);
        }

        $cashInHand = DeliveryManCashInHand::where('deliveryman_id', $deliveryMan->id)->first();
        if(!isset($cashInHand->balance)){
            $cashInHand = DeliveryManCashInHand::create([
                'deliveryman_id' => $deliveryMan->id,
                'balance' => 0,
            ]);
        }
        if (isset($_COOKIE['My_FCM_Token'])) {
            $dman =  DeliveryMan::find($deliveryMan->id);
            $dman->fcm_token = $_COOKIE['My_FCM_Token'];
            $dman->save();
            setcookie('mqtt_client_dm_id', $dman->id, time() + (60*60*30* 60), "/");
            // dd($restaurant);
        }
        $dm = DeliveryMan::find($deliveryMan->id);


        if(Session::get('deliveryMan')->type == 'mess'){
            return view('deliveryman.mess.dashboard',compact('wallet','cashInHand','dm'));
        }elseif (Session::get('deliveryMan')->type == 'restaurant') {
            return view('deliveryman.restaurant.dashboard',compact('wallet','cashInHand','dm'));
        }elseif (Session::get('deliveryMan')->type == 'admin') {
            return view('deliveryman.admin.dashboard',compact('wallet','cashInHand','dm'));
        }

    }

    public function logout(Request $request)
    {
        // Get the authenticated user (delivery man)
        $deliveryMan = auth()->guard('delivery_men')->user();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the CSRF token to prevent session fixation attacks
        $request->session()->regenerateToken();

        // Log out the delivery man
        auth()->guard('delivery_men')->logout();

        // Clear specific cookie (for example, 'remb_t_dm')
        Cookie::queue(Cookie::forget('remb_t_dm'));

        // // Clear all other cookies by looping over them
        // foreach ($request->cookies->keys() as $cookie) {
        //     Cookie::queue(Cookie::forget($cookie));
        // }

        // Redirect to the login page after logout
        return redirect()->route('deliveryman.auth.login');
    }

    public function forgotPassword()
    {
        return view('deliveryman.auth.forgot-password');
    }
    public function sendForgotPasswordOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);;
            }
            $phone = $request->input('phone');
            $deliveryMan = DeliveryMan::where('phone', 'LIKE', '%' . $phone . '%')->first();
            if (!empty($deliveryMan) && ($deliveryMan->status == 1)) {
                $now = Carbon::now();

                $messageRssp = '';
                $otp_data = [
                    'otp' => rand(000000, 999999),
                    'otp_expires_at' =>  now()->addMinutes(15)->toDayDateTimeString(),
                ];
                Helpers::send_dlt_sms($otp_data['otp'], $phone);
                Session::put('otp_data', $otp_data);
                $message = 'OTP has been sent to Your mobile Number : ' . $deliveryMan->phone;
                $responseData = [
                    'message' => $message,
                    'view' => view('deliveryman.auth._otp-page', compact('deliveryMan'))->render(),
                    'messag-otp' => $otp_data['otp'],
                ];
            } else {
                $message = 'No Account Found with this Phone Number';
                $responseData = [
                    'message' => $message,
                ];
            }
            return response()->json($responseData);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 403);
        }
    }

    public function resendOtp($phone) {
        $otp_data = [
            'otp' => rand(000000, 999999),
            'otp_expires_at' =>  now()->addMinutes(15)->toDayDateTimeString(),
        ];
        $messageRssp =   Helpers::send_dlt_sms($otp_data['otp'], $phone);
        Session::put('otp_data', $otp_data);
        return response()->json(['message' => 'OTP has been sent to Your mobile Number : ' . $phone]);
    }


    public function forgotPasswordSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'otp' => 'required|digits:6',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $phone = $request->input('phone');
            $otp = $request->otp;

            // Validate OTP and expiration
            $otpData = Session::get('otp_data', null);
            if (!$otpData || intval($otp) !== intval($otpData['otp']) || \Carbon\Carbon::parse($otpData['otp_expires_at'])->isPast()) {
                throw new \Exception('Invalid or expired OTP.');
            }

            // Retrieve DeliveryMan details
            $deliveryMan = DeliveryMan::where('phone', 'LIKE', '%' . $phone . '%')->first();
            if (!$deliveryMan || $deliveryMan->status != 1) {
                throw new \Exception('No account found with this phone number.');
            }

            // Update password
            $deliveryMan->password = Hash::make($request->password);
            Auth::guard('delivery_men')->login($deliveryMan);

            // Authenticate the delivery man
            if (auth('delivery_men')->check()) {
                // Generate token if not already present
                if (empty($deliveryMan->remember_token)) {
                    $deliveryMan->remember_token = Str::random(60);
                }

                // Store FCM token if available
                if (isset($_COOKIE['My_FCM_Token'])) {
                    $deliveryMan->fcm_token = $_COOKIE['My_FCM_Token'];
                }

                // Save updated delivery man data
                $deliveryMan->save();

                // Queue cookies and session
                Cookie::queue('remb_t_dm', $deliveryMan->remember_token, 259200); // 180 days
                Session::put('deliveryMan', $deliveryMan);

                // Prepare headers for response
                $responseHeaders = [
                    'Set-Cookie' => 'remb_t_dm=' . $deliveryMan->remember_token . '; Max-Age=259200; Path=/; HttpOnly',
                    'X-Session-ID' => session()->getId(),
                ];

                // Return success response with headers
                return response()->json([
                    'message' => 'Password reset successfully.',
                    'link' => route('deliveryman.dashboard'),
                ])->withHeaders($responseHeaders);
            } else {
                throw new \Exception('Authentication failed.');
            }
        } catch (\Throwable $th) {
            // Log the error for debugging
            // \Log::error('Forgot password error: ' . $th->getMessage());

            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

}
