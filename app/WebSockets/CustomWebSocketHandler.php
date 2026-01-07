<?php
namespace App\WebSockets;

use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use App\Events\DeliveryLocationUpdated;
use Illuminate\Support\Facades\Log;
use BeyondCode\LaravelWebSockets\Facades\StatisticsLogger;
use BeyondCode\LaravelWebSockets\WebSockets\Messages\PusherMessageFactory;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;


class CustomWebSocketHandler extends WebSocketHandler
{
    public function onMessage(ConnectionInterface $connection, MessageInterface $message)
    {
        $message = PusherMessageFactory::createForMessage($message, $connection, $this->channelManager);

        Log::info('Received message: ' . json_encode($message));

        $message->respond();

        StatisticsLogger::webSocketMessage($connection);
    }
    public function __onMessage(ConnectionInterface $connection, $message)
    {
        $data = json_decode($message, true);
        Log::info('Received message: ' . $message);

        return true;

        if (isset($data['type']) && $data['type'] === 'location_update') {
            $orderId = $data['order_id'] ?? null;
            $lat = $data['lat'] ?? null;
            $lng = $data['lng'] ?? null;

            if ($orderId && $lat && $lng) {
                event(new DeliveryLocationUpdated($orderId, $lat, $lng));
                $connection->send(json_encode(['status' => 'Location broadcasted']));
            } else {
                $connection->send(json_encode(['error' => 'Invalid data']));
            }
        } else {
            $connection->send(json_encode(['error' => 'Unknown message type']));
        }
    }
}
