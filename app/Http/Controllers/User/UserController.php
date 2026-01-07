<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\GuestSession;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\CodeCoverage\Report\Thresholds;
use Illuminate\Support\Str;
use Throwable;

class UserController extends Controller
{
    public function view()
    {
        $user = Customer::find(Auth::guard('customer')->user()->id);
        return view('user-views.account.view', compact('user'));
    }
    public function update(Request $request)
    {

        $user = Customer::find(Auth::guard('customer')->user()->id);
        $user->f_name = $request->f_name;
        $user->l_name = $request->l_name;
        if ($request->has('image')) {
            $user->image = Helpers::updateFile($request->file('image'), 'customers/', $user->image);
        }
        $user->phone = $request->phone;
        $user->email = $request->email;
        $user->address = json_encode([
            'street' => $request->street,
            'city' => $request->city,
            'pincode' => $request->pincode,
        ]);
        $user->dob = $request->dob;
        $user->gender = $request->gender;
        $user->merital_status = $request->merital_status;
        $user->anniversary = $request->anniversary_date;

        if ($user->save()) {         
            return back()->with('success', "Dear $user->f_name, your account has been updated");
        } else {
            return back()->with('error', "Sorry for the inconvenience, your account couldn't be updated");
        }
    }

    public function saveUserAddress(Request $request)
    {
       
        try {
            DB::beginTransaction();
            if (Session::get('userInfo')) {
                $user = Customer::find(Session::get('userInfo')->id);
                if ($request->id != null) {
                    // Update existing address if ID is provided
                    $dataToUpdate = [
                        'latitude' => $request->latitude,
                        'longitude' => $request->longitude,
                        'phone' => $request->phone,
                        'address' => $request->address,
                        'landmark' => $request->landmark,
                        'type' => $request->type
                    ];
                    $user->customerAddress()
                        ->where('id', $request->id)
                        ->update($dataToUpdate);
                    
                } else {
                    // Create a new address

                    $user->customerAddress()->create($request->all());

                    // Set all addresses as non-default
                    $user->customerAddress()->update(['is_default' => 0]);

                    // Set the latest address as the default one
                    $user->customerAddress()->latest()->first()->update(['is_default' => 1]);
                }
                $data = [
                    'lat' => $request->latitude,
                    'lng' => $request->longitude,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'landmark' => $request->landmark,
                    'type' => $request->type
                ];

                $redis = new RedisHelper();
                $redis->set("user:{$user->id}:user_location", $data, null, true);  
            }

            // Prepare data for the cookie

            $guestLocation = [
                'lat' => $request->latitude,
                'lng' => $request->longitude,
                'phone' => $request->phone,
                'address' => $request->address,
                'landmark' => $request->landmark,
                'type' => $request->type
            ];
            if (empty($guestLocation['lat']) || empty($guestLocation['lng'])) {
                throw new \Exception("Location Not Given");
            }


            $sessionToken = Str::random(40);
            $userAgent = request()->header('User-Agent');
            $deviceInfo = substr($userAgent, 0, 255);
            $ipAddress = request()->ip() === '::1' ? '127.0.0.1' : request()->ip();
            $guestToken = $_COOKIE['guest_token'];
            GuestSession::updateOrCreate([
                'guest_id' => $guestToken,
            ], [
                'session_token' => $sessionToken,
                'ip_address' => $ipAddress,
                'device_info' => $deviceInfo,
                'user_agent' => $userAgent,
                'guest_location' => json_encode($guestLocation),
            ]);

            $redis = new RedisHelper();
            $redis->set("guest:{$guestToken}:user_location", $guestLocation, null, true);
            DB::commit();


            return redirect()->route('userHome')->with('success', 'Address Saved');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function listSavedAddress(Request $request)
    {

        if (Session::get('userInfo')) {
            $user = Customer::find(Session::get('userInfo')->id);

            $addressList = $user->customerAddress()->get();

            return view('user-views.account.addressList', compact('user', 'addressList'));
        }
        return back()->with('info', "No Address Found");
    }
    public function deleteSavedAddress(Request $request, $id)
    {
        try {
            $userAddress = CustomerAddress::findOrFail($id);
            $redis = new RedisHelper();
            $redis->delete("user:{$userAddress->customer_id}:user_location");
            $userAddress->delete();
            return back()->with('success', 'Address Deleted');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function saveUserCurrentAddress(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:customer_addresses,id,customer_id,' . auth('customer')->id()
        ]);

        $authUser = auth('customer')->user();
        // $authUser->customerAddress()->update(['is_default', 0]);
        CustomerAddress::where('customer_id', auth('customer')->id())->update(['is_default' => 0]);
        CustomerAddress::where('id', $request->address_id)->update(['is_default' => 1]);
        $updtedAddress = CustomerAddress::find($request->address_id);
        $data = [
            'lat' => $updtedAddress->latitude,
            'lng' => $updtedAddress->longitude,
            'phone' => $updtedAddress->phone,
            'address' => $updtedAddress->address,
            'landmark' => $updtedAddress->landmark,
            'type' => $updtedAddress->type
        ];

        $redis = new RedisHelper();
        $redis->set("user:{$authUser->id}:user_location", $data, 60 * 60, true);
        $redis->set("user:{$authUser->id}:user_location_update", true, 60 * 5);
        setcookie('user_location_update', '1', time() + (60 * 5), "/");

        if($request->manual_selection){
            setcookie('user_location_manual_selection', '1', time() + (60 * 30), "/");
            $redis->set("user:{$authUser->id}:user_location_manual_selection", true, 60 * 30);
        }
        return back();
    }

    public function _halt_refreshUserCurrentAddress(Request $request)
    {
        try {
            $authUser = auth('customer')->user();
            $distanceLimit = 2; // in kilometers
            $point1 = ['lat' => $request->json('lat'), 'lon' => $request->json('lng')];
            $customerAddresses = CustomerAddress::where('customer_id', $authUser->id)->get()->toArray();

            foreach ($customerAddresses as &$address) {
                $point2 = ['lat' => $address['latitude'], 'lon' => $address['longitude']];

                // Calculate distance using Haversine formula
                $address['distance'] = Helpers::haversineDistance($point1, $point2);
            }

            // Filter addresses based on the distance limit
            $filteredAddresses = array_filter($customerAddresses, function ($address) use ($distanceLimit) {
                return $address['distance'] > $distanceLimit;
            });


            usort($filteredAddresses, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            if (!$filteredAddresses[0]) {
                throw new \Exception('Nearestw Address Not Found');
            }

            $nearestAddress = $filteredAddresses[0];

            CustomerAddress::where('customer_id', $authUser->id)->update(['is_default' => 0]);
            CustomerAddress::where('id', $nearestAddress['id'])->update(['is_default' => 1]);
            
            $data = [
                'lat' => $nearestAddress['latitude'],
                'lng' => $nearestAddress['longitude'],
                'phone' => $nearestAddress['phone'],
                'address' => $nearestAddress['address'],
                'landmark' => $nearestAddress['landmark'],
                'type' => $nearestAddress['type']
            ];
            $redis = new RedisHelper();
            $redis->set("user:{$authUser->id}:user_location", $data, null, true);  

            return response()->json(['message' => 'Nearest Address Updated']);
        } catch (Throwable $th) {
            // dd($th);
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function refreshUserCurrentAddress(Request $request)
    {
        try {
            $authUser = auth('customer')->user();
            $distanceLimit = 400; // in mtrs
            $point1 = ['lat' => $request->json('lat'), 'lon' => $request->json('lng')];

            $customerAddresses = CustomerAddress::select(
                'customer_addresses.*',
                DB::raw("(
                    6371 * ACOS(
                        COS(RADIANS(?)) * COS(RADIANS(latitude)) *
                        COS(RADIANS(longitude) - RADIANS(?)) +
                        SIN(RADIANS(?)) * SIN(RADIANS(latitude))
                    )
                ) AS distance")

            )->addBinding([$point1['lat'], $point1['lon'], $point1['lat']], 'select') // binds in correct order
                ->where('customer_id', $authUser->id)
                ->orderBy('distance') // sort by nearest
                ->get()
                ->toArray();

            // Filter addresses based on the distance limit
            $filteredAddresses = array_filter($customerAddresses, function ($address) use ($distanceLimit) {
                return $address['distance'] * 1000 < $distanceLimit;
            });


            if (!$filteredAddresses || $filteredAddresses[0] == null) {
                throw new \Exception('Nearestw Address Not Found');
            }

            usort($filteredAddresses, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });
            // dd($filteredAddresses);  
            $nearestAddress = $filteredAddresses[0];

            CustomerAddress::where('customer_id', $authUser->id)->update(['is_default' => 0]);
            CustomerAddress::where('id', $nearestAddress['id'])->update(['is_default' => 1]);
          
            $data = [
                'lat' => $nearestAddress['latitude'],
                'lng' => $nearestAddress['longitude'],
                'phone' => $nearestAddress['phone'],
                'address' => $nearestAddress['address'],
                'landmark' => $nearestAddress['landmark'],
                'type' => $nearestAddress['type']
            ];

            $redis = new RedisHelper();
            $redis->set("user:{$authUser->id}:user_location", $data, 3600, true);   
            return response()->json(['message' => 'Nearest Address Updated']);
        } catch (Throwable $th) {

            if(auth('customer')->check()){
                $authUser = auth('customer')->user();
                $redis = new RedisHelper();
                $google_address =  Helpers::getAddressLocation($request->json('lat').','.$request->json('lng'));
                $data = [
                    'lat' => $request->json('lat'),
                    'lng' =>$request->json('lng'),
                    'phone' => null,
                    'address' => $google_address?? '',
                    'landmark'=> '' ,
                    'type' => '',
                ];
                $redis->set("user:{$authUser->id}:user_location", $data, 3600, true);
            }
            // dd($data);
            return response()->json();
        }
    }
 



    public function pages($name)
    {

        $data = BusinessSetting::when($name == 'privacy_policy', function ($query) {
            return $query->where(['key' => 'privacy_policy']);
        })->when($name == 'terms_and_conditions', function ($query) {
            return $query->where(['key' => 'terms_and_conditions']);
        })->when($name == 'refund_policy', function ($query) {
            return $query->where(['key' => 'refund_policy']);
        })->when($name == 'shipping_policy', function ($query) {
            return $query->where(['key' => 'shipping_policy']);
        })->when($name == 'cancellation_policy', function ($query) {
            return $query->where(['key' => 'cancellation_policy']);
        })->when($name == 'about_us', function ($query) {
            return $query->where(['key' => 'about_us']);
        })->first();

        if (!$data) {
            return back()->with('error', 'Page Not Found');
        } elseif ($name == "refund_policy") {
            $data = json_decode($data->value, true);
            return view('user-views.account.pages.refund-policy', compact('data'));
        } else {

            return view('user-views.account.pages.page', compact('data'));
        }
    }

    public function contact_us()
    {
        return view('user-views.account.pages.contact-us');
    }

    public function contact_us_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ], [
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'phone.required' => 'Please enter your phone number',
            'subject.required' => 'Please enter a subject',
            'message.required' => 'Please enter your message',
            'message.max' => 'Message cannot be longer than 5000 characters'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $contactUs = new ContactUs();
            $contactUs->name = $request->name;
            $contactUs->email = $request->email;
            $contactUs->phone = $request->phone;
            $contactUs->subject = $request->subject;
            $contactUs->message = $request->message;
            $contactUs->status = ContactUs::STATUS_PENDING;
            $contactUs->ip_address = $request->ip();
            $contactUs->user_agent = $request->userAgent();
            
            // If user is logged in, associate with customer
            if (Auth::guard('customer')->check()) {
                $contactUs->customer_id = Auth::guard('customer')->id();
            }

            $contactUs->save();

            // Send notification email to admin (optional)
            try {
                $this->sendContactUsNotificationToAdmin($contactUs);
            } catch (\Exception $e) {
                // Log email error but don't fail the request
                Log::error('Failed to send contact us notification: ' . $e->getMessage());
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you for contacting us. We will get back to you soon!'
                ]);
            }

            return redirect()->back()->with('success', 'Thank you for contacting us. We will get back to you soon!');

        } catch (\Exception $e) {
            Log::error('Contact Us Form Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again later.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again later.')
                ->withInput();
        }
    }

    private function sendContactUsNotificationToAdmin($contactUs)
    {
        $business_name = BusinessSetting::where('key', 'business_name')->first();
        $admin_email = BusinessSetting::where('key', 'email_address')->first();
        
        if ($admin_email && $admin_email->value) {
            $data = [
                'name' => $contactUs->name,
                'email' => $contactUs->email, 
                'phone' => $contactUs->phone,
                'subject' => $contactUs->subject,
                'message' => $contactUs->message,
                'business_name' => $business_name ? $business_name->value : 'FoodYari',
                'submitted_at' => $contactUs->created_at->format('M d, Y h:i A')
            ];

            Mail::send('emails.contact-us-notification', $data, function ($message) use ($admin_email, $contactUs) {
                $message->to($admin_email->value)
                        ->subject('New Contact Us Message: ' . $contactUs->subject);
            });
        }
    }
}
