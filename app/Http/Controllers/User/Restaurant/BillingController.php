<?php

namespace App\Http\Controllers\User\Restaurant;


use App\CentralLogics\Helpers;
use App\CentralLogics\Restaurant\BillingMaker;
use App\Http\Controllers\User\Restaurant\CartHelper;
use App\Models\Customer;
use App\Models\Food;
use App\Models\Restaurant;
use GPBMetadata\Google\Api\Billing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingController
{
    private ?BillingMaker $billing = null ;
    public  ?BillingMaker $billMakerData = null ;
    public $rawFoodListData = [] ;

    public $order_details = [];
    public $dm_tips = 0;
    public $userId = null;
    public $userType = "customer";
    public $guestId = null;
    public $sendBill = false;
    public $order_to = 'self';
    public $restaurant = null;
    public $producIds = [];

    public function __construct(?Customer $user , ?array $guest , string $userType =  "customer"  )
    {
        $this->userType = $userType;
        $this->userId = $user?->id ?? null;
        $this->guestId = $guest?->id ?? null;
          
      
        $dm_tips = $user != null ? (float)  Helpers::getOrderSessions($user?->id, "dm_tips") : 0;
        if($dm_tips > 0){

            $this->dm_tips = $dm_tips;
        }
    }

    /**
     * Process something.
     *
     * @throws \Throwable When processing fails
     */
    public function process() : self 
    {

        $foodData = null;

        try {
            if(!CartHelper::cartExist($this->userId ?? null))
            {
                throw new \Exception('Empty Cart');
            }
            $cart = CartHelper::getCart($this->userId ?? null);

            Log::info('mycart'. json_encode($cart).' userId '.$this->userId);
            if (!empty($cart)) {
                foreach ($cart as $c) {
                    $product = Food::find($c['product_id']);
                    $this->producIds[] = $product->id;

                    if(empty($this->restaurant)){

                        $this->restaurant = Restaurant::find($product->restaurant_id) ;
                    }else{
                        if($this->restaurant->id != $product->restaurant->id){
                            continue;
                        }
                    }
                    if($product->isCustomize != 1){
                        // dd($c);
                        $foodData =  [
                            'foodName' => $product->name,
                            'adminMargin' => $product->admin_margin ,
                            'quantity' => $c['quantity']??0 ,
                            'discountBy' => $product->discount_by,
                            'restaurantPrice' => $product->restaurant_price ,
                        ];
                        if($foodData['discountBy'] == "admin"){

                            $foodData['AdminDiscountType'] = $product->discount_type;
                            $foodData['AdminDiscount'] = $product->discount;
                        }else{
                            $foodData['restaurantDiscount'] = $product->discount ;
                        }
                        $foodData['restaurantPackingCharge'] = $product->packing_charge ;

                        $this->rawFoodListData[] = $foodData ;

                    }else{
                        if(isset($c['variations'])){
                            $variationDetails = Helpers::get_varient($product, $c['variations']);

                            foreach($variationDetails as $index => $variation){
                                // dd($variationDetails);
                                foreach ($variation['values'] as $key => $value){
                                    $foodData = null ;
                                    $foodData =  [
                                        'foodName' => $product->name." ({$value['label']})",
                                        'adminMargin' => $value['admin_margin'] ,
                                        'quantity' =>$value['qty'],
                                        'discountBy' => $product->discount_by,
                                        'restaurantPrice' => $value['price'] ,
                                        'restaurantPackingCharge' => $product->packing_charge
                                    ];

                                    if($foodData['discountBy'] == "admin"){

                                        $foodData['AdminDiscountType'] = $product->discount_type;
                                        $foodData['AdminDiscount'] = $product->discount;
                                    }else{
                                        $foodData['restaurantDiscount'] = $product->discount ;
                                    }
                                    $this->rawFoodListData[] = $foodData ;

                                }
                            }

                        }
                    }

                    if(isset($c['addons'])){
                        foreach (($c['addons']) as $key => $addon){
                            $foodData = null ;
                            $foodData =  [
                                'foodName' => $addon['name'],
                                'adminMargin' =>0 ,
                                'quantity' =>$addon['qty'],
                                'discountBy' => '',
                                'restaurantPrice' =>$addon['price'] ,
                                'packing_charge' =>0
                            ];

                            $foodData['AdminDiscount'] = 0;
                            $foodData['restaurantDiscount'] = 0 ;

                            $this->rawFoodListData[] = $foodData ;
                        }
                    }

                    //setting Up ord details
                    if ($product) {
                        $variation_data = null ;
                        if(isset($c['variations'])){
                            $variation_data = Helpers::get_varient( $product, $c['variations']);
                        }

                        $or_detail = [
                            'food_id' => $product->id,
                            'food_details' => json_encode($product),
                            'quantity' => $c['quantity'],
                            'variation' => json_encode($variation_data??[]) ,
                            'add_ons' => json_encode($c['addons']??[]),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        $this->order_details[] = $or_detail ;
                    }
                }

            }

            $this->billing = new BillingMaker($this->userId , $this->guestId , $this->restaurant, $this->dm_tips);

            return $this ;

        } catch (\Throwable $th) {
            Log::error('Billing Process :' . $th->getMessage(). " line no :". $th->getLine() ."file" . $th->getFile());
            throw $th;
        }

    }

    public function billMaker() : BillingMaker
    {
        $_billMakerData = $this->billing->processCart($this->rawFoodListData);
        $this->billMakerData = $_billMakerData;
        return $_billMakerData;
    }



    public function clearCouponCache()
    {
        if($this->userType == 'customer'){
            DB::table('order_sessions')->where('customer_id', $this->userId)->update(['applied_coupons' => '[]']);
        }
        return $this;
    }


}
