<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/
Broadcast::channel('order', function ($user) {
    return !is_null($user);
},['guards' => ['web', 'admin','delivery_men']]);

Broadcast::channel('order.{id}', function ($user, $id) {
    // return $user->id === Order::find($id)->user_id;
    return true; // simple auth check, customize as needed
},['guards' => ['web', 'admin','delivery_men']]);

Broadcast::channel('user.{id}', function ($user, $id) {
    // return (int) $user->id === (int) $id;
    true;
},['guards' => ['web', 'admin','delivery_men','vendor','restaurant']]);
Broadcast::channel('admin', function ($user) {
    return !is_null($user);
    // return $user->isAdmin();
},['guards' => ['admin']]);
Broadcast::channel('deliveryman.{id}', function ($user, $id) {
    return !is_null($user);
    // return $user->isAdmin();
},['guards' => ['delivery_men']]);
Broadcast::channel('vendor.{id}', function ($user, $id) {
    return !is_null($user);
},['guards' => ['vendor']]);

// Chat Channels
Broadcast::channel('chat.admin.{id}', function ($user, $id) {
    return auth('admin')->check() && (int) $user->id === (int) $id;
}, ['guards' => ['admin']]);

Broadcast::channel('chat.customer.{id}', function ($user, $id) {
    return auth('customer')->check() && (int) $user->id === (int) $id;
}, ['guards' => ['web', 'customer']]);

Broadcast::channel('conversation.{id}', function ($user, $conversationId) {
    // Allow both admin and customer to join conversation channels
    $conversation = \App\Models\Conversation::find($conversationId);
    if (!$conversation) return false;
    
    // Check if user is part of this conversation
    if (auth('admin')->check()) {
        return $conversation->sender_id == $user->id && $conversation->sender_type == 'admin' ||
               $conversation->receiver_id == $user->id && $conversation->receiver_type == 'admin';
    }
    
    if (auth('customer')->check()) {
        return $conversation->sender_id == $user->id && $conversation->sender_type == 'customer' ||
               $conversation->receiver_id == $user->id && $conversation->receiver_type == 'customer';
    }
    
    return false;
}, ['guards' => ['web', 'admin', 'customer']]);
