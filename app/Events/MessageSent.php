<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $receiverId;
    public $receiverType;

    public function __construct(Message $message, $receiverId, $receiverType)
    {
        $this->message = $message;
        $this->receiverId = $receiverId;
        $this->receiverType = $receiverType;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("chat.{$this->receiverType}.{$this->receiverId}");
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'conversation_id' => $this->message->conversation_id,
                'message' => $this->message->message,
                'sender_id' => $this->message->sender_id,
                'sender_type' => $this->message->sender_type,
                'created_at' => $this->message->created_at->format('Y-m-d H:i:s'),
                'sender' => [
                    'full_name' => $this->message->sender->full_name ?? 'Unknown',
                    'image' => $this->message->sender->image ?? null
                ]
            ]
        ];
    }
}
