<?php

namespace App\Services;

use App\CentralLogics\Restaurant\DeliveryCharge\ZoneWiseDeliveryChargeCalculate;
use App\Models\ZoneDeliveryChargeSetting;

class DeliveryChargeService
{
    /**
     * Calculate delivery charge for a zone
     *
     * @param int $zoneId
     * @param float $distanceKm
     * @param float $orderAmount
     * @param array $environmentalFactors ['rain' => 0-1, 'traffic' => 0-1, 'night' => 0-1]
     * @return array
     */
    public static function calculateForZone(int $zoneId, float $distanceKm, float $orderAmount, array $environmentalFactors = []): array
    {
        $calculator = new ZoneWiseDeliveryChargeCalculate($zoneId);
        return $calculator->calculate(
            $distanceKm,
            $orderAmount,
            $environmentalFactors['rain'] ?? 0,
            $environmentalFactors['traffic'] ?? 0,
            $environmentalFactors['night'] ?? 0
        );
    }

    /**
     * Get current environmental factors based on time and weather
     *
     * @param array $conditions ['weather' => 'rain', 'time' => '22:00', 'traffic_level' => 'high']
     * @return array
     */
    public static function getEnvironmentalFactors(array $conditions = []): array
    {
        $factors = [
            'rain' => 0,
            'traffic' => 0,
            'night' => 0
        ];

        // Rain factor
        if (isset($conditions['weather']) && in_array($conditions['weather'], ['rain', 'heavy_rain', 'storm'])) {
            $factors['rain'] = match($conditions['weather']) {
                'rain' => 0.5,
                'heavy_rain' => 0.8,
                'storm' => 1.0,
                default => 0
            };
        }

        // Night factor
        if (isset($conditions['time'])) {
            $hour = (int) date('H', strtotime($conditions['time']));
            if ($hour >= 22 || $hour <= 6) {
                $factors['night'] = 1.0;
            } elseif ($hour >= 20 || $hour <= 8) {
                $factors['night'] = 0.5;
            }
        }

        // Traffic factor
        if (isset($conditions['traffic_level'])) {
            $factors['traffic'] = match($conditions['traffic_level']) {
                'low' => 0,
                'medium' => 0.3,
                'high' => 0.7,
                'extreme' => 1.0,
                default => 0
            };
        }

        return $factors;
    }

    /**
     * Get delivery charge settings for a zone
     *
     * @param int $zoneId
     * @return ZoneDeliveryChargeSetting|null
     */
    public static function getZoneSettings(int $zoneId): ?ZoneDeliveryChargeSetting
    {
        return ZoneDeliveryChargeSetting::getActiveSettingForZone($zoneId);
    }

    /**
     * Check if zone has custom delivery charge settings
     *
     * @param int $zoneId
     * @return bool
     */
    public static function hasCustomSettings(int $zoneId): bool
    {
        return ZoneDeliveryChargeSetting::getActiveSettingForZone($zoneId) !== null;
    }

    /**
     * Calculate delivery charge with auto-detected environmental factors
     *
     * @param int $zoneId
     * @param float $distanceKm
     * @param float $orderAmount
     * @param array $autoDetectConditions
     * @return array
     */
    public static function calculateWithAutoDetection(int $zoneId, float $distanceKm, float $orderAmount, array $autoDetectConditions = []): array
    {
        // Auto-detect environmental factors if not provided
        if (empty($autoDetectConditions['time'])) {
            $autoDetectConditions['time'] = now()->format('H:i');
        }

        $environmentalFactors = self::getEnvironmentalFactors($autoDetectConditions);
        
        return self::calculateForZone($zoneId, $distanceKm, $orderAmount, $environmentalFactors);
    }

    /**
     * Get tier information for a distance in a zone
     *
     * @param int $zoneId
     * @param float $distanceKm
     * @return array|null
     */
    public static function getTierInfo(int $zoneId, float $distanceKm): ?array
    {
        $calculator = new ZoneWiseDeliveryChargeCalculate($zoneId);
        $tiers = $calculator->getTiers();
        
        foreach ($tiers as $name => $tier) {
            if ($distanceKm <= $tier['max_distance']) {
                return [
                    'name' => $name,
                    'data' => $tier
                ];
            }
        }
        
        return [
            'name' => 'C',
            'data' => $tiers['C'] ?? null
        ];
    }
}