<?php

namespace App\Services;

use App\Models\Customer;
use App\Notifications\CommonNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    protected $webSocketService;

    public function __construct()
    {
        $this->webSocketService = new WebSocketService();
    }

    /**
     * Send notification to customer with real-time broadcast
     */
    public function sendToCustomer(Customer $customer, array $notificationData)
    {
        try {
            // Send Laravel notification (will be stored in database)
            $customer->notify(new CommonNotification($notificationData['type'] ?? 'general', $notificationData));

            // Broadcast via WebSocket for real-time update
            $this->broadcastNotification($customer->id, 'customer', $notificationData);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send notification to customer: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk notifications to multiple customers
     */
    public function sendBulkToCustomers($customers, array $notificationData)
    {
        try {
            // Send Laravel notifications
            Notification::send($customers, new CommonNotification($notificationData['type'] ?? 'general', $notificationData));

            // Broadcast to each customer via WebSocket
            foreach ($customers as $customer) {
                $this->broadcastNotification($customer->id, 'customer', $notificationData);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send bulk notifications: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Broadcast notification update via WebSocket
     */
    public function broadcastNotification($userId, $userType, $notificationData)
    {
        $data = [
            'type' => 'notification',
            'user_id' => $userId,
            'user_type' => $userType,
            'notification' => [
                'title' => $notificationData['subject'] ?? $notificationData['title'] ?? 'New Notification',
                'message' => $notificationData['message'] ?? 'You have a new notification',
                'image' => $notificationData['image'] ?? null,
                'created_at' => now()->toISOString(),
                'read_at' => null
            ]
        ];

        $this->webSocketService->sendMessage($data);
    }

    /**
     * Broadcast notification count update
     */
    public function broadcastUnreadCount($userId, $userType, $unreadCount)
    {
        $data = [
            'type' => 'unread_count',
            'user_id' => $userId,
            'user_type' => $userType,
            'unread_count' => $unreadCount
        ];

        $this->webSocketService->sendMessage($data);
    }

    /**
     * Broadcast notification marked as read
     */
    public function broadcastNotificationRead($userId, $userType, $notificationId, $unreadCount)
    {
        $data = [
            'type' => 'notification_read',
            'user_id' => $userId,
            'user_type' => $userType,
            'notification_id' => $notificationId,
            'unread_count' => $unreadCount
        ];

        $this->webSocketService->sendMessage($data);
    }

    /**
     * Broadcast notification deleted
     */
    public function broadcastNotificationDeleted($userId, $userType, $notificationId, $unreadCount)
    {
        $data = [
            'type' => 'notification_deleted',
            'user_id' => $userId,
            'user_type' => $userType,
            'notification_id' => $notificationId,
            'unread_count' => $unreadCount
        ];

        $this->webSocketService->sendMessage($data);
    }

    /**
     * Send notification to admin(s) with real-time broadcast
     */
    public function sendToAdmin($title, $message, $type = 'system', $actionUrl = null, $data = [], $adminId = null)
    {
        try {
            $admins = $adminId ? [\App\Models\Admin::find($adminId)] : \App\Models\Admin::all();
            $admins = array_filter($admins); // Remove null entries
            
            if (empty($admins)) {
                return false;
            }

            $notification = new \App\Notifications\AdminNotification($title, $message, $type, $actionUrl, $data);
            
            foreach ($admins as $admin) {
                if ($admin) {
                    $admin->notify($notification);
                    
                    // Send real-time update
                    $this->broadcastAdminNotification($admin->id, $title, $message, $type);
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Broadcast admin notification via WebSocket
     */
    protected function broadcastAdminNotification($adminId, $title, $message, $type)
    {
        try {
            $unreadCount = \App\Models\Admin::find($adminId)->unreadNotifications()->count();
            
            $data = [
                'type' => 'admin_notification',
                'admin_id' => $adminId,
                'title' => $title,
                'message' => $message,
                'notification_type' => $type,
                'unread_count' => $unreadCount,
                'timestamp' => now()->toISOString()
            ];

            $this->webSocketService->sendMessage($data);
        } catch (\Exception $e) {
            Log::warning('Failed to broadcast admin notification: ' . $e->getMessage());
        }
    }

    /**
     * Send order notification to admins
     */
    public function notifyOrderPlaced($order)
    {
        $title = "New Order Received";
        $message = "Order #{$order->id} placed by {$order->customer->name} for " . number_format($order->total_amount, 2);
        $actionUrl = route('admin.order.details', $order->id);
        
        $data = [
            'order_id' => $order->id,
            'customer_name' => $order->customer->name,
            'amount' => $order->total_amount
        ];

        return $this->sendToAdmin($title, $message, 'order', $actionUrl, $data);
    }

    /**
     * Send customer registration notification
     */
    public function notifyCustomerRegistered($customer)
    {
        $title = "New Customer Registration";
        $message = "Customer {$customer->name} registered with email {$customer->email}";
        $actionUrl = route('admin.customer.view', $customer->id);
        
        $data = [
            'customer_id' => $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email
        ];

        return $this->sendToAdmin($title, $message, 'customer', $actionUrl, $data);
    }

    /**
     * Send restaurant registration notification
     */
    public function notifyRestaurantRegistered($restaurant)
    {
        $title = "New Restaurant Registration";
        $message = "Restaurant '{$restaurant->name}' registered and awaiting approval";
        $actionUrl = route('admin.restaurant.view', $restaurant->id);
        
        $data = [
            'restaurant_id' => $restaurant->id,
            'restaurant_name' => $restaurant->name,
        ];

        return $this->sendToAdmin($title, $message, 'restaurant', $actionUrl, $data);
    }

    /**
     * Send system maintenance notification
     */
    public function notifySystemMaintenance($details)
    {
        $title = "System Maintenance Alert";
        $message = "Maintenance scheduled for {$details['date']} at {$details['time']}";
        
        return $this->sendToAdmin($title, $message, 'system', null, $details);
    }
}