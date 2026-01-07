<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'position',
        'status',
        'category_id',
        'restaurant_id'
    ];

    

    // public function restaurant()
    // {
    //     return $this->belongsTo(Restaurant::class);
    // }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
    
    public function subCategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function scopeParentCategories($query)
    {
        return $query->whereNull('category_id')->where('position',1);
    }
    public function scopeIsActive($query, $active)
    {
        return $query->where('status',$active);
    }

    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('category_id');
    }

    public static function getSubCategoriesWithParents()
    {
        return self::whereNotNull('category_id')->with('parentCategory')->get();
    }

    public function scopeCategoriesByzonesHavingAtLeastOneProduct($query, $zoneId){
        return $query->whereExists(function ($q) use ($zoneId) {
            $q->select(DB::raw(1))
                ->from('food')
                ->join('restaurants', 'restaurants.id', '=', 'food.restaurant_id')
                ->whereColumn('food.category_id', 'categories.id')
                ->where('restaurants.zone_id', $zoneId);
        });
        
    }
}
