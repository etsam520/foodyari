<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingDetailsHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'banking_details_id',
        'vendor_id',
        'action_type',
        'old_data',
        'new_data',
        'changed_fields',
        'changed_by_type',
        'changed_by_id',
        'ip_address',
        'user_agent',
        'remarks'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'changed_fields' => 'array',
    ];

    // Relationships
    public function bankingDetails()
    {
        return $this->belongsTo(BankingDetails::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    // Scopes
    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action_type', $action);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
