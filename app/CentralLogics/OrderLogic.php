<?php

namespace App\CentralLogics;

use App\Models\AdminFund;
use App\Models\BusinessSetting;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class OrderLogic {

    public $dmMaxOrders ;
    public $dm_max_cash_in_hand ;
    public function __construct()
    {
        $this->dmMaxOrders = 6;
        $this->dm_max_cash_in_hand = 1000;
    }

    // public static function create_transaction($order,$status = null)
    // {
    //     try {
    //         OrderTransaction::updateOrCreate([
    //             'order_id' =>$order->id],[
    //             'vendor_id' =>$order->restaurant->vendor->id,
    //             'delivery_man_id'=>$order->delivery_man_id,
    //             'order_amount'=>$order->order_amount,
    //             'restaurant_amount'=> ($order->order_amount - $order->dm_tips - $order->total_tax_amount),
    //             'admin_commission'=>0,
    //             'restaurant_id' => $order->restaurant->id,
    //             //add a new column. add the comission here
    //             'delivery_charge'=>$order->delivery_charge,//minus here
    //             'original_delivery_charge'=>$order->original_delivery_charge,//calculate the comission with this. minus here
    //             'tax'=>$order->total_tax_amount,
    //             // 'received_by'=> null,
    //             'received_by'=> 'customer',
    //             'zone_id'=>$order->zone_id,
    //             'status'=> $status??1,
    //             'dm_tips'=> $order->dm_tips,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //             'delivery_fee_comission'=>0,
    //             'admin_expense'=>0,
    //             'restaurant_expense'=>0,
    //             // for restaurant business model
    //             'is_subscribed'=> null,
    //             'commission_percentage'=> 0,
    //             'discount_amount_by_restaurant' => $order->restaurant_discount_amount,
    //             // for subscription order
    //             'is_subscription' => 0 ,
    //         ]);

    //         return true;
    //     } catch (\Throwable $th) {
    //         dd($th->getMessage());
    //        return false;
    //     }
    // }

    public static function order_transaction($orderId, $received_by = null , $delivery_by = null)
    {
        try {
            DB::beginTransaction();
            // Fetch the order with related restaurant and details
            $order = Order::with(['restaurant', 'orderCalculationStmt'])->find($orderId);
            $customerData = json_decode($order->orderCalculationStmt->customerData) ;
            $restaurantData = json_decode($order->orderCalculationStmt->restaurantData) ;
            $adminData = json_decode($order->orderCalculationStmt->adminData) ;

            // $deliveryMan = $order->delivery_man??DeliveryMan::find($order->delivery_man_id);

            // $ADMIN = Helpers::getAdmin();

            // Initialize variables
            /* IGNORE IT
            if ($order->restaurant->subscription_type == 'commission') {

                if ($order->restaurant->commission > 0) {
                    $adminCommissionByRestaurant += $order->restaurant->commission;
                } else {
                    $commissionPercent = ZoneBusinessSetting::getSettingValue('admin_commission', $order->getZoneId());
                    if ($commissionPercent > 0) {
                        $adminCommissionByRestaurant += ($total_food_price * $commissionPercent) / 100;
                    }
                }
            } */


            // Define the data array for OrderTransaction insertion
            $data = [
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'delivery_man_id' => $order->delivery_man_id,
                'free_delivery' => $customerData->deliveryCharge < 1 ,
                'order_amount' => floor($customerData->billingTotal),
                'gst_amount' => $customerData->gstAmount,
                'gst_percent' =>  $customerData->gstPercent,
                'platform_charge' =>$customerData->platformCharge ,
                'dm_tips' => $customerData->dm_tips,

                'delivery_charge' => $customerData->deliveryCharge,
                'packing_charge' => $restaurantData->sumofPackingCharge,
                'restaurant_earning' => $restaurantData->earning,
                'restaurant_gst_amount' => $restaurantData->gstAmount,
                'restaurant_receivable_amount' =>$restaurantData->receivableAmount ,
                'admin_commission_amount' => $adminData->comissionAmount,
                'admin_earning' => $adminData->earning,
                'admin_gst_amount' =>$adminData->gstAmount ,
                'admin_receivable_amount' => $adminData->receivableAmount,
                'customer_data' => $order->orderCalculationStmt->customerData,
                'restaurant_data' => $order->orderCalculationStmt->restaurantData,
                'admin_data' => $order->orderCalculationStmt->adminData,
                'received_by' => $received_by ?? $order->customer->f_name,
                'zone_id' => $order->restaurant->zone_id,
                'status' => true,
                'delivery_service_provider' => 'admin',

            ];


          $orderTxn = OrderTransaction::create($data);
          DB::commit();
          return self::order_transaction_based_amount_transfer($orderTxn->id , $order , $delivery_by);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public static function order_transaction_based_amount_transfer($order_transaction_id, Order $order, $delivery_by = null)
    {
        // Fetch the order transaction
        $orderTxn = OrderTransaction::find($order_transaction_id);
        if (!$orderTxn) {
            throw new \Exception('Order transaction not found');
        }



        // Fetch wallets
        $dmWallet = Wallet::where('deliveryman_id', $order->delivery_man_id)->first();
        $adminFund = AdminFund::getFund();
        $vendorWallet = Wallet::where('vendor_id', $order->restaurant->vendor_id)->first();
        if(!$vendorWallet){
            $vendorWallet = new Wallet();
            $vendorWallet->vendor_id = $order->restaurant->vendor_id;
        }

        $deliveryMan = DeliveryMan::find($order->delivery_man_id);
        $ADMIN = Helpers::getAdmin(); // Assuming this fetches the admin details


        if(!$order->delivery_man_id){
            if (!$adminFund || !$vendorWallet ) {
                throw new \Exception('Wallets or delivery man not found');
            }
        }else{

            if (!$dmWallet || !$adminFund || !$vendorWallet || !$deliveryMan) {
                throw new \Exception('Wallets or delivery man not found');
            }
        }

        DB::beginTransaction();
        try {

            if($delivery_by == 'admin' && $order->payment_method == 'cash'){ // it need to work only when directly admin deliver order
                $adminFund->balance += $orderTxn->order_amount;
                $adminFund->txns()->create([
                    'amount' => $orderTxn->order_amount,
                    'txn_type' => 'received',
                    'received_from' => 'customer',
                    'customer_id' => $order->customer_id,
                    'remarks' => "Cash Order Amount Directlty Received From Customer: {$order->customer->f_name} ({$order->customer->phone}) for the Order No: #{$order->id}"
                ])->save();

                $adminFund->cashTxns()->create([
                    'amount' => $orderTxn->order_amount ,
                    'txn_type' => 'received',
                    'received_from' => 'customer',
                    'paid_to' => 'admin',
                    'customer_id' => $order->customer_id,
                    'remarks' => "Cash Order for the Order No: #{$order->id}"
                ]);
                $adminFund->save();
                // REFESH FUND TO SAVE BELOW TXNS
                $adminFund = AdminFund::getFund();

            }

            if ($deliveryMan && $deliveryMan->earning == 1) {
                // Transfer net delivery charge from admin fund to delivery man wallet
                $adminFund->balance -= $orderTxn->delivery_charge;
                $adminFund->txns()->create([
                    'amount' => $orderTxn->delivery_charge,
                    'txn_type' => 'paid',
                    'paid_to' => 'deliveryman',
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => "Delivery Charge Paid To {$deliveryMan->f_name} (Deliveryman) for the Order No: #{$order->id}"
                ])->save();

                $dmWallet->balance += $orderTxn->delivery_charge;
                $dmWallet->WalletTransactions()->create([
                    'amount' => $orderTxn->delivery_charge,
                    'admin_id' => $ADMIN->id,
                    'type' => 'received',
                    'remarks' => 'Delivery Charge Accepted From Admin',
                ])->save();
            }

            if ($orderTxn->dm_tips > 0 && $deliveryMan != null) {
                // Transfer delivery man tips from admin fund to delivery man wallet
                $adminFund->balance -= $orderTxn->dm_tips;
                $adminFund->txns()->create([
                    'amount' => $orderTxn->dm_tips,
                    'txn_type' => 'paid',
                    'paid_to' => 'deliveryman',
                    'deliveryman_id' => $deliveryMan->id,
                    'remarks' => "Delivery Man Tips Paid To {$deliveryMan->f_name} (Deliveryman) for the Order No: #{$order->id}"
                ])->save();

                $dmWallet->balance += $orderTxn->dm_tips;
                $dmWallet->WalletTransactions()->create([
                    'amount' => $orderTxn->dm_tips,
                    'admin_id' => $ADMIN->id,
                    'type' => 'received',
                    'remarks' => 'Delivery Tips for Order No: #'.$order->id,
                ])->save();
            }

            // Transfer restaurant amount to vendor wallet
            $adminFund->balance -= $orderTxn->restaurant_receivable_amount;
            $adminFund->txns()->create([
                'amount' => $orderTxn->restaurant_receivable_amount,
                'txn_type' => 'paid',
                'paid_to' => 'vendor',
                'vendor_id' => $order->restaurant->vendor_id,
                'restaurant_id' => $order->restaurant->id,
                'remarks' => "Restaurant \"" . ucfirst($order->restaurant->name) . "\" Order Amount paid for the Order No: #{$order->id}"
            ])->save();

            $vendorWallet->balance += $orderTxn->restaurant_receivable_amount;
            $vendorWallet->WalletTransactions()->create([
                'amount' => $orderTxn->restaurant_receivable_amount,
                'admin_id' => $ADMIN->id,
                'type' => 'received',
                'restaurant_id' =>  $order->restaurant->id,
                'remarks' => "Restaurant \"" . ucfirst($order->restaurant->name) . "\" Order Amount Accepted for the Order No: #{$order->id}",
            ])->save();
            $adminFund->save();
            $vendorWallet->save();
            $dmWallet->save();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

}
