<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Deliveryman\DeliverymanLastLocation;
use App\CentralLogics\DeliveryTime\DeliveryTimer;
use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\Restaurant\BillingController;
use App\Mail\ShareInvoiceMail;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Services\ReferralService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpMqtt\Client\Facades\MQTT;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        if(Auth::guard('customer')->check()){
            return redirect()->route('user.dashboard');
        }

        return view('user-views.auth.login');
    }

    public function checkUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);;
            }
            $phone = $request->input('phone');
            // $user = Customer::where('phone', $phone)->first();
            $user = Customer::where('phone', 'LIKE', '%' . $phone . '%')->first();
            if (!empty($user) && ($user->status == 1)) {
                $now = Carbon::now();

                $messageRssp = '';
                if (Carbon::parse($user->otp_expiry)->isPast()) {
                    $user->otp_expiry = $now->addMinute(5)->toDateTime();
                    $user->otp = rand(000000, 999999);
                    $user->save();
                    $messageRssp =  Helpers::send_dlt_sms($user->otp, $phone);
                    //check for otp
                    $message = 'OTP has been sent to Your mobile Number : ' . $user->phone;
                } else {
                    $message = "Otp has already been sent on your Number : " . $user->phone;
                }
                $responseData = [
                    'message' => $message,
                    'view' => view('user-views.auth.existingUserForm', compact('user'))->render(),
                    // 'messag-otp' => $user->otp
                ];
            } elseif (!empty($user) && !empty($user->deleted_at)) {

                throw new \Exception("Your Account is Deleted Please Contact Service Team");
            } else {
                $temp_user = [
                    'phone' => $phone,
                    'otp' => rand(000000, 999999),
                    'otp_expires_at' =>  now()->addMinutes(15)->toDayDateTimeString(),
                ];
                $messageRssp =   Helpers::send_dlt_sms($temp_user['otp'], $temp_user['phone']);
                Session::put('temp_user', $temp_user);
                
                // Pass any session referral code to the view
                $referralCode = Session::get('referral_code');
                
                $responseData = [
                    'message' => 'An OTP has been sent to your mobile.',
                    'view' => view('user-views.auth.newUserForm', compact('referralCode'))->render(),
                    'messag-otp' => $messageRssp
                ];
            }
            return response()->json($responseData);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 403);
        }
    }




    public function registerUser(Request $request)
    {
        try {

            $request->validate([
                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:customers,phone',
                'otp' => 'required|digits:6',
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:customers,email',
                'referral_code' => 'nullable|string|max:20',
            ]);

            $temp_user = Session::get('temp_user');
            $temp_user['name'] = $request->name;
            $temp_user['phone'] = $request->phone;
            $temp_user['email'] = $request->email;
            Session::put('temp_user', $temp_user);

            if (!$temp_user['otp'] || !$temp_user['otp_expires_at']) {
                throw new \Exception('OTP is Not Set');
            } elseif (!now()->lessThanOrEqualTo($temp_user['otp_expires_at'])) {
                throw new \Exception('OTP Expired');
            }


            if ($request->otp == $temp_user['otp']) {
                $customer = new Customer();
                $customer->f_name = $request->name;
                $customer->email = $request->email;
                $customer->phone = $request->phone;
                $customer->image = '';
                $customer->address = json_encode([]);
                $customer->remember_token = Str::random(60);

                if (isset($_COOKIE['My_FCM_Token'])) {
                    $customer->fcm_token = $_COOKIE['My_FCM_Token'];
                }
                $customer->save();

                // Handle referral code if provided
                if ($request->referral_code && trim($request->referral_code) !== '') {
                    $referralService = new ReferralService();
                    $referralResult = $referralService->processReferralRegistration($customer, $request->referral_code);
                    
                    if ($referralResult['success']) {
                        Session::flash('referral_success', 'Referral code applied successfully! You will receive rewards when you complete orders.');
                        \Illuminate\Support\Facades\Log::info('Referral code successfully bound to user', [
                            'user_id' => $customer->id,
                            'referral_code' => $request->referral_code,
                            'sponsor_id' => $referralResult['referral']->sponsor_id ?? null
                        ]);
                    } else {
                        Session::flash('referral_error', $referralResult['message'] ?? 'Failed to apply referral code');
                        \Illuminate\Support\Facades\Log::warning('Referral code binding failed', [
                            'user_id' => $customer->id,
                            'referral_code' => $request->referral_code,
                            'error' => $referralResult['message'] ?? 'Unknown error'
                        ]);
                    }
                }

                // Generate referral code for the new user
                $referralService = app(\App\Services\ReferralService::class);
                $referralService->createReferralCode($customer->id);

                Cookie::queue('remb_t_cus', $customer->remember_token, 43200);
                Session::flash('success', 'Your Account Has been Created');
                Auth::guard('customer')->login($customer);
                Session::put('userInfo', $customer);
                Session::remove('temp_user');

                return redirect()->intended(route('user.dashboard'));
            } else {
                throw new \Exception('Invalid OTP.');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
            // dd($th->getMessage());
        }
    }

    public function submit(Request $request)
    {
        
        $user = Customer::where('phone', $request->phone)->first();

        if ($user && $user->otp == $request->otp) {

            Auth::guard('customer')->login($user);

            $flag_to_generate_token = empty($user->remember_token) ? true : false;

            if ($flag_to_generate_token) {
                $user->remember_token = Str::random(60);
                $user->remember_token_created_at = now();
            }

            if (isset($_COOKIE['My_FCM_Token'])) {
                $user->fcm_token = $_COOKIE['My_FCM_Token'];
            }


            $user->save();
            Cookie::queue('remb_t_cus', $user->remember_token, 259200);
            Session::put('userInfo', $user);
            if (Session::get('userInfo')) {
                $user = Customer::with(['customerAddress' => function ($query) {
                    $query->where('is_default', 1)->latest();
                }])->find($user->id);
                // dd($user);
                if (!empty($user->customerAddress[0])) {
                    $address = $user->customerAddress[0];
                    
                    $data = [
                        'lat' => $address->latitude ?? null,
                        'lng' => $address->longitude ?? null,
                        'phone' => $address->phone ?? null,
                        'address' => $address->address ?? null,
                        'landmark' => $address->landmark ?? null,
                        'type' => $address->type ?? null
                    ];

                    $redis = new RedisHelper();
                    $redis->set("user:{$user->id}:user_location", $data, 3600, true); 

                }
            }

            return redirect()->intended(route('user.dashboard'));
        } elseif (!$user) {
            return back()->with('error', 'User Not Found');
        } else {
            return back()->with('error', 'Invalid OTP');
        }
    }

    public function logout(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        if ($customer) {
            $customer->remember_token = null;
            $customer->save();

            Cookie::queue(Cookie::forget('remb_t_cus'));
        }
        $request->session()->invalidate();
        auth()->guard('customer')->logout();
        Session::remove('userInfo');
        return redirect()->route('userHome');
    }

     public function comi(Request $request) {
        // $order = \App\Models\Order::latest()->first();
        // event(new \App\Events\OrderPlaced($order));
        // echo "done0";

        $redis = new RedisHelper();
            $redis->set("eht", 'Hello, this is MD at Shyam. ', 3600, true);   
    }
    public function gomi()  {
        $redis = new RedisHelper();
        // $redis->set("eht", 'Hello, this is MD at Shyam. ', 3600, true);
        // dd($redis->get("eht"));

        // $dloc = new DeliverymanLastLocation(6, 0, 0, date('d-m-Y H:i:s'));
        // dd($dloc->getLastLocation());
        $timer = new DeliveryTimer(6);
        dd($timer->getDeliveryTime(101145, null), '---', $timer->getResturantReachOutTime(101145, null));
        
    }





    /*public function comi() {
        $eht = Customer::find('3972');
        $eht->fcm_token = "fz4h2uKBSdK5677nPtslBp:APA91bHd2VAh8E4At_2kwaDzOM_U7JhEkPJ4sHmYL1a5DbcjVTVk8ujH074P0Ie9WTd-PrBKIXm0uxesE0m01e5knS4zpvaPeNiAqYQ_CKoSnGYlFtXkNPu6My0g-DvrnIEB5HPJNjXg";
        $eht->save();
        $restaurant = Restaurant::where('vendor_id',18)->first();
        $dman = DeliveryMan::find(6);
        // dd($restaurant);
        $admin = Helpers::getAdmin();
        $notification = [
            'type' => 'Manual',
            'subject' => 'Order No 5 Places SuccessFully',
            'message' => 'Wait For 5 minut',
            'order_id' => 100045,
            'order_status' => 'pending',
            'audio_link' => asset('sound/order-received.mp3')
        ];
       return Notification::send($eht, new FirebaseNotification($notification));
    //    dd($res);
    // Helpers::dlkfjd
    } */




//    public function publishMessage(Request $request) // Publish a message
    public function __comi(Request $request)
    {
       // Retrieve the mail config from the database
        $mailConfig = BusinessSetting::where('key', 'mail_config')->first();

        if ($mailConfig && !empty($mailConfig->value)) {
            $emailSettings = json_decode($mailConfig->value, true);

            // Set dynamic mail configuration
            config([
                'mail.mailer' => $emailSettings['mail_mailer'] ?? 'smtp',
                'mail.host' => $emailSettings['mail_host'] ?? 'smtp.gmail.com',
                'mail.port' => $emailSettings['mail_port'] ?? 587,
                'mail.username' => $emailSettings['mail_username'] ?? '',
                'mail.password' => $emailSettings['mail_password'] ?? '',
                'mail.encryption' => $emailSettings['mail_encryption'] ?? 'tls',
                'mail.from.address' => $emailSettings['mail_from_address'] ?? 'noreply@example.com',
                'mail.from.name' => $emailSettings['mail_from_name'] ?? 'Example',
            ]);

            // Send the email using the dynamic configuration
            Mail::to('mdehtesham520@gmail.com')->send(new ShareInvoiceMail(100624));

            return 'Test email sent!';
        }

        return 'Mail configuration not found.';

            $billing = new BillingController(3972);
            $billing->process();
            $billmakerData = $billing->billMaker();
            $customerOrderData = $billmakerData->adminBillData();
            // $customerOrderData = $billmakerData->customerBillData();
            // $customerOrderData = $billmakerData->restaurantBillData();
            dd($customerOrderData);
            // dd($billing->customerBillData());
            // return response()->json($billing->process());
            // try{

            //     $topic = 'foodyari_givni_order_data_'. 6 ;  // Dynamic topic for each user
            //     $message = rand(0000, 9999).": this is a testing message";  // Message from the request input

            //     MQTT::publish($topic, $message);
            //     // Publish message to the MQTT broker
            //     // $this->mqttService->publish($topic, $message);

            //     return response()->json(['status' => 'Message published successfully!']);
            // }catch(\Throwable $th){
            //     dd($th);
            // }
    }
}
