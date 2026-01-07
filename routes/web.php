<?php

use App\CentralLogics\Helpers;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\DeleteAccountController;
use App\Http\Controllers\User\JoineeController;
use App\Http\Controllers\User\LoginController;
use App\Http\Controllers\User\Mess\Attendance;
use App\Http\Controllers\User\Mess\MainController;
use App\Http\Controllers\User\Mess\MealController;
use App\Http\Controllers\User\Mess\PackageOrderController;
use App\Http\Controllers\User\Mess\SubscribedPackageController;
use App\Http\Controllers\User\PaymentHistoryController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\Restaurant\CheckoutController;
use App\Http\Controllers\User\Restaurant\CollectionController;
use App\Http\Controllers\User\Restaurant\FavoriteController;
use App\Http\Controllers\User\Restaurant\FoodController;
use App\Http\Controllers\User\Restaurant\MainController as RestaurantController;
use App\Http\Controllers\User\Restaurant\OrderController;
use App\Http\Controllers\User\Restaurant\ReviewController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\WalletController;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () { echo route('user.auth.login');});

// Referral Landing Route with fallback to normal home
Route::get('/get-passkey', function () {
    return response()->json(['pass_key' => Helpers::syncPassKey(request: request() , canCreatePassKey: true)]);
})->name('get.passkey');
Route::get('/', function () {
    Helpers::syncPassKey(request: request() , canCreatePassKey: false);
    $referralCode = request()->get('ref',  null);
    if ($referralCode) {
        // Store referral code in session for registration
        session(['referral_code' => $referralCode]);
        
        // If user is already logged in, redirect to dashboard
        if (auth('customer')->check()) {
            return redirect()->route('user.dashboard')->with('info', 'You are already registered. Referral codes can only be used during new registration.');
        }
        
        // Show referral landing page
        return app(\App\Http\Controllers\User\Restaurant\ReferralController::class)->landing(request());
    }
    
    // Normal home page
    return app(\App\Http\Controllers\User\DashboardController::class)->home(request());
})->name('userHome');
// Route::get('/' , function(){
//     return view('welcome');
// });

Route::get('/comi', [LoginController::class, 'comi'])->name('comi');
Route::get('/gomi', [LoginController::class, 'gomi'])->name('gomi');

// WebSocket Test Route
Route::get('/test-websocket', function () {
    return view('test-websocket');
})->name('test.websocket');

Route::get('/download/user', function () {
    return view('user-views.downloads.index');
})->name('download');
Route::get('/download/restaurant', function () {
    return view('user-views.downloads.admin');
})->name('reastaurant.download');
Route::get('/download/deliveryman', function () {
    return view('user-views.downloads.deliveryman');
})->name('deliveryman.download');

// CSRF token refresh route
Route::get('/csrf-token', function () {return response()->json(['csrf_token' => csrf_token()]);})->name('csrf-token');

Route::get('/page/{name}', [UserController::class, 'pages'])->name('user.pages');
Route::get('/contact-us', [UserController::class, 'contact_us'])->name('user.contact-us');
Route::post('/contact-us', [UserController::class, 'contact_us_submit'])->name('user.contact-us.submit');

Route::group(['prefix' => 'join-as', 'as' => 'join-as.'], function () {
    Route::get('/restaurant', [JoineeController::class, 'asRestaurant'])->name('restaurant');
    Route::post('/restaurant-save', [JoineeController::class, 'joinAsRestaurantStore'])->name('restaurant-save');
    Route::get('/deliveryman', [JoineeController::class, 'asDeliveryMan'])->name('deliveryman');
    Route::post('/deliveryman-save', [JoineeController::class, 'joinAsDelivlerymanStore'])->name('deliveryman-save');
});
// Route::get('/',function(){
//     return response()->json(['route' => route('')])
// });

