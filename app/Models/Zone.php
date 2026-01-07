<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coordinates',
        'radius',
        'status',
        'isTopOrders',
        'restaurant_wise_topic',
        'customer_wise_topic',
        'deliveryman_wise_topic',
        'max_cod_order_amount',
        'platform_charge',
        'platform_charge_original',
        'min_purchase',
        'delivery_verification',
    ];

    protected $casts = [
        'isTopOrders' => 'boolean',
    ];

    public function deliverymen()
    {
        return $this->hasMany(DeliveryMan::class);
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }

    public function messes()
    {
        return $this->hasMany(VendorMess::class, 'zone_id');
    }

    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function deliveryChargeSettings()
    {
        return $this->hasMany(ZoneDeliveryChargeSetting::class);
    }
    public function deliveryChargeSetting()
    {
        return $this->hasOne(ZoneDeliveryChargeSetting::class, 'zone_id')->where('is_active', true);
    }

    public function activeDeliveryChargeSetting()
    {
        return $this->hasOne(ZoneDeliveryChargeSetting::class, 'zone_id')->where('is_active', true);
    }

    public function businessSettings()
    {
        return $this->hasMany(ZoneBusinessSetting::class);
    }

    public function getBusinessSetting($key, $default = null)
    {
        return ZoneBusinessSetting::getSettingValue($key, $this->id, $default);
    }

    public function setBusinessSetting($key, $value)
    {
        return ZoneBusinessSetting::setSettingValue($key, $this->id, $value);
    }

    public function getAllBusinessSettings()
    {
        return ZoneBusinessSetting::getZoneSettings($this->id);
    }

    public function scopeIsActive($query, $status= true)
    {
        return $query->where('status',$status);
    }
}
