<?php

use Illuminate\Support\Facades\Route;
use App\Services\WebSocketService;

Route::get('/test-websocket', function () {
    $webSocketService = new WebSocketService();
    
    $testMessage = (object) [
        'id' => 999,
        'message' => 'Test message from Laravel at ' . now(),
        'attachments' => null,
        'attachment_type' => null,
        'sender_id' => 1,
        'sender_type' => 'admin',
        'created_at' => now(),
        'is_seen' => false
    ];
    
    $webSocketService->broadcastChatMessage($testMessage, 1);
    
    return response()->json(['status' => 'Message broadcasted', 'message' => $testMessage]);
});

Route::get('/test-chat-create', function () {
    // Create a test conversation and message
    $admin = \App\Models\Admin::first();
    $customer = \App\Models\Customer::first();
    
    if (!$admin || !$customer) {
        return response()->json(['error' => 'Admin or customer not found']);
    }
    
    // Find or create conversation
    $conversation = \App\Models\Conversation::firstOrCreate([
        'sender_id' => $admin->id,
        'sender_type' => 'admin',
        'receiver_id' => $customer->id,
        'receiver_type' => 'customer'
    ], [
        'unread_message_count' => 0,
        'last_message_time' => now()
    ]);
    
    // Create a test message
    $message = \App\Models\Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $admin->id,
        'sender_type' => 'admin',
        'message' => 'Test message created at ' . now(),
        'is_seen' => false
    ]);
    
    // Broadcast it
    $webSocketService = new WebSocketService();
    $webSocketService->broadcastChatMessage($message, $conversation->id);
    
    return response()->json([
        'status' => 'Test message created and broadcasted',
        'conversation_id' => $conversation->id,
        'message_id' => $message->id
    ]);
});