Route::post('/create-user', [MainController::class, 'createUser'])->name('createUser');
Route::group(['as' => 'user.'], function () {
    Route::group(['prefix' => 'user', 'as' => 'auth.'], function () {
        Route::get('login', [LoginController::class, 'login'])->middleware('user')->name('login');
        Route::post('login', [LoginController::class, 'submit']);
        Route::post('user-check', [LoginController::class, 'checkUser'])->name('user-check');
        Route::post('login-otp', [LoginController::class, 'loginOTP'])->name('login-otp');
        Route::post('register-user', [LoginController::class, 'registerUser'])->name('register-user');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('delete-account', [DeleteAccountController::class, 'index'])->name('delete-account');
        Route::delete('delete-account/{id}', [DeleteAccountController::class, 'destroy'])->name('user.destroy');

        Route::post('verify-account', [DeleteAccountController::class, 'verify_account'])->name('verify-account');

        Route::post('save-user-address', [UserController::class, 'saveUserAddress'])->name('save-user-address');
        Route::post('save-user-current-address', [UserController::class, 'saveUserCurrentAddress'])->name('save-user-current-address');
        Route::post('refresh-saved-current-address', [UserController::class, 'refreshUserCurrentAddress'])->name('refresh-saved-current-address');
        Route::get('list-user-address', [UserController::class, 'listSavedAddress'])->name('list-user-address');
        Route::delete('delete-user-address/{id}', [UserController::class, 'deleteSavedAddress'])->name('delete-user-address');
    });
    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications-fetch', [NotificationController::class, 'fetchNotifications'])->name('notifications.fetch');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification'])->name('notifications.delete');
    Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
    Route::get('/notifications-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');

    //dashboardj
    Route::group(['as' => 'restaurant.', 'middleware' => 'no-cache'], function () {
        Route::get('top-foods', [FoodController::class, 'topFoods'])->name('top-foods');
        Route::get('restaurant/{name}', action: [RestaurantController::class, 'restaurant'])->name('get-restaurant');
        Route::get('get-restaurants', [RestaurantController::class, 'getRestaurants'])->name('get-restaurants');
        Route::post('get-menu', [RestaurantController::class, 'get_menu'])->name('get-menu');
        Route::get('get-foods', [FoodController::class, 'getFoods'])->name('get-foods');
        Route::get('get-food', [FoodController::class, 'getFood'])->name('get-food');
        Route::post('add-to-cart', [FoodController::class, 'addToCart'])->name('add-to-cart');
        Route::get('get-cart-items', [FoodController::class, 'getCartItems'])->name('get-cart-items');
        Route::post('update_cart', [FoodController::class, 'update_cart'])->name('update_cart');
        Route::get('get-temp-cart-items', [FoodController::class, 'getTempCartItems'])->name('get-temp-cart-items');
        Route::get('remove-cart-item', [FoodController::class, 'removeCartItem'])->name('remove-cart-item');

        Route::get('order-invoice', [RestaurantController::class, 'invoice'])->name('order-invoice');
       Route::get('share-order', [OrderController::class, 'shareOrder'])->name('share-order');
       Route::get('dm-position', [OrderController::class, 'dmPostion'])->name('dm-postion');



        Route::middleware('user')->group(function () {
            Route::group(['prefix' => 'collection', 'as' => 'collection.'], function () {
                Route::get('/', [CollectionController::class, 'index']);
                Route::post('/save', [CollectionController::class, 'store'])->name('save');
                Route::post('/add-item', [CollectionController::class, 'addItem'])->name('add-item');
                Route::post('/undo_item', [CollectionController::class, 'undoItem'])->name('undo-item');
                Route::post('/favorite/food', [CollectionController::class, 'favoriteFood'])->name('food');

                Route::get('/restaurants', [CollectionController::class, 'myRestaurantCollection'])->name('restaurants');
                Route::get('/foods', [CollectionController::class, 'myFoodCollection'])->name('foods');
            });



            Route::group(['prefix' => 'favorite', 'as' => 'favorite.'], function () {
                Route::get('/', [FavoriteController::class, 'index']);
                Route::post('/favorite/restaurant', [FavoriteController::class, 'favoriteRestaurant'])->name('restaurant');

                Route::post('/favorite/food', [FavoriteController::class, 'favoriteFood'])->name('food');

                Route::get('/my-favorite-restaurants', [FavoriteController::class, 'myFavoriteRestaurants'])->name('restaurants');
                Route::get('/my-favorite-foods', [FavoriteController::class, 'myFavoriteFoods'])->name('foods');
            });
            Route::group(['prefix' => 'unfavorite', 'as' => 'unfavorite.'], function () {
                Route::post('/restaurant', [FavoriteController::class, 'unfavoriteRestaurant'])->name('restaurant');
                Route::post('/food', [FavoriteController::class, 'unfavoriteFood'])->name('food');
            });


            Route::get('check-out', [CheckoutController::class, 'index'])->name('check-out');
            Route::get('check-out-items', [CheckoutController::class, 'getItems'])->name('check-out-items');
            Route::get('billing-summery', [CheckoutController::class, 'billingSummery'])->name('billing-summery');
            Route::get('dm-tips', [CheckoutController::class, 'dmTips'])->name('dm-tips');
            Route::post('loved-one-data-store', [CheckoutController::class, 'lovedOneDataStore'])->name('loved-one-data-store');
            Route::get('get-loved-one-stored-data', [CheckoutController::class, 'getLoveOneStoredData'])->name('get-loved-one-stored-data');
            Route::get('get-coupons', [CheckoutController::class, 'getCoupons'])->name('get-coupons');
            Route::get('apply-coupons', [CheckoutController::class, 'applyCoupons'])->name('apply-coupons');
            Route::get('remove-applied-coupon/{id}', [CheckoutController::class, 'removeAppliedCoupon'])->name('remove-applied-coupon');
            Route::get('apply-referral-discount', [CheckoutController::class, 'applyReferralDiscount'])->name('apply-referral-discount');
            Route::get('remove-referral-discount', [CheckoutController::class, 'removeReferralDiscount'])->name('remove-referral-discount');
            Route::get('check-referral-discount-status', [CheckoutController::class, 'checkReferralDiscountStatus'])->name('check-referral-discount-status');
            Route::post('cooking-instruction', [CheckoutController::class, 'cookingInstruction'])->name('cooking-instruction');
            Route::post('delivery-instruction', [CheckoutController::class, 'deliveryInstruction'])->name('delivery-instruction');
            Route::post('schedule-order', [CheckoutController::class, 'scheduleOrder'])->name('schedule-order');
            Route::get('remove-schedule', [CheckoutController::class, 'removeSchedule'])->name('remove-schedule');
            Route::get('payment-options', [CheckoutController::class, 'paymentOptions'])->name('payment-options');
            Route::get('order-payment-online', [CheckoutController::class, 'orderPaymentOnline'])->name('order-payment-online');
            Route::get('handle-order-payment-online-callback', [CheckoutController::class, 'handleOrderPaymentOnlineCallback'])->name('handle-order-payment-online-callback');

            Route::get('place-order', [CheckoutController::class, 'placeOrder_via_cashOrWallet'])->name('place-order');
            Route::get('order-list/{status}', [OrderController::class, 'list'])->name('order-list');
            Route::get('order-status', [RestaurantController::class, 'orderStatus'])->name('order-status');
            Route::get('order-trace', [OrderController::class, 'orderTrace'])->name('order-trace');
            Route::get('/live-order', [OrderController::class, 'liveOrder'])->name('live-order');
            
            // Scheduled Orders Routes
            Route::get('scheduled-orders', [OrderController::class, 'scheduledOrders'])->name('scheduled-orders');
            Route::get('scheduled-order/{orderId}', [OrderController::class, 'scheduledOrderDetails'])->name('scheduled-order-details');
            Route::post('cancel-scheduled-order/{orderId}', [OrderController::class, 'cancelScheduledOrder'])->name('cancel-scheduled-order');
        });
    });

    Route::middleware('user')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'home'])->name('dashboard');

        Route::get('/user-view', [UserController::class, 'view'])->name('view');
        Route::post('/user-update', [UserController::class, 'update'])->name('update');

        /**
         * Chat System
         */
        Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
            Route::get('/', [App\Http\Controllers\User\ChatController::class, 'index'])->name('index');
            Route::post('/start-admin', [App\Http\Controllers\User\ChatController::class, 'startConversationWithAdmin'])->name('start-admin');
            Route::get('/conversation/{id}', [App\Http\Controllers\User\ChatController::class, 'conversation'])->name('conversation');
            Route::post('/conversation/{id}/message', [App\Http\Controllers\User\ChatController::class, 'sendMessage'])->name('send-message');
            Route::get('/conversation/{id}/messages', [App\Http\Controllers\User\ChatController::class, 'getMessages'])->name('get-messages');
            // Message deletion routes
            Route::delete('/message/{id}', [App\Http\Controllers\User\ChatController::class, 'deleteMessage'])->name('delete-message');
            Route::delete('/messages', [App\Http\Controllers\User\ChatController::class, 'deleteMessages'])->name('delete-messages');
            Route::delete('/conversation/{id}/clear', [App\Http\Controllers\User\ChatController::class, 'clearConversation'])->name('clear-conversation');
        });


        Route::group(['prefix' => 'review', 'as' => 'review.'], function () {
            Route::post('/', [ReviewController::class, 'makeReview']);
            Route::post('/dm', [ReviewController::class, 'makeReviewDm'])->name('dm');
            Route::get('/check-dm', [ReviewController::class, 'checkDmReview'])->name('check-dm');
            Route::get('/check-res', [ReviewController::class, 'checkResReview'])->name('check-res');
            // Route::get('testR/{order_id}', [ReviewController::class, 'getR']);
            // Route::post('/food', [FavoriteController::class, 'unfavoriteFood'])->name('food');
        });
        // Refund System Routes for Users
        Route::group(['as' => 'refund.', 'prefix' => 'refund'], function () {
            Route::get('/', [App\Http\Controllers\User\RefundController::class, 'index'])->name('index');
            Route::get('/create/{orderId}', [App\Http\Controllers\User\RefundController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\User\RefundController::class, 'store'])->name('store');
            Route::get('/show/{id}', [App\Http\Controllers\User\RefundController::class, 'show'])->name('show');
            Route::post('/cancel/{id}', [App\Http\Controllers\User\RefundController::class, 'cancel'])->name('cancel');
            Route::get('/reasons', [App\Http\Controllers\User\RefundController::class, 'getReasons'])->name('reasons');
        });
    });

    Route::group(['as' => 'mess.', 'prefix' => 'mess'], function () {
        Route::get('/', [DashboardController::class, 'mess'])->name('index');

        Route::get('/mess-view/{messId}', [MainController::class, 'index'])->name('view');
        Route::get('/index2', [MainController::class, 'index2'])->name('view2');
        Route::get('/index3', [MainController::class, 'index3'])->name('view3');
        Route::get('/index4', [MainController::class, 'index4'])->name('view4');

        Route::get('weekly-menu-days', [DashboardController::class, 'getWeeklyMenuDay'])->name('weeklymenudays');
        Route::get('weekly-menu', [DashboardController::class, 'getWeeklyMenu'])->name('weeklymenu');
        Route::Post('package-add-to-cart', [CartController::class, 'packageAddToCart'])->name('package-add-to-cart');
        Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::post('mess-pacakge-order', [PackageOrderController::class, 'order'])->name('mess-pacakge-order');
        Route::get('payment_options', [PackageOrderController::class, 'paymentOptions'])->name('payment_options');
        Route::get('order-quick-payment', [PackageOrderController::class, 'placeOrder_via_cashOrWallet'])->name('order-quick-payment');
        Route::get('order-payment-online', [PackageOrderController::class, 'orderPaymentOnline'])->name('order-payment-online');
        Route::get('handle-order-payment-online-callback', [PackageOrderController::class, 'handleOrderPaymentOnlineCallback'])->name('handle-order-payment-online-callback');
        Route::get('order-list/{status}', [PackageOrderController::class, 'list'])->name('order-list');
        Route::get('my-order/{order_id}', [PackageOrderController::class, 'myorder'])->name('my-order');

        Route::get('mess-package-history', [SubscribedPackageController::class, 'list'])->name('mess-package-history');
        Route::get('mess-package-history-items', [SubscribedPackageController::class, 'listItems'])->name('mess-package-history-items');
        //Activate pacakge
        Route::get('active-package', [SubscribedPackageController::class, 'activatePackage'])->name('activate-package');

        /*===========//Meal page //=============*/
        Route::get('meal-page', [MealController::class, 'index'])->name('meal-page');
        Route::get('mess-invoice', [MainController::class, 'invoice'])->name('invoice');


        Route::get('my-attendance', [Attendance::class, 'index'])->name('myattendance');
        Route::get('subscriptions/{id}', [MainController::class, 'list'])->name('subscriptions');
        Route::get('addons', [MainController::class, 'addons'])->name('addons');
        Route::post('addons', [MainController::class, 'storeaddons']);
        Route::get('diet-cancel', [MainController::class, 'dietCancel'])->name('dietCancel');
        Route::get('hold-diet', [MainController::class, 'holdDiet'])->name('hold-diet');
        Route::get('my-diet-qr', [Attendance::class, 'my_diet_qr'])->name('my-diet-qr');
        Route::get('my-diet-qr-image', [Attendance::class, 'my_diet_qr_image'])->name('my-diet-qr-image');
    });
    
    Route::middleware('user')->group(function () {
        Route::group(['as' => 'wallet.', 'prefix' => 'wallet'], function () {
            Route::get('/', [WalletController::class, 'index'])->name('get');
            Route::get('history', [WalletController::class, 'histories'])->name('history');
            Route::post('top-up', [WalletController::class, 'topUP'])->name('top-up');
            Route::get('top-up-handle', [WalletController::class, 'topUPHandle'])->name('top-up-handle');

        });

        Route::group(['as' => 'loyalty.', 'prefix' => 'loyalty'], function () {
            Route::get('/', [\App\Http\Controllers\User\LoyaltyPointController::class, 'index'])->name('get');
            Route::post('redeem', [\App\Http\Controllers\User\LoyaltyPointController::class, 'redeemPoints'])->name('redeem');
            Route::get('history', [\App\Http\Controllers\User\LoyaltyPointController::class, 'getHistory'])->name('history');
            Route::get('calculate', [\App\Http\Controllers\User\LoyaltyPointController::class, 'calculatePoints'])->name('calculate');
        });

        
        Route::group(['as' => 'payments.', 'prefix' => 'payments'], function () {
            Route::post('history', [PaymentHistoryController::class, 'histories'])->name('history');

            Route::get('/cash', [PaymentHistoryController::class, 'cash'])->name('cash');
            Route::get('online', [PaymentHistoryController::class, 'online'])->name('online');
        });

        // Referral System Routes
        Route::group(['prefix' => 'referral', 'as' => 'referral.'], function () {
            Route::get('/', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'index'])->name('index');
            Route::post('/generate', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'generateCode'])->name('generate');
            Route::get('/stats', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'getStats'])->name('stats');
            Route::post('/validate', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'validateCode'])->name('validate');
            Route::post('/apply', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'applyReferralCode'])->name('apply');
            Route::get('/rewards', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'getRewards'])->name('rewards');
            Route::get('/claimed-rewards', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'getClaimedRewards'])->name('claimed-rewards');
            Route::post('/claim-user', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'claimUserReward'])->name('claim-user');
            Route::post('/claim-sponsor', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'claimSponsorReward'])->name('claim-sponsor');
            Route::get('/share-info', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'getShareInfo'])->name('share-info');
            Route::get('/history', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'getHistory'])->name('history');
        });
        
        // Referral landing page route
        Route::get('/join/{referralCode}', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'landing'])->name('referral.landing');
    });

    // Public referral validation (for registration)
    Route::post('/validate-referral', [App\Http\Controllers\User\Restaurant\ReferralController::class, 'validateCode'])->name('public.referral.validate');
});

