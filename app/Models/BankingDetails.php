<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankingDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_number',
        'bank_name',
        'ifsc_code',
        'account_holder_name',
        'upi_id',
        'data',
        'vendor_id',
        'admin_id',
        'deliveryman_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    // Track original attributes for change detection
    protected $originalAttributes = [];

    protected static function booted()
    {
        // Store original values when model is retrieved
        static::retrieved(function ($model) {
            $model->originalAttributes = $model->getAttributes();
        });

        // Log creation
        static::created(function ($model) {
            $model->logHistory('created', null, $model->getAttributes());
        });

        // Log updates
        static::updated(function ($model) {
            $changedFields = [];
            $oldData = [];
            $newData = [];

            foreach ($model->getDirty() as $field => $newValue) {
                $oldValue = $model->getOriginal($field);
                if ($oldValue !== $newValue) {
                    $changedFields[] = $field;
                    $oldData[$field] = $oldValue;
                    $newData[$field] = $newValue;
                }
            }

            if (!empty($changedFields)) {
                $model->logHistory('updated', $oldData, $newData, $changedFields);
            }
        });

        // Log deletion
        static::deleted(function ($model) {
            $model->logHistory('deleted', $model->getAttributes(), null);
        });
    }

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function histories()
    {
        return $this->hasMany(BankingDetailsHistory::class)->orderBy('created_at', 'desc');
    }

    // Helper method to log history
    private function logHistory($actionType, $oldData = null, $newData = null, $changedFields = null)
    {
        BankingDetailsHistory::create([
            'banking_details_id' => $this->id,
            'vendor_id' => $this->vendor_id,
            'action_type' => $actionType,
            'old_data' => $oldData,
            'new_data' => $newData,
            'changed_fields' => $changedFields,
            'changed_by_type' => 'vendor', // Default to vendor, can be overridden
            'changed_by_id' => session('restaurant')?->vendor_id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
