<?php

namespace App\CentralLogics\Restaurant;

class BillingForRestaurant {


    public $foodItemList = [];
    public $foodPriceCollectionPreDiscount = 0 ;
    public $foodPriceCollection = 0 ;
    public $sumOfDiscount = 0 ;

    public $couponDiscount = 0 ;
    public $couponDiscountAmount = 0 ;
    public $sumofPackingCharge = 0 ;
    public $priceAfterCouponDiscount = 0 ;

    public $commissionChargedByAdmin = 0 ;
    public $priceAfterCommissinChargedByAdmin = 0 ;
    public $grossTotal = 0 ;

    public $gstPercent = 0 ;
    public $gstAmount = 0 ;
    public $receivableAmount = 0 ;
    public $earning = 0 ;

    public function __construct()
    {
        return $this;
    }

}

