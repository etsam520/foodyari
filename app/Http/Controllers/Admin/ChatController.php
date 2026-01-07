<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Get all conversations for this admin
        $conversations = Conversation::where(function($query) use ($admin) {
                $query->where('sender_id', $admin->id)->where('sender_type', 'admin')
                      ->orWhere('receiver_id', $admin->id)->where('receiver_type', 'admin');
            })
            ->with(['lastMessage'])
            ->orderBy('last_message_time', 'desc')
            ->get();

        // Manually load sender and receiver data for each conversation
        foreach ($conversations as $conversation) {
            // Load sender
            if ($conversation->sender_type === 'admin') {
                $conversation->sender = Admin::find($conversation->sender_id);
            } elseif ($conversation->sender_type === 'customer') {
                $conversation->sender = Customer::find($conversation->sender_id);
            }

            // Load receiver  
            if ($conversation->receiver_type === 'admin') {
                $conversation->receiver = Admin::find($conversation->receiver_id);
            } elseif ($conversation->receiver_type === 'customer') {
                $conversation->receiver = Customer::find($conversation->receiver_id);
            }
        }

        return view('admin-views.chat.index', compact('conversations'));
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->get('search');
        
        $customers = Customer::when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('f_name', 'like', "%{$search}%")
                      ->orWhere('l_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->select('id', 'f_name', 'l_name', 'phone', 'email', 'image')
            ->limit(20)
            ->get();

        return response()->json($customers);
    }

    public function startConversation(Request $request)
    {
        $admin = Helpers::getAdmin();
        $customerId = $request->customer_id;

        // Check if conversation already exists
        $conversation = Conversation::where(function($query) use ($admin, $customerId) {
            $query->where(function($q) use ($admin, $customerId) {
                $q->where('sender_id', $admin->id)->where('sender_type', 'admin')
                  ->where('receiver_id', $customerId)->where('receiver_type', 'customer');
            })->orWhere(function($q) use ($admin, $customerId) {
                $q->where('sender_id', $customerId)->where('sender_type', 'customer')
                  ->where('receiver_id', $admin->id)->where('receiver_type', 'admin');
            });
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $admin->id,
                'sender_type' => 'admin',
                'receiver_id' => $customerId,
                'receiver_type' => 'customer',
                'unread_message_count' => 0,
                'last_message_time' => now()
            ]);
        }

        return redirect()->route('admin.chat.conversation', $conversation->id);
    }

    public function conversation($conversationId)
    {
        $admin = Helpers::getAdmin();
        $conversation = Conversation::with(['messages'])
            ->findOrFail($conversationId);

        // Verify admin has access to this conversation
        if (($conversation->sender_type == 'admin' && $conversation->sender_id != $admin->id) && 
            ($conversation->receiver_type == 'admin' && $conversation->receiver_id != $admin->id)) {
            abort(403);
        }

        // Load messages and manually load sender/receiver data
        $conversation->load(['messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        // Manually load sender and receiver
        if ($conversation->sender_type === 'admin') {
            $conversation->sender = Admin::find($conversation->sender_id);
        } elseif ($conversation->sender_type === 'customer') {
            $conversation->sender = Customer::find($conversation->sender_id);
        }

        if ($conversation->receiver_type === 'admin') {
            $conversation->receiver = Admin::find($conversation->receiver_id);
        } elseif ($conversation->receiver_type === 'customer') {
            $conversation->receiver = Customer::find($conversation->receiver_id);
        }

        // Load sender for each message
        foreach ($conversation->messages as $message) {
            if ($message->sender_type === 'admin') {
                $message->sender = Admin::find($message->sender_id);
            } elseif ($message->sender_type === 'customer') {
                $message->sender = Customer::find($message->sender_id);
            }
        }

        // Mark messages as read
        $conversation->markMessagesAsRead($admin->id, 'admin');

        $otherUser = $conversation->getOtherUser($admin->id, 'admin');

        return view('admin-views.chat.conversation', compact('conversation', 'otherUser'));
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        // Check if message or attachments are provided
        if (empty($request->message) && empty($request->file('attachments'))) {
            return response()->json(['error' => 'Message or attachments required'], 422);
        }

        $admin = Auth::guard('admin')->user();
        
        $conversation = Conversation::findOrFail($conversationId);

        // Verify admin has access to this conversation
        if ($conversation->sender_id != $admin->id && $conversation->receiver_id != $admin->id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $attachmentData = [];
            $attachmentType = null;
            
            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $filePath = $file->storeAs('chat_attachments', $fileName, 'public');
                    
                    $attachmentData[] = [
                        'name' => $file->getClientOriginalName(),
                        'path' => $filePath,
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType(),
                        'extension' => $file->getClientOriginalExtension()
                    ];
                    
                    if (!$attachmentType) {
                        $attachmentType = $file->getMimeType();
                    }
                }
            }

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $admin->id,
                'sender_type' => 'admin',
                'message' => $request->message,
                'attachments' => !empty($attachmentData) ? $attachmentData : null,
                'attachment_type' => $attachmentType,
                'is_seen' => false
            ]);

            // Update conversation
            $conversation->update([
                'last_message_id' => $message->id,
                'last_message_time' => now(),
                'unread_message_count' => $conversation->unread_message_count + 1
            ]);

            DB::commit();

            // Broadcast the message via WebSocket
            $webSocketService = new \App\Services\WebSocketService();
            $webSocketService->broadcastChatMessage($message, $conversation->id);

            // Always return JSON response for AJAX requests
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'attachments' => $message->attachments,
                    'attachment_type' => $message->attachment_type,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'created_at' => $message->created_at->format('H:i'),
                    'is_seen' => $message->is_seen,
                    'sender' => [
                        'full_name' => $admin->full_name,
                        'image' => $admin->image
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getMessages(Request $request, $conversationId)
    {
        $admin = Auth::guard('admin')->user();
        $conversation = Conversation::findOrFail($conversationId);

        // Verify access
        if ($conversation->sender_id != $admin->id && $conversation->receiver_id != $admin->id) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'is_seen' => $message->is_seen,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'sender' => [
                        'full_name' => $message->sender->full_name ?? 'Unknown',
                        'image' => $message->sender->image ?? null
                    ]
                ];
            });

        return response()->json($messages);
    }

    public function markAsRead(Request $request, $conversationId)
    {
        $admin = Auth::guard('admin')->user();
        $conversation = Conversation::findOrFail($conversationId);

        // Verify admin has access to this conversation
        if (($conversation->sender_type == 'admin' && $conversation->sender_id != $admin->id) && 
            ($conversation->receiver_type == 'admin' && $conversation->receiver_id != $admin->id)) {
            abort(403);
        }

        // Mark messages as read
        $conversation->markMessagesAsRead($admin->id, 'admin');

        return response()->json(['success' => true]);
    }

    /**
     * Delete a single message
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $admin = Auth::guard('admin')->user();
        $message = Message::findOrFail($messageId);
        
        // Verify admin has access to this message
        $conversation = $message->conversation;
        if ($conversation->sender_id != $admin->id && $conversation->receiver_id != $admin->id) {
            abort(403);
        }

        $message->softDeleteMessage();

        return response()->json(['success' => true, 'message' => 'Message deleted successfully']);
    }

    /**
     * Delete multiple messages
     */
    public function deleteMessages(Request $request)
    {
        $request->validate([
            'message_ids' => 'required|array',
            'message_ids.*' => 'exists:messages,id'
        ]);

        $admin = Auth::guard('admin')->user();
        $messageIds = $request->message_ids;
        
        $messages = Message::whereIn('id', $messageIds)->get();
        
        // Verify admin has access to all messages
        foreach ($messages as $message) {
            $conversation = $message->conversation;
            if ($conversation->sender_id != $admin->id && $conversation->receiver_id != $admin->id) {
                abort(403);
            }
        }

        // Delete all messages
        Message::whereIn('id', $messageIds)->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Messages deleted successfully']);
    }

    /**
     * Clear entire conversation
     */
    public function clearConversation(Request $request, $conversationId)
    {
        $admin = Auth::guard('admin')->user();
        $conversation = Conversation::findOrFail($conversationId);

        // Verify admin has access to this conversation
        if ($conversation->sender_id != $admin->id && $conversation->receiver_id != $admin->id) {
            abort(403);
        }

        // Soft delete all messages in the conversation
        $conversation->messages()->update([
            'is_deleted' => true,
            'deleted_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'Conversation cleared successfully']);
    }
}
