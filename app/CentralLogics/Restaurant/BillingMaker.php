<?php

namespace App\CentralLogics\Restaurant;

use App\CentralLogics\CartHelper;
use App\CentralLogics\Helpers;
use App\CentralLogics\Redis\RedisHelper;
use App\Http\Controllers\User\Restaurant\apparatusReferral\PreOrderProcess;
use App\Http\Controllers\User\Restaurant\apparatusReferral\ReferralPreOrderProcess;
use App\Models\CustomerAddress;
use App\Models\Food;
use App\Models\Restaurant;
use App\Models\Zone;
use App\Models\ZoneDeliveryChargeSetting;
use App\Services\DeliveryChargeService;
use Carbon\Carbon;
use Faker\Extension\Helper;
use Google\Rpc\Help;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Throwable;

class BillingMaker
{


    /*=========================
            Restaurant terms
    ===========================*/

    public $restaurant = null;

    public $foodItemListDataToRestaurant = [];

    public $restaurantFoodPriceCollectionPreDiscount = 0;
    public $restaurantFoodPriceCollection = 0; // here sum of restaurant food price
    public $sumOfRestaurantDiscount = 0;
    public $restaurantCouponDiscount = 0;
    public $restaurantCouponDiscountAmount = 0;
    public $restaurantPriceAfterCouponDiscount = 0;

    public $commissionChargedByAdmin = 0;
    public $restaurantPriceAfterCommissinChargedByAdmin = 0;
    public $restaurantGrossTotal = 0;

    public $restaurantGstPercent = 0;
    public $restaurantGstAmount = 0;
    public $restaurantReceivableAmount = 0;
    public $restaurantEarning = 0;
    /*=========================
            Admin terms
    ===========================*/
    public $foodItemListDataToAdmin = [];
    public $adminMargingCollection = 0;

    public $adminComissionPercent = 0;
    public $adminComissionAmount = 0;


    public $adminCouponDiscount = 0;
    public $adminCouponDiscountAmount = 0;
    public $adminMarginAfterCouponDiscount = 0;
    public $adminSelfBalanceCouponDiscountAmount = 0;
    public $adminMarginAfterSelfBalanceCouponDiscountAmouont = 0;

    public $grossAdminMargin = 0;
    public $netAdminMargin = 0;


    public $adminGstAmount = 0;
    public $adminReceivableAmount = 0;
    public $adminEarning = 0;

    /*=========================
            Customer terms
    ===========================*/
    public $userId = null;
    public $guestId = null;
    public $sendBill = false;
    public $order_to = 'self';

    public $distance = 0;
    public $foodItemListDataToCustomer = [];
    public $sumOfFoodPriceBeforDiscount = 0;
    public $sumOfFoodPrice = 0;
    public $sumOfDiscount = 0;

    public $couponDiscount = 0;
    public $couponDiscountType = null;
    public $couponDiscountAmount = 0;
    public $sumOfFoodPriceAfterCouponDiscount = 0;

    public $referralDiscount = 0;
    public $referralDiscountType = null;
    public $referralDiscountAmount = 0;
    public $sumOfFoodPriceAfterReferralDiscount = 0;

    public $foodAmountForGstValuation = 0;
    public $grossTotal = 0;
    public $gstPercent = 0;
    public $gstAmount = 0;
    public $billingTotal = 0;

    /*=========================
            global  terms
    ===========================*/
    public $couponDiscountBy = null;
    public $deliveryAddress = null;
    public $zone = null;
    public $deliveryCharge = 0;
    public $deliveryChargeFaceVal = 0;
    public $freeDelivery = 0;
    public $freeDeliveryCoupon = [];
    public $freeDeliveryRange = 0;
    public $extraRange = 0;
    public $platformCharge = 0;
    public $dm_tips = 0;
    public $couponDetails = [];

    // Environmental factors for delivery charge calculation
    public $rainFactor = 0;        // 0-1 scale
    public $trafficFactor = 0;     // 0-1 scale  
    public $nightFactor = 0;       // 0-1 scale
    public $deliveryChargeDetails = [];


