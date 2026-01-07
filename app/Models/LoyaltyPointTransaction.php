<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyPointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'points',
        'type',
        'amount',
        'description'
    ];

    protected $casts = [
        'points' => 'decimal:2',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the customer that owns the loyalty point transaction.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order that this transaction relates to.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope for earned points
     */
    public function scopeEarned($query)
    {
        return $query->where('type', 'earned');
    }

    /**
     * Scope for redeemed points
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', 'redeemed');
    }

    /**
     * Scope for expired points
     */
    public function scopeExpired($query)
    {
        return $query->where('type', 'expired');
    }
}
