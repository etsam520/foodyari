<?php

namespace App\CentralLogics;

use App\Http\Controllers\User\Mess\CartHelper;

class MessSubscriptionBilling
{
    public $faceValue = 0;
    public $subtotal = 0;
    public $saved = 0;
    public $customDiscount = 0;
    public $tax = 0;
    public $discount = 0;
    public $couponDiscount = 0;
    public $deliveryCharge = 0;

    public function __construct()
    {
        $cart = CartHelper::getCart();
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $package = $item['package_data'];
                if (!empty($package)) {
                    $this->faceValue +=( (int) $package['price'] * (int) $item['quantity'] );
                    $this->subtotal +=( (int) Helpers::food_discount($package['price'], $package['discount']) * (int) $item['quantity'] );
                }
            }
            $this->saved = (int) $this->faceValue - $this->subtotal;
        }
    }

    public function total()
    {
        $tempTotal = (int) $this->subtotal;
        $tempTotal -= (int) $this->customDiscount;
        $tempTotal -= (int) $this->discount;
        $tempTotal += (int) $this->tax;
        $tempTotal += (int) $this->deliveryCharge;
        $tempTotal -= (int) $this->couponDiscount;

        return $tempTotal;
    }
}
