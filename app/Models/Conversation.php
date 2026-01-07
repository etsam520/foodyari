<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'unread_message_count',
        'last_message_id',
        'last_message_time'
    ];

    protected $casts = [
        'last_message_time' => 'datetime'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function sender()
    {
        if ($this->sender_type === 'admin') {
            return $this->belongsTo(Admin::class, 'sender_id');
        } elseif ($this->sender_type === 'customer') {
            return $this->belongsTo(Customer::class, 'sender_id');
        }
        return null;
    }

    public function receiver()
    {
        if ($this->receiver_type === 'admin') {
            return $this->belongsTo(Admin::class, 'receiver_id');
        } elseif ($this->receiver_type === 'customer') {
            return $this->belongsTo(Customer::class, 'receiver_id');
        }
        return null;
    }

    public function scopeWhereUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)->orWhere('receiver_id', $userId);
        });
    }

    public function scopeWhereUserType($query, $userType)
    {
        return $query->where(function ($q) use ($userType) {
            $q->where('sender_type', $userType)->orWhere('receiver_type', $userType);
        });
    }

    public function getOtherUser($currentUserId, $currentUserType)
    {
        if ($this->sender_id == $currentUserId && $this->sender_type == $currentUserType) {
            return [
                'id' => $this->receiver_id,
                'type' => $this->receiver_type,
                'user' => $this->receiver
            ];
        }
        return [
            'id' => $this->sender_id,
            'type' => $this->sender_type,
            'user' => $this->sender
        ];
    }

    public function markMessagesAsRead($userId, $userType)
    {
        $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('sender_type', '!=', $userType)
            ->where('is_seen', false)
            ->update([
                'is_seen' => true,
                'seen_at' => now()
            ]);
            
        self::find($this->id)->update(['unread_message_count' => 0]);
    }
}
