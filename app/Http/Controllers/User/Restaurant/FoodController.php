<?php

namespace App\Http\Controllers\User\Restaurant;


use App\Http\Controllers\User\Restaurant\CartHelper;
use App\CentralLogics\Helpers;
use App\CentralLogics\ReportLogic;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\CollectionItem;
use App\Models\Food;
use App\Models\OrderDetail;
use App\Models\OrderTransaction;
use App\Models\Restaurant;
use App\Models\RestaurantSchedule;
use Carbon\Carbon;
use Google\Rpc\Help;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Str;

class FoodController extends Controller
{
    public function getFoods(Request $request)
    {

        // if(auth('customer')->check()){
        //     $user = auth('customer')->user()->customerAddress()->orderByDesc('is_default')->orderByDesc('id')->first();
        //     $user_location = ['lat'=>$user->latitude,'lng'=>$user->longitude];
        // }elseif(Helpers::guestCheck()){
        //     $user_location = Helpers::getGuestSession('guest_location');
        // }

        $category = $request->cookie('category_name');
        $foodId = $request->query('food_id');
        $filter = $request->query('filter');
        // if(empty($restaurant)){
        $restaurantName = $request->query('restaurant_name');
        $restaurant = Restaurant::with(['schedules', 'zone'])->where(DB::raw("
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
        "), 'LIKE', "%{$restaurantName}%")->first();
        // }

        $submenu = null;

        $foods = Food::with(['menu', 'submenu'])->isActive(true)
            ->when(isset($filter), function ($query) use ($filter) {
                return $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($filter) . '%']);
            })->when(Cache::has('submenu'), function ($query) {
                $submenu = Cache::get('submenu');
                $submenu = json_decode($submenu, true);
                $submenuID = $submenu['id'];
                Cache::forget('submenu');
                return $query->where('restaurant_submenu_id', $submenuID);
            })
            ->when(!empty($foodId), function ($query) use ($foodId) {
                return $query->where('id', $foodId);
            })
            // ->when($category === 'all', function ($query) {
            //     return $query;
            // })->when(isset($category) && $category !== 'all', function ($query) use ($category) {
            //     return $query->whereHas('category', function ($q) use ($category) {
            //         $q->where('name', $category);
            //     });
            // })
            ->where('restaurant_id', $restaurant->id)->distinct()->get();

        $cart = null;
        if (CartHelper::cartExist()) {
            $cart = CartHelper::getCart();
        }
        $foods = $foods->groupBy('restaurant_menu_id');
        $menu = [];
        foreach ($foods as $key => $foodList) {
            $menuId = $key; // Assign menu_id
            $submenu = [];
            foreach ($foodList as $food) {
                if (isset($food->submenu->id) && !in_array($food->submenu->id, $submenu)) {
                    $submenu[] = $food->submenu->id;
                }
            }
            $menu[]  = ['menu_id' => $menuId, 'submenu_ids' => $submenu];
        }
        $user = Session::get('userInfo');
        if ($user) {
            $collectionItems = CollectionItem::whereHas('collection', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->get()->toArray();
        } else {
            $collectionItems = collect(); // Return an empty collection if the user is not found in the session
        }
        // dd($collectionItems);



        return response()->json([
            'view' => view('user-views.restaurant.food.partials._items', compact('foods', 'cart', 'collectionItems'))->render(),
            'menu' => $menu
        ]);
    }

    // public function getRecommendedFoods()

    public function getFood(Request $request)
    {

        $food = Food::with('restaurant')->findOrFail($request->query('food_id'));
        $cart = null;
        if (CartHelper::cartExist()) {
            $cart = CartHelper::getCart();
        }
        return response()->json([
            'view' => view('user-views.restaurant.food.partials._single-food', compact('food', 'cart'))->render(),
            'data' => $food
        ]);
    }

    public function topFoods()
    {
        $transactionsOrder = OrderTransaction::with('order')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->get();

        $transactionsOrderIds = $transactionsOrder->pluck('order_id')->toArray();

        $productsWithOrderDetails = OrderDetail::with('order')  // Eager load the related 'order' model
            ->whereIn('order_id', $transactionsOrderIds)  // Filter by the order IDs
            ->get()  // Fetch all records
            ->groupBy('food_id');

        $productsWithOrderDetailsResult = ReportLogic::getFoodReport_process_func($productsWithOrderDetails);
        $foodSelected = [];
        $loop = 0;
        foreach ($productsWithOrderDetailsResult['productItems'] as $item) {
            if (isset($item['food_id'])) { // Check if food_id exists in the current item
                // Avoid duplicates by checking if food_id is already in the selected list
                if (!in_array($item['food_id'], array_column($foodSelected, 'food_id'))) {
                    $foodSelected[] = [
                        "food_id" => $item['food_id'],
                        'food' => Food::find($item['food_id']), // Retrieve food details from the database
                        'quantity' => $item['quantity']
                    ];

                    $loop++; // Increment the loop counter
                    if ($loop == 10) { // Stop after selecting 10 items
                        break;
                    }
                }
            }
        }
        $cart = null;
        if (CartHelper::cartExist()) {
            $cart = CartHelper::getCart();
        }

        return response()->json([
            "view" => view('user-views.restaurant.food.partials._top-foods', compact('foodSelected', 'cart'))->render(),
        ]);
    }

    public function addToCart(Request $request)
    {
        try {

            $quantity = $request->json('qty');
            $productOptions = $request->json('options');

            if(Helpers::isCartLock(auth('customer')->id())){
                return response()->json(['status' => "error", 'message' => 'Cart is locked.!! try again later'],403);
            }
            $existingFirstItem = CartHelper::getCart();
            $existingFirstItem = !empty($existingFirstItem) ? $existingFirstItem[0] : [];

            $product = Food::with('restaurant')->find($request->json('id'));

            if (!$product) {
                return response()->json(['status' => "error", 'message' => 'Product Not Exists']);
            }
            $restaurant = $product->restaurant;
            $now =  Carbon::now();
            $restaurantSchedule = RestaurantSchedule::where('day', $now->format('l'))
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

            $productClosed = Helpers::remainingTime($product->available_time_starts, $product->available_time_ends);
            if ($restaurantSchedule->temp_close == true || $restaurantSchedule->is_open_now == 0) {
                throw new \Error("Restaurant Closed Now");
            } elseif ($productClosed['isClosed'] == true) {
                throw new \Error(ucfirst($product->name) . " avaliable time " . $productClosed['format']);
            }
            $addon_price = 0;

            $data = [
                'variations' => null,
            ];
            if (!empty($productOptions['addons'])) {
                foreach ($productOptions['addons'] as $x) {
                    $addon = Addon::find($x['id']);
                    $addon_price += ($addon->price * $x['qty']);
                    $data['addons'][] = ['name' => $addon->name, 'price' => $addon->price, 'qty' => $x['qty']];
                }
            }
            if (!empty($productOptions['variation'])) {
                $data['variations'] = $productOptions['variation'];
            }

            $data =  array_merge(
                [
                    'product_id' => $product->id,
                    'restaurant_id' => $product->restaurant_id,
                    'quantity' => $quantity,
                    'name' => $product->name,
                    'addon_price' => $addon_price,
                ],
                $data
            );
            if ($product->isCustomize == 1) {
                if (empty($productOptions['variation'])) {
                    return self::triggerWarning($request, 'Variations Can\'t be Zero');
                }
                $data['quantity'] = 0;
                $warningMessage =  self::validateVariations($product->variations, $data['variations']);

                if ($warningMessage != null && !empty($warningMessage)) {
                    return self::triggerWarning($request, $warningMessage);
                }
            }

            // dd($data);
            $itemofSameRestaurant = CartHelper::itemOfSameRestaurant($data);
            if (CartHelper::hasItem($product->id) && $itemofSameRestaurant) {
                $data['uuid'] = CartHelper::getItem($product->id)['uuid'];
                CartHelper::updateItem($data);
                $message = "Cart Item Updated";
            } else {

                if ($itemofSameRestaurant) {
                    CartHelper::addItem($data);
                    $message = "Cart Item Added";
                } else {
                    if (Cache::has('allow_to_confirm')) {

                        $cart = [];
                        $data['uuid'] = Str::uuid();
                        $cart[] = $data;
                        CartHelper::storeCart($cart);
                        $message = "Cart Item Added";
                        Cache::forget('allow_to_confirm', true);
                    } else {
                        Cache::put('allow_to_confirm', true);
                        $cartedRestaurant = ucfirst(Restaurant::find($existingFirstItem['restaurant_id'])->name);
                        $newrestaurnt = ucfirst($product->restaurant->name);

                        $message = "Your cart contains dishes from $cartedRestaurant restaurant
                         so we discard those items to add new Items for $newrestaurnt restaurant";
                        if(auth('customer')->check()){
                            event(new \App\Events\User\Restaurant\ClearSavedCoupon(auth('customer')->user()->id,false));
                        }
                        return response()->json(['status' => "success", 'confirm' => $message]);
                    }
                    // throw new \Exception($message);
                }
            }

            return response()->json(['status' => "success", 'message' => $message, 'data' => $data]);
        } catch (\Throwable $ex) {
            // dd($ex);
            return response()->json(['message' => $ex->getMessage()], 403);
        }
    }

    public static function validateVariations($variationsJson, $data)
    {
        // Convert JSON to arrays
        $variations = json_decode($variationsJson, true);
        $message = null;


        // Validate if the conversion was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = "Invalid JSON format.";
            return;
        }

        foreach ($variations as $variation) {
            $name = $variation['name'];
            $min = $variation['min'];
            $max = $variation['max'];
            $required = $variation['required'];
            $values = $variation['values'];

            $found = array_filter($data, function ($variation) use ($name) {
                return isset($variation['option']) && $variation['option'] === $name;
            })[0] ?? [];


            // Check if the required variation exists in data
            if ($required === 'on' && empty($found)) {
                $message = "Variation '$name' is required but not provided.";
                break;
            }

            // Check if the provided data follows the min and max constraints
            /*if (!empty($data[$name])) {
                $selectedValues = $data[$name]['values'];
                $count = count($selectedValues);

                if ($count < $min || $count > $max) {
                     $message = "Variation '$name' must have between $min and $max selections.";
                }

                // Validate each selected value
                foreach ($selectedValues as $selectedValue) {
                    $label = $selectedValue['label'];
                    $validValue = array_filter($values, function ($value) use ($label) {
                        return $value['label'] === $label;
                    });

                    if (empty($validValue)) {
                         $message = "Invalid option selected for '$name': '$label'.";
                    }
                }
            }*/
        }
        return $message;
    }

