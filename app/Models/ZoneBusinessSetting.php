<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ZoneBusinessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'key',
        'value'
    ];

    protected $casts = [
        'value' => 'string'
    ];

    /**
     * Relationship with Zone
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Get a zone-specific setting value
     */
    public static function getSettingValue(string $key, int $zoneId, $default = null)
    {
        $setting = self::where('zone_id', $zoneId)
                      ->where('key', $key)
                      ->first();
        
        if ($setting) {
            // Handle JSON values
            $decoded = json_decode($setting->value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $setting->value;
        }

        // Fallback to global business setting
        $globalSetting = BusinessSetting::where('key', $key)->first();
        if ($globalSetting) {
            $decoded = json_decode($globalSetting->value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $globalSetting->value;
        }

        return $default;
    }

    /**
     * Set a zone-specific setting value
     */
    public static function setSettingValue(string $key, int $zoneId, $value): bool
    {
        // Convert arrays and objects to JSON
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }

        $result = self::updateOrCreate(
            ['zone_id' => $zoneId, 'key' => $key],
            ['value' => $value]
        );

        return $result ? true : false;
    }

    /**
     * Get all settings for a zone
     */
    public static function getZoneSettings(int $zoneId): array
    {
        $zoneSettings = self::where('zone_id', $zoneId)->pluck('value', 'key')->toArray();
        
        // Get global settings as fallback
        $globalSettings = BusinessSetting::pluck('value', 'key')->toArray();
        
        // Merge with zone settings taking precedence
        $allSettings = array_merge($globalSettings, $zoneSettings);
        
        // Decode JSON values
        foreach ($allSettings as $key => $value) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $allSettings[$key] = $decoded;
            }
        }
        
        return $allSettings;
    }

    /**
     * Copy global business settings to a specific zone
     */
    public static function copyGlobalSettingsToZone(int $zoneId): void
    {
        $globalSettings = BusinessSetting::whereIn('key', self::getZoneConfigurableKeys())->get();
        
        foreach ($globalSettings as $setting) {
            self::updateOrCreate(
                ['zone_id' => $zoneId, 'key' => $setting->key],
                ['value' => $setting->value]
            );
        }
    }

    /**
     * Get list of business setting keys that can be configured per zone
     */
    public static function getZoneConfigurableKeys(): array
    {
        return [
            // Customer Notification Messages
            'customer_order_place_message',
            
            // Admin Notification Messages
            'admin_order_pending_message',
            'admin_order_processing_message',
            'admin_order_cancel_message',
            'admin_order_confirmed_message',
            'admin_order_accepted_message',
            'admin_order_handovered_message',
            'admin_order_picked_up_message',
            'admin_order_on_way_message',
            'admin_dm_at_restaurant_message',
            'admin_dm_arrived_at_door_message',
            'admin_order_delivered_message',
            'admin_order_refund_request_message',
            'admin_order_refund_response_message',
            'admin_order_refunded_message',
            'admin_order_scheduled_message',
            
            // Deliveryman Notification Messages
            'dm_order_placed_message',
            'dm_order_accepted_message',
            'dm_order_rejected_message',
            'dm_order_at_restaurant_message',
            'dm_order_picked_up_message',
            'dm_arrived_at_customer_door_message',
            'dm_order_delivered_message',
            
            // Legacy Notification Messages (keeping for backward compatibility)
            'order_confirm_message',
            'order_delivered_message',
            'delivery_boy_delivered_message',
            'order_cancled_message',
            'order_handover_message',
            'order_refunded_message',
            'out_for_delivery_message',
            
            // Business Settings
            'customer_verification',
            'toggle_restaurant_registration',
            'toggle_dm_registration',
            'admin_order_notification',
            'schedule_order',
            'order_delivery_verification',
            'dm_tips_status',
            'show_dm_earning',
            'toggle_veg_non_veg',
            'business_model',
            'order_subscription',
            'order_confirmation_model',
            'canceled_by_deliveryman',
            'canceled_by_restaurant',
            
            // Operational Settings
            'schedule_order_slot_duration',
            'dm_maximum_orders',
            'free_delivery_over',
            'dm_max_cash_in_hand',
            'admin_commission',
            'delivery_charge_comission',
            'loyalty_percent',
            'loyalty_value',
            'minimum_redeem_value',
        ];
    }

    /**
     * Check if a setting key is zone-configurable
     */
    public static function isZoneConfigurable(string $key): bool
    {
        return in_array($key, self::getZoneConfigurableKeys());
    }
}
