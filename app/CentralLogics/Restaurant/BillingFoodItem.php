<?php

namespace App\CentralLogics\Restaurant;

class BillingFoodItem
{
    // Global Data
    public $foodName = null;
    public $quantity = 0 ;
    public $discountBy = null;

    // Restaurant Terms
    public $restaurantPrice = 0;
    public $restaurantPriceEach = 0;
    public $restaurantDiscount = 0;
    public $restaurantDiscountAmount = 0;
    public $restaurantPriceAfterDiscount = 0;
    public $restaurantPackingCharge = 0;
    public $restaurantPackingChargeEach = 0;
    public $restaurantPriceAfterPackingCharge = 0;

    // Admin Terms
    public $adminMarginOnEach = 0 ;
    public $adminMargin = 0;
    public $adminDiscountType = null;
    public $adminDiscount = 0;
    public $adminDiscountAmount = 0;
    public $adminMarginAfterDiscount = 0;

    // Customer Terms
    public $foodPrice = 0;
    public $foodPriceEach = 0;
    public $discountAmount = 0;
    public $foodPriceAfterDiscount = 0;
    public $packingCharge = 0;
    public $packingChargeEach = 0;
    public $foodPriceAfterPackingCharge = 0;

    // Constructor
    public function __construct(array $data)
    {
        $this->init($data);
    }

    // Initialization Method
    private function init(array $data)
    {
        try {
            $this->foodName = $data['foodName'] ?? null;
            $this->quantity = (int) $data['quantity'];

            $this->adminMarginOnEach = (float) ($data['adminMargin'] );
            $this->adminMargin = (float) ($this->adminMarginOnEach * $this->quantity);

            $this->discountBy = $data['discountBy'] ?? null;
            $this->adminDiscountType = $data['AdminDiscountType'] ?? 'amount';
            if($this->adminDiscountType  == "amount"){
                $this->adminDiscount = (float) ($data['AdminDiscount']??0 * $this->quantity );
            }else{
                $this->adminDiscount = (float) ($data['AdminDiscount'] ?? 0);
            }

            // Restaurant Data
            $this->restaurantPriceEach = ($data['restaurantPrice']?? 0 );
            $this->restaurantPrice = (float) ($this->restaurantPriceEach * $this->quantity );
            $this->restaurantDiscount = (float) ($data['restaurantDiscount']?? 0 * $this->quantity);
            $this->restaurantPackingChargeEach = (float) ($data['restaurantPackingCharge']?? 0 );
            $this->restaurantPackingCharge = (float) ($this->restaurantPackingChargeEach * $this->quantity );
        } catch (\Throwable $th) {
            throw new \Exception("Error initializing BillingFoodItem: " . $th->getMessage());
        }
    }

    // Main Process Method
    public function process()
    {
        $this->calculateMargin();
        $this->applyDiscounts();
        $this->calculateFoodPriceAfterDiscount();
        $this->calculateMarginAfterDiscount();
        $this->applyPackingCharge();
        $this->calculateFoodPriceAfterPackingCharge();

        return $this;
    }

    // Calculate Margin
    private function calculateMargin()
    {
        $this->foodPriceEach = $this->restaurantPriceEach + $this->adminMarginOnEach ;
        $this->foodPrice = $this->restaurantPrice + $this->adminMargin ;
    }

    // Apply Discounts
    private function applyDiscounts()
    {
        if ($this->discountBy === 'restaurant') {
            $this->restaurantDiscountAmount = self::calculateDiscount($this->restaurantPrice, $this->restaurantDiscount);
            $this->restaurantPriceAfterDiscount = $this->restaurantPrice - $this->restaurantDiscountAmount;
        } elseif ($this->discountBy === 'admin') {
            $this->adminDiscountAmount = self::calculateDiscount($this->foodPrice, $this->adminDiscount, $this->adminDiscountType);
        }
        // Total Discount for Customer
        $this->discountAmount = $this->restaurantDiscountAmount + $this->adminDiscountAmount;
    }

    // Calculate Food Price After Discount
    private function calculateFoodPriceAfterDiscount()
    {
        $this->restaurantPriceAfterDiscount = $this->restaurantPrice - $this->restaurantDiscountAmount;
        $this->foodPriceAfterDiscount = $this->foodPrice - $this->discountAmount;
    }

    // Calculate Admin Margin After Discount
    private function calculateMarginAfterDiscount()
    {
        $this->adminMarginAfterDiscount = $this->adminMargin - $this->adminDiscountAmount;
    }

    // Apply Packing Charge
    private function applyPackingCharge()
    {
        $this->packingCharge = $this->restaurantPackingCharge;
        $this->packingChargeEach = $this->restaurantPackingChargeEach;
    }

    // Calculate Food Price After Packing Charge
    private function calculateFoodPriceAfterPackingCharge()
    {
        $this->restaurantPriceAfterPackingCharge = $this->restaurantPriceAfterDiscount + $this->restaurantPackingCharge;
        $this->foodPriceAfterPackingCharge = $this->foodPriceAfterDiscount + $this->packingCharge;
    }

    // Discount Calculation Method
    public static function calculateDiscount(float $price, float $discountValue, string $discountType = 'amount'): float
    {
        if ($discountType === 'percent') {
            return ($price * $discountValue / 100);
        } elseif ($discountType === 'amount') {
            return $discountValue;
        }
        return 0;
    }

    // Customer Data
    public function customerData()
    {
        $cdata = new FoodItemDataToCustomer();
        $cdata->foodName = $this->foodName;
        $cdata->quantity = $this->quantity;
        $cdata->foodPriceEach = $this->foodPriceEach ;
        $cdata->foodPrice = $this->foodPrice;
        $cdata->discountAmount = $this->discountAmount;
        $cdata->foodPriceAfterDiscount = $this->foodPriceAfterDiscount;
        $cdata->packingCharge = $this->packingCharge;
        $cdata->packingChargeEach = $this->packingChargeEach;
        $cdata->foodPriceAfterPackingCharge = $this->foodPriceAfterPackingCharge;

        return $cdata;
    }

    // Restaurant Data
    public function restaurantData()
    {
        $rdata = new FoodItemDataToRestaurant();
        $rdata->foodName = $this->foodName;
        $rdata->restaurantPrice = $this->restaurantPrice;
        $rdata->restaurantPriceEach = $this->restaurantPriceEach;
        $rdata->quantity = $this->quantity;
        $rdata->restaurantDiscount = $this->restaurantDiscount;
        $rdata->restaurantDiscountAmount = $this->restaurantDiscountAmount;
        $rdata->restaurantPriceAfterDiscount = $this->restaurantPriceAfterDiscount;
        $rdata->restaurantPackingChargeEach = $this->restaurantPackingChargeEach;
        $rdata->restaurantPackingCharge = $this->restaurantPackingCharge;
        $rdata->restaurantPriceAfterPackingCharge = $this->restaurantPriceAfterPackingCharge;

        return $rdata;
    }

    // Admin Data
    public function adminData()
    {
        $adata = new FoodItemDataToAdmin();
        $adata->foodName = $this->foodName;
        $adata->adminMargin = $this->adminMargin;
        $adata->adminMarginOnEach = $this->adminMarginOnEach;
        $adata->quantity = $this->quantity;
        $adata->adminDiscountType = $this->adminDiscountType;
        $adata->adminDiscount = $this->adminDiscount;
        $adata->adminDiscountAmount = $this->adminDiscountAmount;
        $adata->adminMarginAfterDiscount = $this->adminMarginAfterDiscount;

        return $adata;
    }
}
