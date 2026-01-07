<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Kreait\Firebase\Contract\Messaging;
use Illuminate\Support\Facades\Log;

class FirebaseChannel
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function send($notifiable, Notification $notification)
    {
        $token = $notifiable->routeNotificationFor('fcm');

        if (!$token) {
            Log::error('FCM token is missing');
            return;
        }

        $message = $notification->toFcm($notifiable);

        try {
            $this->messaging->send($message);
            Log::info('FCM message sent', ['message' => $message]);
        } catch (\Exception $e) {
            Log::error('Error sending FCM message', ['error' => $e->getMessage()]);
        }
    }
}
