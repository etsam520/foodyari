<?php
namespace App\Http\Controllers\User\Restaurant\apparatusReferral;

use App\Models\Referral;
use App\Models\ReferralUserReward;
use Illuminate\Support\Facades\Log;

class ReferralPostOrderProcess
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function unlockReward() : void
    {
        try {
            $rewardCore =  ReferralUserReward::where('user_id', $this->userId)
                ->where('is_unlocked', false)
                ->where('is_user_claimed', false)
                ->where('is_sponsor_claimed', false);

            // Increment order count for all applicable rewards
            $updatedCount = $rewardCore->increment('user_current_orders');
            Log::info("ReferralPostOrderProcess: Incremented user_current_orders for user {$this->userId}, affected rewards: {$updatedCount}");
            
            // Check if any rewards should be unlocked
            $rewards = ReferralUserReward::where('user_id', $this->userId)
                ->where('is_unlocked', false)
                ->where('is_user_claimed', false)
                ->where('is_sponsor_claimed', false)
                ->whereRaw('user_current_orders >= order_limit')
                ->get();

            foreach ($rewards as $reward) {
                $reward->is_unlocked = true;
                $reward->unlocked_at = now();
                $reward->save();
                
                Log::info("ReferralPostOrderProcess: Unlocked reward ID {$reward->id} for user {$this->userId}. Current orders: {$reward->user_current_orders}, Required: {$reward->order_limit}");
            }

        } catch (\Exception $e) {
            Log::error("ReferralPostOrderProcess: Error processing rewards for user {$this->userId}: " . $e->getMessage());
            throw $e;
        }
    }

    public function markRewardAsUsed(int $rewardId, $userId) : bool
    {
        if($userId !== $this->userId) return false;
        if($rewardId == 0) return false;
        $reward = ReferralUserReward::find($rewardId);
        if(!$reward) return false;

        if($reward->user_id === $this->userId){
            $reward->is_user_used = true;
            $reward->user_used_at = now();
            $reward->save();
            return true;
        }else if($reward->sponsor_id === $this->userId){
            $reward->is_sponsor_used = true;
            $reward->sponsor_used_at = now();
            $reward->save();
            return true;
        }
        return false;
    }
}