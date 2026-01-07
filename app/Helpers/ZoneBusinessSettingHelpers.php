<?php

use App\Models\ZoneBusinessSetting;
use App\Models\BusinessSetting;

if (!function_exists('getZoneBusinessSetting')) {
    /**
     * Get zone-specific business setting value
     *
     * @param string $key
     * @param int|null $zoneId
     * @param mixed $default
     * @return mixed
     */
    function getZoneBusinessSetting($key, $zoneId = null, $default = null)
    {
        if (!$zoneId) {
            // Try to get zone from current context or default to global setting
            $zoneId = getCurrentZoneId();
        }

        if ($zoneId) {
            return ZoneBusinessSetting::getSettingValue($key, $zoneId, $default);
        }

        // Fallback to global business setting
        $globalSetting = BusinessSetting::where('key', $key)->first();
        if ($globalSetting) {
            $decoded = json_decode($globalSetting->value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $globalSetting->value;
        }

        return $default;
    }
}

if (!function_exists('setZoneBusinessSetting')) {
    /**
     * Set zone-specific business setting value
     *
     * @param string $key
     * @param mixed $value
     * @param int $zoneId
     * @return bool
     */
    function setZoneBusinessSetting($key, $value, $zoneId)
    {
        return ZoneBusinessSetting::setSettingValue($key, $zoneId, $value);
    }
}

if (!function_exists('getCurrentZoneId')) {
    /**
     * Get current zone ID from various contexts
     *
     * @return int|null
     */
    function getCurrentZoneId()
    {
        // Try to get from session
        if (session()->has('current_zone_id')) {
            return session('current_zone_id');
        }

        // Try to get from request
        if (request()->has('zone_id')) {
            return request('zone_id');
        }

        // Try to get from route parameter
        if (request()->route() && request()->route()->parameter('zone')) {
            $zone = request()->route()->parameter('zone');
            return is_object($zone) ? $zone->id : $zone;
        }

        // Try to get from authenticated user's zone (if applicable)
        if (auth()->check() && method_exists(auth()->user(), 'zone_id')) {
            return auth()->user()->zone_id;
        }

        return null;
    }
}

if (!function_exists('getAllZoneBusinessSettings')) {
    /**
     * Get all business settings for a specific zone
     *
     * @param int $zoneId
     * @return array
     */
    function getAllZoneBusinessSettings($zoneId)
    {
        return ZoneBusinessSetting::getZoneSettings($zoneId);
    }
}

if (!function_exists('isZoneBusinessSettingConfigurable')) {
    /**
     * Check if a business setting key is configurable per zone
     *
     * @param string $key
     * @return bool
     */
    function isZoneBusinessSettingConfigurable($key)
    {
        return ZoneBusinessSetting::isZoneConfigurable($key);
    }
}

if (!function_exists('getBusinessSettingWithZoneFallback')) {
    /**
     * Get business setting value with zone-specific fallback logic
     * First checks zone-specific setting, then global setting, then default
     *
     * @param string $key
     * @param int|null $zoneId
     * @param mixed $default
     * @return mixed
     */
    function getBusinessSettingWithZoneFallback($key, $zoneId = null, $default = null)
    {
        // If this setting is zone-configurable and we have a zone ID
        if (isZoneBusinessSettingConfigurable($key) && $zoneId) {
            $zoneSetting = ZoneBusinessSetting::where('zone_id', $zoneId)
                                            ->where('key', $key)
                                            ->first();
            
            if ($zoneSetting) {
                $decoded = json_decode($zoneSetting->value, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : $zoneSetting->value;
            }
        }

        // Fallback to global business setting
        $globalSetting = BusinessSetting::where('key', $key)->first();
        if ($globalSetting) {
            $decoded = json_decode($globalSetting->value, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : $globalSetting->value;
        }

        return $default;
    }
}

if (!function_exists('getZoneConfigurableKeys')) {
    /**
     * Get list of all zone-configurable business setting keys
     *
     * @return array
     */
    function getZoneConfigurableKeys()
    {
        return ZoneBusinessSetting::getZoneConfigurableKeys();
    }
}

if (!function_exists('copyGlobalSettingsToZone')) {
    /**
     * Copy all global business settings to a specific zone
     *
     * @param int $zoneId
     * @return void
     */
    function copyGlobalSettingsToZone($zoneId)
    {
        ZoneBusinessSetting::copyGlobalSettingsToZone($zoneId);
    }
}

if (!function_exists('getZoneBusinessSettingForOrder')) {
    /**
     * Get zone-specific business setting for order context
     * This function can be used in order processing to get zone-specific settings
     *
     * @param string $key
     * @param object $order
     * @param mixed $default
     * @return mixed
     */
    function getZoneBusinessSettingForOrder($key, $order, $default = null)
    {
        $zoneId = null;

        // Try to get zone from order's restaurant
        if (isset($order->restaurant) && isset($order->restaurant->zone_id)) {
            $zoneId = $order->restaurant->zone_id;
        }
        // Try to get zone from order's delivery address
        elseif (isset($order->delivery_address) && method_exists($order->delivery_address, 'getZone')) {
            $zone = $order->delivery_address->getZone();
            $zoneId = $zone ? $zone->id : null;
        }
        // Try to get zone directly from order if it has zone_id
        elseif (isset($order->zone_id)) {
            $zoneId = $order->zone_id;
        }

        return getZoneBusinessSetting($key, $zoneId, $default);
    }
}

if (!function_exists('getZoneBusinessSettingForRestaurant')) {
    /**
     * Get zone-specific business setting for restaurant context
     *
     * @param string $key
     * @param object $restaurant
     * @param mixed $default
     * @return mixed
     */
    function getZoneBusinessSettingForRestaurant($key, $restaurant, $default = null)
    {
        $zoneId = isset($restaurant->zone_id) ? $restaurant->zone_id : null;
        return getZoneBusinessSetting($key, $zoneId, $default);
    }
}

if (!function_exists('getZoneBusinessSettingForDeliveryMan')) {
    /**
     * Get zone-specific business setting for delivery man context
     *
     * @param string $key
     * @param object $deliveryMan
     * @param mixed $default
     * @return mixed
     */
    function getZoneBusinessSettingForDeliveryMan($key, $deliveryMan, $default = null)
    {
        $zoneId = isset($deliveryMan->zone_id) ? $deliveryMan->zone_id : null;
        return getZoneBusinessSetting($key, $zoneId, $default);
    }
}