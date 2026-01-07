<?php

namespace App\Events;

use App\CentralLogics\Deliveryman\DeliverymanLastLocation;
use App\Models\DeliveryMan;
use App\Services\JsonDataService;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Predis\Client as RedisClient;

class DeliveryLocationUpdated
{
    use Dispatchable, SerializesModels;

    public SplObjectStorage $clients;
    public RedisClient $redis;
    public $userConnections;
    
    // Static array to track last save time for each deliveryman
    private static $lastSaveTimes = [];



    public function __construct( ConnectionInterface $from, $msg ,SplObjectStorage $clients , $userConnections)
    {
        $this->clients = $clients;
        $this->redis = new RedisClient();
        $this->userConnections = $userConnections;
        $this->handle($from, $msg);
    }


    public function handle(ConnectionInterface $from, $msg): void
    {
        echo "Message from {$from->resourceId}: $msg\n";

        try {
            $data = json_decode($msg, true);
            if (isset($data['user_id'], $data['type'], $data['lat'], $data['lng']) && $data['type'] == 'deliveryman') {
                $dmId = $data['user_id'];
                $user = SocketEvent::findUserById($this->userConnections, $dmId);
                // Read from Redis
                try {
                    // Try reading Redis key
                    $hasOrder = $this->redis->get("deliveryman:{$user->id}:has_order");
                    if ($hasOrder === null) {
                        echo "Redis key not found: deliveryman:{$user->id}:has_order\n";
                    } else {
                        if ($hasOrder === '1') {
                            $order_customer_ids = $this->redis->get("deliveryman:{$user->id}:order_customer_ids");
                            echo "User {$user->id} has an active order with customer ID {$order_customer_ids}.\n";

                            $order_customer_ids = json_decode($order_customer_ids,true);
                            $user->has_active_order = &$hasOrder;
                            $user->customer_ids= &$order_customer_ids;
                        } else {
                            echo "Deliveryman has NO active order — skipping location send.\n";
                        }
                    }
                } catch (\Exception $e) {
                    // dd($e);
                    echo "Redis Exception: " . $e->getMessage() . "\n";
                }
                
                // Save location only if 5 seconds have passed since last save for this deliveryman
                $currentTime = time();
                if (!isset(self::$lastSaveTimes[$dmId]) || ($currentTime - self::$lastSaveTimes[$dmId]) >= 5) {
                    $dloc = new DeliverymanLastLocation($dmId, $data['lat'], $data['lng'], date('d-m-Y H:i:s'));
                    $dloc->saveLastLocation();
                    self::$lastSaveTimes[$dmId] = $currentTime;
                    echo "Location saved for deliveryman {$dmId} at " . date('H:i:s') . "\n";
                } else {
                    echo "Location save skipped for deliveryman {$dmId} - within 5 second interval\n";
                }
                
                $deliveryman = DeliveryMan::where('id', $dmId)->exists();
                if (!$deliveryman) {
                    Log::error("Deliveryman with ID {$dmId} does not exist.");
                    $from->send(json_encode([
                        'status' => 'error',
                        'message' => 'Deliveryman not found'
                    ]));
                    return;
                }
                $dmData = new JsonDataService($dmId);
                $dmData->last_location = [
                    'lat' => $data['lat'],
                    'lng' => $data['lng']
                ];
                // Send back a response to the client
                $from->send(json_encode([
                    'status' => 'success',
                    'data' => [
                        'message' => 'Location updated',
                        'lat' => $data['lat'],
                        'lng' => $data['lng'],
                    ]
                ]));

                if($user->has_active_order){
                    foreach($user->customer_ids as $customer_id){
                        $customerUser = SocketEvent::findUserById($this->userConnections, $customer_id);
                        $sendToResourceId = $customerUser->resourceId;
                        if($sendToResourceId != null){
                            foreach($this->clients as $client){
                                if($client->resourceId == $sendToResourceId){
                                    $client->send(json_encode([
                                        'status' => 'success',
                                        'data' => [
                                            'message' => 'Location updated',
                                            'dm_id' => $dmId,
                                            'lat' => $data['lat'],
                                            'lng' => $data['lng'],
                                        ]
                                    ]));
                                    break;
                                }
                            }
                        }
                    }
                }



                // Log::info("Delivery location updated for deliveryman ID {$dmId}");
                echo "Users:". json_encode($this->userConnections)."\n";

            }
        } catch (\Exception $e) {
            Log::error("Error updating delivery location: " . $e->getMessage());
            $from->send(json_encode([
                'status' => 'error',
                'message' => 'Failed to update delivery location'
            ]));
        }

    }

}

