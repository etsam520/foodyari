<?php

namespace App\Http\Controllers\Vendor\PosBillingHelper;


use App\CentralLogics\Helpers;

use App\CentralLogics\CartHelper;
use App\Models\CustomerAddress;
use App\Models\Food;
use App\Models\Zone;
use App\Services\DeliveryChargeService;
use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class BillingController
{


    public $subtotal = 0;
    public $saved = 0;
    public $customDiscount = 0;
    public $tax = 0;
    public $txtPercent = 0;
    public $platform_fee = 0;
    public $discount_on_food = 0;
    public $product_price_after_discount_on_food = 0;
    public $product_price_after_coupon_discount = 0;

    public $couponDiscount = 0;
    private $tempCouponDiscount = 0;
    public $deliveryCharge = 0;
    public $total_addon_price = 0;
    public $total_variant_price = 0;
    public $product_price = 0;
    public $order_details = [];
    public $gross_total = 0;
    public $restaurant = null;
    public $zone = null;
    public $couponDetails= [];
    public $total = 0;
    public $dm_tips = 0;
    public $userId = null;
    public $distance = 0;
    public $deliveryAddress = null;
    public $restaurantDiscount = 0;

    public function __construct($user_id = null)
    {
        $this->userId = $user_id;
        return $this;
    }

    public function process()
    {
        try {
            if(!CartHelper::cartExist())
            {
                throw new \Exception('Empty Cart');
            }
            $cart = CartHelper::getCart();
            if (!empty($cart)) {
                foreach ($cart as $c) {
                    $product = Food::with('restaurant')->find($c['product_id']);
                    $price = $product->price;
                    if(empty($this->restaurant)){
                        $this->restaurant = $product->restaurant;
                    }else{
                        if($this->restaurant->id != $product->restaurant->id){
                            continue;
                        }
                    }
                    $product->tax = $product->restaurant->tax;
                    if(isset($c['variations'])){
                        $variation_data = Helpers::get_varient(json_decode($product->variations), $c['variations']);

                    }

                    if ($product) {
                        $or_d = [
                            'food_id' => $product->id,
                            'food_details' => json_encode($product),
                            'quantity' => $c['quantity'],
                            'price' => $product->price,
                            'tax_amount' => Helpers::tax_calculate($product, ($c['price']-$c['discount'])),
                            'discount_on_food' => $c['discount'],
                            'discount_type' => 'discount_on_product',
                            'variation' => json_encode($variation_data['variations']??[]) ,
                            'add_ons' => json_encode($c['addons']??[]),
                            'variation_price' =>  $variation_data['price']??0 ,
                            'addon_price' => $c['addon_price']??0,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        if($product->isCustomize == 1){
                            $or_d['discount_on_food'] = (int)$variation_data['price']  - (int)Helpers::food_discount($variation_data['price'], $product->discount, $product->discount_type);
                            $or_d['tax_amount'] = Helpers::tax_calculate($product, ($variation_data['price'] - $or_d['discount_on_food'])) ;
                            $this->discount_on_food =  $or_d['discount_on_food'];
                        }else{
                            $this->discount_on_food += $or_d['discount_on_food']*$or_d['quantity'];
                            $this->product_price += $price*$or_d['quantity'];
                        }
                        $this->total_addon_price += $or_d['addon_price'];
                        $this->total_variant_price += $or_d['variation_price'];
                        $this->order_details[] = $or_d;
                    }

                }

            }

            $this->grossTotal();
            $this->taxation();
            $this->platformCharge();
            if(Session::has('dm_tips')){

                $this->dm_tips = Session::get('dm_tips');
                $this->total += $this->dm_tips;
            }
            return $this;
        } catch (\Throwable $th) {
            //throw $th;
            // dd($th);
           return $th;
        }

    }

    public function grossTotal()
    {
        $this->subtotal = ($this->total_addon_price + $this->total_variant_price + $this->product_price );

        $this->product_price_after_discount_on_food +=  $this->subtotal - $this->discount_on_food;
        // if(Cache::has('applied_coupons')){
            $this->couponsApply();
        // }
        $this->product_price_after_coupon_discount += $this->product_price_after_discount_on_food - $this->tempCouponDiscount;
        $this->tempCouponDiscount = 0;

        $this->gross_total = $this->product_price_after_coupon_discount;
        $this->restaurantDiscount = $this->discount_on_food;



        $this->saved = $this->subtotal - $this->gross_total;
        // dd($this->product_price_after_coupon_discount);
        if(Session::has('custom_discount')){
            $this->customDiscountCalculate();
        }


        return $this;
    }

    public function taxation()
    {
        if(Session::has('update_tax')){
            $this->txtPercent = Session::get('update_tax');
        }else{
            $this->txtPercent = $this->restaurant->tax;
        }
        $this->tax = Helpers::product_tax($this->gross_total,$this->txtPercent);

        $this->shipping_charge();
        $this->total = $this->gross_total + $this->tax;
        return $this;
    }

    public function platformCharge(){
        $this->platform_fee = $this->zone->platform_charge_original;
        $this->gross_total += $this->platform_fee;
        return $this;

    }

    public function couponsApply()
    {
        $coupons = Cache::get('applied_coupons', []);
        // dd($this->product_price_after_coupon_discount);
        foreach ($coupons as $key => $coupon) {
            $startDate = Carbon::parse($coupon->start_date);
            $expireDate = Carbon::parse($coupon->expire_date);

            if ($expireDate->isPast(Carbon::now())) {
                unset($coupons[$key]);
                continue;
            }

            $basePrice = max($this->product_price_after_discount_on_food, $this->product_price_after_coupon_discount);

            if ($basePrice > $coupon->min_purchase) {
                if ($coupon->discount_type == 'amount') {
                    $couponDiscountPrice = $coupon->discount;
                } else {
                    $couponDiscountPrice = ($basePrice * $coupon->discount) / 100;
                }

                $this->tempCouponDiscount = min($couponDiscountPrice, $coupon->max_discount);
                $this->couponDiscount += $this->tempCouponDiscount;

                $appliedCoupon = [
                    'id' => $coupon->id, 'code' => $coupon->code, 'couponDiscount' => $this->tempCouponDiscount,'created_by' => $coupon->created_by
                ];
                $this->couponDetails[] = $appliedCoupon;

            } else {
                unset($coupons[$key]); // Remove coupons that do not meet the minimum purchase requirement
            }
        }

        // Cache::put('applied_coupons', array_values($coupons));
        return $this;
    }


    public function shipping_charge()
    {
        $tempCharge = 0;
        $customerData = [];
        if (!empty($this->userId)) {
            $sessiontAddress = Session::get('address',null);
            // dd($sessiontAddress);
            if($sessiontAddress != null){

                // $customerData['lat'] = $sessiontAddress->lat;
                // $customerData['lon'] = $sessiontAddress->lng;
                // $customerData['phone'] = $sessiontAddress->phone??null;
                // $customerData['address'] = $sessiontAddress->address??null;
                // $customerData['type'] = $sessiontAddress->type??null;

                $customerData['contact_person_name'] = $sessiontAddress['contact_person_name'];
                $customerData['contact_person_number'] = $sessiontAddress['contact_person_number'];
                $customerData['type'] = $sessiontAddress['address_type'];
                $customerData['stringAddress'] = $sessiontAddress['stringAddress'];
                $customerData['landmark'] = $sessiontAddress['landmark']??null;
                $customerData['distance'] = $sessiontAddress['distance'];
                $customerData['lat'] = $sessiontAddress['position']['lat'];
                $customerData['lon'] = $sessiontAddress['position']['lon'];
                $customerData['position'] = $sessiontAddress['position'];


            }else{
                $savedaddress = CustomerAddress::whereHas('customer', function($query) {
                    $query->where('id', $this->userId);
                })->where('is_default',1)->latest()->first();

                $customerData['lat'] = $savedaddress->latitude;
                $customerData['lon'] = $savedaddress->longitude;
                $customerData['phone'] = $savedaddress->phone??null;
                $customerData['stringAddress'] = $savedaddress->address??null;
                $customerData['landmark'] = $savedaddress->landmark??null;
                $customerData['type'] = $savedaddress->type??null;
                $customerData['position'] = ['lat' => $savedaddress->latitude, 'lon' => $savedaddress->longitude];
            }

            $zone = Zone::find($this->restaurant->zone_id);
            $this->zone = $zone;
            $userPoint = ['lat' => $customerData['lat']??0, 'lon' => $customerData['lon']??0];
            $restaurantPoint = ['lat' => $this->restaurant->latitude, 'lon' => $this->restaurant->longitude];

            $this->distance = Helpers::haversineDistance($userPoint, $restaurantPoint);
            $customerData['distance'] =  $this->distance;

            $this->deliveryAddress = json_encode($customerData);

            // Use new delivery charge service
            try {
                $deliveryResult = DeliveryChargeService::calculateForZone(
                    $zone->id,
                    $this->distance,
                    $this->subtotal, // Use subtotal as order amount
                    [
                        'rain' => 1.0,
                        'traffic' => 1.0,
                        'night' => 1.0
                    ]
                );
                $this->deliveryCharge = $deliveryResult['charge'];
            } catch (\Exception $e) {
                $this->deliveryCharge = 0;
            }

            $this->gross_total += $this->deliveryCharge;

        }
        return $this;
    }

    public function clearCouponCache()
    {
        Cache::delete('applied_coupons');
        return $this;
    }

    public function customDiscountCalculate()
    {
        $this->gross_total;
            if(Session::has('custom_discount')){
                $custom_discount = Session::get('custom_discount');
                if ($custom_discount['discount_type'] == 'percent') {
                    $custom_discount  = ($this->gross_total / 100) * $custom_discount['discount'];
                } else {
                    $custom_discount = $custom_discount['discount'];
                }

                $this->customDiscount = $custom_discount;
                $this->restaurantDiscount += $this->customDiscount;
                $this->gross_total = $this->gross_total - $this->customDiscount;
            }
    }

}
