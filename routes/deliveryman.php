<?php

use App\Http\Controllers\DeliveryBoy\Auth\LoginController;
use App\Http\Controllers\DeliveryBoy\MainController;
use App\Http\Controllers\DeliveryBoy\mess\OrderController;
use App\Http\Controllers\DeliveryBoy\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\DeliveryBoy\CashController;
use App\Http\Controllers\DeliveryBoy\restaurant\OrderController as RestaurantOrderController;
use App\Http\Controllers\DeliveryBoy\WalletController;
use App\Http\Controllers\DeliveryBoy\NotificationController;
use Illuminate\Support\Facades\Route;

// mess-deliveryBoy

Route::group(['as' => 'deliveryman.'], function () {
    Route::post('forgot-password-otp', [LoginController::class, 'sendForgotPasswordOtp'])->name('forgot-password-otp');
    Route::get('resend-otp/{phone}', [LoginController::class , 'resendOtp'])->name('resend-otp');
    Route::post('forgot-password-save',[LoginController::class, 'forgotPasswordSave'])->name('forgot-password-save');

    Route::middleware('deliveryman')->group(function(){
        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::get('login', [LoginController::class, 'login'])->name('login');
            Route::get('forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot-password');
            Route::post('login-submit', [LoginController::class, 'submit'])->name('login-submit');
            Route::get('logout', [LoginController::class, 'logout'])->name('logout');
        });

        // Route::get('index', function (){return view('deliveryman.index');});
        Route::get('/', [LoginController::class , 'dashboard'])->name('dashboard');
        Route::get('/activate', [MainController::class , 'activate'])->name('activate');
        // Route::post('/location-update', [MainController::class , 'locationUpdate'])->middleware('throttle:location-update-throttle')->name('location-update');
        Route::post('/location-update', [MainController::class, 'locationUpdate'])
        ->middleware('throttle:location-update-throttle')
        ->name('location-update');

        Route::group([ 'prefix' => 'attendance', 'as' => 'attendance.' ], function () {
            Route::post('meter-check-in',[MainController::class, 'meter_check_in'])->name('meter-check-in');
            Route::post('meter-check-out',[MainController::class, 'meter_check_out'])->name('meter-check-out');
        });

        Route::get('/attendance',[MainController::class, 'attendance'] )->name('attendance');
        Route::get('/dm-working-report', [MainController::class, 'workingReport'])->name('dm-working-report');
        Route::get('/dm-distance-report',[MainController::class, 'distanceReport'])->name('dm-distance-report');
        Route::get('/dm-fuel-report',[MainController::class, 'fuelReport'])->name('dm-fuel-report');
        Route::get('/profile', [MainController::class, 'profile'])->name('profile');
        Route::post('/profile-update', [MainController::class, 'profileUpdate'])->name('profile-update');


        Route::group(['prefix' => 'mess', 'as'=> 'mess.'], function(){
            Route::get('orders',[OrderController::class, 'getOrders'])->name('orders');
            Route::get('order-confirmation',[OrderController::class, 'confirmOrder'])->name('order-confirmation');
            Route::get('order-list/{state}',[OrderController::class, 'orderList'])->name('order-list');
            Route::get('order-track/{dmOrderAcceptId}',[OrderController::class, 'orderTrack'])->name('order-track');
            Route::get('order-varify-QR', [OrderController::class , 'varifyQR'])->name('order-varify-qr');
        });
        Route::group(['prefix' => 'restaurant', 'as'=> 'restaurant.'], function(){
            Route::get('orders',[RestaurantOrderController::class, 'getOrders'])->name('orders');
            Route::get('get-latest-orders',[RestaurantOrderController::class, 'get_latest_orders'])->name('get_latest_orders');
            //
            Route::get('order-confirmation',[RestaurantOrderController::class, 'confirmOrder'])->name('order-confirmation');
            Route::get('order-list/{state}',[RestaurantOrderController::class, 'orderList'])->name('order-list');
            Route::get('order-track/{dmOrderAcceptId}',[RestaurantOrderController::class, 'orderTrack'])->name('order-track');
            Route::get('order-varify-QR', [RestaurantOrderController::class , 'varifyQR'])->name('order-varify-qr');
        });

        Route::group(['prefix' => 'admin', 'as'=> 'admin.'], function(){

            Route::get('orders',[AdminOrderController::class, 'getOrders'])->name('orders');
            Route::get('order',[AdminOrderController::class, 'getOrder'])->name('order');
            Route::get('get-latest-orders',[AdminOrderController::class, 'get_latest_orders'])->name('get_latest_orders');

            Route::get('order-confirmation',[AdminOrderController::class, 'confirmOrder'])->name('order-confirmation');
            Route::get('order-stage-changer',[AdminOrderController::class, 'OrderStageChanger'])->name('order-stage-changer');
            Route::get('order-list/{state}',[AdminOrderController::class, 'orderList'])->name('order-list');
            Route::get('order-delivered-list',[AdminOrderController::class, 'orderDeliveredList'])->name('order-delivered-list');
            Route::get('order-track/{dmOrderAcceptId}',[AdminOrderController::class, 'orderTrack'])->name('order-track');
            Route::get('order-varify-QR', [AdminOrderController::class , 'varifyQR'])->name('order-varify-qr');
            Route::get('order-payment-option', [AdminOrderController::class , 'paymentOption'])->name('order-payment-option');
            Route::get('order-deliver', [AdminOrderController::class , 'order_deliver'])->name('order-deliver');
            Route::get('get-updated-timers', [AdminOrderController::class , 'getUpdatedTimers'])->name('get-updated-timers');

            Route::get('report', function () {
                return view('deliveryman.admin.order.report');
            })->name('report'); // Route name: deliveryman.order.report

            // Notifications
            Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
            Route::get('notifications-fetch', [NotificationController::class, 'fetchNotifications'])->name('notifications.fetch');
            Route::get('notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
            Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
            Route::delete('notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
            Route::delete('notifications', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
            Route::get('notifications-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
            Route::get('notifications-stats', [NotificationController::class, 'getStats'])->name('notifications.stats');
            Route::get('debug-routes', function() {
                return response()->json([
                    'mark_as_read_route' => route('deliveryman.admin.notifications.markAsRead', 'TEST_ID'),
                    'delete_route' => route('deliveryman.admin.notifications.delete', 'TEST_ID'),
                    'base_url' => url('deliveryman/admin/notifications'),
                    'current_url' => request()->fullUrl(),
                    'all_routes' => collect(\Illuminate\Support\Facades\Route::getRoutes())->filter(function($route) {
                        return str_contains($route->uri(), 'notification');
                    })->map(function($route) {
                        return [
                            'uri' => $route->uri(),
                            'name' => $route->getName(),
                            'methods' => $route->methods()
                        ];
                    })->values()
                ]);
            })->name('debug-routes');
            
            Route::get('test-notification', function() {
                $deliveryMan = auth('delivery_men')->user();
                if ($deliveryMan) {
                    try {
                        // Test by creating notification directly
                        $notificationCount = \Illuminate\Notifications\DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                            ->where('notifiable_id', $deliveryMan->id)
                            ->count();
                        
                        // Create notification manually
                        \Illuminate\Notifications\DatabaseNotification::create([
                            'id' => \Illuminate\Support\Str::uuid(),
                            'type' => 'App\Notifications\DeliverymanNotification',
                            'notifiable_type' => 'App\Models\DeliveryMan',
                            'notifiable_id' => $deliveryMan->id,
                            'data' => [
                                'title' => 'Test Notification',
                                'message' => 'This is a test notification sent at ' . now()->format('Y-m-d H:i:s'),
                                'type' => 'system',
                                'data' => [
                                    'test' => true,
                                    'timestamp' => now()->toISOString()
                                ]
                            ],
                            'read_at' => null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        $newCount = \Illuminate\Notifications\DatabaseNotification::where('notifiable_type', 'App\Models\DeliveryMan')
                            ->where('notifiable_id', $deliveryMan->id)
                            ->count();
                        
                        return response()->json([
                            'success' => true, 
                            'message' => 'Test notification sent',
                            'previous_count' => $notificationCount,
                            'new_count' => $newCount
                        ]);
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Error: ' . $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                    }
                }
                return response()->json(['success' => false, 'message' => 'Not authenticated']);
            })->name('test-notification');

        });

        Route::group(['as' => 'wallet.','prefix'=>'wallet'], function () {
            Route::get('/', [WalletController::class , 'index'])->name('index');
            Route::get('/histories', [WalletController::class , 'histories'])->name('histories');
        });

        Route::group(['as' => 'cash.','prefix'=>'cash'], function () {
            // Route::get('/', [CashController::class , 'index'])->name('index');
            Route::get('/histories', [CashController::class , 'histories'])->name('histories');
            Route::get('/payment-history', function () {
                return view('deliveryman.cash.payment-history'); // Adjust the path if necessary
            })->name('payment-history');
        });

    });
});

?>
