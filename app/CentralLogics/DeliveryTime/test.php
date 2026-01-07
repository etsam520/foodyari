<?php

// use App\CentralLogics\DeliveryTime\DeliveryTimeCalculator;

include_once 'DeliveryTimeCalculator.php';

$calc = new DeliveryTimeCalculator(
    baseSpeed: 0.5,     // 30 km/h
    // trafficFactor: 1.2, // 20% slower due to traffic
    trafficFactor: 1.1, // 10% slower due to traffic
    rainFactor: 1.0,    // 30% slower due to rain
    nightFactor: 1.0,   // 0% slower due to night
    otherFactor: 1.0,   // 0% slower due to other factors
    pickupFixed: 2.0,
    buffer: 2.0
);

$result = $calc->calculateETA(
    distanceDriverToRestaurant: 3.0,   // km
    distanceRestaurantToCustomer: 4.5, // km
    restaurantBaseTime: 12.0,          // minutes
    restaurantExtraTime: 3.0,          // additional delay
    processingElapsed: 5.0             // just started cooking
);

print_r($result);
echo "\n";

function println($text)
{
    echo $text . "\n";
}

$result2 = $calc->calculateDriverToRestaurantETA(
    distanceDriverToRestaurant: 3.0 // km

);
print_r($result2);