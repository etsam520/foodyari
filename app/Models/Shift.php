<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'zone_id',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
        'zone_id' => 'integer',
    ];

    /**
     * Get the zone that owns the shift
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