    public $sumofPackingCharge = 0;

    /*=========================
            Referral Reward terms
    ===========================*/
    public ?ReferralPreOrderProcess $referralReward = null;




    public function __construct(?int $userId , ?string $guestId, Restaurant $restaurant, $dmTips = 0) 
    {
        if ($restaurant) {
            $this->userId = $userId;
            $this->guestId = $guestId;
            $this->restaurant = $restaurant;
            $this->restaurantGstPercent = (float) $restaurant->tax ?? 0;
            $this->adminComissionPercent = (float) $restaurant->comission ?? 0;
            $this->dm_tips = $dmTips;
        }
    }

    /**
     * Set environmental factors manually
     * @param float $rain 0-1 scale (0 = no rain, 1 = heavy rain)
     * @param float $traffic 0-1 scale (0 = clear, 1 = heavy traffic)  
     * @param float $night 0-1 scale (0 = day, 1 = night)
     */
    public function setEnvironmentalFactors($zoneID): self
    {
        $zoneSetting = ZoneDeliveryChargeSetting::where('zone_id', $zoneID)->first();
        $factors = $zoneSetting->getEnvironmentalFactors();
       
        if ($zoneSetting) {
            $this->rainFactor = max(0, floatval($factors['rain']));
            $this->trafficFactor = max(0, floatval($factors['traffic']));
            $this->nightFactor = max(0, floatval($factors['night']));
        }

        return $this;
    }

    public function processCart($data) : self
    {

        foreach ($data as $dataItem) {
            $foodItem = new BillingFoodItem($dataItem);
            $foodItem->process();
            /*========// for restaurant // ===========*/
            $resturantData = $foodItem->restaurantData();
            $this->restaurantFoodPriceCollectionPreDiscount += $resturantData->restaurantPrice;
            $this->sumOfRestaurantDiscount = $resturantData->restaurantDiscountAmount;

            if ($resturantData->restaurantPackingCharge > 0) {
                $this->restaurantFoodPriceCollection += $resturantData->restaurantPriceAfterPackingCharge;
            } else {
                $this->restaurantFoodPriceCollection += $resturantData->restaurantPriceAfterDiscount;
            }

            $this->foodItemListDataToRestaurant[] = $resturantData;

            /*========// for admin // ===========*/
            $adminData = $foodItem->adminData();
            $this->adminMargingCollection += $adminData->adminMarginAfterDiscount;

            $this->foodItemListDataToAdmin[] = $adminData;



            /*========// for customer // ===========*/
            $customerData = $foodItem->customerData();
            $this->sumOfFoodPriceBeforDiscount += $customerData->foodPrice;
            $this->sumOfDiscount += $customerData->discountAmount;
            $this->sumofPackingCharge += $customerData->packingCharge;
            if ($customerData->packingCharge > 0) {
                $this->sumOfFoodPrice += $customerData->foodPriceAfterPackingCharge;
            } else {
                $this->sumOfFoodPrice += $customerData->foodPriceAfterDiscount;
            }
            $this->foodItemListDataToCustomer[] = $customerData;
        }

        $this->process2();

        return $this;
    }

    private function process2() : void
    {
        try {


           $this->userId ? $this->subTask_couponDiscount() : null;
            $this->userId ? (
                $this->referralReward = new ReferralPreOrderProcess($this))->applyPreOrder() : null;
            $this->setPriceAfterCouponDiscount();
            $this->setPriceAfterReferralDiscount();
            $this->setAdminCommisionOnRestaurant();
            $this->subTast_platformCharge();
            $this->setPlatformCharge();
            $this->shipping_charge();
            $this->setDeliveryCharge();

            $this->subTask_GST();

            $this->customerBillPrincing();
            $this->subTask_earnings();
        } catch (Throwable $th) {
            throw $th;
            // dd('BillingMaker Error: ' . $th->getMessage(), $th->getFile(), $th->getLine());\\
            // dd($th);
        }

        // // return $this;
        // dd($this);
    }
    private static function removerAppliedCoupon($id) : mixed{
        try {
            if (empty($id)) {
                throw new \Error('Coupon ID is required.');
            }
            $userId = auth('customer')->user()->id;

            $appliedCoupons = Helpers::getOrderSessions($userId, "applied_coupons");

            $key = array_search($id, array_column($appliedCoupons, 'id'));
            if ($key !== false) {
                unset($appliedCoupons[$key]);
                $appliedCoupons = array_values($appliedCoupons);
                DB::table('order_sessions')->where('customer_id', $userId)->update(['applied_coupons' => json_encode($appliedCoupons)]);
            }

            return true ;
        } catch (\Throwable $th) {
            return $th ;
        }
    }

