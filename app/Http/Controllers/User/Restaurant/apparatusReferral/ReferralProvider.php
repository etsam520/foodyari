<?php
namespace App\Http\Controllers\User\Restaurant\apparatusReferral;
use App\Models\ReferralUserReward;

class ReferralProvider
{
    protected $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }


    /**
     * Get all rewards for the user
     * @return array [<array of rewards>]
     */
    public function getRewards() : array
    {
        return array_values(array_merge($this->userRewards(), $this->sponsorRewards()));
    }

    private function sponsorRewards() : array
    {
        $_rewards = ReferralUserReward::where('sponsor_id', $this->userId)
            ->where('is_unlocked', true)
            ->where('is_sponsor_claimed', true)
            ->where('is_sponsor_used', false)
            ->get()->toArray();
        return array_map(function($reward) {
            // return $reward;
            return [
                'id' => $reward['id'],
                'reward_of' => 'sponsor',
                'reward_type' => $reward['sponsor_reward_type'],
                'discount_type' => $reward['sponsor_discount_type'],
                'reward_value' => $reward['sponsor_reward_value'],
                'max_amount' => $reward['max_amount'],
                'is_unlocked' => $reward['is_unlocked'],
                'unlocked_at' => $reward['unlocked_at'],
                'claimed_at' => $reward['sponsor_claimed_at'],
                'is_used' => $reward['is_sponsor_used'],
                'used_at' => $reward['sponsor_used_at']
            ];
        }, $_rewards);
    }

    public function userRewards() : array
    {
        $_rewards = ReferralUserReward::where('user_id', $this->userId)
            ->where('is_unlocked', true)
            ->where('is_user_claimed', true)
            ->where('is_user_used', false)
            ->get()->toArray();
        return array_map(function($reward) {
            return [
                'id' => $reward['id'],
                'reward_of' => 'beneficiary',
                'reward_type' => $reward['user_reward_type'],
                'discount_type' => $reward['user_discount_type'],
                'reward_value' => $reward['user_reward_value'],
                'max_amount' => $reward['max_amount'],
                'is_unlocked' => $reward['is_unlocked'],
                'unlocked_at' => $reward['unlocked_at'],
                'claimed_at' => $reward['user_claimed_at'],
                'is_used' => $reward['is_user_used'],
                'used_at' => $reward['user_used_at']
            ];
        }, $_rewards);

    }

}