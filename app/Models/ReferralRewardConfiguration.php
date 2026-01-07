<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralRewardConfiguration extends Model
{
    protected $fillable = [
        'order_limit',
        'user_reward_type',
        'sponsor_reward_type',
        'user_discount_type',
        'sponsor_discount_type',
        'user_reward_value',
        'sponsor_reward_value',
        'max_amount',
        'is_active'
    ];

    protected $casts = [
        'user_reward_value' => 'decimal:2',
        'sponsor_reward_value' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function userRewards()
    {
        return $this->hasMany(ReferralUserReward::class, 'configuration_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
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

    public function scopeOrderByCount($query)
    {
        return $query->orderBy('order_limit', 'asc');
    }
}
