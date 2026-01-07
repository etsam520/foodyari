<?php
namespace App\Http\Controllers\User\Restaurant\apparatusReferral;

use App\CentralLogics\Helpers;
use App\CentralLogics\Restaurant\BillingMaker;
use App\Models\ReferralUserReward;

class ReferralPreOrderProcess
{
  public $reward = null;
  protected $billingMaker;

    public function __construct(BillingMaker $billingMaker)
    {
        $rewardId = Helpers::getOrderSessions($billingMaker->userId, 'referral_user_reward_id');
        $this->reward = ReferralUserReward::find($rewardId);
        $this->billingMaker = $billingMaker;
    }

    public function applyPreOrder() : void
    {
        $reward = $this->reward;
        if(!$reward) return ;
        // dd($reward->user_id, $this->billingMaker->userId, $reward->sponsor_id);
        if($reward->user_id === $this->billingMaker->userId ){
            $this->processRewardAsBeneficiary($reward);
        }else if($reward->sponsor_id === $this->billingMaker->userId){
            $this->processRewardAsSponser($reward);
        }
    }



    private function processRewardAsBeneficiary(ReferralUserReward $reward) : void
    {
        $billingMaker = $this->billingMaker;
        $discountAmount= 0;
        if((string) $billingMaker->userId === (string) $reward->user_id && $reward->is_user_claimed && $reward->is_unlocked && !$reward->is_user_used){
            if($reward->user_reward_type === 'discount'){
                $billingMaker->referralDiscount = (float) $reward->user_reward_value;
                if($reward->user_discount_type === 'flat'){
                    $discountAmount = (float) $reward->user_reward_value;
                }elseif($reward->user_discount_type === 'percentage'){
                    $discountAmount = ((float) $billingMaker->sumOfFoodPrice * (float) $reward->user_reward_value) / 100;
                    if((float) $reward->max_amount && (float) $discountAmount > (float) $reward->max_amount){
                        $discountAmount =  (float) $reward->max_amount;
                    }
                }
                $billingMaker->referralDiscountType = (string) $reward->user_reward_type;
                $billingMaker->referralDiscountAmount = $discountAmount;

            }
        }
    }

    function processRewardAsSponser(ReferralUserReward $reward) : void
    {
        $billingMaker = $this->billingMaker;
        $discountAmount= 0;
        if((string) $billingMaker->userId === (string) $reward->sponsor_id && $reward->is_sponsor_claimed && $reward->is_unlocked && !$reward->is_sponsor_used){
            if($reward->sponsor_reward_type === 'discount'){
                $billingMaker->referralDiscount = (float) $reward->sponsor_reward_value;
                if($reward->sponsor_discount_type === 'flat'){
                    $discountAmount = (float) $reward->sponsor_reward_value;
                }elseif($reward->sponsor_discount_type === 'percentage'){
                    $discountAmount = ((float) $billingMaker->sumOfFoodPrice * (float) $reward->sponsor_reward_value) / 100;
                    if((float) $reward->max_amount && (float) $discountAmount > (float) $reward->max_amount){
                        $discountAmount =  (float) $reward->max_amount;
                    }
                }
                $billingMaker->referralDiscountType = (string) $reward->sponsor_reward_type;
                $billingMaker->referralDiscountAmount = $discountAmount;

            }
        }
    }

    


}