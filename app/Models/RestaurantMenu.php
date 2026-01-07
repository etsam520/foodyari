<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'custom_id',
        'restaurant_id',
        'name',
        'position',
        'image',
        'status',
        'details',
    ];

    public function restaurant(){
        return $this->belongsTo(Restaurant::class);
    }
    public function foods(){
        return $this->hasMany(Food::class);
    }

    public function submenu(){
        return $this->hasMany(RestaurantSubMenu::class);
    }
    
    public function scopeIsActive($query, $status= true)
    {
        return $query->where('status',$status);
    }
}
