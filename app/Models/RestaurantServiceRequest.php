<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
    'restaurant_id','image','pdf','excel','attachement','restaurant_remarks','admin_remarks','status','pending','approve','reject',
    ];

    public function restaurant()
    {
       return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }


}
