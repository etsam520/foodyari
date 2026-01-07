<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'amount_paid',
        'payment_status',
        'payment_method',
        'transaction_reference',
        'payments_note',
        'delivery_man_id',
        'restaurant_id',
        'admin_id',
        'vendor_id',
        'banking_details_id',
        'pending',
        'accepted',
        'complete',
        'processing',
        'reject',
        'txn_id',
        'remarks',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
        'pending' => 'datetime',
        'accepted' => 'datetime',
        'complete' => 'datetime',
        'processing' => 'datetime',
        'reject' => 'datetime',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    public function vendor()
    {
       return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function bankingDetails()
    {
        return $this->belongsTo(BankingDetails::class, 'banking_details_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }
}
