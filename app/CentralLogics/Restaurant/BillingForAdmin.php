<?php

namespace App\CentralLogics\Restaurant;

class BillingForAdmin {

    public $foodItemList = [];
    public $margingCollection = 0;

    public $comissionPercent = 0;
    public $comissionAmount = 0;


    public $couponDiscountType = null;
    public $couponDiscount = 0;
    public $couponDiscountAmount = 0 ;
    public $marginAfterCouponDiscount = 0;
    public $selfBalanceCouponDiscountAmount = 0;
    public $marginAfterSelfBalanceCouponDiscountAmouont = 0;

    public $grossMargin = 0 ;


    public $gstAmount = 0;
    public $receivableAmount = 0;
    public $earning = 0;

    public function __construct()
    {
        return $this;
    }
}
