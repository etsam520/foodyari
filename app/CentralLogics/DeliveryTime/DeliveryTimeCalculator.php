<?php
namespace App\CentralLogics\DeliveryTime;

class DeliveryTimeCalculator
{
    
    /**
     * Base speed of the delivery rider (km/min).
     * Example: 30 km/h = 0.5 km/min
     */
    private float $baseSpeed;

    /** Multipliers for various conditions */
    private float $trafficFactor;
    private float $rainFactor;
    private float $nightFactor;
    private float $otherFactor;

    /** Fixed overhead times (minutes) */
    private float $pickupFixed;
    private float $buffer;

    public function __construct(
        float $baseSpeed = 0.5,  // 30 km/h
        float $trafficFactor = 1.0,
        float $rainFactor = 1.0,
        float $nightFactor = 1.0,
        float $otherFactor = 1.0,
        float $pickupFixed = 2.0,
        float $buffer = 2.0
    ) {
        $this->baseSpeed = $baseSpeed;
        $this->trafficFactor = $trafficFactor;
        $this->rainFactor = $rainFactor;
        $this->nightFactor = $nightFactor;
        $this->otherFactor = $otherFactor;
        $this->pickupFixed = $pickupFixed;
        $this->buffer = $buffer;
    }

    /**
     * Calculate total ETA (in minutes and as a timestamp)
     */
    public function calculateETA(
        float $distanceDriverToRestaurant,
        float $distanceRestaurantToCustomer,
        float $restaurantBaseTime,
        float $restaurantExtraTime = 0.0,
        float $processingElapsed = 0.0,
        ?float $customNowTimestamp = null
    ): array {
        // Combined travel multiplier
        $travelMultiplier = $this->trafficFactor * $this->rainFactor * $this->nightFactor * $this->otherFactor;

        // 1️⃣ Restaurant processing time
        $tProcEst = $restaurantBaseTime + $restaurantExtraTime;
        $tProcRemaining = max(0, $tProcEst - $processingElapsed);

        // 2️⃣ Travel times
        $tDriverToRest = ($distanceDriverToRestaurant / $this->baseSpeed) * $travelMultiplier;
        $tRestToCust   = ($distanceRestaurantToCustomer / $this->baseSpeed) * $travelMultiplier;

        // 3️⃣ Waiting time (if rider arrives early)
        $tWaitForFood = max(0, $tProcRemaining - $tDriverToRest);

        // 4️⃣ Total time until rider departs restaurant
        $tUntilDepart = $tDriverToRest + $tWaitForFood + $this->pickupFixed;

        // 5️⃣ ETA total
        $etaMinutesFromNow = $tUntilDepart + $tRestToCust + $this->buffer;

        // 6️⃣ Timestamp output
        $now = $customNowTimestamp ?? time();
        $etaTimestamp = $now + ($etaMinutesFromNow * 60); // convert to seconds

        return [
            'eta_minutes' => round($etaMinutesFromNow, 2),
            'eta_time' => date('Y-m-d H:i:s', $etaTimestamp),
            'breakdown' => [
                'driver_to_restaurant' => round($tDriverToRest, 2),
                'driver_to_restaurant_eta_time' => date('Y-m-d H:i:s', $now + ($tDriverToRest ) * 60),
                'wait_for_food' => round($tWaitForFood, 2),
                'processing_estimated' => round($tProcEst, 2),
                'processing_remaining' => round($tProcRemaining, 2),
                'elapsed_processing' => round($processingElapsed, 2),
                'pickup_eta_time' => date('Y-m-d H:i:s', $now + ($tDriverToRest + $tWaitForFood) * 60),
                'pickup_fixed' => round($this->pickupFixed, 2),
                'restaurant_to_customer' => round($tRestToCust, 2),
                'buffer' => round($this->buffer, 2),
            ],
            'factors' => [
                'traffic' => $this->trafficFactor,
                'rain' => $this->rainFactor,
                'night' => $this->nightFactor,
                'other' => $this->otherFactor,
                'travel_multiplier' => $travelMultiplier,
            ],
        ];
    }

    /*
    * Calculate Delivery man to Restaurant ETA
    */
    public function calculateDriverToRestaurantETA(
        float $distanceDriverToRestaurant,
        ?float $customNowTimestamp = null
    ): array {
        // Combined travel multiplier
        $travelMultiplier = $this->trafficFactor * $this->rainFactor * $this->nightFactor * $this->otherFactor;

        // Travel time
        $tDriverToRest = ($distanceDriverToRestaurant / $this->baseSpeed) * $travelMultiplier;

        // Timestamp output
        $now = $customNowTimestamp ?? time();
        $etaTimestamp = $now + ($tDriverToRest * 60); // convert to seconds

        return [
            'eta_minutes' => round($tDriverToRest, 2),
            'eta_time' => date('Y-m-d H:i:s', $etaTimestamp),
            'factors' => [
                'traffic' => $this->trafficFactor,
                'rain' => $this->rainFactor,
                'night' => $this->nightFactor,
                'other' => $this->otherFactor,
                'travel_multiplier' => $travelMultiplier,
            ],
        ];
    }

}