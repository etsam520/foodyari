<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\LoyaltyPointTransaction;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\AdminFund;
use App\Models\ZoneBusinessSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoyaltyPointService
{
    /**
     * Award loyalty points when an order is completed
     */
    public static function awardPointsOnOrderCompletion(Order $order)
    {
        try {
            // Get loyalty settings
            $loyaltyPercent = ZoneBusinessSetting::getSettingValue('loyalty_percent', $order->getZoneId());
            $loyaltyPercent = $loyaltyPercent ? (float)$loyaltyPercent->value : 0;

            // If loyalty percentage is 0, don't award points
            if ($loyaltyPercent <= 0) {
                return false;
            }

            // Calculate points based on order amount
            $pointsToAward = ((float)$order->order_amount * $loyaltyPercent )/ 100;

            if ($pointsToAward <= 0) {
                return false;
            }

            // Round to 2 decimal places
            $pointsToAward = round($pointsToAward, 2);

            DB::beginTransaction();

            // Update customer's loyalty points
            $customer = Customer::find($order->customer_id);
            if (!$customer) {
                DB::rollBack();
                return false;
            }

            $customer->loyalty_points += $pointsToAward;
            $customer->save();

            // Create transaction record
            LoyaltyPointTransaction::create([
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'points' => $pointsToAward,
                'type' => 'earned',
                'description' => "Points earned from order #{$order->id}"
            ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error awarding loyalty points: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Redeem loyalty points to wallet
     */
    public static function redeemPointsToWallet($customerId, $pointsToRedeem)
    {
        try {
            // Get loyalty settings
            $zoneId = Order::where('customer_id', $customerId)->leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
             ->where('orders.order_status', 'delivered')
             ->where('orders.payment_status', 'paid')
             ->orderBy('orders.created_at', 'desc')
             ->limit(1)
             ->value('restaurants.zone_id');
            $loyaltyValue = ZoneBusinessSetting::getSettingValue('loyalty_value', $zoneId)->first();
            $loyaltyValue = $loyaltyValue ? (float)$loyaltyValue->value : 1;

            $minimumRedeemValue = ZoneBusinessSetting::getSettingValue('minimum_redeem_value', $zoneId)->first();
            $minimumRedeemValue = $minimumRedeemValue ? (float)$minimumRedeemValue->value : 10;

            // Calculate currency amount
            $currencyAmount = $pointsToRedeem * $loyaltyValue;

            // Check minimum redeem value
            if ($currencyAmount < $minimumRedeemValue) {
                return [
                    'success' => false,
                    'message' => "Minimum redeem value is " . \App\CentralLogics\Helpers::format_currency($minimumRedeemValue)
                ];
            }

            DB::beginTransaction();

            // Get customer
            $customer = Customer::find($customerId);
            if (!$customer) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Customer not found'
                ];
            }

            // Check if customer has enough points
            if ($customer->loyalty_points < $pointsToRedeem) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Insufficient loyalty points'
                ];
            }

            // Deduct points from customer
            $customer->loyalty_points -= $pointsToRedeem;
            $customer->save();

            // Get or create customer wallet
            $wallet = Wallet::where('customer_id', $customerId)->first();
            if (!$wallet) {
                $wallet = Wallet::create([
                    'customer_id' => $customerId,
                    'balance' => 0
                ]);
            }

            // deduct from admin fund
            $adminFund = AdminFund::getFund();

            $adminFund->balance -= (float) $currencyAmount;
            $adminFund->txns()->create([
                'amount' => (float) $currencyAmount,
                'txn_type' => 'paid',
                'paid_to' => 'customer',
                'customer_id' => $customerId,
                'remarks' => "Loyalty points redemption for customer: {$customer->f_name} {$customer->l_name}($customer->phone)"
            ]);
            $adminFund->save();

            // Add amount to wallet
            $wallet->balance += (float) $currencyAmount;
            $wallet->save();

            // Create wallet transaction
            $wallet->walletTransactions()->create([
                'amount' => (float) $currencyAmount,
                'type' => 'received',
                'customer_id' => $customerId,
                'remarks' => "Loyalty points redeemed: {$pointsToRedeem} points"
            ]);

            // Create loyalty point transaction
            LoyaltyPointTransaction::create([
                'customer_id' => $customerId,
                'points' => $pointsToRedeem,
                'type' => 'redeemed',
                'amount' => (float) $currencyAmount,
                'description' => "Points redeemed to wallet: {$pointsToRedeem} points = " . Helpers::format_currency($currencyAmount)
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully redeemed {$pointsToRedeem} points to wallet worth " .Helpers::format_currency($currencyAmount),
                'amount' => $currencyAmount
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error redeeming loyalty points: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to redeem points. Please try again.'
            ];
        }
    }

    /**
     * Get loyalty point history for a customer
     */
    public static function getLoyaltyPointHistory($customerId, $limit = 20)
    {
        return LoyaltyPointTransaction::where('customer_id', $customerId)
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Get loyalty settings as array
     */
    public static function getLoyaltySettings($customerId)
    {
        $zoneId = Order::where('customer_id', $customerId)->leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
             ->where('orders.order_status', 'delivered')
             ->where('orders.payment_status', 'paid')
             ->orderBy('orders.created_at', 'desc')
             ->limit(1)
             ->value('restaurants.zone_id');
        if(!$zoneId){
            $zoneId = 2; // default zone id
        }
        $loyaltyPercent = ZoneBusinessSetting::getSettingValue('loyalty_percent', $zoneId);
        $loyaltyValue = ZoneBusinessSetting::getSettingValue('loyalty_value', $zoneId);
        $minimumRedeemValue = ZoneBusinessSetting::getSettingValue('minimum_redeem_value', $zoneId);
        // dd($loyaltyPercent, $loyaltyValue, $minimumRedeemValue);
        return [
            'loyalty_percent' => $loyaltyPercent ? floatval($loyaltyPercent) : 0,
            'loyalty_value' => $loyaltyValue ? floatval($loyaltyValue) : 1,
            'minimum_redeem_value' => $minimumRedeemValue ? floatval($minimumRedeemValue) : 10,
        ];
    }

    /**
     * Calculate how many points customer will earn from order
     */
    public static function calculatePointsForOrder($orderAmount , $customerId = null)
    {
        if($customerId == null) return 0;
        $zoneId = Order::where('customer_id', $customerId)->leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
            ->where('orders.order_status', 'delivered')
            ->where('orders.payment_status', 'paid')
            ->orderBy('orders.created_at', 'desc')
            ->limit(1)
            ->value('restaurants.zone_id');
        $loyaltyPercent = ZoneBusinessSetting::getSettingValue('loyalty_percent', $zoneId);
        $loyaltyPercent = $loyaltyPercent ? (float)$loyaltyPercent->value : 0;

        if ($loyaltyPercent <= 0) {
            return 0;
        }

        return round(($orderAmount * $loyaltyPercent) / 100, 2);
    }

    /**
     * Calculate currency value of points
     */
    public static function calculateCurrencyValue($points, $customerId = null)
    {
        if($customerId == null) return 0;
        $zoneId = Order::where('customer_id', $customerId)->leftJoin('restaurants', 'orders.restaurant_id', '=', 'restaurants.id')
            ->where('orders.order_status', 'delivered')
            ->where('orders.payment_status', 'paid')
            ->orderBy('orders.created_at', 'desc')
            ->limit(1)
            ->value('restaurants.zone_id');
        $loyaltyValue = ZoneBusinessSetting::getSettingValue('loyalty_value', $zoneId)->first();
        $loyaltyValue = $loyaltyValue ? (float)$loyaltyValue->value : 1;

        return round($points * $loyaltyValue, 2);
    }
}
