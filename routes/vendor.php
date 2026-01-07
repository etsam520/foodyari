<?php

use App\Http\Controllers\Vendor\CustomerController;
use App\Http\Controllers\Vendor\ActivateRestaurantController;
use App\Http\Controllers\Vendor\AddOnController;
use App\Http\Controllers\Vendor\FoodController;
use App\Http\Controllers\Vendor\Auth\LoginController;
use App\Http\Controllers\Vendor\BankingDetailsController;
use App\Http\Controllers\Vendor\BusinessSettingsController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\CouponController;
use App\Http\Controllers\Vendor\DashboardController;
use App\Http\Controllers\Vendor\DeliveryManController;
use App\Http\Controllers\Vendor\EmployeeController;
use App\Http\Controllers\Vendor\FoodserviceController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\OrderTransaction;
use App\Http\Controllers\Vendor\PaymentRequestController;
use App\Http\Controllers\Vendor\POSController;
use App\Http\Controllers\Vendor\RefundController;
use App\Http\Controllers\Vendor\ReportController;
use App\Http\Controllers\Vendor\RestaurantMenuController;
use App\Http\Controllers\Vendor\RestaurantSubMenuController;
use App\Http\Controllers\Vendor\RolePermissionController;
use App\Http\Controllers\Vendor\RolesAndPermission;
use App\Http\Controllers\Vendor\WalletController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'vendor.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', [LoginController::class, 'login'])->name('login') ->middleware('vendor');
        Route::post('login', [LoginController::class, 'submit']);
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    });
    Route::post('activate',[ActivateRestaurantController::class, 'activate'])->name('activate');
    // Route::group(['middleware' => ['module:active','restaurant']], function () {
    Route::group(['middleware' => ['restaurant']], function () {
        //dashboard

        Route::get('/dashboard', [DashboardController::class , 'dashboard'])->name('dashboard');
        Route::get('/qr', [DashboardController::class, 'qrGenerate'])->name('qr');
        Route::get('/dashboard-changer', [LoginController::class , 'dashboardChanger'])->name('dashboard-changer');
        
        // Delivery man arrival endpoints
        Route::get('/delivery-man-arrival', [DashboardController::class, 'getDeliveryManArrival'])->name('delivery-man-arrival');
        Route::get('/delivery-man-location', [DashboardController::class, 'getDeliveryManLocation'])->name('delivery-man-location');
        
        // Extra cooking time endpoints
        Route::post('/extra-cooking-time', [DashboardController::class, 'updateExtraCookingTime'])->name('extra-cooking-time.update');
        Route::get('/extra-cooking-time', [DashboardController::class, 'getExtraCookingTime'])->name('extra-cooking-time.get');
        
        // Order processing endpoints
        Route::post('/start-processing', [DashboardController::class, 'startProcessing'])->name('start-processing');
        Route::post('/force-ready', [DashboardController::class, 'forceReady'])->name('force-ready');
        Route::post('/handover', [DashboardController::class, 'handover'])->name('handover');


        Route::group(['as' => 'profile.','prefix'=>'profile'], function () {
            Route::get('/update', [DashboardController::class , 'profile'])->name('update');
            Route::get('/edit', [DashboardController::class , 'profileEdit'])->name('edit');
            Route::post('/update', [DashboardController::class , 'profileUpdateStore']);
            Route::get('/view-document', function () {
                return view('vendor-views.info.view-document');
            })->name('view-document');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('list', [CategoryController::class, 'index'])->name('list');
            Route::get('view/{id}', [CategoryController::class, 'view'])->name('view');
            Route::get('get_categories',[CategoryController::class, 'get_categories'] )->name('get_categories');
        });

        Route::resource('/restaurant-menu', RestaurantMenuController::class)->except(['show']);
        Route::get('menu-custom-id-regenerate', [RestaurantMenuController::class, 'menuCustomIdRegenerate'])->name('menu-custom-id-regenerate');
        Route::group(['prefix' => 'restaurant-menu', 'as' => 'restaurant-menu.'], function () {
            Route::get('status',[RestaurantMenuController::class, 'status'] )->name('status');
            Route::get('sort', [RestaurantMenuController::class, 'sort'])->name('sort');
            Route::post('sort', [RestaurantMenuController::class, 'sort_update']);
        });
        Route::group(['prefix' => 'restaurant-sub-menu', 'as' => 'restaurant-sub-menu.'], function () {
            Route::get('index',[RestaurantSubMenuController::class, 'index'] )->name('index');
            Route::get('create',[RestaurantSubMenuController::class, 'create'] )->name('create');
            Route::post('store',[RestaurantSubMenuController::class, 'store'] )->name('store');
            Route::get('edit/{id}',[RestaurantSubMenuController::class, 'edit'] )->name('edit');
            Route::delete('destroy/{id}',[RestaurantSubMenuController::class, 'destroy'] )->name('destroy');
            Route::put('update',[RestaurantSubMenuController::class, 'update'] )->name('update');
            Route::get('status',[RestaurantSubMenuController::class, 'status'] )->name('status');
            Route::get('sort', [RestaurantSubMenuController::class, 'sort'])->name('sort');
            Route::post('sort', [RestaurantSubMenuController::class, 'sort_update']);
            Route::get('custom-id-regenerate', [RestaurantSubMenuController::class, 'menuCustomIdRegenerate'])->name('custom-id-regenerate');
        });


        Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
            Route::get('add',[AddOnController::class, 'index'] )->name('add');
            Route::post('store', [AddOnController::class, 'store'])->name('store');
            Route::get('edit/{id}',[AddOnController::class, 'edit'] )->name('edit');
            Route::delete('delete/{id}',[AddOnController::class, 'delete'] )->name('delete');
            Route::put('update',[AddOnController::class, 'update'] )->name('update');
            Route::get('status',[AddOnController::class, 'status'] )->name('status');

            // Route::get('list', [AddOnController::class, 'list'])->name('list');
            Route::get('view/{id}', [AddOnController::class, 'view'])->name('view');
            Route::get('get_addons',[AddOnController::class, 'get_addons'])->name('get_addons');
        });

        Route::group(['prefix' => 'food', 'as' => 'food.'], function () {
            Route::get('add',[FoodController::class, 'index'] )->name('add');
            Route::post('store', [FoodController::class, 'store'])->name('store');
            Route::get('edit/{id}', [FoodController::class, 'edit'])->name('edit');
            Route::get('delete/{id}', [FoodController::class, 'delete'])->name('delete');
            Route::post('update', [FoodController::class, 'update'])->name('update');
            Route::get('list', [FoodController::class, 'list'])->name('list');

            Route::get('get-submenu-option', [FoodController::class, 'get_submenu_option'])->name('get-submenu-option');

            Route::get('status',[FoodController::class, 'status'] )->name('status');
            Route::get('bulk-import',[FoodController::class, 'bulkImport'] )->name('bulk-import');
            Route::post('bulk-import',[FoodController::class, 'bulk_import_save'] );
            Route::get('food-sample-download',[FoodController::class, 'SampleFoodXlsx'] )->name('food-sample-download');
            Route::get('food-export',[FoodController::class, 'exportFood'] )->name('food-export');
            Route::get('get-food-zone-wise',[FoodController::class, 'getFoodsZoneWise'] )->name('getFoodZoneWise');

        });

        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::get('/', [POSController::class, 'index'])->name('index');
            Route::get('/foods', [POSController::class, 'getFoods'])->name('get-foods');
            Route::get('quick-view', [POSController::class, 'quick_view'])->name('quick-view');
            Route::get('quick-view-cart-item', [POSController::class, 'quick_view_card_item'])->name('quick-view-cart-item');
            Route::get('get-food-item-details', [POSController::class, 'foodDetails'])->name('get-food-item-details');
            Route::post('add-to-cart', [POSController::class, 'addToCart'])->name('add-to-cart');
            Route::get('get-cart-items', [POSController::class, 'getCartItems'])->name('get-cart-items');
            Route::get('clear-cart', [POSController::class, 'clearCart'])->name('clear-cart');
            Route::get('delete-cart-item',[POSController::class, 'deleteSingleItem'] )->name('delete-cart-item');
            Route::post('customer-store', [POSController::class, 'customer_store'])->name('customer-store');
            Route::post('place-order', [POSController::class, 'place_order'])->name('order');
            Route::post('tax', [POSController::class, 'update_tax'])->name('tax');
            Route::post('discount',[POSController::class, 'update_discount'] )->name('discount');
            Route::post('add-delivery-info',[POSController::class, 'addDeliveryInfo'])->name('add-delivery-info');
            Route::get('customer-delivery-info',[POSController::class, 'customerDeliveryInfo'])->name('customer-delivery-info');
            // del-add
        });

        Route::group(['prefix' => 'order', 'as' => 'order.' ], function () {
            Route::get('list/{status}', [OrderController::class,'list'])->name('list');
            // Route::put('status-update/{id}', [OrderController::class,'status'])->name('status-update');
            Route::post('search', [OrderController::class,'search'])->name('search');
            Route::post('add-to-cart', [OrderController::class,'add_to_cart'])->name('add-to-cart');
            Route::post('remove-from-cart', [OrderController::class,'remove_from_cart'])->name('remove-from-cart');
            Route::get('update/{order}', [OrderController::class,'update'])->name('update');
            Route::get('edit-order/{order}', [OrderController::class,'edit'])->name('edit');
            Route::get('details/{id}', [OrderController::class,'details'])->name('details');
            // Route::get('status', [OrderController::class,'status'])->name('status');
            Route::get('order-status-update', [OrderController::class,'order_status_update'])->name('order-status-update');
            Route::get('quick-view', [OrderController::class,'quick_view'])->name('quick-view');
            Route::get('quick-view-cart-item', [OrderController::class,'quick_view_cart_item'])->name('quick-view-cart-item');
            Route::get('generate-invoice/{id}', [OrderController::class,'generate_invoice'])->name('generate-invoice');
            Route::get('generate-KOT/{id}', [OrderController::class,'generate_KOT'])->name('generate-KOT');
            Route::post('add-payment-ref-code/{id}', [OrderController::class,'add_payment_ref_code'])->name('add-payment-ref-code');
            Route::get('dm-assign-manually', [OrderController::class,'order_dm_assign_manually'])->name('dm_assign_manually');
        });

        // Route::group(['prefix' => 'report', 'as' => 'report.' ], function () {
        //     Route::get('/order', [ReportController::class,'order_report'])->name('order');

        // });
        Route::group(['prefix' => 'report', 'as' => 'report.' ], function () {
            Route::get('/order', [ReportController::class,'order_report'])->name('order');
            Route::get('/product', [ReportController::class,'product_report'])->name('product');
            Route::get('/tax', [ReportController::class,'tax_report'])->name('tax');
        });

        Route::group(['prefix' => 'banking', 'as' => 'banking.' ], function () {
            // Banking Dashboard & Summary
            Route::get('/dashboard', function() {
                return view('vendor-views.banking.summary');
            })->name('dashboard');
            
            // Banking Details Management
            Route::get('/add-bank-details', [BankingDetailsController::class,'index'])->name('add-bank-details');
            Route::get('/get-bank-details', [BankingDetailsController::class,'getDetails'])->name('get-bank-details');
            Route::post('/save-bank-details', [BankingDetailsController::class,'storeDetails'])->name('save-bank-details');
            Route::put('/update-bank-details/{id}', [BankingDetailsController::class,'updateDetails'])->name('update-bank-details');
            Route::delete('/delete-bank-details/{id}', [BankingDetailsController::class,'deleteDetails'])->name('delete-bank-details');
            
            // Banking History & Audit Trail
            Route::get('/banking-history', [BankingDetailsController::class,'getHistory'])->name('banking-history');
            Route::get('/banking-history-view', function() {
                return view('vendor-views.banking.history');
            })->name('banking-history-view');
            Route::get('/banking-audit-log', [BankingDetailsController::class,'getHistory'])->name('banking-audit-log');

            // Payment Request Management
            Route::get('/add-payment-request', [PaymentRequestController::class,'index'])->name('add-payment-request');
            Route::post('/payment-request', [PaymentRequestController::class,'storeRequstedTxns'])->name('payment-request');
            Route::get('/payment-requests', [PaymentRequestController::class,'getAllRequests'])->name('payment-requests');
            Route::get('/payment-request/{id}', [PaymentRequestController::class,'getRequest'])->name('payment-request.show');
            Route::put('/payment-request/{id}/cancel', [PaymentRequestController::class,'cancelRequest'])->name('payment-request.cancel');
        });
        Route::group(['prefix' => 'service', 'as' => 'service.' ], function ()  {
            Route::get('/food-request',[FoodserviceController::class , 'index'])->name('add.food');
            Route::post('/food-request',[FoodserviceController::class , 'saveFoodRequest']);
        });

        /***
         * customer
         */
        Route::group(['as' => 'customer.','prefix'=>'customer'], function () {
            Route::get('/add', [CustomerController::class , 'index'])->name('add');
            Route::post('/add', [CustomerController::class , 'submit']);
            Route::get('/list', [CustomerController::class , 'list'])->name('list');
            Route::get('/view/{id}', [CustomerController::class , 'view'])->name('view');
            Route::get('/getdata', [CustomerController::class , 'getdata'])->name('getdata');
        });

        /**
         * Discount  Coupon
         */
        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::get('add-new', [CouponController::class,'add_new'])->name('add-new');
            Route::post('store', [CouponController::class,'store'])->name('store');
            Route::get('update/{id}', [CouponController::class,'edit'])->name('update');
            Route::post('update/{id}', [CouponController::class,'update']);
            Route::get('status/{id}/{status}', [CouponController::class,'status'])->name('status');
            Route::delete('delete/{id}', [CouponController::class,'delete'])->name('delete');
            Route::post('search', [CouponController::class,'search'])->name('search');
        });
        /***
         * customer wallet
         */
        Route::group(['as' => 'wallet.','prefix'=>'wallet'], function () {
            Route::get('/', [WalletController::class , 'index'])->name('index');
            Route::get('/histories', [WalletController::class , 'histories'])->name('histories');
        });

        /***
         * Delivery Man
         */
        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.'], function () {
                Route::get('get-account-data/{deliveryman}', [DeliveryManController::class,'get_account_data'])->name('restaurantfilter');
                Route::get('add', [DeliveryManController::class,'index'])->name('add');
                Route::post('store', [DeliveryManController::class,'store'])->name('store');
                Route::get('list', [DeliveryManController::class,'list'])->name('list');
                Route::get('preview/{id}/{tab?}', [DeliveryManController::class,'preview'])->name('preview');
                Route::get('status/{id}/{status}', [DeliveryManController::class,'status'])->name('status');
                Route::get('earning/{id}/{status}', [DeliveryManController::class,'earning'])->name('earning');
                Route::get('update-application/{id}/{status}', [DeliveryManController::class,'update_application'])->name('application');
                Route::get('edit/{id}', [DeliveryManController::class,'edit'])->name('edit');
                Route::post('update/{id}', [DeliveryManController::class,'update'])->name('update');
                Route::delete('delete/{id}', [DeliveryManController::class,'delete'])->name('delete');
                Route::post('search', [DeliveryManController::class,'search'])->name('search');
                Route::get('get-deliverymen', [DeliveryManController::class,'get_deliverymen'])->name('get-deliverymen');
                Route::get('export-delivery-man', [DeliveryManController::class,'dm_list_export'])->name('export-delivery-man');
                Route::get('pending/list', [DeliveryManController::class,'pending'])->name('pending');
                Route::get('denied/list', [DeliveryManController::class,'denied'])->name('denied');

                Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
                    Route::get('list', [DeliveryManController::class,'reviews_list'])->name('list');
                    Route::get('status/{id}/{status}', [DeliveryManController::class,'reviews_status'])->name('status');
                });

                //incentive
                Route::get('incentive', [DeliveryManController::class,'pending_incentives'])->name('incentive');
                Route::get('incentive-history', [DeliveryManController::class,'get_incentives'])->name('incentive-history');
                Route::put('incentive', [DeliveryManController::class,'update_incentive_status']);
                Route::post('incentive_all', [DeliveryManController::class,'update_all_incentive_status'])->name('update-incentive');
                 //bonus
                Route::get('bonus', [DeliveryManController::class,'get_bonus'])->name('bonus');
                Route::post('bonus', [DeliveryManController::class,'add_bonus']);
                // message
                Route::get('message/{conversation_id}/{user_id}', [DeliveryManController::class,'conversation_view'])->name('message-view');
                Route::get('{user_id}/message/list', [DeliveryManController::class,'conversation_list'])->name('message-list');
                Route::get('messages/details', [DeliveryManController::class,'get_conversation_list'])->name('message-list-search');
        });

        Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
            Route::get('add-new', [EmployeeController::class,'add_new'])->name('add-new');
            Route::post('add-new',[EmployeeController::class ,'store'])->name('store');
            Route::get('list', [EmployeeController::class,'list'])->name('list');
            Route::get('update/{id}', [EmployeeController::class,'edit'])->name('edit');
            Route::post('update/{id}', [EmployeeController::class,'update'])->name('update');
            Route::delete('delete/{id}', [EmployeeController::class,'distroy'])->name('delete');
            Route::post('search', [EmployeeController::class,'search'])->name('search');
            Route::get('export-employee',[EmployeeController::class ,'employee_list_export'])->name('export-employee');
        });

        Route::group(['prefix' => 'administration', 'as' => 'administration.' ], function () {
            Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles.permissions');
            Route::post('/assign-role-to-user', [RolePermissionController::class, 'assignRoleToUser'])->name('assign.role.to.user');
            Route::post('/assign-permission-to-role', [RolePermissionController::class, 'assignPermissionToRole'])->name('assign.permission.to.role');
        });
        Route::group(['as' => 'roles.','prefix'=> 'role'], function () {
            Route::get('/add', [RolesAndPermission::class , 'index'])->name('add');
            Route::post('/add', [RolesAndPermission::class , 'submit']);
        });


        /***
         * business Setting
         */
        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', ], function () {
            Route::get('restaurant-setup', [BusinessSettingsController::class,'restaurant_index'])->name('restaurant-setup');
            Route::post('update-setup', [BusinessSettingsController::class,'restaurant_setup'])->name('update-setup');
            Route::post('tepm-off', [BusinessSettingsController::class,'temp_off'])->name('temp-off');
            Route::post('add-schedule',[BusinessSettingsController::class,'add_schedule'] )->name('add-schedule');
            Route::get('remove-schedule/{restaurant_schedule}', [BusinessSettingsController::class,'remove_schedule'])->name('remove-schedule');
            // Route::get('update-active-status', [BusinessSettingsController::class,'active_status'])->name('update-active-status');
            // Route::get('toggle-settings-status/{restaurant}/{status}/{menu}', [BusinessSettingsController::class,'restaurant_status'])->name('toggle-settings');
            // Route::get('site_direction_vendor', [BusinessSettingsController::class,'site_direction_vendor'])->name('site_direction_vendor');
        });

        // Refund Management Routes
        Route::group(['prefix' => 'refund', 'as' => 'refund.'], function () {
            Route::get('/', [\App\Http\Controllers\Vendor\RefundController::class, 'index'])->name('index');
            Route::get('/show/{id}', [\App\Http\Controllers\Vendor\RefundController::class, 'show'])->name('show');
            Route::post('/add-comment/{id}', [\App\Http\Controllers\Vendor\RefundController::class, 'addComment'])->name('add-comment');
            Route::get('/stats', [\App\Http\Controllers\Vendor\RefundController::class, 'getStats'])->name('stats');
        });

    });

});


?>
