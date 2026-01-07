<?php
namespace App\CentralLogics;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartHelper
{

    // Get the current cart from the cookie
    public static function getCart()
    {
        $cart = Cookie::get('res_cart');
        return $cart ? json_decode($cart, true) : [];
    }


    public static function storeCart($cart)
    {
        $minutes = 60*24*365;
        $cart = array_values($cart);
        Cookie::queue('res_cart', json_encode($cart), $minutes);
    }

    // Add an item to the cart
    public static function addItem($item)
    {

        $cart = self::getCart();
        $item['uuid'] = Str::uuid();
        $cart[] = $item;
        self::storeCart($cart);
    }


    // Remove an item from the cart
    public static function removeItem($uuid)
    {
        $cart = self::getCart();
        $cart = array_filter($cart, function ($item) use ($uuid) {
            return $item['uuid'] !== $uuid;
        });
        self::storeCart($cart);

    }



    // Update an item in the cart
    public static function updateItem($data)
    {
        $cart = self::getCart();
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
        self::storeCart($cart);
    }

    public static function checkItemProduct_id($product_id)
    {
        $cart = self::getCart();

        // Iterate through the cart to find the product by ID
        foreach ($cart as $item) {
            if ($item['product_id'] == $product_id) {
                return $item; // Return the item if found
            }
        }

        return null; // Return null if the item is not found
    }
    public static function hasItem($productId)
    {
        $cart = self::getCart();
        foreach ($cart as $item) {
            if ($item['product_id'] === $productId) {
                return true;
            }
        }
        return false;
    }

    public static function getItem($productId)
    {
        $cart = self::getCart();
        foreach ($cart as $item) {
            if ($item['product_id'] === $productId) {
                return $item;
            }
        }
        return false;
    }

    // Calculate the total price of the cart
    public static function calculateTotalPrice()
    {
        $cart = self::getCart();
        $totalPrice = 0;

        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        return $totalPrice;
    }
    public static function itemOfSameRestaurant($item){
        $itemOfsameRestaurant = true;
        $cartItems = self::getCart();

        foreach($cartItems as $cartItem){
            if($cartItem['restaurant_id'] != $item['restaurant_id']){
                $itemOfsameRestaurant = false;
            }
        }

        return $itemOfsameRestaurant;
    }

    // Calculate the total quantity of the cart
    public static function calculateTotalQuantity()
    {
        $cart = self::getCart();
        $totalQuantity = 0;

        foreach ($cart as $item) {
            $totalQuantity += $item['quantity'];
        }

        return $totalQuantity;
    }

    public static function clearCart()
    {
       return Cookie::queue(Cookie::forget('res_cart'));
    }

    public static function cartExist()
    {
        return Cookie::has('res_cart');
    }


}


