<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $user = auth('customer')->user();

        $unreadCount = $user
            ? $user->unreadNotifications()->count()
            : 0;

        return view(
            'user-views.restaurant.notifications.index',
            compact('unreadCount')
        );
    }

    /**
     * Fetch notifications with proper pagination
     */
    public function fetchNotifications(Request $request)
    {
        try {
            $user = auth('customer')->user();
            $page = $request->input('page', 1);
            $perPage = $request->input('per_page', 10);
            $type = $request->input('type', 'all'); // all, unread, read
            
            $query = $user->notifications();
            
            // Filter by type
            if ($type === 'unread') {
                $query->whereNull('read_at');
            } elseif ($type === 'read') {
                $query->whereNotNull('read_at');
            }
            
            $notifications = $query
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
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
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            $user = auth('customer')->user();
            $notification = $user->notifications()->findOrFail($id);
            
            if (!$notification->read_at) {
                $notification->markAsRead();
                
                // Broadcast the update via WebSocket
                $unreadCount = $user->unreadNotifications()->count();
                $this->notificationService->broadcastNotificationRead($user->id, 'customer', $id, $unreadCount);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read!',
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = auth('customer')->user();
            $user->unreadNotifications()->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read!',
                'unread_count' => 0
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific notification
     */
    public function deleteNotification(Request $request, $id)
    {
        try {
            $user = auth('customer')->user();
            $notification = $user->notifications()->findOrFail($id);
            $notification->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully!',
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete all notifications
     */
    public function deleteAll(Request $request)
    {
        try {
            $user = auth('customer')->user();
            $type = $request->input('type', 'all'); // all, read
            
            $query = $user->notifications();
            
            if ($type === 'read') {
                $query->whereNotNull('read_at');
            }
            
            $query->delete();
            
            return response()->json([
                'success' => true,
                'message' => $type === 'read' ? 'All read notifications deleted!' : 'All notifications deleted!',
                'unread_count' => $user->unreadNotifications()->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notification count
     */
    public function getUnreadCount(Request $request)
    {
        try {
            $user = auth('customer')->user();
            $count = $user->unreadNotifications()->count();
            
            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
