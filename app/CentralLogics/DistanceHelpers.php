<?php
namespace App\CentralLogics;

class DistanceHelpers {


    public static function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Radius of the Earth in kilometers
    
        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
    
        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
    
        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dlon / 2) * sin($dlon / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c;
    
        return $distance;
    }


    function findDeliverymenSortedByDistance($customerPos, $restaurantPos, $deliverymen) {
        $midpoint = [
            'lat' => ($customerPos['lat'] + $restaurantPos['lat']) / 2,
            'lon' => ($customerPos['lon'] + $restaurantPos['lon']) / 2
        ];
    
        // Calculate distance for each deliveryman and store it in the array
        foreach ($deliverymen as &$deliveryman) {
            $deliveryman['distance'] = self::haversineDistance(
                $midpoint['lat'], $midpoint['lon'],
                $deliveryman['lat'], $deliveryman['lon']
            );
        }
    
        // Sort deliverymen by distance
        usort($deliverymen, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
    
        return $deliverymen;
    }

}

/*example
// Example usage
$customerPos = ['lat' => 40.712776, 'lon' => -74.005974]; // New York
$restaurantPos = ['lat' => 34.052235, 'lon' => -118.243683]; // Los Angeles

$deliverymen = [
    ['id' => 1, 'lat' => 36.169941, 'lon' => -115.139832], // Las Vegas
    ['id' => 2, 'lat' => 41.878113, 'lon' => -87.629799], // Chicago
    ['id' => 3, 'lat' => 34.052235, 'lon' => -118.243683] // Los Angeles
];

$sortedDeliverymen = findDeliverymenSortedByDistance($customerPos, $restaurantPos, $deliverymen);


*/