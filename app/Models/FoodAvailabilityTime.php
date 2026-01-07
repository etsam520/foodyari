<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodAvailabilityTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'day',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Scope to get availability times for a specific day
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day', strtolower($day));
    }

    /**
     * Check if the current time falls within this availability window
     */
    public function isCurrentlyAvailable()
    {
        $now = now()->format('H:i');
        $start = $this->start_time instanceof \Carbon\Carbon ? $this->start_time->format('H:i') : $this->start_time;
        $end = $this->end_time instanceof \Carbon\Carbon ? $this->end_time->format('H:i') : $this->end_time;
        
        return $now >= $start && $now <= $end;
    }
}
