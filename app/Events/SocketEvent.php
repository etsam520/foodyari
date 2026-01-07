<?php

namespace App\Events;

use App\Models\DeliveryMan;
use App\Services\JsonDataService;
use Illuminate\Support\Facades\Log;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Predis\Client as RedisClient;

class SocketEvent implements MessageComponentInterface
{
    protected $clients;
    protected $userConnections;
    protected $redis;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->redis = new RedisClient();
        $this->userConnections = [];
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "New connection ({$conn->resourceId})\n";
    }



    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Message from {$from->resourceId}: $msg\n";
        
        try {
            $data = json_decode($msg, true);
            
            if (!$data) {
                echo "Invalid JSON received\n";
                return;
            }
            
            // Handle user subscription/registration
            if (isset($data['type']) && $data['type'] === 'subscribe') {
                $this->handleSubscription($from, $data);
                return;
            }
            
            // Handle user registration for location tracking (existing functionality)
            if (isset($data['user_id'])) {
                $usrData = new SUser(
                    $data['user_id'],
                    $data['user_type'] ?? 'deliveryman',
                    $from->resourceId
                );

                $alreadyExists = false;
                foreach ($this->userConnections as $existingUser) {
                    if ($existingUser->resourceId === $from->resourceId) {
                        $alreadyExists = true;
                        break;
                    }
                }
                if (!$alreadyExists) {
                    $this->userConnections[] = $usrData;
                }
            }
            
            // Handle chat message broadcasting
            if (isset($data['type']) && $data['type'] === 'message') {
                $this->handleChatMessage($from, $data);
                return;
            }
            
            // Handle typing indicator
            if (isset($data['type']) && $data['type'] === 'typing') {
                $this->handleTypingIndicator($from, $data);
                return;
            }

            // Handle delivery location (existing functionality)
            if (isset($data['user_id'], $data['type'], $data['lat'], $data['lng']) && $data['type'] =='deliveryman') {
                $user = self::findUserByResourceId($this->userConnections,$from->resourceId) ;
                if ($user && $user->user_type === 'deliveryman') {
                    event(new DeliveryLocationUpdated($from, $msg, $this->clients, $this->userConnections));
                }else{
                    echo "User {$user->id} is not a deliveryman.\n";
                }
            }
            
        } catch (\Exception $e) {
            Log::error("Failed to decode JSON: {$e->getMessage()}");
            echo "Error processing message: {$e->getMessage()}\n";
            return;
        }
    }
    
    protected function handleSubscription(ConnectionInterface $from, $data)
    {
        if (isset($data['channel'], $data['user_id'], $data['user_type'])) {
            // Store the subscription info in the connection
            $from->channel = $data['channel'];
            $from->user_id = $data['user_id'];
            $from->user_type = $data['user_type'];
            
            echo "User {$data['user_id']} ({$data['user_type']}) subscribed to channel: {$data['channel']}\n";
            
            // Send confirmation
            $from->send(json_encode([
                'type' => 'subscription_confirmed',
                'channel' => $data['channel']
            ]));
        }
    }
    
    protected function handleChatMessage(ConnectionInterface $from, $data)
    {
        if (isset($data['conversation_id'], $data['message'])) {
            // Broadcast to all connected clients in the same conversation
            $this->broadcastToConversation($data['conversation_id'], $data, $from);
            echo "Broadcasting chat message for conversation {$data['conversation_id']}\n";
        }
    }
    
    protected function handleTypingIndicator(ConnectionInterface $from, $data)
    {
        if (isset($data['conversation_id'], $data['is_typing'])) {
            // Broadcast typing indicator to other users in the conversation
            $this->broadcastToConversation($data['conversation_id'], $data, $from, true);
            echo "Broadcasting typing indicator for conversation {$data['conversation_id']}\n";
        }
    }
    
    protected function broadcastToConversation($conversationId, $data, ConnectionInterface $sender, $excludeSender = false)
    {
        foreach ($this->clients as $client) {
            // Skip sender if specified
            if ($excludeSender && $client === $sender) {
                continue;
            }
            
            // Check if client is subscribed to a relevant channel
            if (isset($client->channel)) {
                // For chat messages, broadcast to admin and customer channels
                $shouldReceive = false;
                
                if (strpos($client->channel, 'chat.admin.') === 0 || 
                    strpos($client->channel, 'chat.customer.') === 0) {
                    $shouldReceive = true;
                }
                
                if ($shouldReceive) {
                    try {
                        $client->send(json_encode($data));
                    } catch (\Exception $e) {
                        echo "Failed to send message to client: {$e->getMessage()}\n";
                    }
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        foreach ($this->userConnections as $key => $user) {
            if ($user->resourceId === $conn->resourceId) {
                unset($this->userConnections[$key]);
                echo "User {$user->id} disconnected from conn {$conn->resourceId}\n";
            }
        }
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    /*cusom functions*/


    public static  function findUserByResourceId($userConnections, $resourceId) : ?SUser
    {
        foreach ($userConnections as $user) {
            if ($user->resourceId === $resourceId) {
                return $user;
            }
        }
        return null;
    }

    public static function findUserById($userConnections,$userId) : ?SUser
    {
        foreach ($userConnections as $user) {
            if ($user->id === $userId) {
                return $user;
            }
        }
        return null;
    }

}

class SUser {
    public $id;
    public $user_type;
    public $resourceId;
    public $has_active_order = false;
    public $customer_ids = null; // timestamp in seconds

    public function __construct($id, $user_type, $resourceId) {
        $this->id = $id;
        $this->user_type = $user_type;
        $this->resourceId = $resourceId;
        $this->customer_ids = [];
    }
}

