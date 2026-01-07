<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralUserReward extends Model
{
    protected $fillable = [
        'sponsor_id',
        'user_id',
        'referral_id', 
        'referral_use_id',
        'order_limit',
        'user_reward_type',
        'sponsor_reward_type',
        'user_discount_type',
        'sponsor_discount_type',
        'user_reward_value',
        'sponsor_reward_value',
        'max_amount',
        'user_current_orders',
        'is_unlocked',
        'is_user_claimed',
        'is_sponsor_claimed',
        'unlocked_at',
        'user_claimed_at',
        'sponsor_claimed_at'
    ];

    protected $casts = [
        'user_reward_value' => 'decimal:2',
        'sponsor_reward_value' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_unlocked' => 'boolean',
        'is_user_claimed' => 'boolean',
        'is_sponsor_claimed' => 'boolean',
        'unlocked_at' => 'datetime',
        'user_claimed_at' => 'datetime',
        'sponsor_claimed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(Customer::class, 'sponsor_id');
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function referralUse()
    {
        return $this->belongsTo(ReferralUse::class);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_unlocked', true);
    }

    public function scopeUserUnclaimed($query)
    {
        return $query->where('is_user_claimed', false);
    }

    public function scopeSponsorUnclaimed($query)
    {
        return $query->where('is_sponsor_claimed', false);
    }

    public function scopeUserAvailable($query)
    {
        return $query->where('is_unlocked', true)->where('is_user_claimed', false);
    }

    public function scopeSponsorAvailable($query)
    {
        return $query->where('is_unlocked', true)->where('is_sponsor_claimed', false);
    }

    public function checkAndUnlock()
    {
        if (!$this->is_unlocked && $this->user_current_orders >= $this->order_limit) {
            $this->update([
                'is_unlocked' => true,
                'unlocked_at' => now()
            ]);
            return true;
        }
        return false;
    }

    public function claimUserReward($rewardType="discount")
    {
        if ($this->is_unlocked && !$this->is_user_claimed) {
            $this->update([
                'is_user_claimed' => true,
                'user_claimed_at' => now(),
                'is_user_used' => preg_match('/cashback/', $rewardType) ? true : false,
                'user_used_at' => preg_match('/cashback/', $rewardType) ? now() : null
            ]);
            return true;
        }
        return false;
    }

    public function claimSponsorReward($rewardType="discount")
    {
        if ($this->is_unlocked && !$this->is_sponsor_claimed) {
            $this->update([
                'is_sponsor_claimed' => true,
                'sponsor_claimed_at' => now(),
                'is_sponsor_used' => preg_match('/cashback/', $rewardType) ? true : false,
                'sponsor_used_at' => preg_match('/cashback/', $rewardType) ? now() : null
            ]);
            return true;
        }
        return false;
    }

    // Helper methods to get reward details for specific user types
    public function getUserRewardDetails()
    {
        return [
            'reward_type' => $this->user_reward_type,
            'discount_type' => $this->user_discount_type,
            'amount' => $this->user_reward_value,
            'max_amount' => $this->max_amount
        ];
    }

    public function getSponsorRewardDetails()
    {
        return [
            'reward_type' => $this->sponsor_reward_type,
            'discount_type' => $this->sponsor_discount_type,
            'amount' => $this->sponsor_reward_value,
            'max_amount' => $this->max_amount
        ];
    }
}
