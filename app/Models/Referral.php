<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referral_code',
        'sponsor_id',
        'total_uses',
        'last_used_at',
        'is_active'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function sponsor()
    {
        return $this->belongsTo(Customer::class, 'sponsor_id');
    }

    public function uses()
    {
        return $this->hasMany(ReferralUse::class);
    }

    public function beneficiaries()
    {
        return $this->belongsToMany(Customer::class, 'referral_uses', 'referral_id', 'beneficiary_id')
            ->withPivot('used_at')
            ->withTimestamps();
    }

    public function userRewards()
    {
        return $this->hasMany(ReferralUserReward::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUnused($query)
    {
        return $query->where('total_uses', 0);
    }

    public function scopeUsed($query)
    {
        return $query->where('total_uses', '>', 0);
    }

    // Check if a specific user has already used this referral code
    public function isUsedBy($userId)
    {
        return $this->uses()->where('beneficiary_id', $userId)->exists();
    }

    // Add a new user to this referral
    public function addUser($userId)
    {
        if ($this->isUsedBy($userId)) {
            return false; // User already used this code
        }

        $this->uses()->create([
            'beneficiary_id' => $userId,
            'used_at' => now()
        ]);

        $this->increment('total_uses');
        $this->update(['last_used_at' => now()]);

        return true;
    }

    public static function generateUniqueCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
}