    public static function triggerWarning(Request $request, $message)
    {
        return response()->json(['message' => $message], 201);
    }

    public function getCartItems()
    {
        // $restaurant = Restaurant::with('zone')->find(Session::get('restaurant')->id);

        $cartedItemList = [];
        // dd($cartedItem);
        if (CartHelper::cartExist()) {
            $cartedItem = CartHelper::getCart();

            foreach ($cartedItem as  $itemDetails) {
                $product = Food::with('restaurant')->find($itemDetails['product_id']);
                $variationDetails = $itemDetails['variations'] ? Helpers::get_varient($product, $itemDetails['variations']) : [];
                // $packingCharge = $product->packing_charge ?? 0 ;


                if (empty($variationDetails)) {
                    // dd($itemDetails);
                    $cartedItemList[] = [
                        'name' => $itemDetails['name'],
                        'quantity' => $itemDetails['quantity'],
                        'packing_charge' => $product->packing_charge ?? 0,
                        'amount' => $itemDetails['quantity'] * (float) $product->price,
                        'cart_item_id' => $itemDetails['uuid'],
                        'item_type' => 'solo',
                        'index' => 0,
                        'position' => null,
                    ];
                } else {
                    foreach ($variationDetails as $index => $variation) {
                        // dd($variationDetails);
                        foreach ($variation['values'] as $key => $value) {
                            $cartedItemList[] = [
                                'name' => $itemDetails['name'] . " ({$value['label']})",
                                'quantity' => $value['qty'],
                                'packing_charge' => $value['packing_charge'],
                                'amount' => ($value['price'] + $value['admin_margin']) * $value['qty'],
                                'cart_item_id' => $itemDetails['uuid'],
                                'item_type' => 'variant',
                                'index' => $index,
                                'position' => $key,
                            ];
                        }
                    }
                }
                if (isset($itemDetails['addons'])) {
                    foreach (($itemDetails['addons']) as $key => $addon) {
                        $cartedItemList[] = [
                            'name' => $addon['name'],
                            'quantity' => $addon['qty'],
                            'packing_charge' => 0,
                            'amount' => $addon['price'] * $addon['qty'],
                            'cart_item_id' => $itemDetails['uuid'],
                            'item_type' => 'addon',
                            'index' => 0,
                            'position' => $key,
                        ];
                    }
                }
            }


            $data['items'] = $cartedItemList;

            return response()->json([
                'view' => view('user-views.restaurant.checkout._carted-items', compact('data'))->render()
            ]);
        } else {
            return response()->json(['view' => ''], 200);
        }
    }

