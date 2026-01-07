<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\CartHelper;
use App\CentralLogics\Helpers;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Customer;
use App\Models\Food;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class POSController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::all();
        $customers = Customer::latest()->get();
        // dd($customers);  
        return view('admin-views.pos.index', compact('restaurants','customers'));
    }

    public function getFoods(Request $request)
    {
        if($request->restaurant_id){
           $products = Food::where('restaurant_id', $request->restaurant_id)->get(); 
        }elseif($request->category_id){
           $products = Food::where('category_id', $request->category_id)->get(); 
        }else{
           $products = Food::all(); 
        }
        
        return response()->json($products);
    }

    public function foodDetails(Request $request)
    {
        try {
            $id = $request->get('food_id');
            if(empty($id)){
                throw new \Error('Invalid id');
            }
            $food = Food::find($id);
            return view('admin-views.pos.food-details',compact('food'));
        } catch (\Throwable $th) {
            //throw $th;
            
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
        if(Session::has('cart')){
            return response()->json(['items'=> CartHelper::getCart()]);
        }else{
            return response()->json(['status' => 'error', 'message' => 'Cart Is Empty'], 500);
        }
    }
    public function addToCart(Request $request)
    {
        try{ 
            // $netPrice = $request->json('price');
            $quantity = $request->json('qty');
            $productOptions = $request->json('options');
            $product = Food::find( $request->json('id'));
            if(!$product){
                return response()->json(['status'=> "error",'message'=> 'Product Not Exists']);
            }
            
            $addon_price = 0;
            $variation_price=0;
            $data = [];
            if (!empty($productOptions['addons'])) {
                foreach ( $productOptions['addons'] as $x) {
                    $addon = Addon::find($x['id']);
                    $addon_price += $addon->price;
                    $data['addons'][] = ['name'=> $addon->name,'price'=>$addon->price]; 
                }
            }
            if (!empty($productOptions['variation'])) {
                foreach ( $productOptions['variation'] as $v) {
                    foreach ( $v['values'] as $value){
                        $variation_price += $value['price'];
                    }  
                }
                $data['variations'] = $productOptions['variation'];
            }
        
            $data =  array_merge(
                [
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'variation_price' => $variation_price,
                    'quantity' => $quantity??1,
                    'name' => $product->name,
                    'discount' =>(int)$product->price  - (int)Helpers::food_discount($product->price, $product->discount, $product->discount_type),
                    'tax' => null,
                    'image' => $product->image,
                    'addon_price' => $addon_price,
                ],
                $data
            );

            $cartItemIfExists = CartHelper::checkItemProduct_id($product->id);
            if($cartItemIfExists){
                $data['cart_id'] = $cartItemIfExists['cart_id'];
                CartHelper::updateItem($data);
                $message = "Cart Item Updated";

            }else{
                CartHelper::addItem($data);
                $message = "Cart Item Added";
            }
            
            return response()->json(['status' => "success",'message'=> $message]);
        
        }catch(\Error $ex){
            return response()->json(['error' => $ex->getMessage()]);
        }
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

    public function customer_store(Request $request)
    {
        $request->validate([
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
        ]);
        try {
        User::create([
            'f_name' => $request['f_name'],
            'l_name' => $request['l_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => bcrypt('password')
        ]);
        
           return redirect()->back()->with('success',__('customer_added_successfully'));
        } catch (\Exception $ex) {
           return redirect()->back()->with('error',$ex);
        }
    }

    public function place_order(Request $request)
    {
        // dd($request);
        if(!$request->user_id){
            return back()-> with('error',__('messages.no_customer_selected') );
        }
        if(!$request->type){
            return back()-> with('error','No payment method selected');
        }
        if (Session::has('cart')) {
            if (count(Session::get('cart')) <= 0) {
                return back()-> with('error',__('messages.cart_empty_warning') );
            }
        } else {
            return back()-> with('error',__('messages.cart_empty_warning') );
        }
        if (Session::has('address')) {
            $address = Session::get('address');
        }
       
        try {
       DB::beginTransaction();
        
    

        $cart = Session::get('cart');
        $total_addon_price = 0;
        $product_price = 0;
        $restaurant_discount_amount = 0;

        $order_details = [];
        $order = new Order();
        $order->id = 100000 + Order::all()->count() + 1;
        if (Order::find($order->id)) {
            $order->id = Order::latest()->first()->id + 1;
        }
        $order->distance = isset($address) ? $address['distance'] : 0;
        $order->payment_status = $request->type == 'wallet'?'paid':'unpaid';
        $order->order_status = $request->type == 'wallet'?'confirmed':'pending';
        $order->order_type = 'delivery';
        // $order->restaurant_id = $restaurant->id;
        $order->user_id = $request->user_id;
        $order->delivery_charge = isset($address)?$address['delivery_fee']:0;
        $order->original_delivery_charge = isset($address)?$address['delivery_fee']:0;
        $order->delivery_address = isset($address)?json_encode($address):null;
        $order->checked = 1;
        $order->schedule_at = now();
        $order->created_at = now();
        $order->updated_at = now();
        $order->otp = rand(1000, 9999);
        $restaurantaSet = false;
        foreach ($cart as $c) {
            if (is_array($c)) {
                $product = Food::with('restaurant')->find($c['id']);
                // dd($product->variations);
                if ($product) {
                    if(!$restaurantaSet){
                        $order->restaurant_id =$product->restaurant_id; 
                        $restaurantaSet = true;
                    }
                    $price = $c['price'];
                    $product->tax = $product->restaurant->tax;
                    // $product = Helpers::product_data_formatting($product);
                    $addon_data = 423;

                    $variation_data = Null;
                    $variations = null;
                    $or_d = [
                        'food_id' => $c['id'],
                        'item_campaign_id' => null,
                        'food_details' => json_encode($product),
                        'quantity' => $c['quantity'],
                        'price' => $price,
                        'tax_amount' => 125,
                        'discount_on_food' =>$c['discount'],
                        'discount_type' => 'discount_on_product',
                        // 'variant' => json_encode($c['variant']),
                        'variation' =>true,
                        // 'variation' => json_encode(count($c['variations']) ? $c['variations'] : []),
                        // 'add_ons' => json_encode($addon_data['addons']),
                        'add_ons' => json_encode($addon_data),
                        'total_add_on_price' => 14,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $total_addon_price += $or_d['total_add_on_price'];
                    $product_price += $price * $or_d['quantity'];
                    $restaurant_discount_amount += $or_d['discount_on_food'] * $or_d['quantity'];
                    $order_details[] = $or_d;
                }
            }
        }


        $order->discount_on_product_by = 'vendor';
        $restaurant_discount = 100;
        if(isset($restaurant_discount)){
            $order->discount_on_product_by = 'admin';
        }

        if (isset($cart['discount'])) {
            $restaurant_discount_amount += $cart['discount_type'] == 'percent' && $cart['discount'] > 0 ? ((($product_price + $total_addon_price - $restaurant_discount_amount) * $cart['discount']) / 100) : $cart['discount'];
        }
        // dd($order);
        $restaurant = Restaurant::find($order->restaurant_id);
        $total_price = $product_price + $total_addon_price - $restaurant_discount_amount;
        $tax = isset($cart['tax']) ? $cart['tax'] : $restaurant->tax;
        // $total_tax_amount = ($tax > 0) ? (($total_price * $tax) / 100) : 0;


        $order->tax_status = 'excluded';

        $tax_included = 12;
        if ($tax_included ==  1){
            $order->tax_status = 'included';
        }

        $total_tax_amount=150;
        $tax_a=$order->tax_status =='included'?0:$total_tax_amount;

        
            
            $order->restaurant_discount_amount = $restaurant_discount_amount;
            $order->total_tax_amount = $total_tax_amount;


            $order->order_amount = $total_price + $tax_a + $order->delivery_charge;


            $order->payment_method = $request->type == 'wallet'?'wallet':'cash_on_delivery';
            $order->adjusment = $order->order_amount;

            $max_cod_order_amount = 1500;
            $max_cod_order_amount_value= 1500;
            // if( $max_cod_order_amount_value > 0 && $order->payment_method == 'cash_on_delivery' && $order->order_amount < $max_cod_order_amount_value){
            // return Redirect()->back()->with('error',__('messages.You can not Order more then ').$max_cod_order_amount_value .Rs.' '. __('messages.on COD order.')  );
            // }

            // if($request->type == 'wallet'){
            //     if($request->user_id){

            //         $customer = User::find($request->user_id);
            //         if($customer->wallet_balance < $order->order_amount){
            //             Toastr::error(translate('messages.insufficient_wallet_balance'));
            //             return back();
            //         }else{
            //             CustomerLogic::create_wallet_transaction($order->user_id, $order->order_amount, 'order_place', $order->id);
            //         }
            //     }else{
            //         Toastr::error(translate('messages.no_customer_selected'));
            //         return back();
            //     }
            // };




            $order->save();
            
            foreach ($order_details as $key => $item) {
                $order_details[$key]['order_id'] = $order->id;
            }
            OrderDetail::insert($order_details);
            DB::commit();
            session()->forget('cart');
            session()->forget('address');
            session(['last_order' => $order->id]);

            // if ($restaurant->restaurant_model == 'subscription' && isset($rest_sub)) {
            //     if ($rest_sub->max_order != "unlimited" && $rest_sub->max_order > 0 ) {
            //         $rest_sub->decrement('max_order' , 1);
            //             // if ( $rest_sub->max_order <= 0 ){
            //             //     $restaurant->update(['status' => 0]);
            //             // }
            //         }
            // }
            return redirect()->back()->with('success',__('messages.order_placed_successfully'));
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back()->with('warning', $e->getMessage());
        }
        return redirect()->back()->with('warning',__('messages.failed_to_place_order'));

    }
}