    private function subTask_couponDiscount() : void
    {

        $coupons = Helpers::getOrderSessions($this->userId, "applied_coupons");
        foreach($coupons ?? [] as $key => $coupon){
            if($this->sumOfFoodPrice < $coupon['min_purchase'] ){
                self::removerAppliedCoupon($coupon['id']) ;
            }
        }
        // dd($coupons);
        foreach ($coupons ?? [] as $key => $coupon) {
            $startDate = Carbon::parse($coupon['start_date']);
            $expireDate = Carbon::parse($coupon['expire_date']);
            $tempCouponDiscount = 0;
            $tempCouponDiscountAdmin = 0;
            $tempCouponDiscountRestaurant = 0;

            if ($expireDate->isPast(Carbon::now())) {
                // dd($coupon);
                unset($coupons[$key]);
                continue;
            }

            // $this->deliveryCharge

            /*=========// for customer //===================*/

            if ($coupon['coupon_type'] == "free_delivery") {
                $this->freeDeliveryCoupon = $coupon;
            }
            // $customer_basePrice = max($this->sumOfFoodPrice,  ($this->sumOfFoodPrice + $this->couponDiscountAmount ));
            $customer_basePrice = $this->sumOfFoodPrice;

            if ($customer_basePrice > $coupon['min_purchase']) {

                $couponDiscountPrice = self::CouponDiscount_calc(
                    $customer_basePrice,
                    $coupon['discount'],
                    $coupon['discount_type']
                );

                $tempCouponDiscount = min($couponDiscountPrice, $coupon['max_discount']);

                // for admin & restaurant
                if ($coupon['created_by'] == "restaurant" || $coupon['created_by'] == "vendor") {
                    $tempCouponDiscountRestaurant = self::CouponDiscount_calc(
                        $this->restaurantFoodPriceCollection,
                        $coupon['discount'],
                        $coupon['discount_type']
                    );
                    $tempCouponDiscountRestaurant = min($tempCouponDiscountRestaurant, $coupon['max_discount']);
                }

                if ($coupon['created_by'] == "admin") {
                    $tmpD = self::CouponDiscount_calc(
                        $customer_basePrice,
                        $coupon['discount'],
                        $coupon['discount_type'] // here sum of food price is of cumtomer food price
                    );
                    $tmpD = min($tmpD, $coupon['max_discount']);
                    $tempCouponDiscountAdmin += $tmpD;
                }

                $appliedCoupon = [
                    'id' => $coupon['id'],
                    'code' => $coupon['code'],
                    'couponDiscount' => $tempCouponDiscount,
                    'couponDiscountAdmin' => $tempCouponDiscountAdmin,
                    'couponDiscountRestaurant' => $tempCouponDiscountRestaurant,
                    'created_by' => $coupon['created_by']
                ];

                $this->couponDetails[] = $appliedCoupon;
                $this->couponDiscountAmount += $tempCouponDiscount;

                $this->restaurantCouponDiscountAmount += $tempCouponDiscountRestaurant;
                $this->adminCouponDiscountAmount += $tempCouponDiscountAdmin;
            } else {
                unset($coupons[$key]); // Remove coupons that do not meet the minimum purchase requirement
            }
        }
    }

    
    private function setPriceAfterCouponDiscount() : void
    {
        /*============// for restaurant //=============*/
        $this->restaurantPriceAfterCouponDiscount = $this->restaurantFoodPriceCollection - $this->restaurantCouponDiscountAmount;

        /*============// for admin //=============*/
        // difference of customer coupon discount and restaurnat coupon discount balcing from margin ;
        $this->adminSelfBalanceCouponDiscountAmount = $this->couponDiscountAmount -
            ($this->restaurantCouponDiscountAmount + $this->adminCouponDiscountAmount);
        //( selfBalace(deduct from admin margin) = customerCouponDiscount - (restaurantCouponDiscountAmount + admounCouponDiscontAmount))

        $this->adminMarginAfterCouponDiscount = $this->adminMargingCollection  - $this->adminCouponDiscountAmount;

        $this->adminMarginAfterSelfBalanceCouponDiscountAmouont = $this->adminMarginAfterCouponDiscount - $this->adminSelfBalanceCouponDiscountAmount;

        /*============// for customer //=============*/
        $this->sumOfFoodPriceAfterCouponDiscount = ($this->sumOfFoodPrice)
                                                     - $this->couponDiscountAmount;
    }

