<?php

namespace App\Http\Controllers\DeliveryBoy;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the notifications page
     */
    public function index()
    {
        $user = auth('delivery_men')->user();
        $unreadCount = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        return view('deliveryman.admin.notifications.index', compact('unreadCount'));
    }

    /**
     * Fetch notifications with proper pagination
     */
    public function fetchNotifications(Request $request)
    {
        try {
            $user = auth('delivery_men')->user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            $type = $request->input('type', 'all'); // all, unread, read
            
            $query = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id);
            
            // Filter by type
            if ($type === 'unread') {
                $query->whereNull('read_at');
            } elseif ($type === 'read') {
                $query->whereNotNull('read_at');
            }
            
            // Order by latest first
            $query->orderBy('created_at', 'desc');
            
            // Paginate
            $notifications = $query->paginate($perPage, ['*'], 'page', $page);
            
            $formattedNotifications = $notifications->getCollection()->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? 'You have a new notification',
                    'data' => $notification->data,
                    'is_read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at->diffForHumans(),
                    'created_at_full' => $notification->created_at->format('M j, Y g:i A'),
                ];
            });
            
            return response()->json([
                'success' => true,
                'notifications' => $formattedNotifications,
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'has_more' => $notifications->hasMorePages()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        try {
            Log::info('markAsRead called', ['id' => $id, 'request_url' => request()->fullUrl()]);
            
            $user = auth('delivery_men')->user();
            
            if (!$user) {
                Log::error('User not authenticated in markAsRead');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            $notification = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->where('id', $id)
                ->first();
            
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            if (is_null($notification->read_at)) {
                $notification->markAsRead();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'notification_id' => $id,
                'is_read' => !is_null($notification->fresh()->read_at)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to mark notification as read', [
                'notification_id' => $id,
                'user_id' => auth('delivery_men')->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            $user = auth('delivery_men')->user();
            DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific notification
     */
    public function deleteNotification($id)
    {
        try {
            $user = auth('delivery_men')->user();
            $notification = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->where('id', $id)
                ->first();
                
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }
            
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all notifications
     */
    public function deleteAll()
    {
        try {
            $user = auth('delivery_men')->user();
            DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications deleted successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete all notifications: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadCount()
    {
        try {
            $user = auth('delivery_men')->user();
            $count = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count: ' . $e->getMessage(),
                'count' => 0
            ], 500);
        }
    }

    /**
     * Get notification statistics
     */
    public function getStats()
    {
        try {
            $user = auth('delivery_men')->user();
            
            $total = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->count();
                
            $unread = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->count();
                
            $read = $total - $unread;
            
            // Get notifications from last 7 days
            $recent = DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                ->where('notifiable_id', $user->id)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count();
            
            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $total,
                    'unread' => $unread,
                    'read' => $read,
                    'recent' => $recent
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get notification stats: ' . $e->getMessage()
            ], 500);
        }
    }
}