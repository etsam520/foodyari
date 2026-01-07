<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundReason extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'status',
        'created_by',
        'user_type' // admin, customer, restaurant, delivery_man
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    public function scopeForCustomer($query)
    {
        return $query->whereIn('user_type', ['customer', 'admin']);
    }

    public function scopeForRestaurant($query)
    {
        return $query->whereIn('user_type', ['restaurant', 'admin']);
    }
}