    private function setPriceAfterReferralDiscount() : void
    {
        /*============// for customer //=============*/
        $this->sumOfFoodPriceAfterReferralDiscount = max(0, $this->sumOfFoodPriceAfterCouponDiscount - $this->referralDiscountAmount);
        $this->foodAmountForGstValuation = $this->sumOfFoodPriceAfterReferralDiscount ;
    }


    private function setPlatformCharge() : void
    {
        /*============// for customer //=============*/
        $this->grossTotal = ($this->sumOfFoodPriceAfterReferralDiscount + $this->platformCharge);
    }
    private function setDeliveryCharge() : void
    {
        /*============// for customer //=============*/
        $this->grossTotal += $this->deliveryCharge;
    }

    private function setAdminCommisionOnRestaurant() : void
    {
        /*============// for restaurant //====================*/
        $this->commissionChargedByAdmin = ($this->restaurantPriceAfterCouponDiscount * $this->adminComissionPercent) / 100;
        $this->restaurantPriceAfterCommissinChargedByAdmin = $this->restaurantPriceAfterCouponDiscount - $this->commissionChargedByAdmin;
        $this->restaurantGrossTotal = $this->restaurantPriceAfterCommissinChargedByAdmin;

        /*============// for Admin //====================*/
        $this->adminComissionAmount = $this->commissionChargedByAdmin;


        $this->grossAdminMargin = ($this->adminMarginAfterSelfBalanceCouponDiscountAmouont + $this->platformCharge + $this->deliveryCharge + $this->adminComissionAmount);
        $this->netAdminMargin = $this->grossAdminMargin - ($this->referralDiscountAmount+ $this->adminGstAmount + $this->dm_tips);
    }

    private function subTask_GST() : void
    {

        /*============// for customer //====================*/
        $this->gstPercent = $this->restaurantGstPercent;
        // $customerGST = self::gst_calc($this->grossTotal, $this->gstPercent);
        $customerGST = self::gst_calc($this->foodAmountForGstValuation, $this->gstPercent);
        $this->gstAmount =  max(0, $customerGST);

        /*============// for restaurant //====================*/

        $restaurantGST = self::gst_calc($this->restaurantGrossTotal, $this->restaurantGstPercent);
        $this->restaurantGstAmount = max(0, $restaurantGST);

        /*============// for admin //====================*/
        $adminGst = $customerGST - $restaurantGST;
        $this->adminGstAmount = max(0, $adminGst);
    }

    private function customerBillPrincing() : void
    {
        /*============// for customer //====================*/
        $this->billingTotal = $this->gstAmount + $this->grossTotal + $this->dm_tips;
    }

    private function subTask_earnings() : void
    {
        /*============// for restaurant //====================*/
        $this->restaurantReceivableAmount = $this->restaurantGrossTotal + $this->restaurantGstAmount;
        $this->restaurantEarning = $this->restaurantReceivableAmount - $this->restaurantGstAmount;

        /*============// for admin //====================*/
        $this->adminReceivableAmount = $this->grossAdminMargin + $this->adminGstAmount + $this->dm_tips;
        $this->adminEarning = $this->adminReceivableAmount - ($this->adminGstAmount  + $this->dm_tips);
    }