// WebSocket test routes
Route::get('/test-websocket', function () {
    $webSocketService = new \App\Services\WebSocketService();
    
    $testMessage = (object) [
        'id' => 999,
        'message' => 'Test message from Laravel at ' . now(),
        'attachments' => null,
        'attachment_type' => null,
        'sender_id' => 1,
        'sender_type' => 'admin',
        'created_at' => now(),
        'is_seen' => false
    ];
    
    $webSocketService->broadcastChatMessage($testMessage, 1);
    
    return response()->json(['status' => 'Message broadcasted', 'message' => $testMessage]);
});

Route::get('/test-chat-create', function () {
    // Create a test conversation and message
    $admin = \App\Models\Admin::first();
    $customer = \App\Models\Customer::first();
    
    if (!$admin || !$customer) {
        return response()->json(['error' => 'Admin or customer not found']);
    }
    
    // Find or create conversation
    $conversation = \App\Models\Conversation::firstOrCreate([
        'sender_id' => $admin->id,
        'sender_type' => 'admin',
        'receiver_id' => $customer->id,
        'receiver_type' => 'customer'
    ], [
        'unread_message_count' => 0,
        'last_message_time' => now()
    ]);
    
    // Create a test message
    $message = \App\Models\Message::create([
        'conversation_id' => $conversation->id,
        'sender_id' => $admin->id,
        'sender_type' => 'admin',
        'message' => 'Test message created at ' . now(),
        'is_seen' => false
    ]);
    
    // Broadcast it
    $webSocketService = new \App\Services\WebSocketService();
    $webSocketService->broadcastChatMessage($message, $conversation->id);
    
    return response()->json([
        'status' => 'Test message created and broadcasted',
        'conversation_id' => $conversation->id,
        'message_id' => $message->id
    ]);
});


