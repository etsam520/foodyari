<?php

namespace App\Http\Controllers\User\Restaurant;

use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\CollectionItem;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CollectionController extends Controller
{
    public function index(){
        return view('user-views.restaurant.collection.index');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $collection = Collection::create([
            'name' => $request->name,
            'user_id' => Session::get('userInfo')->id,
        ]);
        // Return a JSON response
        return response()->json($collection, 201);
    }

    // Add item to collection (food or restaurant)
    public function addItem(Request $request)
    {
        $itemId =  $request->json('itemId');
        $collectionId =  $request->json('collectionId');
        $type =  $request->json('type');


        $collection = Collection::findOrFail($collectionId);

        if ($collection->user_id !== Session::get('userInfo')->id) {
            return response()->json(['message' => 'Unauthorized Access'],403);
        }

        // Add food or restaurant to collection
        if ($type === 'food') {
            $collection->items()->create([
                'item_id' => $itemId,
                'type' => 'food',
            ]);
        } else {
            $collection->items()->create([
                'item_id' => $itemId,
                'type' => 'restaurant',
            ]);
        }
        return response()->json(['message' => 'Item added to collection!']);
    }

    // Remove item from collection
    public function undoItem(Request $request)
    {
        $itemId =  $request->json('itemId');
        $type =  $request->json('type');
        $collectionId =  $request->json('collectionId');
        CollectionItem::where('item_id', $itemId)
            ->where('type', $type)
            ->where('collection_id', $collectionId)
            ->delete();
        return response()->json(['message' => 'Item removed from collection!']);
    }


    public function show($id)
    {
        $collection = Collection::with(['items.food', 'items.restaurant'])->findOrFail($id);
        return view('collections.show', compact('collection'));
    }

    public function myRestaurantCollection(Request $request)
    {
        try{
            $user = Session::get('userInfo');
            $collectionItems = Collection::with(['items' => function ($query) {
                $query->where('type', 'restaurant');
            }])
            ->where('user_id', $user->id)
            ->get()
            ->toArray();
            $restaurantIds = [3,5,6,8,13];
            foreach ($collectionItems as $collection) {
                foreach ($collection['items'] as $item) { // Corrected "item" to "items"
                    if (!in_array($item['item_id'], $restaurantIds)) { // Added "$" before "item"
                        $restaurantIds[] = $item['item_id']; // Added ";" to end the line
                    }
                }
            } 
            
            // dd($restaurantIds);
            $redis = new RedisHelper();
            $user_location = $redis->get("user:{$user->id}:user_location", true) ?? null;  

            if($user_location != null){
                $restaurants = Restaurant::whereIn('id',$restaurantIds)->with('zone')->nearby($user_location['lat'],$user_location['lng'])->orderBy('position')->get()->toArray();
            }else{
                if (auth('customer')->check()) {
                    $userLocation = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->first();
                    if($userLocation){
                    $restaurants =  Restaurant::whereIn('id',$restaurantIds)->with('zone')->nearby($userLocation->latitude,$userLocation->longitude)->orderBy('position')->get()->toArray();
                    }else{
                        return response()->json(['message' => "please set location"
                    ],404);
                    }
                }else{
                    return response()->json(['message' => "please set location"
                    ],404);
                }
            }

            $restaurantIndex = [];
            foreach ($restaurants as $restaurant) {
                $restaurantIndex[$restaurant['id']] = $restaurant;
            }

            foreach ($collectionItems as &$collection) {
                foreach ($collection['items'] as &$item) {
                    if (isset($restaurantIndex[$item['item_id']])) {
                        $item['restaurant'] = $restaurantIndex[$item['item_id']];
                    }
                }
            }

            // dd($collectionItems);

            return response()->json([
                'view' => view('user-views.restaurant.collection.partials._restaurants',compact('collectionItems'))->render(),
                'count' =>count($restaurants),
                // 'rest' => $restaurants
            ]);

        }catch(\Exception $e){
            return response()->json([
                'view' => null,
                'count' =>0,
                // 'rest' => $restaurants
            ]);       
            
        }

    }

    // Show user's favorite foods
    public function myFoodCollection()
    {
        $user = Session::get('userInfo');
        $collections = Collection::with(['items' => function ($query) {
            $query->where('type', 'food');
        }])->where('user_id', $user->id)->get()->toArray();

        $cart = [];
        if(CartHelper::cartExist()){
            $cart = CartHelper::getCart();
        }
        return response()->json([
            'view'=>view('user-views.restaurant.collection.partials._foods',compact('cart' , "collections"))->render(),
        ]);
    }
}