    private static function CouponDiscount_calc($price, $d_value, $d_type = 'amount') : float
    {
        $price = (float) $price;
        $d_value = (float) $d_value;

        if ($d_type === 'percent') {
            $dis = ($price * $d_value / 100);
        } else if ($d_type === 'amount') {
            $dis = $d_value;
        } else {
            $dis = 0;
        }
        return $dis;
    }

    private static function gst_calc($price, $d_value) : float
    {
        $price = (float) $price;
        $d_value = (float) $d_value;
        return ($price * $d_value / 100);
    }

    private function shipping_charge() : void
    {
        $customerData = [];
        $zone = Zone::find($this->restaurant->zone_id);
        $this->zone = $zone;

        if (!empty($this->userId)) {
            $redis = new RedisHelper();
            $user_location = $redis->get("user:{$this->userId}:user_location") ?? null;
            
            if ($user_location != null) {
                $user_location = json_decode($user_location);
                $customerData['lat'] = $user_location->lat;
                $customerData['lon'] = $user_location->lng;
                $customerData['phone'] = $user_location->phone ?? null;
                $customerData['address'] = $user_location->address ?? null;
                $customerData['landmark'] = $user_location->landmark ?? null;
                $customerData['type'] = $user_location->type ?? null;
            } else {
                $savedaddress = CustomerAddress::whereHas('customer', function ($query) {
                    $query->where('id', $this->userId);
                })->where('is_default', 1)->latest()->first();

                if ($savedaddress) {
                    $customerData['lat'] = $savedaddress->latitude;
                    $customerData['lon'] = $savedaddress->longitude;
                    $customerData['phone'] = $savedaddress->phone ?? null;
                    $customerData['address'] = $savedaddress->address ?? null;
                    $customerData['landmark'] = $savedaddress->landmark ?? null;
                    $customerData['type'] = $savedaddress->type ?? null;
                }
            }

            $userPoint = ['lat' => $customerData['lat'], 'lon' => $customerData['lon']];
            $restaurantPoint = ['lat' => $this->restaurant->latitude, 'lon' => $this->restaurant->longitude];

            $this->distance = Helpers::haversineDistance($userPoint, $restaurantPoint);

            $addressData = [
                'position' => $userPoint,
                'stringAddress' => $customerData['address'],
                'landmark' => $customerData['landmark'],
                'type' => $customerData['type'],
            ];

            $loved_one_data = Helpers::getOrderSessions($this->userId, "loved_one_data");
            if ($loved_one_data != null) {
                $addressData['contact_person_name'] = $loved_one_data['name'];
                $addressData['contact_person_number'] = $loved_one_data['phone'];
                $this->sendBill = $loved_one_data['sendBill'];
                $this->order_to = "loved_one";
            }

            $this->deliveryAddress = json_encode($addressData);

            // === NEW ZONE-WISE DELIVERY CHARGE CALCULATION ===
            try {
                $this->setEnvironmentalFactors($zone->id);
                // dd($this->rainFactor, $this->trafficFactor, $this->nightFactor, $this->distance, $this->grossTotal);
                // Calculate delivery charge using zone-wise system with environmental factors
                $deliveryResult = DeliveryChargeService::calculateForZone(
                    $zone->id,
                    $this->distance,
                    $this->grossTotal, // Current order amount for free delivery check
                    [
                        'rain' => $this->rainFactor,
                        'traffic' => $this->trafficFactor,
                        'night' => $this->nightFactor
                    ]
                );

                $this->deliveryCharge = $deliveryResult['charge'];
                $this->deliveryChargeDetails = $deliveryResult['details'];
                // dd($this->deliveryCharge);
              

                // Handle free delivery from calculation
                if ($deliveryResult['details']['free_delivery']) {
                    $this->freeDelivery = $this->deliveryCharge;
                    $this->deliveryCharge = 0;
                } else {
                    $this->freeDelivery = 0;
                }
            } catch (\Exception $e) {
                throw $e;
            }
            
            // === HANDLE FREE DELIVERY COUPONS ===
            $this->handleFreeDeliveryCoupons($zone);

        } else {
            // For guest users, use zone delivery charge calculation with default environmental factors
            try {
                $deliveryResult = DeliveryChargeService::calculateForZone(
                    $zone->id,
                    $this->distance,
                    $this->grossTotal,
                    [
                        'rain' => 1.0,  // Default factor
                        'traffic' => 1.0, // Default factor
                        'night' => 1.0   // Default factor
                    ]
                );
                
                $this->deliveryCharge = $deliveryResult['charge'];
                $this->deliveryChargeDetails = $deliveryResult['details'];
                
                // Handle free delivery from calculation
                if ($deliveryResult['details']['free_delivery']) {
                    $this->freeDelivery = $this->deliveryCharge;
                    $this->deliveryCharge = 0;
                } else {
                    $this->freeDelivery = 0;
                }
                
                $this->deliveryChargeFaceVal = $this->deliveryCharge;
            } catch (\Exception $e) {
                // Fallback if delivery charge service fails
                $this->deliveryCharge = 0;
                $this->deliveryChargeFaceVal = 0;
                $this->freeDelivery = 0;
            }
        }
    }

  

