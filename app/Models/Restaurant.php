<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Restaurant extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'restaurant_no',
        'name',
        'phone',
        'email',
        'password',
        'logo',
        'cover_photo',
        'radius',
        'address',
        'minimum_order',
        'delivery_charge',
        'commission',
        'currency',
        'badges',
        'description',
        'tax',
        'opening_time',
        'closing_time',
        'delivery_time',
        'position',
        'self_delivery_system',
        'pos_system',
        'cash_on_delivery',
        'status',
        'vendor_id',
        'off_day',
        'ready_to_handover',
        'minimum_shipping_charge',
        'maximum_shipping_charge',
        'per_km_shipping_charge',
        'gst',
        'subscription_type',
        'zone_id',
        'coordinates',
        'latitude',
        'longitude',
        'is_blocked',
        'blocked_at',
        'blocked_reason',
        'fcm_token',
        'rating',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeNotBlocked($query)
    {
        return $query->where('is_blocked', 0);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'restaurant_category');
    }



    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function foods()
    {
        return $this->hasMany(Food::class);
    }
    public function zone()
    {
        return $this->belongsTo(Zone::class,'zone_id');
    }

    public function schedules()
    {
        return $this->hasMany(RestaurantSchedule::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function collectionItems()
    {
        return $this->morphToMany(Collection::class, 'item');
    }

    public function scopeIsActive($query, $active=true)
    {
        return $query->where('status',$active);
    }




    public function scopeNearby($query, $latitude, $longitude)
    {
        $haversine = "(6371 * acos(cos(radians($latitude)) * cos(radians(latitude)) * cos(radians(longitude) - radians($longitude)) + sin(radians($latitude)) * sin(radians(latitude))))";

        return $query->fromSub(function ($query) use ($haversine) {
            $query->select('restaurants.*')
                ->selectRaw("{$haversine} AS distance")
                ->from('restaurants');
        }, 'restaurants')
        ->whereRaw('distance < radius')
        // ->orderBy('distance')
        ;
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(Customer::class, 'favorites', 'restaurant_id', 'customer_id');
    }

    public function routeNotificationForFcm()
    {

        return $this->fcm_token;
    }


}
