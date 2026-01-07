<?php
namespace App\CentralLogics\DeliveryTime;

use App\CentralLogics\Deliveryman\DeliverymanLastLocation;
use App\CentralLogics\Helpers;
use App\Models\Order;
use App\Models\Restaurant;
use App\Services\JsonDataService;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class DeliveryTimer {
    // private $speed = 0.5; // km per minute (30 km/h)
    const AVERAGE_SPEED = 0.4; // km per minute (24 km/h)

    // private $defaultSpeed = 0.5; // km per minute (30 km/h)
    public $dmId;
    private ?DeliverymanLastLocation $dmLastLocation;

    public function __construct($dmId)
    {
       $this->dmId = $dmId; 
       $lastLocation = new DeliverymanLastLocation($dmId, 0, 0, '');
       $lastLoc = $lastLocation->getLastLocation();
       $this->dmLastLocation = $lastLoc;
    }

    public function getDeliveryTime(int $orderID , ?Order $order) : array
    {

        $_distanceDriverToRestaurant = 0.0;
        $_distanceRestaurantToCustomer = 0.0;
        $speed = $this->getDmConcurrentSpeed($this->dmId);
        /* prepare parameters */
        if($order == null){
            $order = Order::find($orderID);
        }
        $restaurant = Restaurant::select('id','latitude','longitude')->where('id',$order->restaurant_id)->first();
        $restaurantPoint = [
            'lat' => $restaurant->latitude,
            'lon' => $restaurant->longitude
        ];
        $dmLastLocation = $this->getDmLastLocation();
        $dmPoint = [
            'lat' => $dmLastLocation->currentLocation->lat??0,
            'lon' => $dmLastLocation->currentLocation->lng??0
        ];
        $_distanceDriverToRestaurant = Helpers::haversineDistance($dmPoint, $restaurantPoint);
        if($order->handover != null || $order->picked_up != null){
            $_distanceDriverToRestaurant = 0.0; // already at restaurant
        }

        $customerAddress = json_decode($order->delivery_address, true);
        $customerPoint = [
            'lat' => $customerAddress['position']['lat']??0,
            'lon' => $customerAddress['position']['lon']??0
        ];
        $_distanceRestaurantToCustomer = Helpers::haversineDistance($restaurantPoint, $customerPoint);
        // dd($_distanceDriverToRestaurant,$_distanceRestaurantToCustomer);
        // $totalDistance = $_distanceDriverToRestaurant + $_distanceRestaurantToCustomer;
        // dd("Total Distance: ".$totalDistance." km, Speed: ".$speed." km/min");


        $restaurantBaseTime = $this->getRestaurantBaseTime($order) > 0 ? $this->getRestaurantBaseTime($order) : floatval($restaurant->min_delivery_time);
        $restaurantExtraTime = $this->getRestaurantExtraTime($order);
        $extraCookingTime = $this->getExtraCookingTime($order); // Additional cooking time set by restaurant
        $processingElapsed = $this->getProcessingElapsedTime($order); // can be dynamic based on restaurant load
        /*end prepare parameters */
        // dd('baseTime: '.$restaurantBaseTime.' , extraTime: '.$restaurantExtraTime.' , extraCookingTime: '.$extraCookingTime.' , processingElapsed: '.$processingElapsed);

        $trafficFactor = $this->calculateTrafficFactor($speed);
        //  dd($trafficFactor, $speed);
        $deliveryTimeCalculator = new DeliveryTimeCalculator(
            baseSpeed: self::AVERAGE_SPEED,
            trafficFactor: $trafficFactor, //1.0,
            rainFactor: 1.0,
            nightFactor: 1.0,
            otherFactor: 1.0,
            pickupFixed: 2.0,
            buffer: 2.0
        );
        return $deliveryTimeCalculator->calculateETA(
            distanceDriverToRestaurant: $_distanceDriverToRestaurant,
            distanceRestaurantToCustomer: $_distanceRestaurantToCustomer  ,// 0.0,
            restaurantBaseTime:  $restaurantBaseTime ,// 0.0,
            restaurantExtraTime: $restaurantExtraTime + $extraCookingTime, // Include extra cooking time
            processingElapsed: $processingElapsed
        );
    }
    

    public function getResturantReachOutTime($orderID , ?Order $order) : array{

        $speed = $this->getDmConcurrentSpeed($this->dmId);
        $trafficFactor = $this->calculateTrafficFactor($speed);
        $deliveryTimeCalculator = new DeliveryTimeCalculator(
            baseSpeed: self::AVERAGE_SPEED,
            trafficFactor: $trafficFactor,
            rainFactor: 1.0,
            nightFactor: 1.0,
            otherFactor: 1.0,
            pickupFixed: 2.0,
            buffer: 2.0
        );
        // dd($this->getDmConcurrentSpeed($this->dmId));
         /* prepare parameters */


        if($order == null){
            $order = Order::find($orderID);
        }

        $dmLastLocation = $this->getDmLastLocation();
        $dmPoint = [
            'lat' => $dmLastLocation->currentLocation->lat??0,
            'lon' => $dmLastLocation->currentLocation->lng??0
        ];
        $restaurant = Restaurant::select('id','latitude','longitude')->where('id',$order->restaurant_id)->first();
        $restaurantPoint = [
            'lat' => $restaurant->latitude,
            'lon' => $restaurant->longitude
        ];
        $distanceDriverToRestaurant = Helpers::haversineDistance($dmPoint, $restaurantPoint);
        return $deliveryTimeCalculator->calculateDriverToRestaurantETA(
            distanceDriverToRestaurant: $distanceDriverToRestaurant,
        );
        
    }

    /**
     * Calculates the traffic factor based on the current speed.
     * * @param float $currentSpeed The speed in km/min (e.g., 0.01 to 0.5)
     * @return float The traffic factor. Returns false if current speed is 0 to avoid division by zero.
    */
    function calculateTrafficFactor(float $currentSpeed) :float {
        if ($currentSpeed <= 0) {
            return 0 ; // Error handling for division by zero
        }
        
        // The core formula: F_traffic = Average_Speed / Current_Speed
        $trafficFactor = self::AVERAGE_SPEED / $currentSpeed;
        return $trafficFactor;
    }


   

    /*  
    * Calculate speed given distance (km) and time (minutes)
    * If time is zero or negative, return default speed
    */

    private function getSpeed(float $distance, float $time) : float
    {
        try {
            if ($time <= 0) {
                throw new InvalidArgumentException("Time must be greater than zero.");
            }
            return $distance / $time;

        } catch (InvalidArgumentException $e) {
            Log::error("Error: " . $e->getMessage());
            return self::AVERAGE_SPEED; // default speed
        }
    }

    public function getDmConcurrentSpeed($dmId) 
    {
        try {
            $lastLocation = $this->getDmLastLocation();
            $lastLoc = $lastLocation;
            $timeDiff = 0.0;
            $distanceDiff = 0.0;
            if (!$lastLoc) throw new \Exception("No last location data found.");

            if (!$lastLoc->previousLocation || !$lastLoc->currentLocation) {
                throw new \Exception("Can't calculate without both timestamps.");
            }

            $current = \DateTime::createFromFormat('d-m-Y H:i:s', $lastLoc->currentLocation->timestamp);
            $previous = \DateTime::createFromFormat('d-m-Y H:i:s', $lastLoc->previousLocation->timestamp);

            if (!$current || !$previous) throw new \Exception("Invalid timestamp format.");

            $interval = $current->getTimestamp() - $previous->getTimestamp();
            $timeDiff = (float) $interval / 60; // Convert seconds to minutes
            $distanceDiff = Helpers::haversineDistance([
                'lat' => $lastLocation->previousLocation->lat??0,
                'lon' => $lastLocation->previousLocation->lng??0
            ],[
                'lat' => $lastLocation->currentLocation->lat??0,
                'lon' => $lastLocation->currentLocation->lng??0
            ]);
            $speed = $this->getSpeed($distanceDiff, $timeDiff);
            
            if($speed <= 0) {
                throw new \Exception("Calculated speed is non-positive.");
            }
            // return $speed;
            // return max($speed, 0.2); // minimum speed 0.2 km/min
            return max($speed, 0.1); // minimum speed 0.1 km/min
        } catch (\Exception $e) {
            // error_log("Time difference calculation failed: " . $e->getMessage());
            // Log::error("Error: " . $e->getMessage());
            return self::AVERAGE_SPEED; // default speed

        }
    }

    public function getDmLastLocation(): ?DeliverymanLastLocation
    {
       try{
           $loc = $this->dmLastLocation;
           if(!$loc) throw new \Exception("No last location data found.");
           if(!$loc->previousLocation || !$loc->currentLocation){
               throw new \Exception("Incomplete location data.");
           }
            return $loc;
       } catch (\Exception $e) {
           // Log::error("Error: " . $e->getMessage());
           $dmData = new JsonDataService($this->dmId);
            $dmData = $dmData->readData();
              if(isset($dmData->last_location)){
                $lastLoc = new DeliverymanLastLocation(
                     $this->dmId,
                     $dmData->last_location['lat'],
                     $dmData->last_location['lng'],
                     date('d-m-Y H:i:s')
                );
                return $lastLoc;
           }
           return null;
       }
    }

    public function getRestaurantBaseTime(Order $order) : float
    {
        return  floatval($order->processing_time) ?? 0.0;
    }
    public function getRestaurantExtraTime(Order $order) : float
    {
        return floatval($order->extra_processing_time) ?? 0.0;
    }

    /**
     * Get extra cooking time added by restaurant
     */
    public function getExtraCookingTime(Order $order) : float
    {
        return floatval($order->extra_cooking_time) ?? 0.0;
    }
    
    public function getProcessingElapsedTime(Order $order): float
    {
        if ($order->picked_up != null || $order->handover != null || $order->delivered != null || $order->canceled != null) {
            return 0.0; // No processing time after pickup or handover
        }
        $startedAt = null;
      
        if (!$order->processing && $order->confirmed) :
            $startedAt = \Carbon\Carbon::parse($order->confirmed);
        elseif (!$order->processing && $order->accepted) :
            $startedAt = \Carbon\Carbon::parse($order->accepted);
        else :
            $startedAt = \Carbon\Carbon::parse($order->processing);
        endif;

        try {
            $now = \Carbon\Carbon::now();
            $elapsedSeconds = $now->diffInSeconds($startedAt);
            return round($elapsedSeconds / 60, 2); // Convert to minutes, rounded to 2 decimals

        } catch (\Exception $e) {
            // Log::error("Failed to calculate processing elapsed time: " . $e->getMessage()." , file: ".$e->getFile()." , line: ".$e->getLine());
            return 0.0;
        }
    } 
    
}