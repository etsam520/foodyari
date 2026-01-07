<?php

use App\Http\Controllers\Mess\DeliveryManController;
use App\Http\Controllers\Mess\Auth\LoginController;
use App\Http\Controllers\Mess\CouponController;
use App\Http\Controllers\Mess\CustomerAttaindanceController;
use App\Http\Controllers\Mess\CustomerController;
use App\Http\Controllers\Mess\DashboardController;
use App\Http\Controllers\Mess\DietController;
use App\Http\Controllers\Mess\DietOrder;
use App\Http\Controllers\Mess\EmployeeController;
use App\Http\Controllers\Mess\FoodProcessController;
use App\Http\Controllers\Mess\MenuAddon;
use App\Http\Controllers\Mess\MenuController;
use App\Http\Controllers\Mess\OrderController;
use App\Http\Controllers\Mess\QRController;
use App\Http\Controllers\Mess\ReportController;
use App\Http\Controllers\Mess\RolesAndPermission;
use App\Http\Controllers\Mess\SubscriptionController;
use App\Http\Controllers\Mess\TiffinController;
use App\Http\Controllers\User\Mess\MainController;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;

Route::group(['as' => 'mess.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::post('login', [LoginController::class, 'submit']);
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });

    Route::group(['middleware' => ['mess']], function () {

        Route::get('/dashboard', [DashboardController::class , 'dashboard'])->name('dashboard');
        Route::get('/dashboard-changer', [LoginController::class , 'dashboardChanger'])->name('dashboard-changer');

        Route::get('/mywallet', [MainController::class , 'mywallet'])->name('mywallet');
        Route::get('/mywalletHistories', [MainController::class , 'mywalletHistories'])->name('mywalletHistories');
        Route::post('/add-to-mywallet', [MainController::class , 'addToMywallet'])->name('addToMywallet');
        Route::group(['as' => 'roles.','prefix'=> 'role'], function () {
            Route::get('/add', [RolesAndPermission::class , 'index'])->name('add');
            Route::post('/add', [RolesAndPermission::class , 'submit']);
        });

        Route::group(['as' => 'profile.','prefix'=>'profile'], function () {
            Route::get('/update', [DashboardController::class , 'profileUpdate'])->name('update');
            Route::post('/update', [DashboardController::class , 'profileUpdateStore']);
            Route::get('/timing', [MainController::class , 'timing'])->name('timing');
            Route::post('/timing', [MainController::class , 'timingsave']);
            Route::post('/auto-ateendance-timing', [DashboardController::class , 'attendanceTiming'])->name('auto-ateendance-timing');

        });

       

        Route::group(['as' => 'menu.','prefix'=>'menu'], function () {
            Route::get('/add', [MenuController::class , 'index'])->name('add');
            Route::post('/add', [MenuController::class , 'submit']);
            Route::get('/add-weekly', [MenuController::class , 'indexWeekly'])->name('add.weekly');
            Route::get('/edit-weekly/{id}', [MenuController::class , 'editWeekly'])->name('edit.weekly');
            Route::post('/add-weekly', [MenuController::class , 'submitWeekly']);
            Route::post('/update-weekly', [MenuController::class , 'updateWeekly'])->name('update.weekly');
            Route::get('/list', [MenuController::class , 'list'])->name('list');
            Route::get('/list-weekly', [MenuController::class , 'Weeklylist'])->name('list.weekly');
        });

        Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
            Route::get('add',[MenuAddon::class, 'index'] )->name('add');
            Route::post('store', [MenuAddon::class, 'store'])->name('store');
            Route::get('list', [MenuAddon::class, 'list'])->name('list');
            Route::get('view/{id}', [MenuAddon::class, 'view'])->name('view');
            Route::get('get_addons/{restaurant_id}',[MenuAddon::class, 'get_addons'])->name('get_addons');
        });

        Route::group(['prefix' => 'tiffin', 'as' => 'tiffin.'], function () {
            Route::get('add',[TiffinController::class, 'index'] )->name('add');
            Route::post('store', [TiffinController::class, 'store'])->name('store');
            // Route::get('list', [MenuAddon::class, 'list'])->name('list');
        });

        /**
         * Diet Calander
         */
        Route::group(['as' => 'diet-calander.','prefix'=>'diet-calander'], function () {
            Route::get('/', [DietController::class , 'dietCalander'])->name('view');
            Route::get('/get-calender-elements', [DietController::class , 'getCalendarElements'])->name('get-calender-elements');
            Route::post('/update-calender-elements', [DietController::class , 'updateCalenderItem'])->name('update-calender-elements');
        });
        
        // Route::group(['as' => 'coupon.','prefix'=>'coupons'], function () {
        //     Route::get('/add', [CouponController::class , 'subscriptionCoupon'])->name('add');
        //     // Route::post('/add', [MenuController::class , 'submit']);
        //     // Route::get('/list', [MenuController::class , 'list'])->name('list');
        // });
        Route::group(['as' => 'subscription.','prefix'=>'subscription'], function () {
            Route::get('/add', [SubscriptionController::class , 'index'])->name('add');
            Route::post('/add', [SubscriptionController::class , 'submit']);
            Route::get('/list', [SubscriptionController::class , 'list'])->name('list');
            Route::get('/edit/{id}', [SubscriptionController::class , 'edit'])->name('edit');
            Route::post('/update', [SubscriptionController::class , 'update'])->name('update');

            Route::get('/pakage-lists', [SubscriptionController::class , 'pakagelist'])->name('p.lists');

        });

        Route::group(['as' => 'customer.','prefix'=>'customer'], function () {
            Route::get('/add', [CustomerController::class , 'index'])->name('add');
            Route::post('/add', [CustomerController::class , 'submit']);
            Route::get('/list', [CustomerController::class , 'list'])->name('list');
            Route::get('/view/{id}', [CustomerController::class , 'view'])->name('view');
            Route::get('/getdata', [CustomerController::class , 'getdata'])->name('getdata');
            
            Route::group(['as' => 'attaindance.','prefix'=>'attaindance'], function () {
                Route::get('/', [CustomerAttaindanceController::class , 'index'])->name('list');    
                Route::post('/single', [CustomerAttaindanceController::class , 'attendanceBySingle'])->name('bySingle');
                Route::post('/all', [CustomerAttaindanceController::class , 'attendanceByAll'])->name('byAll');
            });

            Route::group(['as' => 'report.','prefix'=>'report'], function () {
                Route::get('/today-attendance', [ReportController::class , 'attendance'])->name('today-attendance');
                Route::get('/today', [ReportController::class , 'today'])->name('today');
                Route::get('/daily', [ReportController::class , 'daily'])->name('daily');
                Route::get('/monthly', [ReportController::class , 'monthly'])->name('monthly');
            });
        });
        /**
         * Staff Management
         */
        Route::group(['as' => 'employee.','prefix'=>'employee'], function () {
            Route::get('/add', [EmployeeController::class , 'index'])->name('add');
            Route::post('/add', [EmployeeController::class , 'submit']);
            Route::get('/list', [EmployeeController::class , 'list'])->name('list');
        });
        
        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.'], function () {
                Route::get('add', [DeliveryManController::class,'index'])->name('add');
                Route::post('store', [DeliveryManController::class,'store'])->name('store');
                Route::get('list', [DeliveryManController::class,'list'])->name('list');
                Route::get('wallet', [DeliveryManController::class,'wallet'])->name('wallet');
                Route::get('getwalletdata', [DeliveryManController::class,'getwalletdata'])->name('getwalletdata');
                Route::post('updateWallet', [DeliveryManController::class,'updateWallet'])->name('updateWallet');
                Route::get('walletTransactions', [DeliveryManController::class,'walletTransactions'])->name('walletTransactions');
                // Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
                //     Route::get('list', [DeliveryManController::class,'reviews_list'])->name('list');
                //     Route::get('status/{id}/{status}', [DeliveryManController::class,'reviews_status'])->name('status');
        });
        /**
         * Process Food Controler
         */
        Route::group(['prefix'=>'process-food', 'as' => 'process-food.'], function(){
            Route::get('process', [FoodProcessController::class , 'process'])->name('process');
        });

        /**
         * Diet Order
         */
        Route::group(['prefix'=>'diet-order', 'as' => 'diet-order.'], function(){
            Route::get('allot', [DietOrder::class , 'index'])->name('allot');
            Route::post('allot', [DietOrder::class , 'orderAllotSubmit']);
            Route::get('get-ordered-customers', [DietOrder::class , 'getOrderedCustomers'])->name('getOrderedCustomers');
            Route::get('get-tiffin-no', [DietOrder::class , 'getTiffinNo'])->name('getTiffinNo');
            Route::get('get-customer-by-id', [DietOrder::class , 'getCustomerById'])->name('getCustomerById'); 
            Route::get('get-tiffin-by-id', [DietOrder::class , 'getTiffinById'])->name('getTiffinById');
            Route::get('get-lists-orders-alloted-to-deliveryman', [DietOrder::class , 'listOfOrderToDeliveryMany'])->name('listOfOrderToDeliveryMany');
            Route::get('varify-QR', [QRController::class , 'varify'])->name('varyfyQR');
        });

        /**
         * Subscripton package order
         */
        Route::group(['prefix' => 'order', 'as' => 'order.' ], function () {
            Route::get('list/{status}', [OrderController::class,'list'])->name('list');
            Route::put('status-update/{id}', [OrderController::class,'status'])->name('status-update');
            Route::post('search', [OrderController::class,'search'])->name('search');
            Route::post('add-to-cart', [OrderController::class,'add_to_cart'])->name('add-to-cart');
            Route::post('remove-from-cart', [OrderController::class,'remove_from_cart'])->name('remove-from-cart');
            Route::get('update/{order}', [OrderController::class,'update'])->name('update');
            Route::get('edit-order/{order}', [OrderController::class,'edit'])->name('edit');
            Route::get('details/{id}', [OrderController::class,'details'])->name('details');
            Route::get('status', [OrderController::class,'status'])->name('status');
            Route::get('quick-view', [OrderController::class,'quick_view'])->name('quick-view');
            Route::get('quick-view-cart-item', [OrderController::class,'quick_view_cart_item'])->name('quick-view-cart-item');
            Route::get('generate-invoice/{id}', [OrderController::class,'generate_invoice'])->name('generate-invoice');
            Route::post('add-payment-ref-code/{id}', [OrderController::class,'add_payment_ref_code'])->name('add-payment-ref-code');
            Route::get('dm-assign-manually', [OrderController::class,'order_dm_assign_manually'])->name('dm_assign_manually');
        });

        Route::group(['prefix'=>'business-setup', 'as' => 'business-setup.'], function(){
            Route::get('charges', [MainController::class , 'charges'])->name('charges');
            Route::post('charges', [MainController::class , 'chargesSave']);
        });

    });
});