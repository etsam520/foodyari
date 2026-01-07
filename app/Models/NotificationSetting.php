<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'order_notifications',
        'customer_notifications',
        'restaurant_notifications',
        'delivery_notifications',
        'system_notifications',
        'email_notifications',
        'sound_notifications',
    ];

    protected $casts = [
        'order_notifications' => 'boolean',
        'customer_notifications' => 'boolean',
        'restaurant_notifications' => 'boolean',
        'delivery_notifications' => 'boolean',
        'system_notifications' => 'boolean',
        'email_notifications' => 'boolean',
        'sound_notifications' => 'boolean',
    ];

    /**
     * Get the user that owns the notification settings
     */
    public function user()
    {
        return $this->morphTo();
    }

    /**
     * Get settings for admin
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'user_id')->where('user_type', 'admin');
    }

    /**
     * Get settings for customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id')->where('user_type', 'customer');
    }

    /**
     * Get settings for restaurant
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'user_id')->where('user_type', 'restaurant');
    }

    /**
     * Get settings for delivery man
     */
    public function deliveryMan()
    {
        return $this->belongsTo(DeliveryMan::class, 'user_id')->where('user_type', 'delivery_man');
    }

    /**
     * Check if a specific notification type is enabled
     */
    public function isEnabled($type)
    {
        return $this->{$type . '_notifications'} ?? true;
    }

    /**
     * Get default settings array
     */
    public static function getDefaults()
    {
        return [
            'order_notifications' => true,
            'customer_notifications' => true,
            'restaurant_notifications' => true,
            'delivery_notifications' => true,
            'system_notifications' => true,
            'email_notifications' => true,
            'sound_notifications' => true,
        ];
    }
}