<?php

namespace App\Http\Controllers\Vendor;

use App\CentralLogics\CartHelper;
use Carbon\Carbon;
use App\Models\Food;
use App\Models\User;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Category;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\PosBillingHelper\BillingController;
use App\Models\Addon;
use App\Models\Customer;
use App\Models\Restaurant;
use App\Services\DeliveryChargeService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class POSController extends Controller
{

    public function index()
    {


        $customers = Customer::latest()->get();
        // dd($customers);
        return view('vendor-views.pos.index', compact('customers'));
    }

    public function getFoods(Request $request)
    {
        // Start building the query
        $products = Food::where('restaurant_id', Session::get('restaurant')->id);

        $cart = CartHelper::getCart();

        // Apply filters (without calling paginate again)
        if ($request->menu_id == "all" && empty($request->query('food_name'))) {
            // No need to paginate here again, it's handled later
        } elseif (!empty($request->food_name)) {
            $products = $products->where('name', 'like', '%' . $request->food_name . '%');
        } elseif (!empty($request->submenu_id)) {
            $products = $products->where('restaurant_submenu_id', $request->submenu_id);
        } elseif ($request->menu_id) {
            $products = $products->where('restaurant_menu_id', $request->menu_id);
        }

        // Finally, paginate the query after applying filters
        $products = $products->paginate(10);

        // Return the paginated products and render the view for AJAX
        return response()->json([
            'view' => view('vendor-views.pos._food-items', compact('products', 'cart'))->render()
        ]);
    }





    public function foodDetails(Request $request)
    {
        try {
            $id = $request->get('food_id');
            if(empty($id)){
                throw new \Error('Invalid id');
            }
            $food = Food::find($id);
            $cart = CartHelper::getCart();
            return response()->json(['view'=>view('vendor-views.pos.single-food',compact('food','cart'))->render()]);

        } catch (\Throwable $th) {
            dd($th);
          return response()->json(['message' => "Food Not Found"],404);
        }

    }


    public function quick_view(Request $request)
    {
        $product = Food::with('restaurant')->findOrFail($request->product_id);
        // dd($product);
        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data', compact('product'))->render(),
        ]);
    }

    public function getCartItems()
    {
        $restaurant = Restaurant::with('zone')->find(Session::get('restaurant')->id);
        if(Session::has('address')){
            $zone = $restaurant->zone;
            $delivery_address = Session::get('address');
            
            try {
                // Calculate order total first
                $orderTotal = 0;
                if(CartHelper::cartExist()){
                    $cartedItem = CartHelper::getCart();
                    foreach ($cartedItem as $item) {
                        $orderTotal += $item->price * $item->quantity;
                    }
                }
                
                // Use new delivery charge service
                $deliveryResult = DeliveryChargeService::calculateForZone(
                    $zone->id,
                    (double) $delivery_address['distance'],
                    $orderTotal,
                    [
                        'rain' => 1.0,
                        'traffic' => 1.0,
                        'night' => 1.0
                    ]
                );
                $data['deliveryCharge'] = number_format($deliveryResult['charge'], 2, '.', '');
            } catch (\Exception $e) {
                // Fallback to 0 if service fails
                $data['deliveryCharge'] = '0.00';
            }

        }else{
            $data['deliveryCharge'] = 0;
        }
        $cartedItemList = [];
        // dd($cartedItem);
        if(CartHelper::cartExist()){
            $cartedItem = CartHelper::getCart();
            $discount = 0;
            foreach ($cartedItem as $itemDetails){
                $variationDetails =$itemDetails['variations']??[];
                $product = Food::with('restaurant')->find($itemDetails['product_id']);
                $productAmount = 0;
                $productDiscount = 0;

                if(empty($variationDetails)){
                    $cartedItemList[] =[
                        'name' => $itemDetails['name'],
                        'quantity' => $itemDetails['quantity'],
                        'amount' => $itemDetails['quantity'] * $itemDetails['price'] ,
                    ];
                    $productAmount += $itemDetails['quantity'] * $itemDetails['price'];
                }else{
                    foreach($variationDetails as $variation){

                        foreach ($variation['values'] as $value){
                            $cartedItemList[] = [
                            'name' =>$itemDetails['name']." ({$value['label']})",
                            'quantity' =>$value['qty'],
                            'amount' => $value['price'] * $value['qty'] ,
                        ];
                        $productAmount += $value['price'] * $value['qty'];
                        }
                    }
                }
                if(isset($itemDetails['addons'])){
                    foreach (($itemDetails['addons']) as $addon){
                        $cartedItemList[] = [
                            'name' =>$addon['name'],
                            'quantity' => $addon['qty'],
                            'amount' => $addon['price'] * $addon['qty'] ,
                        ];
                    }
                }


                $productDiscount = (int) $productAmount - (int)Helpers::food_discount($productAmount, $product->discount, $product->discount_type);
                // dd($discount);
                $discount += $productDiscount;
            }

            $data['items'] = $cartedItemList;
            $data['discount'] = $discount;
            $data['tax'] = $restaurant->tax;
            $data['update_tax'] = Session::has('update_tax')?Session::get('update_tax') : 0;

            $data['custom_discount'] = Session::has('custom_discount')?Session::get('custom_discount') : 0;

            return response()->json($data);
        }else{
            return response()->json(['items' => ''], 200);
        }
    }

    public function clearCart()
    {
        if(CartHelper::cartExist()){
            Cookie::queue(Cookie::forget('res_cart'));
            Session::remove('update_tax');
            Session::remove('custom_discount');
            Session::remove('address');
            return response()->json(['message'=> "Cart Cleared"]);
        }else{
            return response()->json(['message' => 'Cart Already Cleared'], 403);
        }
    }

    public function addToCart(Request $request)
    {
        try{
            $quantity = $request->json('qty');
            $productOptions = $request->json('options');
            $product = Food::find( $request->json('id'));
            // dd($productOptions);
            if(!$product){
                return response()->json(['status'=> "error",'message'=> 'Product Not Exists']);
            }

            $addon_price = 0;
            $variation_price=0;
            $data = [];
            if (!empty($productOptions['addons'])) {
                foreach ( $productOptions['addons'] as $x) {
                    $addon = Addon::find($x['id']);
                    $addon_price += ($addon->price * $x['qty']);
                    $data['addons'][] = ['name'=> $addon->name,'price'=>$addon->price , 'qty' => $x['qty']];
                }
            }
            if (!empty($productOptions['variation'])) {
                foreach ( $productOptions['variation'] as $v) {
                    foreach ( $v['values'] as $value){
                        $variation_price += ($value['price'] * $value['qty']) ;
                    }
                }
                $data['variations'] = $productOptions['variation'];
            }

            $data =  array_merge(
                [
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'variation_price' => $variation_price,
                    'quantity' => $quantity??0,
                    'name' => $product->name,
                    'discount' =>(int)$product->price  - (int)Helpers::food_discount($product->price, $product->discount, $product->discount_type),
                    'tax' => null,
                    'image' => $product->image,
                    'addon_price' => $addon_price,
                ],
                $data
            );
            if($product->isCustomize == 1){
                if(empty($productOptions['variation'])){
                 return self::triggerWarning($request,'Variations Can\'t be Zero');
                }
                $data['quantity'] = 0;
               $warningMessage =  self::validateVariations($product->variations, $data['variations']);

               if($warningMessage != null && !empty($warningMessage)){
                    return self::triggerWarning($request,$warningMessage);
               }
            }

            if(CartHelper::hasItem($product->id)){
                $data['uuid'] = CartHelper::getItem($product->id)['uuid'];
                CartHelper::updateItem($data);
                $message = "Cart Item Updated";

            }else{
                CartHelper::addItem($data);
                $message = "Cart Item Added";
            }
            // dd($data);
            // dd(CartHelper::getCart());

            return response()->json(['status' => "success",'message'=> $message]);

        }catch(\Error $ex){
            return response()->json(['error' => $ex->getMessage()],403);
        }
    }

    public static function triggerWarning(Request $request,$message)
    {
        return response()->json(['message' => $message],201);
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

            // Check if the required variation exists in data
            if ($required === 'on' && empty($data[$name])) {
                 $message = "Variation '$name' is required but not provided.";
                break;
            }

            // Check if the provided data follows the min and max constraints
            if (!empty($data[$name])) {
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
            }
        }
        return $message;
    }

    public function deleteSingleItem(Request $request)
    {
        try {
            $cart_id = $request->cart_id;
            CartHelper::removeItem($cart_id);
            return response()->json(['status' => "success", 'message' => 'Item removed from cart']);
        } catch (\Exception $e) {
            return response()->json(['status' => "error", 'message' => $e->getMessage()]);
        }
    }



    public function place_order(Request $request)
    {

       try{
        // dd($request->all());
            define('FLAG_PRINT_KOT', true);
            define('FLAG_NO_PRINT_KOT', true);
            if($request->customer_id == null){
                throw new \Error('customer Can\'t be null');
            }

            $restaurant = Restaurant::with('zone')->find(Session::get('restaurant')->id);
            if(!$request->type){
                throw new \Error(__('No payment method selected'));
            }

            if(count(CartHelper::getCart('cart')) < 1){
                throw new \Error(__('messages.cart_empty_warning'));
            }
            $deliveryCharge = 0;
            if (Session::has('address')) {
                if(!$request->customer_id){
                    throw new \Error(__('messages.no_customer_selected'));
                }
                $address = Session::get('address');
                $zone = $restaurant->zone;
                
                // Calculate delivery charge using new service
                try {
                    // Calculate order total first
                    $orderTotal = 0;
                    $cartedItem = CartHelper::getCart();
                    foreach ($cartedItem as $item) {
                        $orderTotal += $item->price * $item->quantity;
                    }
                    
                    $deliveryResult = DeliveryChargeService::calculateForZone(
                        $zone->id,
                        (double) $address['distance'],
                        $orderTotal,
                        [
                            'rain' => 1.0,
                            'traffic' => 1.0,
                            'night' => 1.0
                        ]
                    );
                    $deliveryCharge = $deliveryResult['charge'];
                } catch (\Exception $e) {
                    $deliveryCharge = 0;
                }

            }
            /*====// variables //==============*/
            if($request->customer_id == "walk-in"){
                session()->forget('address');
                $billing = new BillingController();
            }else{
                $billing = new BillingController($request->customer_id);
            }
            $billing->process();
            $cash_to_collect = $billing->total;
            $payment_method = 'cash';


            DB::beginTransaction();

            $order_details = [];
            $order = new Order();
            $order->id = 100000 + Order::all()->count() + 1;
            if (Order::find($order->id)) {
                $order->id = Order::latest()->first()->id + 1;
            }
            $order->payment_status = isset($address)?'unpaid':'paid';

            if($request->customer_id){

                $order->order_status = isset($address)?'confirmed':'delivered';
                $order->order_type = isset($address)?'delivery':'take_away';
            }else{

                $order->order_status = 'delivered';
                $order->order_type = 'take_away';
            }

            $order->delivered = $order->order_status ==  'delivered' ?  now() : null ;
            $order->distance = $billing->distance;
            $order->restaurant_id = $restaurant->id;
            if($request->customer_id !== "walk-in"){
                $order->customer_id =   $request->customer_id;
            }

            $order->delivery_charge = $billing->deliveryCharge;
            $order->original_delivery_charge = $billing->deliveryCharge;
            $order->delivery_address = isset($address)?json_encode($address):null;
            $order->checked = 1;
            $order->created_at = now();
            $order->schedule_at = now();
            $order->updated_at = now();
            $order->otp = rand(1000, 9999);
            if($order->delivered == null){
                $order->pending = now();
            }

            $order->discount_on_product_by = 'vendor';
            $order->tax_status = 'excluded';




            $order->discount_on_product_by = 'vendor';



            $order->tax_status = 'excluded';


            $tax_included = BusinessSetting::where(['key'=>'tax_included'])->first() ?  BusinessSetting::where(['key'=>'tax_included'])->first()->value : 0;
            if ($tax_included ==  1){
                $order->tax_status = 'included';
            }

                $tax_a=$order->tax_status =='included'?0:$billing->tax;

                $order->restaurant_discount_amount= $billing->tax ;
                $order->total_tax_amount= $billing->tax;
                if ($billing->total < 0) {
                    throw new \Exception('Something went wrong. Please refresh the page.');
                }


                $order->order_amount = $billing->total;
                $order->payment_method = $payment_method;
                $order_details = $billing->order_details;
                $order->cash_to_collect = $cash_to_collect;

                $order->cooking_instruction = '';
                $order->delivery_instruction = '';

                if($order->order_type != 'take_away'){
                    $order->payment_status = 'paid';
                }


                if($order->save()){

                    foreach ($order_details as $key => $item) {
                        $order_details[$key]['order_id'] = $order->id;
                    }
                    OrderDetail::insert($order_details);
                    // if($order->order_type = 'delivery'){
                    //     Helpers::posNotify('order-made',$restaurant->id,$request->customer_id !="walk-in"?$request->customer_id:null);
                    // }

                    Cookie::queue(Cookie::forget('res_cart'));
                    session()->forget('address');
                    Session::remove('update_tax');
                    Session::remove('custom_discount');
                    session(['last_order' => $order->id]);

                    if($order->order_type != 'take_away'){
                        $ol = OrderLogic::order_transaction($order->id, null);

                        if(!$ol)
                        {
                            throw new \Exception(__('messages.faield_to_create_order_transaction'));
                        }
                    }
                    DB::commit();
                    return response()->json([
                        'success'=>__('messages.order_placed_successfully'),
                        'last_order' => view('vendor-views.pos._show_bill_kot',compact('order'))->render(),
                        'print_kot' => $request->kot == 1 ? FLAG_PRINT_KOT : FLAG_NO_PRINT_KOT,
                        'print_kot_view' => view('vendor-views.pos.order._kot', compact('order'))->render(),
                    ]);

                }else{

                    throw new \Error(__('messages.failed_to_place_order'));
                }
        }catch (\Error $e) {
            DB::rollBack();
            // dd($e);
            return response()->json(['error'=> $e->getMessage()],405

        );
        }

    }

    public function addDeliveryInfo(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',

            'longitude' => 'required',
            'latitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_list($validator)]);
        }


        $restaurant = Session::get('restaurant');
        $restaurantPoint =['lat' => $restaurant->latitude, 'lon' => $restaurant->longitude];
        $customerPoint = ['lat' => (string)$request->latitude, 'lon' => (string)$request->longitude];

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => 'delivery',
            'stringAddress' =>$request->address ,
            'landmark' => $request->landmark,
            'distance'=> Helpers::haversineDistance($customerPoint, $restaurantPoint ),
            'position' => ['lat' =>(string)$request->latitude , 'lon' =>(string)$request->longitude],
        ];

        $request->session()->put('address', $address);

        return response()->json([
            'success'=> "Address Saved",
            'data' => $address,
            'view' => view('vendor-views.pos._address', compact('address'))->render(),
        ]);
    }

    public function customerDeliveryInfo(Request $request)
    {
        try {
          $customer_id =   $request->get('customer_id');
          if(empty($customer_id)){
            throw new \Error('Customer Id not Found');
          }
          $customer = Customer::with(['customerAddress' => function($q) {
                $q->latest()->limit(1);
            }])->find($customer_id);
        Session::remove('address');
        return response()->json([
            'success'=> "Adress Fetched",
            'view' => view('vendor-views.pos._customer-address', compact('customer'))->render(),
        ]);

        } catch (\Exception $th) {
            return response()->json([
            'error'=> $th->getMessage(),
        ]);
        }

    }


    public function update_tax(Request $request)
    {
        Session::put('update_tax', $request->tax);
        return response()->json([],200);
    }

    public function update_discount(Request $request)
    {
        $customDiscount = [];
        $customDiscount['discount'] = $request->discount;
        $customDiscount['discount_type'] = $request->type;
        Session::put('custom_discount', $customDiscount);
        return response()->json([],200);
    }

    public function get_customers(Request $request){
        $key = explode(' ', $request['q']);
        $data = User::
        where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('f_name', 'like', "%{$value}%")
                ->orWhere('l_name', 'like', "%{$value}%")
                ->orWhere('phone', 'like', "%{$value}%");
            }
        })
        ->limit(8)
        ->get([DB::raw('id, CONCAT(f_name, " ", l_name, " (", phone ,")") as text')]);

        $data[]=(object)['id'=>false, 'text'=>__('messages.walk_in_customer')];

        $reversed = $data->toArray();

        $data = array_reverse($reversed);


        return response()->json($data);
    }



    public function order_list()
    {
        $orders = Order::with(['customer'])
        ->where('order_type', 'pos')
        ->where('restaurant_id',\App\CentralLogics\Helpers::get_restaurant_id())
        ->latest()
        ->paginate(config('default_pagination'));

        return view('vendor-views.pos.order.list', compact('orders'));
    }

    public function order_details($id)
    {
        $order = Order::with('details')->where(['id' => $id, 'restaurant_id' => Helpers::get_restaurant_id()])->first();
        if (isset($order)) {
            return view('vendor-views.pos.order.order-view', compact('order'));
        } else {
            // Toastr::info('No more orders!');
            return back();
        }
    }

    public function generate_invoice($id)
    {
        $order = Order::where('id', $id)->first();

        return response()->json([
            'success' => 1,
            'view' => view('vendor-views.pos.order.invoice', compact('order'))->render(),
        ]);
    }

    public function customer_store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
        ]);
        User::create([
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => bcrypt('password')
        ]);
        try {
            // if (config('mail.status')) {
            //     Mail::to($request->email)->send(new \App\Mail\CustomerRegistration($request->f_name . ' ' . $request->l_name,true));
            // }
        } catch (\Exception $ex) {
            info($ex);
        }
        // Toastr::success(__('customer_added_successfully'));
        return back();
    }
    public function extra_charge(Request $request)
    {
        $distance_data = $request->distancMileResult ?? 1;

        $data =  Vehicle::active()->where(function ($query) use ($distance_data) {
            $query->where('starting_coverage_area', '<=', $distance_data)->where('maximum_coverage_area', '>=', $distance_data);
        })
            ->orWhere(function ($query) use ($distance_data) {
                $query->where('starting_coverage_area', '>=', $distance_data);
            })
            ->orderBy('starting_coverage_area')->first();

            $extra_charges = (float) (isset($data) ? $data->extra_charges  : 0);
            return response()->json($extra_charges,200);
    }

}