    /**
     * Handle free delivery coupons
     */
    private function handleFreeDeliveryCoupons($zone): void
    {
        $freeCoupon = $this->freeDeliveryCoupon;
        $originalDeliveryCharge = $this->deliveryCharge;
        $_unsetFreeDeliveryCoupon = true;
        

        // Coupon-based free delivery
        if (isset($freeCoupon) && !empty($freeCoupon)) {
            if ((float) $freeCoupon['min_purchase'] < $this->grossTotal) {
                $coupon_range = $freeCoupon['delivery_range'];
                if ($this->distance < $coupon_range) {
                    $this->freeDelivery = $originalDeliveryCharge;
                    $this->deliveryCharge = 0;
                    $_unsetFreeDeliveryCoupon = false;
                } else {
                    $this->freeDelivery = 0;
                    $this->deliveryCharge = $originalDeliveryCharge;
                }
            }
        }
        if ($_unsetFreeDeliveryCoupon) {
            $this->freeDeliveryCoupon = [];
            
            $this->couponDetails = array_filter($this->couponDetails, function ($coupon) use ($freeCoupon) {
                if(empty($freeCoupon)){
                    return true ;
                }
                return $coupon['id'] !== $freeCoupon['id'];
            });
        }
    }


    private function subTast_platformCharge()
    {
        $this->platformCharge = (float) $this->zone?->platform_charge_original;
    }

    public function clearCouponCache() : self
    {
        DB::table('order_sessions')->where('customer_id', $this->userId)->update(['applied_coupons' => '[]']);
        return $this;
    }


    public function adminBillData() : BillingForAdmin
    {
        $a_bill = new BillingForAdmin();
        $a_bill->foodItemList = $this->foodItemListDataToAdmin;

        $a_bill->margingCollection = $this->adminMargingCollection;
        $a_bill->comissionPercent = $this->adminComissionPercent;
        $a_bill->comissionAmount = $this->adminComissionAmount;

        $a_bill->couponDiscount = $this->adminCouponDiscount;
        $a_bill->couponDiscountAmount = $this->adminCouponDiscountAmount;
        $a_bill->marginAfterCouponDiscount = $this->adminMarginAfterCouponDiscount;
        $a_bill->selfBalanceCouponDiscountAmount = $this->adminSelfBalanceCouponDiscountAmount;
        $a_bill->marginAfterSelfBalanceCouponDiscountAmouont = $this->adminMarginAfterSelfBalanceCouponDiscountAmouont;
        $a_bill->grossMargin = $this->grossAdminMargin;
        $a_bill->gstAmount = $this->adminGstAmount;
        $a_bill->receivableAmount = $this->adminReceivableAmount;
        $a_bill->earning = $this->adminEarning;

        return $a_bill;
    }

