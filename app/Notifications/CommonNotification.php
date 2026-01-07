<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class CommonNotification extends Notification
{
    use Queueable;
    public $type, $data, $notification_subject, $notification_message, $notification_image;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type, $data)
    {
        $this->type = $type;
        $this->data = $data;
        $this->notification_subject = isset($this->data['subject']) ? str_replace("_", " ", ucfirst($this->data['subject'])) : config('app.name');
        $this->notification_message = isset($this->data['message']) ? $this->data['message'] : "You have new notification.";
        $this->notification_image = isset($this->data['image']) ? $this->data['image'] : null;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        $data = [
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'sound' => 'default',
            'status' => 'done',
            'id' => $this->data['id'] ?? '',
            'type' => $this->type,
            'message' => $this->notification_message,
        ];

        $notification = FcmNotification::create()
            ->setTitle($this->notification_subject)
            ->setBody($this->notification_message)
            ->setImage($this->notification_image);

        $message = new FcmMessage();
        $message->setNotification($notification);
        $message->setData($data);
        return $message;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->data;
    }
}
