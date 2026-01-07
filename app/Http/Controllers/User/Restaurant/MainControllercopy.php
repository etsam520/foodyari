<?php

namespace App\Http\Controllers\User\Restaurant;


use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CollectionItem;
use App\Models\Food;
use App\Models\Marquee;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\RestaurantMenu;
use App\Models\RestaurantSchedule;
use App\Models\Subcategory;
use App\Models\Zone;
use Carbon\Carbon;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use stdClass;

class MainControllercopy extends Controller
{
    public function getRestaurants(Request $request)
    {
        $filter  = $request->query('filter');
        $foods = null;
        $_can_show_food = false;
        $cookieName = 'category_name';
        $minutes = 60 * 24 * 365;
        $cookieValue = 'all';
        $customFilter = json_decode($request->query('customFilter'));
        $neares_F = (bool) $request->query('nearest') ?? false;
        $pureVegMode = (bool) $request->query('pureVegMode')  ?? null;
        $vegMode = (bool) $request->query('vegMode')  ?? null;
        $now = Carbon::now();
        // dd($pureVegMode);
        // dd(json_decode($request->query('customFilter')));
        $user_location = [];
        if (auth('customer')->check()) {
            $user_address = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->first();
            if ($user_address) {
                $user_location = ['lat' => $user_address->latitude, 'lng' => $user_address->longitude];
            } else {
                return response()->json(['message' => "please set location"], 404);
            }
        } elseif (Helpers::guestCheck()) {
            $user_address  = Helpers::getGuestSession('guest_location');
            $user_location = ['lat' => $user_address['lat'], 'lng' => $user_address['lng']];
        } else {
            return response()->json(['message' => "please set location"], 404);
        }


        $restaurantt_query =  Restaurant::with('zone:id,name,status')->select(
            'restaurants.*',
            'restaurant_schedule.opening_time',
            'restaurant_schedule.closing_time',
            'restaurant_schedule.id',
            'restaurants.temp_close',
            DB::raw('DATE_FORMAT(restaurant_schedule.opening_time, "%h:%i %p") as formatted_opening_time'),
            DB::raw('DATE_FORMAT(restaurant_schedule.closing_time, "%h:%i %p") as formatted_closing_time'),
            DB::raw('TIMEDIFF(restaurant_schedule.opening_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_open'),
            DB::raw('TIMEDIFF(restaurant_schedule.closing_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_close'),
            DB::raw('
                                    CASE
                                        WHEN TIME(restaurant_schedule.opening_time) < TIME(restaurant_schedule.closing_time)
                                            AND  "' . $now->format('H:i:s') . '" BETWEEN TIME(restaurant_schedule.opening_time) AND TIME(restaurant_schedule.closing_time)
                                        THEN 1
                                        WHEN TIME(restaurant_schedule.opening_time) > TIME(restaurant_schedule.closing_time)
                                            AND ( "' . $now->format('H:i:s') . '" >= TIME(restaurant_schedule.opening_time) OR  "' . $now->format('H:i:s') . '" <= TIME(restaurant_schedule.closing_time))
                                        THEN 1
                                        ELSE 0
                                    END AS is_open_now
                                ')
        )
            ->when($pureVegMode, function ($query) {
                return $query->where('type', 'veg');
            })->when($vegMode, function ($query) {
                return $query->whereIn('restaurants.type', ['veg', 'both']);
            })->addSelect(DB::raw(self::haversine($user_location['lat'], $user_location['lng']) . "as distance"))
            ->leftJoin('restaurant_schedule', 'restaurants.id', '=', 'restaurant_schedule.restaurant_id')
            ->where('restaurant_schedule.day', $now->format('l'));
        // ->first();

        switch ($filter) {
            case 'all':
                $restaurants = $restaurantt_query->get()->toArray();
                dd($restaurants);

            case 'category':
                $categoryId = $request->query('category_id');
                $category = Category::find($categoryId);

                if ($user_location) {

                    $subquery = Restaurant::nearby($user_location['lat'], $user_location['lng']);

                    $restaurants = DB::table(DB::raw("({$subquery->toSql()}) as subquery"))
                        ->mergeBindings($subquery->getQuery())
                        ->whereExists(function ($query) use ($categoryId) {
                            $query->select(DB::raw(1))
                                ->from('food')
                                ->whereRaw('subquery.id = food.restaurant_id')
                                ->where('category_id', $categoryId);
                        })
                        ->orderBy('distance')
                        ->distinct()
                        ->get();
                    foreach ($restaurants as &$restaurant) {
                        $restaurant->zone = Zone::find($restaurant->zone_id);
                    }
                } else {
                    $restaurants = null;
                }
                $cookieValue = $category?->name;
                Cookie::queue($cookieName, $cookieValue, $minutes);
                break;
            default:
                $restaurants = $restaurantt_query->whereHas('foods', function ($query) use ($filter) {

                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%']);
                })
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%']);



                $restaurants = Restaurant::with(['zone', 'foods'])->whereHas('foods', function ($query) use ($filter) {

                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%']);
                })
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%'])
                    ->when($pureVegMode, function ($query) {
                        return $query->where('type', 'veg');
                    })
                    ->when($vegMode, function ($query) {
                        return $query->whereIn('type', ['veg', 'both']); // Fix: Corrected array syntax
                    })
                    ->when(isset($user_location), function ($query) use ($user_location) {
                        return $query->nearby($user_location['lat'], $user_location['lng']);
                    })->get();
                $r_ids =  $restaurants->pluck('id')->toArray();
                $foods = Food::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%']) // Case-insensitive filter
                    ->whereIn('restaurant_id', $r_ids) // Filter by food IDs
                    ->get();

                // Debug the result
                $groupedFoods = $foods->groupBy(function ($food) {
                    return $food->restaurant_id; // Group by the restaurant's name
                });
                // dd($groupedFoods);
                if ($filter != "") {
                    $_can_show_food = true;
                }
                $cart = null;
                if (CartHelper::cartExist()) {
                    $cart = CartHelper::getCart();
                }
                break;
        }




        //apply customFilter
        // customFilter
        dd($customFilter);
        $restaurants = json_decode(json_encode($restaurants), true);
        $today =  Carbon::now();
        // dd($restaurants);
        $openedRestaurants = $closedRestaurants = [];
        foreach ($restaurants as &$element) {
            $restaurantSchedule = RestaurantSchedule::where('day', $today->format('l'))
                ->where('restaurant_schedule.restaurant_id', $restaurant->id)
                ->leftJoin('restaurants', 'restaurant_schedule.restaurant_id', '=', 'restaurants.id')
                ->select(
                    'restaurant_schedule.opening_time',
                    'restaurant_schedule.closing_time',
                    'restaurant_schedule.id',
                    'restaurants.temp_close',
                    DB::raw('DATE_FORMAT(restaurant_schedule.opening_time, "%h:%i %p") as formatted_opening_time'),
                    DB::raw('DATE_FORMAT(restaurant_schedule.closing_time, "%h:%i %p") as formatted_closing_time'),
                    DB::raw('TIMEDIFF(restaurant_schedule.opening_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_open'),
                    DB::raw('TIMEDIFF(restaurant_schedule.closing_time,  "' . $now->format('H:i:s') . '") as time_remaining_to_close'),
                    DB::raw('
                        CASE
                            WHEN TIME(restaurant_schedule.opening_time) < TIME(restaurant_schedule.closing_time)
                                AND  "' . $now->format('H:i:s') . '" BETWEEN TIME(restaurant_schedule.opening_time) AND TIME(restaurant_schedule.closing_time)
                            THEN 1
                            WHEN TIME(restaurant_schedule.opening_time) > TIME(restaurant_schedule.closing_time)
                                AND ( "' . $now->format('H:i:s') . '" >= TIME(restaurant_schedule.opening_time) OR  "' . $now->format('H:i:s') . '" <= TIME(restaurant_schedule.closing_time))
                            THEN 1
                            ELSE 0
                        END AS is_open_now
                    ')
                )
                ->first();
            $schedule = RestaurantSchedule::where('day', strtolower($today->format('l')))->where('restaurant_id', $element['id'])->first();
            $restaurantTiming = [];

            if ($schedule) {
                $closing = Helpers::isClosed($schedule['opening_time'], $schedule['closing_time']);
                $restaurantTiming = Helpers::remainingTime($schedule['opening_time'], $schedule['closing_time']);
            } else {
                $closing['isClosed'] = true;
                $restaurantTiming['isClosed'] = true;
            }
            $element->schedules = $schedule ?? null;
            $element->isClosed = $element->temp_close ?? $closing['isClosed'];


            // if($element->isClosed == true || $schedule ==null || $element->zone->status == 0){
            if ($restaurantTiming['isClosed'] == true || $element->isClosed == true || $element->zone->status == 0) {
                $closedRestaurants[] = $element;
            } else {
                $openedRestaurants[] = $element;
            }
        }
        if ($customFilter != null) {
            $openedRestaurants = self::customFilterProcess($openedRestaurants, $customFilter);

            $closedRestaurants = self::customFilterProcess($closedRestaurants, $customFilter);
        }
        if ($neares_F) {
            $openedRestaurants = self::neares_Filter($openedRestaurants);

            $closedRestaurants = self::neares_Filter($closedRestaurants);
        }

        $restaurants = array_merge($openedRestaurants, $closedRestaurants);

        // dd($restaurants);
        $user = Session::get('userInfo');
        if ($user) {
            $collectionItems = CollectionItem::with('collection')->whereHas('collection', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->where('type', 'restaurant')->get()->toArray();
        } else {
            $collectionItems = collect(); // Return an empty collection if the user is not found in the session
        }



        return response()->json([
            'view' => view('user-views.restaurant.partials.restaurant', compact('restaurants', 'collectionItems'))->render(),
            'count' => count($restaurants),
            'foods' => $_can_show_food ? view('user-views.restaurant.food.partials._searched_foods', compact('groupedFoods', 'cart'))->render() : null,
            // 'rest' => $restaurants
        ]);
    }

    private static function haversine($latitude, $longitude)
    {
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";
        return $haversine;
    }

    private static function neares_Filter(&$restaurants)
    {
        dd($restaurants);
        usort($restaurants, function ($a, $b) {
            return $a['distance'] <=> $b['distance']; // Sort by distance in ascending order
        });
        return $restaurants; // Return the sorted array
    }

    private static function customFilterProcess(&$restaurants, $customFilter)
    {
        foreach ($customFilter as $c_f) {
            if ($c_f?->filterKey == "sortBy") {
                foreach ($c_f->filterValues as $fv) {
                    // relavance
                    if ($fv->itemName === "relevance" && ($fv->value == 1)) {
                        $restaurants = $restaurants;
                    }

                    if ($fv->itemName === "rating" && ($fv->value == 1)) {
                        usort($restaurants, function ($a, $b) {
                            return $b['rating'] ?? 0 <=> $a['rating'] ?? 0;
                        });
                    }

                    if ($fv->itemName === "deliveryTime" && ($fv->value == 1)) {
                        usort($restaurants, function ($a, $b) {
                            $a_avgTime =  Helpers::calculateAverageDeliveryTime($a['min_delivery_time'], $a['max_delivery_time']);
                            $b_avgTime =  Helpers::calculateAverageDeliveryTime($b['min_delivery_time'], $b['max_delivery_time']);
                            // dd($b_avgTime);
                            return $a_avgTime ?? 0 <=> $b_avgTime;
                        });
                    }
                }
            }
            if ($c_f?->filterKey === "type") {
                foreach ($c_f->filterValues as $fv) {
                    if ($fv->itemName === "nonVeg" && ($fv->value == 1)) {
                        $selectedFilter = "non veg"; // Can be 'veg' or 'non-veg'
                        $restaurants = array_filter($restaurants, function ($restaurant) use ($selectedFilter) {
                            return $restaurant['type'] === $selectedFilter;
                        });
                    }

                    if ($fv->itemName === "veg" && $fv->value == 1) {
                        $selectedFilter = "veg"; // Can be 'veg' or 'non-veg'
                        $restaurants = array_filter($restaurants, function ($restaurant) use ($selectedFilter) {
                            return $restaurant['type'] === $selectedFilter || $restaurant['type'] === "both";
                        });
                    }
                }
            }
            if ($c_f?->filterKey === "rating") {

                foreach ($c_f->filterValues as $fv) {
                    if ($fv->itemName === "threeDotFivePlus" && ($fv->value == 1)) {
                        // dd($customFilter);
                        $selectedFilter = 3.5; // Can be 'veg' or 'non-veg'
                        $restaurants = array_filter($restaurants, function ($restaurant) use ($selectedFilter) {
                            $ratin[] = $restaurant['rating'] ?? 1;
                            return $restaurant['rating'] ?? 0 >= $selectedFilter;
                        });
                    }

                    if ($fv->itemName === "foutDotZeroPlus" && ($fv->value == 1)) {
                        $selectedFilter = 4.5; // Can be 'veg' or 'non-veg'
                        $ratin[] = $restaurant['rating'] ?? 0;

                        $restaurants = array_filter($restaurants, function ($restaurant) use ($selectedFilter) {
                            return $restaurant['rating'] ?? 0 >= $selectedFilter;
                        });
                    }
                }
            }

            if ($c_f?->filterKey === "deliveryTime") {

                foreach ($c_f->filterValues as $fv) {
                    if ($fv->itemName === "beforeThirtyMins" && ($fv->value == 1)) {
                        $selectedFilter = 30 * 60; // Can be 'veg' or 'non-veg'
                        $restaurants = array_filter($restaurants, function ($restaurant) use ($selectedFilter) {
                            $r_avgTime =  Helpers::calculateAverageDeliveryTime($restaurant['min_delivery_time'], $restaurant['max_delivery_time']);
                            return $r_avgTime <= $selectedFilter;
                        });
                    }

                    if ($fv->itemName === "beforFourtyfiveMins" && ($fv->value == 1)) {
                        $selectedFilter = 45 * 60; // Can be 'veg' or 'non-veg'
                        $restaurants = array_filter($restaurants, function ($restaurant) use ($selectedFilter) {
                            $r_avgTime =  Helpers::calculateAverageDeliveryTime($restaurant['min_delivery_time'], $restaurant['max_delivery_time']);
                            return $r_avgTime <= $selectedFilter;
                        });
                    }
                }
            }
        }
        return $restaurants;
    }

    public function restaurant(Request $request, $name)
    {
        $today = Carbon::now();
        $banners = [];
        $user_location  = json_decode($request->cookie('user_location'));
        $restaurantName = Str::lower($name);


        // Restaurant::where(DB::raw('LOWER(name)'),'LIKE',"%{$restaurantName}%" )
        $restaurant = Restaurant::with(['schedules' => function ($query) use ($today) {
            $query->where('day', strtolower($today->format('l')));
        }, 'zone'])
            ->where(DB::raw('url_slug'), 'LIKE', "%{$restaurantName}%")
            ->orWhere(DB::raw("
            LOWER(
                REGEXP_REPLACE(
                    REPLACE(
                        REPLACE(
                            REPLACE(name, '''', ''),
                            '&', 'and'
                        ), ' ', '-'
                    ), '[^a-z0-9-]', ''
                )
            )
        "), 'LIKE', "%{$restaurantName}%")
            ->when(!empty($user_location), function ($query) use ($user_location) {
                $query->nearby($user_location->lat, $user_location->lng);
            })->first();

        if (!$restaurant) {
            return redirect()->route('user.dashboard');
        }
        $schedule = $restaurant->schedules->first();
        // dd($schedule);
        if ($schedule) {
            $closing = Helpers::isClosed($schedule->opening_time, $schedule->closing_time);
        } else {
            $closing['isClosed'] = true;
        }

        // if($restaurant->zone->stauts == 0){
        //     return back()->with('warning', 'Service Zone is Closed');
        // }
        $restaurant->isClosed = $closing['isClosed'];
        $cookieName = 'restaurant';
        $minutes = 60 * 24 * 365;
        $cookieValue = $restaurant;
        Cookie::queue($cookieName, $cookieValue, $minutes);




        $userLocation = [];
        $locationPoint1 = [];


        if (auth('customer')->check()) {
            $userLocation = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->first();
            // dd($userLocation);
            $locationPoint1['lat'] = $userLocation->latitude ?? 0;
            $locationPoint1['lon'] = $userLocation->longitude ?? 0;
        } else {
            $userLocation = Helpers::getGuestSession('guest_location');
            if ($userLocation) {
                $default_address['type'] = $userLocation['type'];
                $default_address['address'] = $userLocation['address'];
                $locationPoint1['lat'] = $userLocation['lat'];
                $locationPoint1['lon'] = $userLocation['lng'];
            }
        }

        $banners = Banner::where('zone_id', $restaurant->zone_id)->isActive()->latest()->get();
        $marquees = Marquee::where('zone_id', $restaurant->zone_id)->isActive()->latest()->get();
        $filterBanners = [];
        $filterMarquees = [];

        $zone = $restaurant->zone ?? Zone::find($restaurant->zone_id);
        foreach ($banners as $banner) {
            if ($banner->type == 'location' && !empty($locationPoint1)) {
                $locationPoint2 = ['lat' => $banner->latitude, 'lon' => $banner->longitude];
                $distance = Helpers::haversineDistance($locationPoint1, $locationPoint2);
                if ((float) $banner->radius > $distance) {
                    $filterBanners[] = $banner;
                }
            } elseif ($banner->type == 'food') {
                if ($banner->zone_id == $zone->id) {
                    $food = Food::where('restaurant_id', $restaurant->id)->find($banner->food_id);
                    if ($food) {
                        $filterBanners[] = $banner;
                    }
                }
            } elseif ($banner->type == 'zone') {
                if ($banner->zone_id == $zone->id) {
                    // $filterBanners[] = $banner;
                }
            } elseif ($banner->type == 'restaurant') {

                if ($banner->restaurant_id == $restaurant->id && ($banner->screen_to == "inside_restaurant")) {
                    $filterBanners[] = $banner;
                }
            }
        }

        // dd($filterBanners);
        $banners = $filterBanners;
        // dd($banners);

        foreach ($marquees as $marquee) {
            if ($marquee->type == 'location' && !empty($locationPoint1)) {
                $locationPoint2 = ['lat' => $marquee->latitude, 'lon' => $marquee->longitude];
                $distance = Helpers::haversineDistance($locationPoint1, $locationPoint2);
                if ((float) $marquee->radius > $distance) {
                    $filterMarquees[] = $marquee;
                }
            } elseif ($marquee->type == 'food') {
                if ($marquee->zone_id == $zone->id) {
                    $food = Food::where('restaurant_id', $restaurant->id)->find($marquee->food_id);
                    if ($food) {
                        $filterMarquees[] = $marquee;
                    }
                }
            } elseif ($marquee->type == 'zone') {
                if ($marquee->zone_id == $zone->id) {
                    // $filterBanners[] = $banner;
                }
            } elseif ($marquee->type == 'restaurant') {

                if ($marquee->restaurant_id == $restaurant->id && ($marquee->screen_to == "inside_restaurant")) {
                    $filterMarquees[] = $marquee;
                }
            }
        }

        // dd($filterBanners);
        $marquees = $filterMarquees;

        return view('user-views.restaurant.food.index', compact('restaurant', 'filterBanners', 'filterMarquees'));
    }

    public function get_menu(Request $request)
    {
        $menudata =  $request->json('menu');
        try {
            $menu = [];
            foreach ($menudata as $data) {
                $menu[] = RestaurantMenu::whereHas('foods')
                    ->with(['submenu' => function ($query) use ($data) {
                        $query->whereIn('id', $data['submenu_ids'])
                            ->isActive()
                            ->orderBy('position');
                    }, 'foods'])
                    ->find($data['menu_id']);
            }
            return response()->json(['view' => view('user-views.restaurant.food.partials._menu', compact('menu'))->render()], 200);
        } catch (\Throwable $th) {
            dd($th);
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function invoice(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with(['orderCalculationStmt', 'restaurant', 'customer'])->find($orderId);
        return view('user-views.restaurant.invoices.index', compact('order'));
    }

    public function orderStatus(Request $request)
    {
        $orderId = $request->query('order_id');
        if (isset($orderId)) {
            $order = Order::with(['details', 'restaurant', 'customer'])->find($orderId);
        } else {
            $userId = Session::get('userInfo')->id;
            $order = Order::with(['details', 'restaurant', 'customer'])->where('customer_id', $userId)->latest()->first();
        }
        return view('user-views.restaurant.orders.myorder', compact('order'));
    }
}
