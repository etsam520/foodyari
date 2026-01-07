<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliverymanNotification extends Notification
{
    use Queueable;

    protected $notificationData;

    /**
     * Create a new notification instance.
     */
    public function __construct(array $data)
    {
        $this->notificationData = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->notificationData['title'] ?? 'Foodyari Notification')
            ->line($this->notificationData['message'] ?? 'You have a new notification')
            ->action('View Details', url('/deliveryman/admin/notifications'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->notificationData['title'] ?? 'Notification',
            'message' => $this->notificationData['message'] ?? 'You have a new notification',
            'type' => $this->notificationData['type'] ?? 'general',
            'data' => $this->notificationData['data'] ?? [],
            'action_url' => $this->notificationData['action_url'] ?? null,
            'created_at' => now(),
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return $this->toArray($notifiable);
    }
}