    public function restaurantBillData() : BillingForRestaurant
    {

        $r_bill = new BillingForRestaurant();
        $r_bill->foodItemList = $this->foodItemListDataToRestaurant;
        $r_bill->foodPriceCollectionPreDiscount = $this->restaurantFoodPriceCollectionPreDiscount;
        $r_bill->foodPriceCollection = $this->restaurantFoodPriceCollection;
        $r_bill->sumOfDiscount = $this->sumOfRestaurantDiscount;

        $r_bill->couponDiscount = $this->restaurantCouponDiscount;
        $r_bill->couponDiscountAmount = $this->restaurantCouponDiscountAmount;
        $r_bill->sumofPackingCharge = $this->sumofPackingCharge;
        $r_bill->priceAfterCouponDiscount = $this->restaurantPriceAfterCouponDiscount;

        $r_bill->commissionChargedByAdmin = $this->commissionChargedByAdmin;
        $r_bill->priceAfterCommissinChargedByAdmin = $this->restaurantPriceAfterCommissinChargedByAdmin;
        $r_bill->grossTotal = $this->restaurantGrossTotal;

        $r_bill->gstPercent = $this->restaurantGstPercent;
        $r_bill->gstAmount = $this->restaurantGstAmount;
        $r_bill->receivableAmount = $this->restaurantReceivableAmount;
        $r_bill->earning = $this->restaurantEarning;

        return $r_bill;
    }
    public function customerBillData() : BillingForCustomer
    {
        $c_bill = new BillingForCustomer();
        $c_bill->userId = $this->userId;
        $c_bill->foodItemList = $this->foodItemListDataToCustomer;
        $c_bill->sumOfFoodPriceBeforDiscount = $this->sumOfFoodPriceBeforDiscount;
        $c_bill->sumOfDiscount = $this->sumOfDiscount;

        $c_bill->sumOfFoodPrice = $this->sumOfFoodPrice;
        $c_bill->couponDiscount = $this->couponDiscount;
        $c_bill->couponDiscountType = $this->couponDiscountType;
        $c_bill->couponDiscountAmount = $this->couponDiscountAmount;
        $c_bill->sumOfFoodPriceAfterCouponDiscount = $this->sumOfFoodPriceAfterCouponDiscount;
        $c_bill->referralDiscount = $this->referralDiscount;
        $c_bill->referralDiscountType = $this->referralDiscountType;
        $c_bill->referralDiscountAmount = $this->referralDiscountAmount;
        $c_bill->sumOfFoodPriceAfterReferralDiscount = $this->sumOfFoodPriceAfterReferralDiscount;
        $c_bill->grossTotal = $this->grossTotal;
        $c_bill->gstPercent = $this->gstPercent;
        $c_bill->gstAmount = $this->gstAmount;
        $c_bill->billingTotal = $this->billingTotal;

        $c_bill->couponDiscountBy = $this->couponDiscountBy;
        $c_bill->deliveryAddress = $this->deliveryAddress;
        $c_bill->zone = $this->zone;
        $c_bill->deliveryCharge = $this->deliveryCharge;
        $c_bill->deliveryChargeFaceVal = $this->deliveryChargeFaceVal;
        $c_bill->freeDelivery = $this->freeDelivery;
        $c_bill->freeDeliveryCoupon = $this->freeDeliveryCoupon;
        $c_bill->freeDeliveryRange = $this->freeDeliveryRange;
        $c_bill->extraRange = $this->extraRange;
        $c_bill->platformCharge = $this->platformCharge;
        $c_bill->sumofPackingCharge = $this->sumofPackingCharge;
        $c_bill->dm_tips = $this->dm_tips;
        $c_bill->couponDetails = $this->couponDetails;
        $c_bill->restaurant = $this->restaurant;
        $c_bill->distance = $this->distance;
        $c_bill->order_to = $this->order_to;
        $c_bill->sendBill = $this->sendBill;

        // Environmental factors and delivery charge details
        $c_bill->rainFactor = $this->rainFactor;
        $c_bill->trafficFactor = $this->trafficFactor;
        $c_bill->nightFactor = $this->nightFactor;
        $c_bill->deliveryChargeDetails = $this->deliveryChargeDetails;

        return $c_bill;
    }
}
