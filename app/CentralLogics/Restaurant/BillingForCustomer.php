<?php

namespace App\CentralLogics\Restaurant;

class BillingForCustomer {
    public $userId = null ;
    public $foodItemList = [];

    public $sumOfFoodPriceBeforDiscount = 0 ;
    public $sumOfDiscount = 0 ;
    public $sumOfFoodPrice = 0 ;

    public $couponDiscount = 0;
    public $couponDiscountType = null ;
    public $couponDiscountAmount = 0 ;
    public $sumOfFoodPriceAfterCouponDiscount = 0;

    public $referralDiscount = 0 ;
    public $referralDiscountType = null ;
    public $referralDiscountAmount = 0 ;
    public $sumOfFoodPriceAfterReferralDiscount = 0 ;
    
    public $sumofPackingCharge = 0 ;
    public $rainFactor = 0 ;
    public $trafficFactor = 0 ;
    public $nightFactor = 0 ;
    public $deliveryChargeDetails = [] ;
    public $deliveryCharge = 0 ;
    public $deliveryChargeFaceVal = 0 ;
    public $freeDelivery = 0;
    public $freeDeliveryCoupon = [];

    public $freeDeliveryRange = 0;
    public $extraRange = 0 ;

    public $platformCharge = 0;
    public $dm_tips = 0 ;

    public $grossTotal = 0;
    public $gstPercent = 0;
    public $gstAmount = 0;
    public $billingTotal = 0;

    public $couponDiscountBy = null ;
    public $deliveryAddress = null;
    public $zone = null;
    public $couponDetails = [] ;
    public $restaurant = null ;
    public $distance = null ;
    public $sendBill = false;
    public $order_to = 'self';
    public function __construct()
    {
        return $this;
    }

}
