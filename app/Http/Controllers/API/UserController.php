<?php

namespace App\Http\Controllers\API;

use App\CentralLogics\DeliveryTime\DeliveryTimer;
use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use App\Models\GuestSession;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function userInfo(Request $request) {
        $user = $request->get('auth_user');
        if(!$user){
            return Helpers::ApiResponse(false, 'Unauthorized', null, 401);
        }
        return Helpers::ApiResponse(true, 'Authorized access to user data',[
            "user" => [
                 "id" => $user->id,
                "name" => $user->f_name.' '.$user->l_name,
                "email" => $user->email,
            ],
            "isLoggedIn" => true
        ], 200);
    }
    public function getSavedLocations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "key" => "string|required",
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return Helpers::ApiResponse(false, $validator->errors()->first(), null, 422);
        }
        $redis = new RedisHelper();
        $point1 = [
            'lat' => $request->latitude,
            'lon' => $request->longitude,
        ];
        $user = $request->get('auth_user');
        $distanceLimit = 5000; // 5 km in meters 
        
        if(!$user){
            $sessionToken = Str::random(40);
            $userAgent = request()->header('User-Agent');
            $deviceInfo = substr($userAgent, 0, 255);
            $ipAddress = request()->ip() === '::1' ? '127.0.0.1' : request()->ip();
            $guestToken = $request->header('Guest-Key', Str::uuid());
            $guestLocation = [
                'lat' => $request->latitude,
                'lng' => $request->longitude,
                'phone' => $request->phone,
                'address' => $request->address,
                'landmark' => $request->landmark,
                'type' => $request->type
            ];
            GuestSession::updateOrCreate([
                'guest_id' => $guestToken,
            ], [
                'session_token' => $sessionToken,
                'ip_address' => $ipAddress,
                'device_info' => $deviceInfo,
                'user_agent' => $userAgent,
                'guest_location' => json_encode($guestLocation),
            ]);

            // $redis = new RedisHelper();
            $redis->set("guest:{$guestToken}:user_location", $guestLocation, null, true);
            return Helpers::ApiResponse(true, 'Saved locations retrieved successfully',[
                "addresses" => [
                    [
                        "id"       => null,
                        "key"       => $request->latitude . ',' . $request->longitude,
                        "address"   => $request->address,
                        "name"      => $request->type != null ? $request->type : explode(',', $request->address)[0],
                        "latitude"  => $request->latitude,
                        "longitude" => $request->longitude,
                        "distance"  => 0
                    ]
                ],
                "guest_token" => $guestToken
            ], 200); 
        }
            

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
                ->where('customer_id', $user->id)
                ->orderBy('distance') // sort by nearest
                ->get()
                ->toArray();

            // Filter addresses based on the distance limit
            $filteredAddresses = array_filter($customerAddresses, function ($address) use ($distanceLimit) {
                // return $address['distance'] * 1000 < $distanceLimit;
                return true;
            });


            // if (!$filteredAddresses || $filteredAddresses[0] == null) {
            //     throw new \Exception('Nearestw Address Not Found');
            // }

            usort($filteredAddresses, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });
            // dd($filteredAddresses);  
            if(isset($filteredAddresses[0])){
                $nearestAddress = $filteredAddresses[0];
                $data = [
                    'lat' => $nearestAddress['latitude'],
                    'lng' => $nearestAddress['longitude'],
                    'phone' => $nearestAddress['phone'],
                    'address' => $nearestAddress['address'],
                    'landmark' => $nearestAddress['landmark'],
                    'type' => $nearestAddress['type']
                ];
                $redis->set("user:{$user->id}:user_location", $data, 3600, true); 
                CustomerAddress::where('customer_id', $user->id)->update(['is_default' => 0]);
                CustomerAddress::where('id', $nearestAddress['id'])->update(['is_default' => 1]);  
            }
            
            // Reindex array
            $filteredAddresses = array_values($filteredAddresses);

            // ✅ CORRECT: assign array_map result
            $filteredAddresses = array_map(function ($item) {
                return [
                    'id'       => $item['id'],
                    "key"       => $item['latitude'] . ',' . $item['longitude'],
                    "address"   => $item['address'],
                    "name"      => $item['type'] != null ? $item['type'] : explode(',', $item['address'])[0],
                    "latitude"  => $item['latitude'],
                    "longitude" => $item['longitude'],
                    // "distance"  => $item['distance'] * 90000000 // convert to meters,
                    "distance"  => $item['distance']  // convert to meters,
                ];
            }, $filteredAddresses);
        return Helpers::ApiResponse(true, 'Saved locations retrieved successfully',[
            "addresses" => $filteredAddresses,
            "guest_token" => null
        ], 200);
    }

    public function storeLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "nullable|string",
            "key" => "string|required",
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return Helpers::ApiResponse(false, $validator->errors()->first(), null, 422);
        }
        $point1 = [
            'lat' => $request->latitude,
            'lon' => $request->longitude,
        ];
        $user = $request->get('auth_user');
        $guestKey = $request->header('Guest-Key');
        if(!$user ){
            $guestSession = GuestSession::where('guest_id', $guestKey)->first();
            if(!$guestSession){
                return Helpers::ApiResponse(false, 'Guest session not found', null, 404);
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
            $redis->set("guest:{$guestKey}:user_location", $data, null, true); 
            $guestSession->guest_location = json_encode($data);
            $guestSession->save();
            return Helpers::ApiResponse(true, 'Location saved successfully',null, 200);
        }

        if($request->id == null || $request->id == ''){
            $customerAddress = new CustomerAddress();
            $customerAddress->customer_id = $user->id;
            $customerAddress->latitude = $request->latitude;
            $customerAddress->longitude = $request->longitude;
            $customerAddress->address = $request->address ?? '';
            $customerAddress->landmark = $request->landmark ?? '';
            $customerAddress->type = $request->type ?? '';
            $customerAddress->phone = $request->phone ?? $user->phone;
            $customerAddress->save();
        } else {
            $customerAddress = CustomerAddress::where('id', $request->id)->where('customer_id', $user->id)->first();
            if(!$customerAddress){  
                return Helpers::ApiResponse(false, 'Address not found', [], 404);
            }
        }
        $data = [
            'lat' => $customerAddress['latitude'],
            'lng' => $customerAddress['longitude'],
            'phone' => $customerAddress['phone'],
            'address' => $customerAddress['address'],
            'landmark' => $customerAddress['landmark'],
            'type' => $customerAddress['type']
        ];
                
        $redis = new RedisHelper();
        $redis->set("user:{$user->id}:user_location", $data, 3600, true); 
        CustomerAddress::where('customer_id', $user->id)->update(['is_default' => 0]);
        CustomerAddress::where('id', $customerAddress['id'])->update(['is_default' => 1]);  
        return Helpers::ApiResponse(true, 'Location saved successfully',null, 200);
            
    }

