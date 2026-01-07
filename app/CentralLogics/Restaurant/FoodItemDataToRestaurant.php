<?php

namespace App\CentralLogics\Restaurant;

use App\CentralLogics\Helpers;

 class FoodItemDataToRestaurant
{
    public $foodName = null ;
    public $restaurantPrice = 0 ;
    public $restaurantPriceEach = 0 ;
    public $quantity = 0 ;
    public $restaurantDiscount = 0;
    public $restaurantDiscountAmount= 0;
    public $restaurantPriceAfterDiscount = 0;
    public $restaurantPackingCharge = 0;
    public $restaurantPackingChargeEach = 0;
    public $restaurantPriceAfterPackingCharge = 0;

    public function __construct()
    {
        return $this;
        // Helpers::percent_discount()
    }


}
