<?php

namespace App\CentralLogics\Restaurant;



 class FoodItemDataToCustomer
{
    public $foodName = null ;
    public $foodPrice = 0;
    public $foodPriceEach = 0;
    public $quantity = 0 ;
    public $discountAmount = 0;
    public $foodPriceAfterDiscount = 0;
    public $packingCharge = 0;
    public $packingChargeEach = 0;
    public $foodPriceAfterPackingCharge = 0;

    public function __construct()
    {
        return $this;
    }


}
