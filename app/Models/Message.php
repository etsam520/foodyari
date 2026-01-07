<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_type',
        'message',
        'attachment',
        'attachments',
        'attachment_type',
        'is_seen',
        'seen_at',
        'is_deleted',
        'deleted_at'
    ];

    protected $casts = [
        'is_seen' => 'boolean',
        'is_deleted' => 'boolean',
        'seen_at' => 'datetime',
        'deleted_at' => 'datetime',
        'attachments' => 'array'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
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

    public function scopeUnread($query)
    {
        return $query->where('is_seen', false);
    }

    public function scopeForUser($query, $userId, $userType)
    {
        return $query->where('sender_id', $userId)->where('sender_type', $userType);
    }

    public function markAsRead()
    {
        $this->update([
            'is_seen' => true,
            'seen_at' => now()
        ]);
    }

    /**
     * Check if message has attachments
     */
    public function hasAttachments()
    {
        return !empty($this->attachments);
    }

    /**
     * Get attachment count
     */
    public function getAttachmentCount()
    {
        return count($this->attachments ?? []);
    }

    /**
     * Check if message is an image attachment
     */
    public function isImageAttachment()
    {
        return in_array($this->attachment_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Soft delete message
     */
    public function softDeleteMessage()
    {
        $this->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);
    }
}
