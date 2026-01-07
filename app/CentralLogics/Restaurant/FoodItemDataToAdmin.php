<?php

namespace App\CentralLogics\Restaurant;



 class FoodItemDataToAdmin
{
    public $foodName =null ;
    public $quantity = 0;
    public $adminMarginOnEach = 0;
    public $adminMargin = 0;
    public $adminDiscountType = null;
    public $adminDiscount = 0;
    public $adminDiscountAmount = 0;
    public $priceAfterAdminDiscount = 0;
    public $adminMarginAfterDiscount = 0;

    public function __construct()
    {
        return $this;
    }


}
