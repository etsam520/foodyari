<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ChatController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        // Get all conversations for this customer
        $conversations = Conversation::where(function($query) use ($customer) {
                $query->where('sender_id', $customer->id)->where('sender_type', 'customer')
                      ->orWhere('receiver_id', $customer->id)->where('receiver_type', 'customer');
            })
            ->with(['lastMessage'])
            ->orderBy('last_message_time', 'desc')
            ->get();

        // Manually load sender and receiver data for each conversation
        foreach ($conversations as $conversation) {
            // Load sender
            if ($conversation->sender_type === 'admin') {
                $conversation->sender = \App\Models\Admin::find($conversation->sender_id);
            } elseif ($conversation->sender_type === 'customer') {
                $conversation->sender = \App\Models\Customer::find($conversation->sender_id);
            }

            // Load receiver  
            if ($conversation->receiver_type === 'admin') {
                $conversation->receiver = \App\Models\Admin::find($conversation->receiver_id);
            } elseif ($conversation->receiver_type === 'customer') {
                $conversation->receiver = \App\Models\Customer::find($conversation->receiver_id);
            }
        }

        return view('user-views.chat.index', compact('conversations'));
    }

    public function startConversationWithAdmin(Request $request)
    {
        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        // Get the main admin or first available admin
        $admin = Admin::first();
        
        if (!$admin) {
            return back()->with('error', 'No admin available for chat');
        }

        // Check if conversation already exists
        $conversation = Conversation::where(function($query) use ($customer, $admin) {
            $query->where(function($q) use ($customer, $admin) {
                $q->where('sender_id', $customer->id)->where('sender_type', 'customer')
                  ->where('receiver_id', $admin->id)->where('receiver_type', 'admin');
            })->orWhere(function($q) use ($customer, $admin) {
                $q->where('sender_id', $admin->id)->where('sender_type', 'admin')
                  ->where('receiver_id', $customer->id)->where('receiver_type', 'customer');
            });
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $customer->id,
                'sender_type' => 'customer',
                'receiver_id' => $admin->id,
                'receiver_type' => 'admin',
                'unread_message_count' => 0,
                'last_message_time' => now()
            ]);
        }

        return redirect()->route('user.chat.conversation', $conversation->id);
    }

    public function conversation($conversationId)
    {
        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return redirect()->route('user.auth.login');
        }

        $conversation = Conversation::with(['messages'])
            ->findOrFail($conversationId);

        // Verify customer has access to this conversation
        if ($conversation->sender_id != $customer->id && $conversation->receiver_id != $customer->id) {
            abort(403);
        }
        // if ($conversation->messages->isEmpty()) {
        //     return redirect()->route('user.chat.index')->with('error', 'No messages in this conversation.');
        // }

        if($conversation->sender_type === 'customer'){
            $conversation->sender = Customer::find($conversation->sender_id);
        } elseif($conversation->sender_type === 'admin'){
            $conversation->sender = Admin::find($conversation->sender_id);
        }

        if($conversation->receiver_type === 'customer'){
            $conversation->receiver = Customer::find($conversation->receiver_id);
        } elseif($conversation->receiver_type === 'admin'){
            $conversation->receiver = Admin::find($conversation->receiver_id);
        }

       foreach ($conversation->messages as $message) {
            if ($message->sender_type === 'customer') {
                $message->sender = Admin::find($message->sender_id);
            } elseif ($message->sender_type === 'customer') {
                $message->sender = Customer::find($message->sender_id);
            } elseif ($message->sender_type === 'admin') {
                $message->sender = Admin::find($message->sender_id);
            }
        }

        // Mark messages as read
        $conversation->markMessagesAsRead($customer->id, 'customer');

        $otherUser = $conversation->getOtherUser($customer->id, 'customer');

        return view('user-views.chat.conversation', compact('conversation', 'otherUser'));
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

        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $conversation = Conversation::findOrFail($conversationId);

        // Verify customer has access to this conversation
        if ($conversation->sender_id != $customer->id && $conversation->receiver_id != $customer->id) {
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
                'sender_id' => $customer->id,
                'sender_type' => 'customer',
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
                        'full_name' => $customer->full_name ?? $customer->f_name . ' ' . $customer->l_name,
                        'image' => $customer->image
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
        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $conversation = Conversation::findOrFail($conversationId);

        // Verify access
        if ($conversation->sender_id != $customer->id && $conversation->receiver_id != $customer->id) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($message) {
                // Manually load sender
                $sender = null;
                if ($message->sender_type === 'admin') {
                    $sender = \App\Models\Admin::find($message->sender_id);
                } elseif ($message->sender_type === 'customer') {
                    $sender = \App\Models\Customer::find($message->sender_id);
                }

                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender_id' => $message->sender_id,
                    'sender_type' => $message->sender_type,
                    'is_seen' => $message->is_seen,
                    'created_at' => $message->created_at->format('Y-m-d H:i:s'),
                    'sender' => [
                        'full_name' => $sender->full_name ?? 'Unknown',
                        'image' => $sender->image ?? null
                    ]
                ];
            });

        return response()->json($messages);
    }

    /**
     * Delete a single message
     */
    public function deleteMessage(Request $request, $messageId)
    {
        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $message = Message::findOrFail($messageId);
        
        // Verify customer has access to this message
        $conversation = $message->conversation;
        if ($conversation->sender_id != $customer->id && $conversation->receiver_id != $customer->id) {
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

        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $messageIds = $request->message_ids;
        
        $messages = Message::whereIn('id', $messageIds)->get();
        
        // Verify customer has access to all messages
        foreach ($messages as $message) {
            $conversation = $message->conversation;
            if ($conversation->sender_id != $customer->id && $conversation->receiver_id != $customer->id) {
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
        $customer = Auth::guard('customer')->user() ?? Session::get('userInfo');
        
        if (!$customer) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        $conversation = Conversation::findOrFail($conversationId);

        // Verify customer has access to this conversation
        if ($conversation->sender_id != $customer->id && $conversation->receiver_id != $customer->id) {
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
