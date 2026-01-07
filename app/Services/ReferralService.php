<?php

namespace App\Services;

use App\Models\AdminFund;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Referral;
use App\Models\ReferralRewardConfiguration;
use App\Models\ReferralUserReward;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralService
{
    /**
     * Process referral when a new user registers
     */
    public function processReferralRegistration($beneficiary, $referralCode)
    {
        try {
            DB::beginTransaction();

            // Find the referral record
            $referral = Referral::where('referral_code', $referralCode)
                ->active()
                ->first();

            if (!$referral) {
                return ['success' => false, 'message' => 'Invalid referral code'];
            }

            // Check if user already used this referral code
            if ($referral->isUsedBy($beneficiary->id)) {
                return ['success' => false, 'message' => 'You have already used this referral code'];
            }

            // Add user to referral
            if (!$referral->addUser($beneficiary->id)) {
                return ['success' => false, 'message' => 'Failed to apply referral code'];
            }

            // Update beneficiary's referred_by field (keep the first referrer for primary relationship)
            if (!$beneficiary->referred_by) {
                $beneficiary->update([
                    'referred_by' => $referral->sponsor_id
                ]);
            }

            // Get current active reward configurations
            $configurations = ReferralRewardConfiguration::active()->orderByCount()->get();

            // Create reward records for both sponsor and beneficiary
            // Use the specific referral use for reward tracking
            $referralUse = $referral->uses()->where('beneficiary_id', $beneficiary->id)->first();
            
            foreach ($configurations as $config) {
                // Create single reward record that includes both user and sponsor rewards
                $this->createUserReward($beneficiary->id, $referralUse, $config);
            }

            DB::commit();
            
            return [
                'success' => true, 
                'message' => 'Referral processed successfully',
                'referral' => $referral
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Referral processing failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to process referral'];
        }
    }

    /**
     * Create a user reward record with both user and sponsor rewards
     */
    private function createUserReward($userId, $referralUse, $configuration)
    {
        $userOrderCount = Order::where('customer_id', $userId)
            ->where('order_status', 'completed')
            ->count();
        
        // Get both user and sponsor reward details
        $userRewardDetails = $configuration->getUserRewardDetails();
        $sponsorRewardDetails = $configuration->getSponsorRewardDetails();
        
        return ReferralUserReward::create([
            'sponsor_id' => $referralUse->referral->sponsor_id,
            'user_id' => $userId,
            'referral_id' => $referralUse->referral_id,
            'referral_use_id' => $referralUse->id,
            'order_limit' => $configuration->order_limit,
            'user_reward_type' => $userRewardDetails['reward_type'],
            'sponsor_reward_type' => $sponsorRewardDetails['reward_type'],
            'user_discount_type' => $userRewardDetails['discount_type'],
            'sponsor_discount_type' => $sponsorRewardDetails['discount_type'],
            'user_reward_value' => $userRewardDetails['amount'],
            'sponsor_reward_value' => $sponsorRewardDetails['amount'],
            'max_amount' => $userRewardDetails['max_amount'],
            'user_current_orders' => $userOrderCount,
            'is_unlocked' => $userOrderCount >= $configuration->order_limit,
            'unlocked_at' => $userOrderCount >= $configuration->order_limit ? now() : null,
        ]);
    }

    /**
     * Create referral code for user
     */
    public function createReferralCode($userId)
    {
        $user = Customer::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        if ($user->referral_code) {
            return ['success' => true, 'code' => $user->referral_code];
        }

        $referralCode = Referral::generateUniqueCode();
        
        // Update user's referral code
        $user->update(['referral_code' => $referralCode]);
        
        // Create referral record
        $referral = Referral::create([
            'referral_code' => $referralCode,
            'sponsor_id' => $userId,
            'is_active' => true
        ]);

        return [
            'success' => true, 
            'code' => $referralCode,
            'referral' => $referral
        ];
    }

    /**
     * Update order count and check rewards
     */
    public function updateUserOrderCount($userId)
    {
        $user = Customer::find($userId);
        if (!$user) {
            return false;
        }

        $user->updateOrderCount();
        
        // Also update order count for sponsor if user was referred
        if ($user->referred_by) {
            $sponsor = Customer::find($user->referred_by);
            if ($sponsor) {
                $sponsor->updateOrderCount();
            }
        }

        return true;
    }

    /**
     * Claim a user reward (as beneficiary)
     */
    public function claimUserReward($userId, $rewardId)
    {
        try {
            DB::beginTransaction();

            $reward = ReferralUserReward::where('id', $rewardId)
                ->where('user_id', $userId)
                ->unlocked()
                ->userUnclaimed()
                ->first();

            if (!$reward) {
                return ['success' => false, 'message' => 'Reward not found or not eligible'];
            }

            $user = Customer::find($userId);
            $rewardDetails = $reward->getUserRewardDetails();
            
            // Process the reward based on type
            if ($rewardDetails['reward_type'] === 'cashback') {
                // Add to wallet
                $this->addWalletFunds($user, $rewardDetails['amount'], 'Referral User Cashback Reward');
            }

            // Mark user reward as claimed
            $reward->claimUserReward($rewardDetails['reward_type']);

            DB::commit();

            return [
                'success' => true,
                'message' => 'User reward claimed successfully',
                'reward' => $reward,
                'reward_details' => $rewardDetails
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User reward claim failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to claim user reward'];
        }
    }

    /**
     * Claim a sponsor reward (as sponsor)
     */
    public function claimSponsorReward($userId, $rewardId)
    {
        try {
            DB::beginTransaction();

            $reward = ReferralUserReward::where('id', $rewardId)
                ->where('sponsor_id', $userId)
                ->unlocked()
                ->sponsorUnclaimed()
                ->first();

            if (!$reward) {
                return ['success' => false, 'message' => 'Sponsor reward not found or not eligible'];
            }

            $user = Customer::find($userId);
            $rewardDetails = $reward->getSponsorRewardDetails();
            // dd($rewardDetails);
            
            // Process the reward based on type
            if ($rewardDetails['reward_type'] === 'cashback') {
                // Add to wallet
                $this->addWalletFunds($user, $rewardDetails['amount'], 'Referral Sponsor Cashback Reward');
            }

            // Mark sponsor reward as claimed
            $reward->claimSponsorReward($rewardDetails['reward_type']);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Sponsor reward claimed successfully',
                'reward' => $reward,
                'reward_details' => $rewardDetails
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sponsor reward claim failed: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to claim sponsor reward'];
        }
    }

    /**
     * Add funds to user wallet (you may need to adjust based on your wallet system)
     */
    private function addWalletFunds($user, $amount, $description)
    {
        $adminFund = AdminFund::getFund();
        $customerWallet = $user->wallet;
        if (!$customerWallet ) {
          $customerWallet = Wallet::create(['customer_id' => $user->id, 'balance' => 0]);
        }
        if ($adminFund && $customerWallet) {
            // Deduct from admin fund
            $adminFund->balance -= (double) $amount;
            $adminFund->save();
            $adminFund->txns()->create([
                'amount' => (double) $amount,
                'txn_type' => 'paid',
                'paid_to' => 'customer',
                'customer_id' => $user->id,
                'remarks' => $description . ' to ' . $user->f_name . ' ' . $user->l_name . "($user->phone) (Wallet Top-up)",
            ]);

            // Add to customer wallet
            $customerWallet->balance += (double) $amount;
            $customerWallet->save();

            // Create wallet transaction
            $customerWallet->walletTransactions()->create([
                'amount' => (double) $amount,
                'type' => 'received',
                'customer_id' => $user->id,
                'remarks' => $description.' (Wallet Top-up)',
            ]);
        }
    }

    /**
     * Get referral statistics for user
     */
    public function getReferralStats($userId)
    {
        $user = Customer::with(['sponsoredReferrals.uses', 'referralRewards'])->find($userId);
        
        if (!$user) {
            return null;
        }

        // Calculate total referrals across all referral codes
        $totalReferrals = $user->sponsoredReferrals->sum('total_uses');
        $activeReferrals = $user->sponsoredReferrals()->active()->count();

        $stats = [
            'referral_code' => $user->referral_code,
            'total_referrals' => $totalReferrals,
            'active_referral_codes' => $activeReferrals,
            // User rewards (as beneficiary)
            'user_rewards' => [
                'total' => $user->referralRewards()->count(),
                'unlocked' => $user->referralRewards()->unlocked()->count(),
                'claimed' => $user->referralRewards()->where('is_user_claimed', true)->count(),
                'available' => $user->getUnclaimedUserRewards()
            ],
            // Sponsor rewards (as sponsor)
            'sponsor_rewards' => [
                'total' => $user->sponsorRewards()->count(),
                'unlocked' => $user->sponsorRewards()->unlocked()->count(),
                'claimed' => $user->sponsorRewards()->where('is_sponsor_claimed', true)->count(),
                'available' => $user->getUnclaimedSponsorRewards()
            ],
            'successful_orders' => $user->successful_orders,
            'referred_by' => $user->referrer ? $user->referrer->full_name : null
        ];

        return $stats;
    }

    /**
     * Validate referral code
     */
    public function validateReferralCode($code, $excludeUserId = null)
    {
        $referral = Referral::where('referral_code', $code)
            ->active()
            ->with('sponsor')
            ->first();

        if (!$referral) {
            return ['valid' => false, 'message' => 'Invalid referral code'];
        }

        if ($excludeUserId && $referral->sponsor_id == $excludeUserId) {
            return ['valid' => false, 'message' => 'Cannot use your own referral code'];
        }

        if ($excludeUserId && $referral->isUsedBy($excludeUserId)) {
            return ['valid' => false, 'message' => 'You have already used this referral code'];
        }

        return [
            'valid' => true,
            'sponsor' => $referral->sponsor,
            'total_uses' => $referral->total_uses,
            'message' => 'Valid referral code'
        ];
    }
}
