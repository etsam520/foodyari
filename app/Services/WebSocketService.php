<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WebSocketService
{
    protected $host;
    protected $port;

    public function __construct()
    {
        $this->host = '127.0.0.1';
        $this->port = 6002;
    }

    /**
     * Send message to WebSocket server
     */
    public function sendMessage($data)
    {
        try {
            // Create a socket connection to our WebSocket server
            $context = stream_context_create();
            
            // Convert data to WebSocket frame format
            $payload = json_encode($data);
            
            // For simplicity, we'll use a different approach - 
            // Store in Redis and let the WebSocket server poll it
            // or use ReactPHP socket client
            
            $this->sendViaReactSocket($payload);
            
        } catch (\Exception $e) {
            Log::error('WebSocket send error: ' . $e->getMessage());
        }
    }

    protected function sendViaReactSocket($payload)
    {
        // For now, we'll log the message and let the WebSocket handle polling
        // In production, you'd want to use ReactPHP socket client or similar
        Log::info('WebSocket Message: ' . $payload);
        
        // We can also store in cache/redis for the WebSocket server to pick up
        Cache::put('websocket_message_' . uniqid(), $payload, 60);
    }

    /**
     * Broadcast chat message
     */
    public function broadcastChatMessage($message, $conversationId)
    {
        $data = [
            'type' => 'message',
            'conversation_id' => $conversationId,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'attachments' => $message->attachments,
                'attachment_type' => $message->attachment_type,
                'sender_id' => $message->sender_id,
                'sender_type' => $message->sender_type,
                'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                'is_seen' => $message->is_seen
            ]
        ];

        $this->sendMessage($data);
    }

    /**
     * Broadcast typing indicator
     */
    public function broadcastTyping($conversationId, $userId, $userType, $isTyping)
    {
        $data = [
            'type' => 'typing',
            'conversation_id' => $conversationId,
            'user_id' => $userId,
            'user_type' => $userType,
            'is_typing' => $isTyping
        ];

        $this->sendMessage($data);
    }
}
