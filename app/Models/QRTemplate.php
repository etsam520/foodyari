<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QRTemplate extends Model
{
    use HasFactory;

    protected $table = 'qr_templates';

    protected $fillable = [
        'name',
        'zone_id',
        'template_data',
        'background_type',
        'background_value',
        'status',
        'is_default',
        'created_by'
    ];

    protected $casts = [
        'template_data' => 'array',
        'status' => 'boolean',
        'is_default' => 'boolean'
    ];

    // Relationships
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeForZone($query, $zoneId)
    {
        return $query->where('zone_id', $zoneId);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Accessors
    public function getBackgroundUrlAttribute()
    {
        if ($this->background_type === 'image' && $this->background_value) {
            return asset('storage/qr-templates/backgrounds/' . $this->background_value);
        }
        return null;
    }

    public function getPreviewUrlAttribute()
    {
        return route('admin.qr-template.preview', $this->id);
    }
}
