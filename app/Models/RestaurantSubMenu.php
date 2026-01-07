<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSubMenu extends Model
{
    use HasFactory;
    protected $fillable = ['restaurant_menu_id','custom_id','restaurant_id',
    'position','name','image','status','details'];

    public function menu()
    {
       return $this->belongsTo(RestaurantMenu::class,'restaurant_menu_id');
    }

    public function foods(){
        return $this->hasMany(Food::class);
    }

    public function scopeIsActive($query, $status= true)
    {
        return $query->where('status',$status);
    }
}
