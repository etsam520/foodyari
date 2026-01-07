<?php

namespace App\Events;

use App\Models\DeliveryMan;
use App\Services\JsonDataService;
use Illuminate\Support\Facades\Log;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class _DDD_DeliveryLocationUpdated implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Message from {$from->resourceId}: $msg\n";

        $data = json_decode($msg, true);
        try {
            $data = json_decode($msg, true);
            if (isset($data['id'], $data['lat'], $data['lng'])) {

                $dmId = $data['id'];
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
                    'lng' => $data['lng'],
                ];
                $dmData->updated_at =  now();
                $dmData->save();

                // Send back a response to the client
                $from->send(json_encode([
                    'status' => 'success',
                    'data' => [
                        'message' => 'Location updated',
                        'lat' => $data['lat'],
                        'lng' => $data['lng'],
                    ]
                ]));

                Log::info("Delivery location updated for ID: {$data['id']}");
                // Optionally broadcast to other clients
                // foreach ($this->clients as $client) {
                //     if ($client !== $from) {
                //         $client->send($msg);
                //     }
                // }
            }
        } catch (\Exception $e) {
            Log::error("Failed to decode JSON: {$e->getMessage()}");
            return;
        }

        // Validate and save location

    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}
