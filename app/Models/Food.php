<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'category_id',
        'category_ids',
        'variations',
        'isCustomize',
        'isRecommended',
        'add_ons',
        'attributes',
        'choice_options',
        'price',
        'restaurant_price',
        'admin_margin',
        'tax',
        'tax_type',
        'discount',
        'discount_type',
        'discount_by',
        'available_time_starts',
        'available_time_ends',
        'set_menu',
        'type',
        'status',
        'position',
        'restaurant_id',
        'category_id',
        'restaurant_id',
        'restaurant_menu_id',
        'restaurant_submenu_id',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id');
    }

    public function menu()
    {
        return $this->belongsTo(RestaurantMenu::class, 'restaurant_menu_id');
    }

    public function submenu()
    {
        return $this->belongsTo(RestaurantSubMenu::class, 'restaurant_submenu_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function scopeIsActive($query, $active = true)
    {
        return $query->where('status', $active);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(Customer::class, 'favorites', 'food_id', 'customer_id');
    }

    public function collectionItems()
    {
        return $this->morphToMany(Collection::class, 'item');
    }

    public function availabilityTimes()
    {
        return $this->hasMany(FoodAvailabilityTime::class);
    }

    /**
     * Check if food is available at current time
     */
    public function isAvailableNow()
    {
        $currentDay = strtolower(now()->format('l'));
        $currentTime = now()->format('H:i');
        
        $availableTimes = $this->availabilityTimes()->forDay($currentDay)->get();
        
        
        // If no availability times set, use default available_time_starts/ends
        if ($availableTimes->isEmpty()) {
            if ($this->available_time_starts && $this->available_time_ends) {
                return $currentTime >= $this->available_time_starts && $currentTime <= $this->available_time_ends;
            }
            return true; // Available all day if no restrictions
        }
        
        // Check if current time falls within any availability window for today
        return $availableTimes->some(function ($timeWindow) {
            return $timeWindow->isCurrentlyAvailable();
        });
    }

    /**
     * Get availability times for a specific day
     */
    public function getAvailabilityForDay($day)
    {
        return $this->availabilityTimes()->forDay($day)->orderBy('start_time')->get();
    }

   
}
