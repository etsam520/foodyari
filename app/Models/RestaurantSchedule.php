<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSchedule extends Model
{
    protected $table = 'restaurant_schedule';
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'day',
        'opening_time',
        'closing_time'
    ];


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
