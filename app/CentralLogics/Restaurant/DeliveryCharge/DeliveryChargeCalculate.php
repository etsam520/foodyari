<?php

class DeliveryChargeCalculate
{
    // ---- Configuration: Tier setup ---- //
    private array $tiers = [
        'A' => [
            'max_distance' => 2,
            'base' => 5,
            'min_order' => 50,
            'per_km' => 0,
            'start_km' => 0,
        ],
        'B' => [
            'max_distance' => 6,
            'base' => 7,
            'min_order' => 200,
            'per_km' => 0,
            'start_km' => 2,
        ],
        'C' => [
            'max_distance' => PHP_INT_MAX, // No upper limit
            'base' => 20,
            'min_order' => null, // no free delivery for far distance
            'per_km' => 5,
            'start_km' => 6,
        ]
    ];

    // ---- Configuration: Weight factors ---- //
    private float $rainWeight = 0.20;     // 20% increase in heavy rain
    private float $trafficWeight = 0.15;  // 15% increase in high traffic
    private float $nightWeight = 0.10;    // 10% increase at night

    // ---- Other multipliers ---- //
    private float $surgeMultiplier = 1.0; // Default 1.0, can set to 1.2 during high demand
    private float $locationMultiplier = 1.0; // 1.0 = normal, >1.0 = remote or tough area
    private float $minFee = 5.0;          // Minimum delivery fee if not free

    // ---- Constructor ---- //
    public function __construct(array $options = [])
    {
        // allow overriding configuration
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Calculate the delivery charge.
     *
     * @param float $distanceKm
     * @param float $orderAmount
     * @param float $rainFactor  (0 = none, 1 = heavy rain)
     * @param float $trafficFactor (0 = clear, 1 = heavy traffic)
     * @param float $nightFactor (0 = day, 1 = night)
     * @return float Delivery charge in currency units
     */
    public function calculate(float $distanceKm, float $orderAmount, float $rainFactor = 0, float $trafficFactor = 0, float $nightFactor = 0): float
    {
        $tier = $this->getTier($distanceKm);
        $tierBase = $this->getTierBase($tier, $distanceKm);
        $minOrder = $this->tiers[$tier]['min_order'];

        // --- Check free delivery rule --- //
        if (!is_null($minOrder) && $orderAmount >= $minOrder) {
            return 0.0;
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
        return round($charge, 2);
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
        if ($data['per_km'] > 0 && $distanceKm > $data['start_km']) {
            $extraKm = $distanceKm - $data['start_km'];
            $base += $extraKm * $data['per_km'];
        }

        return $base;
    }
}
