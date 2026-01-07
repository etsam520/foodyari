<?php

namespace App\Http\Controllers\User\Restaurant;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartHelper extends Controller
{

    // Get the current cart from the cookie
    public static function getCart($customerId = null)
    {
        if($customerId == null && auth('customer')->check()){
            $customerId = auth('customer')->user()->id;
            $data = DB::table('carts')->where('customer_id', $customerId)->first()?->cart;

        }elseif($customerId != null){
            $data = DB::table('carts')->where('customer_id', $customerId)->first()?->cart;
        }elseif(isset($_COOKIE['guest_token']) && $_COOKIE['guest_token'] != null){
            $data = DB::table('carts')->where('guest_id', $_COOKIE['guest_token'])->first()?->cart;
        }else{
            return [];
        }
        $cart = json_decode($data??'[]', true);
        return $cart ??[];
    }


    public static function storeCart($cart , $customerId = null)
    {

        $cart = array_values($cart);

        if($customerId == null && auth('customer')->check()){
            $customerId = auth('customer')->user()->id;
        }

        if($customerId != null){
           DB::table('carts')->updateOrInsert(
                ['customer_id' => $customerId], // Search condition
                ['cart' => json_encode($cart)]  // Data to insert or update
           );
        }else{
            DB::table('carts')->updateOrInsert(
                ['guest_id' => $_COOKIE['guest_token']], // Search condition
                ['cart' => json_encode($cart)]  // Data to insert or update
            );
        }

        return true;
    }

    // Add an item to the cart
    public static function addItem($item , $customerId = null)
    {

        $cart = self::getCart($customerId);
        $item['uuid'] = Str::uuid();
        $cart[] = $item;
        self::storeCart($cart, $customerId);
    }


    // Remove an item from the cart
    public static function removeItem($uuid, $customerId = null)
    {
        $cart = self::getCart($customerId);
        $cart = array_filter($cart, function ($item) use ($uuid) {
            return $item['uuid'] !== $uuid;
        });
        self::storeCart($cart, $customerId);

    }



    // Update an item in the cart
    public static function updateItem($data, $customerId = null)
    {
        $cart = self::getCart($customerId);
        foreach ($cart as $key => &$item) {
            if ($item['uuid'] === $data['uuid']) {
                if($data['quantity'] == 0 && empty($data['variations'])){
                    unset($cart[$key]);
                }else{
                    $item = $data; // Update the existing item with new data
                }
                break;
            }
        }
        self::storeCart($cart,$customerId);
    }

    public static function findItem($cartItemId, $customerId = null)
    {
        $cart = self::getCart($customerId);
        foreach ($cart as $item) {
            if ($item['uuid'] === $cartItemId) {
                return $item;
            }
        }
        return false;
    }

    public static function checkItemProduct_id($product_id , $customerId = null)
    {
        $cart = self::getCart($customerId);

        // Iterate through the cart to find the product by ID
        foreach ($cart as $item) {
            if ($item['product_id'] == $product_id) {
                return $item; // Return the item if found
            }
        }

        return null; // Return null if the item is not found
    }
    public static function hasItem($productId, $customerId=null)
    {
        $cart = self::getCart($customerId);
        foreach ($cart as $item) {
            if ($item['product_id'] === $productId) {
                return true;
            }
        }
        return false;
    }

    public static function getItem($productId, $customerId=null)
    {
        $cart = self::getCart($customerId);
        foreach ($cart as $item) {
            if ($item['product_id'] === $productId) {
                return $item;
            }
        }
        return false;
    }

    // Calculate the total price of the cart
    public static function calculateTotalPrice($customerId = null)
    {
        $cart = self::getCart($customerId);
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return $totalPrice;
    }
    public static function itemOfSameRestaurant($item, $customerId = null){
        $itemOfsameRestaurant = true;
        $cartItems = self::getCart($customerId);

        foreach($cartItems as $cartItem){

            if($cartItem['restaurant_id'] != $item['restaurant_id']){
                $itemOfsameRestaurant = false;
            }
        }

        return $itemOfsameRestaurant;
    }

    // Calculate the total quantity of the cart
    public static function calculateTotalQuantity($customerId = null)
    {
        $cart = self::getCart($customerId);
        $totalQuantity = 0;

        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        return $totalQuantity;
    }

    public static function clearCart($customerId=null)
    {

        if($customerId == null && auth('customer')->check()){
            $customerId = auth('customer')->user()->id;
        }

        if($customerId != null){
           DB::table('carts')->updateOrInsert(
                ['customer_id' => $customerId], // Search condition
                ['cart' =>json_encode([]),
                'is_locked' => 0]  // Data to insert or update
           );
           
           // Also clear the scheduled time from order_sessions when cart is cleared
           DB::table('order_sessions')
                ->where('customer_id', $customerId)
                ->update(['order_scheduled_time' => null]);
        }else{
            DB::table('carts')->updateOrInsert(
                ['guest_id' => $_COOKIE['guest_token']], // Search condition
                ['cart' => json_encode([]),
                'is_locked' => 0]  // Data to insert or update
            );
        }


    }

    public static function cartExist($customerId=null)
    {


        if($customerId == null && auth('customer')->check()){
            $customerId = auth('customer')->user()->id;
        }


        if($customerId != null){
           self::guestCartMergeTOCustomer($customerId);

           return DB::table('carts')->where('customer_id', $customerId)->first()?->cart != '[]';
        }else{
            return DB::table('carts')->where('guest_id',  $_COOKIE['guest_token'])->first()?->cart != '[]';
        }

    }

    public static function guestCartMergeTOCustomer($customerId=null){
        if($customerId == null && auth('customer')->check()){
            $customerId = auth('customer')->user()->id;
        }
        if ($customerId !== null && isset($_COOKIE['guest_token'])) {
            $guestToken = $_COOKIE['guest_token'];
            $guestCart = DB::table('carts')
                ->where('guest_id', $guestToken)
                ->value('cart');
            if ($guestCart !== null) {
                DB::table('carts')->updateOrInsert(
                    ['customer_id' => $customerId],
                    ['cart' => $guestCart, 'updated_at' => now()]
                );
                DB::table('carts')->where('guest_id', $guestToken)->delete();
            }
        }

    }


}

