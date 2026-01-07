<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'refund_amount',
        'restaurant_deduction_amount',
        'restaurant_deduction_reason',
        'refund_reason',
        'refund_method',
        'refund_status',
        'processed_by',
        'processed_at',
        'refund_details',
        'admin_note',
        'customer_note',
        'transaction_reference',
        'refund_type'
    ];

    protected $casts = [
        'refund_details' => 'array',
        'processed_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'restaurant_deduction_amount' => 'decimal:2'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'processed_by');
    }

    public function restaurant()
    {
        return $this->hasOneThrough(\App\Models\Restaurant::class, Order::class, 'id', 'id', 'order_id', 'restaurant_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('refund_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('refund_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('refund_status', 'rejected');
    }

    public function scopeProcessed($query)
    {
        return $query->where('refund_status', 'processed');
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PROCESSED = 'processed';

    // Method constants
    const METHOD_WALLET = 'wallet';
    const METHOD_ORIGINAL_PAYMENT = 'original_payment';
    const METHOD_BANK_TRANSFER = 'bank_transfer';

    // Type constants
    const TYPE_FULL = 'full';
    const TYPE_PARTIAL = 'partial';
}
