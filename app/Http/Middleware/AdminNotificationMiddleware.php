<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminNotificationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            
            // Get unread notification count
            $unreadCount = 0;
            try {
                $unreadCount = DB::table('notifications')
                    ->where('notifiable_type', get_class($admin))
                    ->where('notifiable_id', $admin->id)
                    ->whereNull('read_at')
                    ->count();
            } catch (\Exception $e) {
                Log::warning('Failed to get admin notification count: ' . $e->getMessage());
            }
            
            // Share with all admin views
            View::share('adminNotificationCount', $unreadCount);
        }

        return $next($request);
    }
}