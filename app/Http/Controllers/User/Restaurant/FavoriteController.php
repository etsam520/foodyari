<?php

namespace App\Http\Controllers\User\Restaurant;

use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index(){
        return view('user-views.restaurant.favorite.index');
    }
    public function favoriteRestaurant(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);
        Auth::guard('customer')->user()->favoriteRestaurants()->attach($restaurant);

        return response()->json(['message' => 'Restaurant added to favorites']);
    }

    // Add food to favorites
    public function favoriteFood(Request $request)
    {
        $foodId = $request->query('food_id');
        $food = Food::findOrFail($foodId);
        Auth::guard('customer')->user()->favoriteFoods()->attach($food);

        return response()->json(['message' => 'Food added to favorites']);
    }

    // Remove restaurant from favorites
    public function unfavoriteRestaurant(Request $request)
    {
        $restaurantId = $request->query('restaurant_id');
        $restaurant = Restaurant::findOrFail($restaurantId);
        Auth::guard('customer')->user()->favoriteRestaurants()->detach($restaurant);

        return response()->json(['message' => 'Restaurant removed from favorites']);
    }

    // Remove food from favorites
    public function unfavoriteFood(Request $request)
    {
        $foodId = $request->query('food_id');
        $food = Food::findOrFail($foodId);
        Auth::guard('customer')->user()->favoriteFoods()->detach($food);

        return response()->json(['message' => 'Food removed from favorites']);
    }

    // Show user's favorite restaurants
    public function myFavoriteRestaurants(Request $request)
    {
        $user = Auth::guard('customer')->user();
        
        $redis = new RedisHelper();
        $user_location = $redis->get("user:{$user->id}:user_location", true) ?? null; //dd($data);
        if($user_location != null){

            $restaurants = Auth::guard('customer')->user()->favoriteRestaurants()->with('zone')->nearby($user_location['lat'],$user_location['lng'])->orderBy('position')->get();
        }else{
            if (auth('customer')->check()) {
                $userLocation = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->first();
                if($userLocation){
                   $restaurants =  Auth::guard('customer')->user()->favoriteRestaurants()->with('zone')->nearby($userLocation->latitude,$userLocation->longitude)->orderBy('position')->get();
                }else{
                    return response()->json(['message' => "please set location"
                ],404);
                }
            }else{
                return response()->json(['message' => "please set location"
                ],404);
            }
        }
        $openedRestaurants = $closedRestaurants =[];
        foreach ($restaurants as &$element) {
            $today =  Carbon::now();
            $schedule = RestaurantSchedule::where('day', strtolower($today->format('l')))->where('restaurant_id', $element->id)->first();
            // if($element->id == 10){

            //     dd($schedule);
            // }
            if($schedule){
                $closing = Helpers::isClosed($schedule->opening_time, $schedule->closing_time);
            }else{
                $closing['isClosed'] = true;
            }
            $element->schedules = $schedule??null;
            $element->isClosed =$element->temp_close??$closing['isClosed'];


            if($element->isClosed == true || $schedule ==null || $element->zone->status == 0){
                $closedRestaurants[] = $element;
            }else{
                $openedRestaurants[] = $element;
            }
        }


        $restaurants = array_values(array_merge($openedRestaurants, $closedRestaurants));
        // dd($restaurants);

        return response()->json([
            'view' => view('user-views.restaurant.partials.restaurant',compact('restaurants'))->render(),
            'count' =>count($restaurants),
            // 'rest' => $restaurants
        ]);
    }

    // Show user's favorite foods
    public function myFavoriteFoods()
    {
        $foods = Auth::guard('customer')->user()->favoriteFoods()->get();
        $cart = [];
        if(CartHelper::cartExist()){
            $cart = CartHelper::getCart();
        }
        // dd($cart[0]['product_id']);
        return response()->json([
            'view'=>view('user-views.restaurant.favorite.partials._foods',compact('foods','cart'))->render(),
        ]);
    }
}