    public function update_cart(Request $request)
    {
        $cartItemId = $request->json('cart_item_id');
        $item_type = $request->json('item_type');
        $item_index = $request->json('item_index');
        $item_position = $request->json('item_position');
        $item_quantity = $request->json('item_quantity');
        // dd(($request->json()));

        if (Helpers::isCartLock(auth('customer')->id())) {
            return response()->json(['status' => "error", 'message' => 'Cart is locked.!! try again later'], 403);
        }

        $getItem = CartHelper::findItem($cartItemId);
        if ($item_type == 'variant') {
            $getItem['variations'][$item_index]['values'][$item_position]['qty'] = $item_quantity;
            if ($item_quantity < 1) {
                unset($getItem['variations'][$item_index]['values'][$item_position]);
            }
        } elseif ($item_type == 'solo') {
            $getItem['quantity'] = $item_quantity;
        } elseif ($item_type == 'addon') {
            $getItem['addons'][$item_index]['qty'] = $item_quantity;
            if ($item_quantity < 1) {
                unset($getItem['addons'][$item_index]);
            }
        }
        // dd($getItem);

        $addon_price = 0;
        $variation_price = 0;

        if (isset($getItem['addons'])) {
            foreach ($getItem['addons'] as $x) {
                $addon_price += ($x['price'] * $x['qty']);
            }
        }
        if (isset($getItem['variations'])) {
            foreach ($getItem['variations'] as $variation) {
                foreach ($variation['values'] as $value) {
                    $variation_price += ($value['price'] * $value['qty']);
                }
            }
        }

        if ($variation_price < 1) {
            $getItem['variations'] = [];
            $getItem['variation_price'] = 0;
        } else {
            $getItem['variation_price'] = $variation_price;
        }
        if ($addon_price < 1) {
            $getItem['addons'] = [];
            $getItem['addon_price'] = 0;
        } else {
            $getItem['addon_price'] = $addon_price;
        }


        if ($item_type == 'variant' || $item_type == 'solo' || $item_type == 'addon') {
            CartHelper::updateItem($getItem);
            return response()->json([]);
        } else {
            return response()->json(['message' => 'Cart Coudn\'t be updated. Try Againg!!'], 500);
        }
    }

    public function getTempCartItems()
    {
        if (CartHelper::cartExist()) {
            $cart = CartHelper::getCart();

            return response()->json([
                'cart' => $cart,
                'view' => view('user-views.restaurant.food.partials._view-cart-items', compact('cart'))->render()
            ]);
        } else {
            return response()->json(['items' => ''], 200);
        }
    }

    public function removeCartItem(Request $request)
    {
        try {
            $cart_id = $request->cart_id;
            CartHelper::removeItem($cart_id);
            return response()->json(['status' => "success", 'message' => 'Item removed from cart']);
        } catch (\Exception $e) {
            return response()->json(['status' => "error", 'message' => $e->getMessage()]);
        }
    }
}
