<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralUse extends Model
{
    protected $fillable = [
        'referral_id',
        'beneficiary_id',
        'used_at'
    ];

    protected $casts = [
        'used_at' => 'datetime'
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function beneficiary()
    {
        return $this->belongsTo(Customer::class, 'beneficiary_id');
    }

    public function sponsor()
    {
        return $this->hasOneThrough(
            Customer::class,
            Referral::class,
            'id', // Foreign key on referrals table
            'id', // Foreign key on customers table
            'referral_id', // Local key on referral_uses table
            'sponsor_id' // Local key on referrals table
        );
    }
}
