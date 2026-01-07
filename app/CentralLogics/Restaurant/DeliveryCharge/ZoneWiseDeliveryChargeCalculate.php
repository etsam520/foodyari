<?php

namespace App\CentralLogics\Restaurant\DeliveryCharge;

use App\Models\ZoneDeliveryChargeSetting;

class ZoneWiseDeliveryChargeCalculate
{
    private $zoneId;
    private $setting;
    private array $tiers = [];
    private float $rainWeight = 0.20;
    private float $trafficWeight = 0.15;
    private float $nightWeight = 0.10;
    private float $surgeMultiplier = 1.0;
    private float $locationMultiplier = 1.0;
    private float $minFee = 5.0;

    public function __construct($zoneId, array $options = [])
    {
        $this->zoneId = $zoneId;
        $this->loadZoneSettings();
        
        // Allow overriding configuration
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Load zone-specific delivery charge settings
     */
    private function loadZoneSettings()
    {
        $this->setting = ZoneDeliveryChargeSetting::getActiveSettingForZone($this->zoneId);
        
        if ($this->setting) {
            $this->tiers = $this->setting->tiers;
            $this->rainWeight = $this->setting->rain_weight;
            $this->trafficWeight = $this->setting->traffic_weight;
            $this->nightWeight = $this->setting->night_weight;
            $this->surgeMultiplier = $this->setting->surge_multiplier;
            $this->locationMultiplier = $this->setting->location_multiplier;
            $this->minFee = $this->setting->min_fee;
        } else {
            // Use default configuration if no zone-specific setting found
            $this->tiers = ZoneDeliveryChargeSetting::getDefaultTiers();
        }
    }

    /**
     * Calculate the delivery charge for the zone.
     *
     * @param float $distanceKm
     * @param float $orderAmount
     * @param float $rainFactor  (0 = none, 1 = heavy rain)
     * @param float $trafficFactor (0 = clear, 1 = heavy traffic)
     * @param float $nightFactor (0 = day, 1 = night)
     * @return array ['charge' => float, 'details' => array]
     */
    public function calculate(
        float $distanceKm, 
        float $orderAmount, 
        float $rainFactor = 0, 
        float $trafficFactor = 0, 
        float $nightFactor = 0
    ): array {
        $tier = $this->getTier($distanceKm);
        $tierBase = $this->getTierBase($tier, $distanceKm);
        $minOrder = $this->tiers[$tier]['min_order'] ?? null;
        // --- Check free delivery rule --- //
        if (!is_null($minOrder) && $orderAmount >= $minOrder) {
            return [
                'charge' => 0.0,
                'details' => [
                    'zone_id' => $this->zoneId,
                    'tier' => $tier,
                    'distance_km' => $distanceKm,
                    'order_amount' => $orderAmount,
                    'free_delivery' => true,
                    'min_order_required' => $minOrder,
                    'tier_base' => $tierBase,
                    'environmental_factors' => [
                        'rain' => $rainFactor,
                        'traffic' => $trafficFactor,
                        'night' => $nightFactor
                    ]
                ]
            ];
        }

        // --- Environmental multiplier --- //
        $E = 1
            + ($rainFactor * $this->rainWeight)
            + ($trafficFactor * $this->trafficWeight)
            + ($nightFactor * $this->nightWeight)
            + ($this->locationMultiplier - 1);
        // --- Final charge --- //
        $charge = $tierBase * $E * $this->surgeMultiplier;
        $charge = max($charge, $this->minFee); // enforce min fee
        $finalCharge = round($charge, 2);

        return [
            'charge' => $finalCharge,
            'details' => [
                'zone_id' => $this->zoneId,
                'tier' => $tier,
                'distance_km' => $distanceKm,
                'order_amount' => $orderAmount,
                'free_delivery' => false,
                'tier_base' => $tierBase,
                'environmental_multiplier' => $E,
                'surge_multiplier' => $this->surgeMultiplier,
                'min_fee' => $this->minFee,
                'environmental_factors' => [
                    'rain' => $rainFactor,
                    'traffic' => $trafficFactor,
                    'night' => $nightFactor
                ],
                'calculation_breakdown' => [
                    'base_charge' => $tierBase,
                    'after_environmental' => $tierBase * $E,
                    'after_surge' => $tierBase * $E * $this->surgeMultiplier,
                    'final_charge' => $finalCharge
                ]
            ]
        ];
    }

    /**
     * Determine which tier applies based on distance.
     */
    private function getTier(float $distanceKm): string
    {
        foreach ($this->tiers as $name => $tier) {
            if ($distanceKm <= $tier['max_distance']) {
                return $name;
            }
        }
        return 'C'; // fallback
    }

    /**
     * Compute base amount for a given tier and distance.
     */
    private function getTierBase(string $tier, float $distanceKm): float
    {
        $data = $this->tiers[$tier];
        $base = $data['base'];
        // If per km charge applies (like Tier C)
        if (($data['per_km'] ?? 0) > 0 && $distanceKm > $data['start_km']) {
            $extraKm = $distanceKm - $data['start_km'];
            $base += $extraKm * $data['per_km'];
        }

        return $base;
    }

    /**
     * Update zone settings
     */
    public function updateZoneSettings(array $newSettings): bool
    {
        if ($this->setting) {
            return $this->setting->update($newSettings);
        }
        
        $newSettings['zone_id'] = $this->zoneId;
        $this->setting = ZoneDeliveryChargeSetting::create($newSettings);
        $this->loadZoneSettings();
        
        return true;
    }

    /**
     * Get current zone settings
     */
    public function getZoneSettings(): ?ZoneDeliveryChargeSetting
    {
        return $this->setting;
    }

    /**
     * Get tier configuration
     */
    public function getTiers(): array
    {
        return $this->tiers;
    }
}