<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Channels\FirebaseChannel;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FCMNotification;

class FirebaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $data;
    protected $image;
    protected $message;

    public function __construct($data)
    {

        // $this->body = $body;


        $this->data = $data;
        $this->body = isset($this->data['body']) ? $this->data['body'] : null;
        $this->title  = isset($this->data['subject']) ? str_replace("_", " ", ucfirst($this->data['subject'])) : config('app.name');
        $this->message = isset($this->data['message']) ? $this->data['message'] : "You have new notification.";
        $this->image = isset($this->data['image']) ? $this->data['image'] : asset('assets/images/icons/foodyari.logo.jpg');


    }

    public function via($notifiable)
    {

        return ['database', FirebaseChannel::class];

        // return [FirebaseChannel::class];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'image' => $this->image,
            'data' => $this->data,
        ];
    }

    public function toFcm($notifiable)
    {
        $message = CloudMessage::withTarget('token', $notifiable->routeNotificationFor('fcm'))
            ->withNotification(FCMNotification::create($this->title, $this->message,$this->image))
            ->withData($this->data);

        Log::info('Constructed FCM message', ['message' => $message]); // Log the message

        return $message;
    }
}
