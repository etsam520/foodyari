<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\CentralLogics\Helpers;
use App\Models\Restaurant;
use App\Models\RestaurantMenu;
use App\Models\Zone;
use PhpParser\Node\Stmt\TryCatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class VendorController extends Controller
{
    public function index()
    {
        
        $vendors = Vendor::all();
        $zones = Zone::select('name','id','coordinates')->where('status', 1)->get();
        return view('admin-views.vendor.index',compact('zones','vendors'));
    }

    public function store(Request $request)
    {
      
        $rules =  [
            'name' => 'required|string|max:191',
            'ownertype' => 'required|string',
            'radius' => 'required|numeric|max:180',
            'street' => 'required|string|max:1000',
            'city' => 'required|string|max:1000',
            'pincode' => 'required|digits:6',
            'email' => 'nullable|email', //|unique:vendors
            'minimum_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            'maximum_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            'logo' => 'required|max:2048',
            // 'cover_photo' => 'required|max:2048',
            "latitude" => 'required',
            "longitude" => 'required',
            'zone_id' => 'required',
            'tax' => 'required',
        ];

        if ($request->ownertype === "new") {
            $rules = array_merge([

                'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20|unique:vendors',
                'fname' => 'required|string|max:100',
                'lname' => 'nullable|string|max:100',
                'password' => 'required|min:6',
                'cpwd' =>  'required|same:password',
            ], $rules);
        } else {
            $rules = array_merge($rules, [
                'vendor_id' => 'required|numeric',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try{

        $vendor = null;
        if (!isset($request->vendor_id)) {
            $vendor = new Vendor();
            $vendor->f_name = $request->fname;
            $vendor->l_name = $request->lname;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            $vendor->password = bcrypt($request->password);
            $vendor->save();
        }else{
            $vendor = Vendor::find($request->vendor_id);
        }



        $restaurant = new Restaurant();
        $restaurant->name = $request->name;
        $restaurant->email = $request->email;
        $restaurant->logo = Helpers::uploadFile($request->file('logo'),'restaurant' );
        // $restaurant->cover_photo = Helpers::uploadFile( $request->file('cover_photo'),'restaurant/cover/');
        $restaurant->radius = $request->radius;
        $restaurant->address = json_encode([
            'street' => $request->street,
            'city' => $request->city,
            'pincode' => $request->pincode,
        ]);
        $restaurant->vendor_id = $vendor->id ?? $request->vendor_id;
        $restaurant->phone = $vendor->phone;


        $restaurant->email = $request->email??null;

        // Validate restaurant location is within zone polygon
        if (!$this->isLocationInZone($request->latitude, $request->longitude, $request->zone_id)) {
            return back()->with('error', 'Restaurant location must be within the selected zone boundary.')->withInput();
        }

        $restaurant->coordinates =json_encode(['latitude'=>$request->latitude ,'longitude'=> $request->longitude]);
        $restaurant->latitude = $request->latitude;
        $restaurant->longitude = $request->longitude;
        $restaurant->tax = $request->tax;
        $restaurant->zone_id = $request->zone_id;
        $restaurant->min_delivery_time = Carbon::createFromTime(0, 0, 0)->addMinutes($request->minimum_delivery_time)->format('H:i:s') ;
        $restaurant->max_delivery_time = Carbon::createFromTime(0, 0, 0)->addMinutes($request->maximum_delivery_time)->format('H:i:s') ;

        $restaurant->save();

        return redirect('admin/restaurant/list')->with('success',__('messages.vendor') . __('messages.added_successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function list()
    {
        $restaurants = Restaurant::with('vendor')->latest()->get();
        // foreach($restaurants as &$restaurant){
        //     $restaurant->vendor = Vendor::find($restaurant->vendor_id);
        // }
        // dd($restaurants);
        return view('admin-views.vendor.list',compact('restaurants'));
    }

    public function edit($id)  {
        try {//
            if(empty($id)){
                throw new \Error('Restaurant Id can\'t be null');
            }
            $restaurant = Restaurant::with('vendor')->find($id);
            $zones = Zone::select('name','id','coordinates')->where('status', 1)->get();

            return view('admin-views.vendor._edit', compact('restaurant','zones'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request) {

        $rules = [
            'name' => 'required|string|max:191',
            'id' => 'required',
            'restaurant_no' => 'nullable',
            'restaurant_url' => 'required|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            'street' => 'required|string|max:1000',
            'city' => 'required|string|max:1000',
            'pincode' => 'required|numeric|digits:6',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'radius' => 'required|numeric|max:180',
            'badge_one' => 'nullable|string|max:1000',
            'badge_two' =>'nullable|string|max:1000',
            'three' =>'nullable|string|max:1000',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
            'minimum_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2',
            'maximum_delivery_time' => 'required|regex:/^([0-9]{2})$/|min:2|max:2|gt:minimum_delivery_time',
            'email' => 'nullable|email',
            'tax' => 'required|numeric|max:100',
            'comission' => 'required|numeric|max:100',
            'minimum_order' => 'required|numeric|max:99999',
        ];



        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $restaurant = restaurant::find($request->id);
            $restaurant->name = $request->name;
            $restaurant->restaurant_no = $request->restaurant_no;
            $restaurant->phone = $request->phone;
            $restaurant->url_slug = $request->restaurant_url;
            if($request->file('logo')){
                $restaurant->logo = Helpers::updateFile($request->file('logo'), 'restaurant',$restaurant->logo);
            }
            // if($request->file('cover_photo')){
            //     $restaurant->cover_photo = Helpers::updateFile($request->file('cover_photo'), 'restaurant/cover/',$restaurant->cover_photo);
            // }
            $restaurant->radius = $request->radius;
            $restaurant->address = json_encode([
                                        'street' => $request->street,
                                        'city' => $request->city,
                                        'pincode' => $request->pincode,
                                    ]);
            $restaurant->badges = json_encode(['b1' => $request->badge_one, 'b2' => $request->badge_two, 'b3' => $request->badge_three]);
            $restaurant->min_delivery_time = Carbon::createFromTime(0, 0, 0)->addMinutes($request->minimum_delivery_time)->format('H:i:s') ;
            $restaurant->max_delivery_time = Carbon::createFromTime(0, 0, 0)->addMinutes($request->maximum_delivery_time)->format('H:i:s') ;
            $restaurant->zone_id = $request->zone_id;
            $restaurant->minimum_order = $request->minimum_order;
            
            // Validate restaurant location is within zone polygon
            if (!$this->isLocationInZone($request->latitude, $request->longitude, $request->zone_id)) {
                return back()->with('error', 'Restaurant location must be within the selected zone boundary.')->withInput();
            }
            
            $restaurant->latitude = $request->latitude;
            $restaurant->longitude = $request->longitude;
            $restaurant->coordinates = json_encode([
                'longitude' => $request->longitude,
                'latitude' => $request->latitude,
            ]);

            $restaurant->email = $request->email;
            $restaurant->tax = $request->tax;
            $restaurant->comission = $request->comission;
            $restaurant->save();

            return redirect()->back()->with('success', __('Restaurant Updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function get_restaurants(Request $request)
    {
        
        if($request->get('zone_id')){
            $zone_id = $request->get('zone_id');
            return response()->json( Restaurant::select('name', 'id')->where('zone_id',$zone_id)->get());

        }
        return response()->json( Restaurant::select('name', 'id')->get());
    }

    public function view($id)
    {
        try {
            $restaurant = Restaurant::with(['vendor', 'zone', 'foods'])->find($id);
            
            if (!$restaurant) {
                return redirect()->route('admin.restaurant.list')->with('error', 'Restaurant not found');
            }

            // Generate QR code for restaurant
            $restaurantLink = url('/restaurant/' . $restaurant->id);
            $qrbase64 = Helpers::qrGenerate($restaurant->name, $restaurantLink);

            // Get additional restaurant statistics
            $totalOrders = $restaurant->orders()->count();
            $totalRevenue = $restaurant->orders()->where('order_status', 'delivered')->sum('order_amount');
            $avgRating = $restaurant->rating ?? 0;

            return view('admin-views.vendor.view', compact('restaurant', 'qrbase64', 'totalOrders', 'totalRevenue', 'avgRating'));
            
        } catch (\Exception $e) {
            return redirect()->route('admin.restaurant.list')->with('error', $e->getMessage());
        }
    }

    public function access($id)
    {
        $restaurant = Restaurant::with('vendor')->find($id);
        if(!$restaurant){
            return redirect()->route('admin.restaurant.list')->with('warning', 'restaurant Not Found');
        }
        Auth::guard('vendor')->login($restaurant->vendor);
        Session::put('restaurant',$restaurant);

        return redirect()->route('vendor.dashboard');

    }

    public function status($id, $status)
    {
        try {
            $restaurant = Restaurant::find($id);
            if (!$restaurant) {
                Toastr::error('Restaurant not found!');
                return back();
            }

            $restaurant->status = $status;
            $restaurant->save();

            $statusText = $status == 1 ? 'activated' : 'deactivated';
            Toastr::success("Restaurant has been {$statusText} successfully!");
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    public function sort()
    {
        $zone_wise_restaurants = Restaurant::with('zone')->isActive(true)->orderBy('position')->get()->groupBy('zone.name');
        // $restaurants =  $restaurants;
        // dd($restaurants);

        return view('admin-views.vendor.sort',compact('zone_wise_restaurants'));
    }

    public function sort_update(Request $request)
    {
        $sortedArray = $request->json('sortedArray');
        foreach($sortedArray as $key => $value){
            $restaurant = Restaurant::find($value);
            $restaurant->position = $key;
            $restaurant->save();

        }
        return true;
    }

    public function get_menus(Request $request)
    {
        $restaurantId = $request->query('restaurant_id')??null;
        $menu = RestaurantMenu::where('restaurant_id', $restaurantId)->isActive()->get();
        return response()->json($menu);

    }

    public function get_zone_coordinates(Request $request)
    {
        try {
            $zoneId = $request->get('zone_id');
            
            if (!$zoneId) {
                return response()->json(['error' => 'Zone ID is required'], 400);
            }

            $zone = Zone::find($zoneId);
            
            if (!$zone) {
                return response()->json(['error' => 'Zone not found'], 404);
            }

            $coordinates = json_decode($zone->coordinates, true);
            
            return response()->json([
                'success' => true,
                'zone' => [
                    'id' => $zone->id,
                    'name' => $zone->name,
                    'coordinates' => $coordinates
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function ownerList(Request $request){
        $query = Vendor::with(['restaurants' => function($query) {
            $query->select('id', 'vendor_id', 'name', 'email', 'phone', 'status', 'zone_id', 'created_at', 'is_blocked', 'blocked_at', 'blocked_reason')
                  ->with('zone:id,name');
        }]);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('f_name', 'like', "%{$search}%")
                  ->orWhere('l_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status 
        if ($request->has('status') && $request->status !== 'all') {
            switch ($request->status) {
                case 'active':
                    $query->where('status', 1)->where('is_blocked', 0);
                    break;
                case 'inactive':
                    $query->where('status', 0)->where('is_blocked', 0);
                    break;
                case 'blocked':
                    $query->where('is_blocked', 1);
                    break;
                case 'has_restaurants':
                    $query->whereHas('restaurants');
                    break;
                case 'no_restaurants':
                    $query->whereDoesntHave('restaurants');
                    break;
            }
        }

        $vendors = $query->orderBy('created_at', 'desc')->get();
        
        return view('admin-views.vendor.owner.list', compact('vendors'));
    }

    public function ownerEdit($id){
        $vendor = Vendor::find($id);
        return view('admin-views.vendor.owner.edit', compact('vendor'));
    }

    public function ownerView($id){
        $vendor = Vendor::with(['restaurants' => function($query) {
            $query->select('id', 'vendor_id', 'name', 'email', 'phone', 'status', 'zone_id', 'created_at', 'is_blocked', 'blocked_at', 'blocked_reason', 'description')
                  ->with(['zone:id,name', 'foods' => function($foodQuery) {
                      $foodQuery->select('id', 'restaurant_id', 'name', 'status');
                  }]);
        }])->findOrFail($id);
        
        $totalRestaurants = $vendor->restaurants->count();
        $activeRestaurants = $vendor->restaurants->where('status', 1)->count();
        $totalFoods = $vendor->restaurants->sum(function($restaurant) {
            return $restaurant->foods->count();
        });
        
        return view('admin-views.vendor.owner.view', compact('vendor', 'totalRestaurants', 'activeRestaurants', 'totalFoods'));
    }

    public function exportOwners(Request $request)
    {
        $query = Vendor::with(['restaurants' => function($query) {
            $query->select('id', 'vendor_id', 'name', 'status');
        }]);

        // Apply same filters as list
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('f_name', 'like', "%{$search}%")
                  ->orWhere('l_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'active') {
                $query->whereHas('restaurants');
            } elseif ($request->status === 'inactive') {
                $query->whereDoesntHave('restaurants');
            }
        }

        $vendors = $query->get();

        $csv = "Name,Email,Phone,Total Restaurants,Active Restaurants,Joined Date\n";
        
        foreach ($vendors as $vendor) {
            $totalRestaurants = $vendor->restaurants->count();
            $activeRestaurants = $vendor->restaurants->where('status', 1)->count();
            
            $csv .= sprintf(
                '"%s","%s","%s","%d","%d","%s"' . "\n",
                $vendor->f_name . ' ' . $vendor->l_name,
                $vendor->email ?? '',
                $vendor->phone ?? '',
                $totalRestaurants,
                $activeRestaurants,
                $vendor->created_at->format('Y-m-d H:i:s')
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="restaurant_owners_' . date('Y-m-d_H-i-s') . '.csv"');
    }

    public function ownerUpdate(Request $request){
        $rules = [
            'id' => 'required',
            'f_name' => 'required|string|max:100',
            'l_name' => 'nullable|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'password' => 'nullable|min:6',
            'cpwd' => 'nullable|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vendor = Vendor::find($request->id);
            $vendor->f_name = $request->f_name;
            $vendor->l_name = $request->l_name;
            $vendor->email = $request->email;
            $vendor->phone = $request->phone;
            if($request->password){
                $vendor->password = bcrypt($request->password);
            }
            $vendor->save();

            return redirect()->back()->with('success', __('Owner Updated'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Check if a location is within a zone's polygon
     */
    private function isLocationInZone($latitude, $longitude, $zoneId)
    {
        try {
            $zone = Zone::find($zoneId);
            if (!$zone) {
                return false;
            }

            $coordinates = json_decode($zone->coordinates, true);
            if (!$coordinates) {
                return true; // Allow if no polygon data
            }

            // Handle polygon format
            if (isset($coordinates['polygon']) && is_array($coordinates['polygon'])) {
                return Helpers::pointInPolygon(
                    floatval($latitude), 
                    floatval($longitude), 
                    $coordinates['polygon']
                );
            }

            // If it's just a single point, allow any location (backward compatibility)
            if (isset($coordinates['latitude']) && isset($coordinates['longitude'])) {
                return true;
            }

            return true; // Default to allow if format is unclear
        } catch (\Exception $e) {
            Log::error('Error validating location in zone: ' . $e->getMessage());
            return true; // Allow on error to prevent blocking
        }
    }

    /**
     * Change owner status (activate/deactivate)
     */
    public function ownerStatus($id, $status)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                Toastr::error('Owner not found!');
                return back();
            }

            $vendor->status = $status;
            $vendor->save();

            $statusText = $status == 1 ? 'activated' : 'deactivated';
            Toastr::success("Owner has been {$statusText} successfully!");
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    /**
     * Block an owner
     */
    public function ownerBlock(Request $request, $id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                Toastr::error('Owner not found!');
                return back();
            }

            $vendor->is_blocked = 1;
            $vendor->blocked_at = now();
            $vendor->blocked_reason = $request->get('reason', 'Blocked by admin');
            $vendor->save();

            // Also deactivate all restaurants of this owner
            $vendor->restaurants()->update(['status' => 0]);

            Toastr::success('Owner has been blocked successfully! All their restaurants have been deactivated.');
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    /**
     * Unblock an owner
     */
    public function ownerUnblock($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                Toastr::error('Owner not found!');
                return back();
            }

            $vendor->is_blocked = 0;
            $vendor->blocked_at = null;
            $vendor->blocked_reason = null;
            $vendor->save();

            Toastr::success('Owner has been unblocked successfully!');
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    /**
     * Access owner account (login as owner)
     */
    public function ownerAccess($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                Toastr::error('Owner not found!');
                return back();
            }

            if ($vendor->is_blocked) {
                Toastr::error('Cannot access blocked owner account!');
                return back();
            }

            Auth::guard('vendor')->login($vendor);
            
            // If owner has restaurants, set the first active restaurant as session
            $restaurant = $vendor->restaurants()->where('status', 1)->first();
            if ($restaurant) {
                Session::put('restaurant', $restaurant);
            }

            Toastr::success('Successfully logged in as owner!');
            return redirect()->route('vendor.dashboard');
            
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    /**
     * Change restaurant status for specific owner
     */
    public function ownerRestaurantStatus($id, $status)
    {
        try {
            $restaurant = Restaurant::find($id);
            if (!$restaurant) {
                Toastr::error('Restaurant not found!');
                return back();
            }

            // Check if owner is blocked
            if ($restaurant->vendor && $restaurant->vendor->is_blocked) {
                Toastr::error('Cannot activate restaurant of blocked owner!');
                return back();
            }

            // Check if restaurant is blocked
            if ($restaurant->is_blocked && $status == 1) {
                Toastr::error('Cannot activate blocked restaurant! Please unblock it first.');
                return back();
            }

            $restaurant->status = $status;
            $restaurant->save();

            $statusText = $status == 1 ? 'activated' : 'deactivated';
            Toastr::success("Restaurant has been {$statusText} successfully!");
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    /**
     * Block a restaurant
     */
    public function restaurantBlock(Request $request, $id)
    {
        try {
            $restaurant = Restaurant::find($id);
            if (!$restaurant) {
                Toastr::error('Restaurant not found!');
                return back();
            }

            $restaurant->is_blocked = 1;
            $restaurant->blocked_at = now();
            $restaurant->blocked_reason = $request->get('reason', 'Blocked by admin');
            $restaurant->status = 0; // Also deactivate the restaurant
            $restaurant->save();

            Toastr::success('Restaurant has been blocked successfully!');
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

    /**
     * Unblock a restaurant
     */
    public function restaurantUnblock($id)
    {
        try {
            $restaurant = Restaurant::find($id);
            if (!$restaurant) {
                Toastr::error('Restaurant not found!');
                return back();
            }

            $restaurant->is_blocked = 0;
            $restaurant->blocked_at = null;
            $restaurant->blocked_reason = null;
            $restaurant->save();

            Toastr::success('Restaurant has been unblocked successfully!');
            
            return back();
        } catch (\Exception $e) {
            Toastr::error('Something went wrong! Please try again.');
            return back();
        }
    }

}