public function liveOrders()
{
    $customer = auth('customer')->user();

    if (!$customer) {
        return Helpers::ApiResponse(false, 'Unauthenticated', null, 401);
    }

    $orders = Order::with('restaurant')
        ->whereDate('created_at', today())
        ->where('customer_id', $customer->id)
        ->where('created_at', '<=', now()->addMinutes(120))
        ->whereNotIn('order_status', ['delivered', 'canceled'])
        ->latest()
        ->get();

    if ($orders->isEmpty()) {
        return Helpers::ApiResponse(true, 'No live orders found', [
            'orders' => []
        ], 200);
    }

    $orders = $orders->map(function ($order) {
        $_order = $order->toArray();

        // ✅ Normalize restaurant data
        if ($order->restaurant) {
            $order->restaurant = [
                'id'      => $order->restaurant->id,
                'name'    => $order->restaurant->name,
                'address' => $order->restaurant->address,
            ];
        }

        // ✅ Delivery info
        if ($order->delivery_man_id && !in_array($order->order_status, ['delivered', 'canceled'])) {
            try {
                $deliveryTimer = new DeliveryTimer($order->delivery_man_id);

                $deliveryData = $deliveryTimer->getDeliveryTime($order->id, $order);
                $dmLastLocation = $deliveryTimer->getDmLastLocation();

                if (!$order->picked_up) {
                    $etaInfo = $deliveryTimer->getResturantReachOutTime($order->id, $order);
                }

                $processingInfo = $this->calculateProcessingTime($order, $deliveryData);

                // Optional: attach computed data
                $order->delivery_info = [
                    'delivery_time' => $deliveryData ?? null,
                    'processing'    => $processingInfo ?? null,
                    'dm_location'   => $dmLastLocation ?? null,
                ];

            } catch (\Throwable $e) {
                Log::error(
                    "DeliveryTimer error for order {$order->id}: {$e->getMessage()}"
                );
            }
        }

        return $order;
    });

    return Helpers::ApiResponse(true, 'Live orders fetched successfully', [
        'orders' => $orders
    ], 200);
}


    

}
/*

$order->restaurant = $resturant;
            // Get delivery timing information if delivery man is assigned
            if ($order->delivery_man_id && !in_array($order->order_status, ['delivered', 'canceled'])) {
                try {
                    // Initialize DeliveryTimer
                    $deliveryTimer = new DeliveryTimer($order->delivery_man_id);
                    
                    // Get delivery time estimation
                    $deliveryData = $deliveryTimer->getDeliveryTime($order->id, $order);
                    $dmLastLocation = $deliveryTimer->getDmLastLocation();
                    //  $dmLastLocation->currentLocation->lat;
                    // $dmLastLocation->currentLocation->lng;

                    // Get restaurant arrival ETA if not picked up yet
                    if (!$order->picked_up) {
                        $etaInfo = $deliveryTimer->getResturantReachOutTime($order->id, $order);
                    }

                    // Calculate processing time information
                    $processingInfo = $this->calculateProcessingTime($order, $deliveryData);
                    // dd($processingInfo);

                } catch (\Exception $e) {
                    dd($e->getMessage());
                    Log::error('Error calculating delivery times for order ' . $order->id . ': ' . $e->getMessage());
                }
            }
                */
                


