<?php

use App\Http\Controllers\Admin\Addon;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BusinessSettingsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryManController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\Food;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\MarqueeController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\POSController;
use App\Http\Controllers\Admin\ReferralController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RolesAndPermission;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\VendorMessController;
use App\Http\Controllers\Admin\AdminFundController;
use App\Http\Controllers\Admin\DeliverymanJoineeController;
use App\Http\Controllers\Admin\FoodRequestController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\RestaurantJoineeController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\QRTemplateController;
use App\Http\Controllers\Admin\ZoneDeliveryChargeController;
use App\Http\Controllers\Admin\ZoneEnvironmentalFactorsController;
use App\Http\Controllers\DmEarningController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'admin.','middleware' =>'admin'], function () {

        Route::group(['as' => 'auth.'], function () {
            Route::get('_authenticate', [LoginController::class, 'login'])->name('login');
            Route::post('login-post', [LoginController::class, 'submit'])->name('login-post');
            Route::get('logout', [LoginController::class, 'logout'])->name('logout');
        });


        //dashboard
        Route::get('/', [DashboardController::class , 'dashboard'])->middleware("permission:dashboard.view")->name('dashboard');

        Route::group(['as' => 'profile.','prefix'=>'profile'], function () {
            Route::get('/view', [DashboardController::class , 'profile'])->middleware("permission:profile.view")->name('views');
            Route::get('/edit', [DashboardController::class , 'profileEdit'])->middleware("permission:profile.edit")->name('edit');
            Route::post('/update', [DashboardController::class , 'profileUpdateStore'])->middleware("permission:profile.update")->name('update');
        });

        Route::group(['prefix' => 'restaurant', 'as' => 'restaurant.' , ], function () {
            Route::get('add',[VendorController::class, 'index'] )->middleware("permission:restaurant.add")->name('add');
            Route::post('store', [VendorController::class, 'store'])->middleware("permission:restaurant.store")->name('store');
            Route::get('edit/{id}',[VendorController::class, 'edit'] )->middleware("permission:restaurant.edit")->name('edit');
            Route::post('update', [VendorController::class, 'update'])->middleware("permission:restaurant.update")->name('update');
            Route::get('list', [VendorController::class, 'list'])->middleware("permission:restaurant.list")->name('list');
            Route::get('view/{id}', [VendorController::class, 'view'])->middleware("permission:restaurant.view")->name('view');
            Route::get('access/{id}', [VendorController::class, 'access'])->middleware("permission:restaurant.access")->name('access');
            Route::get('status/{id}/{status}', [VendorController::class, 'status'])->middleware("permission:restaurant.status")->name('status');
            Route::get('sort', [VendorController::class, 'sort'])->middleware("permission:restaurant.sort")->name('sort');
            Route::post('sort', [VendorController::class, 'sort_update'])->middleware("permission:restaurant.sort");

            Route::get('get-restaurants',[VendorController::class, 'get_restaurants'] )->middleware("permission:restaurant.get-restaurants")->name('get-restaurants');
            Route::get('get-addons', [VendorController::class, 'get_addons'])->middleware("permission:restaurant.get_addons")->name('get_addons');
            Route::get('get-menus', [VendorController::class, 'get_menus'])->middleware("permission:restaurant.get_menus")->name('get_menus');
            Route::get('get-zone-coordinates', [VendorController::class, 'get_zone_coordinates'])->middleware("permission:restaurant.get-zone-coordinates")->name('get-zone-coordinates');

        });

        Route::group(['prefix'=>'owner', 'as'=> 'owner.'], function(){
            Route::get('/list', [VendorController::class, 'ownerList'])->middleware("permission:owner.list")->name('list');
            Route::put('/update',[VendorController::class, 'ownerUpdate'])->middleware("permission:owner.update")->name('update');
            Route::get('/edit/{id}', [VendorController::class, 'ownerEdit'])->middleware("permission:owner.edit")->name('edit');
            Route::get('/view/{id}', [VendorController::class, 'ownerView'])->middleware("permission:owner.view")->name('view');
            Route::get('/export', [VendorController::class, 'exportOwners'])->middleware("permission:owner.export")->name('export');
            
            // Owner Status Management
            Route::get('/status/{id}/{status}', [VendorController::class, 'ownerStatus'])->middleware("permission:owner.status")->name('status');
            Route::post('/block/{id}', [VendorController::class, 'ownerBlock'])->middleware("permission:owner.block")->name('block');
            Route::get('/unblock/{id}', [VendorController::class, 'ownerUnblock'])->middleware("permission:owner.unblock")->name('unblock');
            Route::get('/access/{id}', [VendorController::class, 'ownerAccess'])->middleware("permission:owner.access")->name('access');
            
            // Restaurant Management by Owner
            Route::get('/restaurant-status/{id}/{status}', [VendorController::class, 'ownerRestaurantStatus'])->middleware("permission:owner.restaurant-status")->name('restaurant-status');
            Route::post('/restaurant-block/{id}', [VendorController::class, 'restaurantBlock'])->middleware("permission:owner.restaurant-block")->name('restaurant-block');
            Route::get('/restaurant-unblock/{id}', [VendorController::class, 'restaurantUnblock'])->middleware("permission:owner.restaurant-unblock")->name('restaurant-unblock');
        });

        Route::group(['prefix' => 'order', 'as' => 'order.' ], function () {
            Route::get('list/{status}', [OrderController::class,'list'])->middleware("permission:order.list")->name('list');
            // Route::put('status-update/{id}', [OrderController::class,'status'])->name('status-update');
            Route::post('search', [OrderController::class,'search'])->middleware("permission:order.search")->name('search');
            Route::post('add-to-cart', [OrderController::class,'add_to_cart'])->middleware("permission:order.add-to-cart")->name('add-to-cart');
            Route::post('remove-from-cart', [OrderController::class,'remove_from_cart'])->middleware("permission:order.remove-from-cart")->name('remove-from-cart');
            Route::get('update/{order}', [OrderController::class,'update'])->middleware("permission:order.update")->name('update');
            Route::get('edit-order/{order}', [OrderController::class,'edit'])->middleware("permission:order.edit")->name('edit');
            Route::get('details/{id}', [OrderController::class,'details'])->middleware("permission:order.details")->name('details');
            // Route::get('status', [OrderController::class,'status'])->name('status');
            Route::get('top-orders', [OrderController::class,'topOrders'])->middleware("permission:order.top-orders")->name('top-orders');
            Route::get('order-status-update', [OrderController::class,'order_status_update'])->middleware("permission:order.order-status-update")->name('order-status-update');
            Route::get('quick-view', [OrderController::class,'quick_view'])->middleware("permission:order.quick-view")->name('quick-view');
            Route::get('quick-view-cart-item', [OrderController::class,'quick_view_cart_item'])->middleware("permission:order.quick-view-cart-item")->name('quick-view-cart-item');
            Route::get('generate-invoice/{id}', [OrderController::class,'generate_invoice'])->middleware("permission:order.generate-invoice")->name('generate-invoice');
            Route::get('generate-KOT/{id}', [OrderController::class,'generate_KOT'])->middleware("permission:order.generate-KOT")->name('generate-KOT');
            Route::post('add-payment-ref-code/{id}', [OrderController::class,'add_payment_ref_code'])->middleware("permission:order.add-payment-ref-code")->name('add-payment-ref-code');
            Route::get('dm-assign-manually', [OrderController::class,'order_dm_assign_manually'])->middleware("permission:order.dm_assign_manually")->name('dm_assign_manually');
        });

        Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
            Route::get('add',[CategoryController::class, 'index'] )->middleware("permission:category.add")->name('add');
            Route::post('store', [CategoryController::class, 'store'])->middleware("permission:category.store")->name('store');
            Route::get('edit/{id}', [CategoryController::class, 'edit'])->middleware("permission:category.edit")->name('edit');
            Route::post('update', [CategoryController::class, 'update'])->middleware("permission:category.update")->name('update');
            Route::get('status/{id}', [CategoryController::class, 'status'])->middleware("permission:category.status")->name('status');

            Route::get('get_categories/',[CategoryController::class, 'get_categories'] )->middleware("permission:category.get_categories")->name('get_categories');

        });

        Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
            Route::get('add', [Addon::class, 'index'])->middleware("permission:addon.add")->name('add');
            Route::post('store', [Addon::class, 'store'])->middleware("permission:addon.store")->name('store');
            Route::get('edit/{id}', [Addon::class, 'edit'])->middleware("permission:addon.edit")->name('edit');
            Route::put('update', [Addon::class, 'update'])->middleware("permission:addon.update")->name('update');
            Route::get('view/{id}', [Addon::class, 'view'])->middleware("permission:addon.view")->name('view');
            Route::delete('delete/{id}', [Addon::class, 'destroy'])->middleware("permission:addon.destroy")->name('destroy');
            Route::get('status', [Addon::class, 'status'])->middleware("permission:addon.status")->name('status');
            Route::get('get_addons', [Addon::class, 'get_addons'])->middleware("permission:addon.get_addons")->name('get_addons');
        });

        Route::group(['prefix' => 'food', 'as' => 'food.', ], function () {
            Route::get('add',[FoodController::class, 'index'] )->middleware("permission:food.add")->name('add');
            Route::post('store', [FoodController::class, 'store'])->middleware("permission:food.store")->name('store');
            Route::get('edit/{id}', [FoodController::class, 'edit'])->middleware("permission:food.edit")->name('edit');
            Route::get('list', [FoodController::class, 'list'])->middleware("permission:food.list")->name('list');
            Route::get('get-food-zone-wise',[FoodController::class, 'getFoodsZoneWise'] )->middleware("permission:food.getFoodZoneWise")->name('getFoodZoneWise');
            Route::post('update', [FoodController::class, 'update'])->middleware("permission:food.update")->name('update');
            Route::get('status',[FoodController::class, 'status'] )->middleware("permission:food.status")->name('status');
            Route::get('delete/{id}', [FoodController::class, 'delete'])->middleware("permission:food.delete")->name('delete');

            Route::get('get-submenu-option', [FoodController::class, 'get_submenu_option'])->middleware("permission:food.get-submenu-option")->name('get-submenu-option');

            Route::group(['prefix'=> 'reqeusts', 'as'=>"reqeusts"], function () {
                Route::get('/',[FoodRequestController::class, 'list'] )->middleware("permission:food.reqeusts");
                Route::get('/reqeusts-form', [FoodRequestController::class,'requestform'])->middleware("permission:food.reqeusts.form")->name('form');
                Route::post('/reqeusts-form-submit', [FoodRequestController::class,'requestformSave'])->middleware("permission:food.reqeusts.form-submit")->name('form-submit');

            });
        });

        /**
         * Food Availability Management
         */
        Route::group(['prefix' => 'food-availability', 'as' => 'food-availability.'], function () {
            Route::get('/', [\App\Http\Controllers\Admin\FoodAvailabilityController::class, 'index'])->middleware("permission:food-availability.index")->name('index');
            Route::get('/{food}', [\App\Http\Controllers\Admin\FoodAvailabilityController::class, 'show'])->middleware("permission:food-availability.show")->name('show');
            Route::post('/store', [\App\Http\Controllers\Admin\FoodAvailabilityController::class, 'store'])->middleware("permission:food-availability.store")->name('store');
            Route::put('/{availabilityTime}', [\App\Http\Controllers\Admin\FoodAvailabilityController::class, 'update'])->middleware("permission:food-availability.update")->name('update');
            Route::delete('/{availabilityTime}', [\App\Http\Controllers\Admin\FoodAvailabilityController::class, 'destroy'])->middleware("permission:food-availability.destroy")->name('destroy');
            Route::delete('/', [\App\Http\Controllers\Admin\FoodAvailabilityController::class, 'bulkDelete'])->middleware("permission:food-availability.bulk-delete")->name('bulk-delete');
        });

        /**
         * QR Template Management
         */
        Route::group(['prefix' => 'qr-template', 'as' => 'qr-template.'], function () {
            Route::get('/', [QRTemplateController::class, 'index'])->middleware("permission:qr-template.index")->name('index');
            Route::get('/create', [QRTemplateController::class, 'create'])->middleware("permission:qr-template.create")->name('create');
            Route::post('/store', [QRTemplateController::class, 'store'])->middleware("permission:qr-template.store")->name('store');
            Route::get('/edit/{id}', [QRTemplateController::class, 'edit'])->middleware("permission:qr-template.edit")->name('edit');
            Route::post('/update/{id}', [QRTemplateController::class, 'update'])->middleware("permission:qr-template.update")->name('update');
            Route::delete('/delete/{id}', [QRTemplateController::class, 'destroy'])->middleware("permission:qr-template.delete")->name('delete');
            Route::post('/toggle-status/{id}', [QRTemplateController::class, 'toggleStatus'])->middleware("permission:qr-template.toggle-status")->name('toggle-status');
            Route::post('/set-default/{id}', [QRTemplateController::class, 'setDefault'])->middleware("permission:qr-template.set-default")->name('set-default');
            Route::get('/preview/{id}', [QRTemplateController::class, 'preview'])->middleware("permission:qr-template.preview")->name('preview');
            Route::get('/zone-templates', [QRTemplateController::class, 'getZoneTemplates'])->middleware("permission:qr-template.zone-templates")->name('zone-templates');
            Route::post('/cleanup-data', [QRTemplateController::class, 'cleanupTemplateData'])->name('cleanup-data');
        });

        /**
         * Zone Delivery Charge & Environmental Factors Management
         */
        Route::group(['prefix' => 'zone-delivery-charge', 'as' => 'zone-delivery-charge.'], function () {
            // Delivery Charge Settings
            Route::get('/', [\App\Http\Controllers\Admin\ZoneDeliveryChargeController::class, 'index'])->middleware("permission:zone-delivery-charge.index")->name('index');
            Route::get('/{zone}/edit', [\App\Http\Controllers\Admin\ZoneDeliveryChargeController::class, 'edit'])->middleware("permission:zone-delivery-charge.edit")->name('edit');
            Route::post('/{zone}/store', [\App\Http\Controllers\Admin\ZoneDeliveryChargeController::class, 'store'])->middleware("permission:zone-delivery-charge.store")->name('store');
            Route::post('/{zone}/test', [\App\Http\Controllers\Admin\ZoneDeliveryChargeController::class, 'testCalculation'])->middleware("permission:zone-delivery-charge.test")->name('test');
            Route::get('/{zone}/settings', [\App\Http\Controllers\Admin\ZoneDeliveryChargeController::class, 'getSettings'])->middleware("permission:zone-delivery-charge.settings")->name('settings');
            Route::post('/clone', [\App\Http\Controllers\Admin\ZoneDeliveryChargeController::class, 'cloneSettings'])->middleware("permission:zone-delivery-charge.clone")->name('clone');
            
            // Environmental Factors
            Route::get('/environmental-factors', [\App\Http\Controllers\Admin\ZoneEnvironmentalFactorsController::class, 'index'])->middleware("permission:zone-delivery-charge.environmental-factors")->name('environmental-factors');
            Route::get('/{zone}/environmental-factors', [\App\Http\Controllers\Admin\ZoneEnvironmentalFactorsController::class, 'edit'])->middleware("permission:zone-delivery-charge.environmental-factors")->name('environmental-factors.edit');
            Route::post('/{zone}/environmental-factors', [\App\Http\Controllers\Admin\ZoneEnvironmentalFactorsController::class, 'update'])->middleware("permission:zone-delivery-charge.environmental-factors")->name('environmental-factors.update');
            Route::get('/{zone}/current-factors', [\App\Http\Controllers\Admin\ZoneEnvironmentalFactorsController::class, 'getCurrentFactors'])->middleware("permission:zone-delivery-charge.environmental-factors")->name('current-factors');
        });

        /**
         * wallet\
         */
        Route::group(['as' => 'fund.','prefix'=>'fund'], function () {
            Route::get('/', [AdminFundController::class , 'index'])->middleware("permission:fund.index")->name('index');
            Route::get('/histories', [AdminFundController::class , 'histories'])->middleware("permission:fund.histories")->name('histories');
        });
        /**
         * Document KYC Management - Proper CRUD Operations
         */
        Route::group(['as' => 'doc.','prefix'=>'doc'], function () {
            // Index/List route - shows all documents
            Route::get('/', [DocumentController::class, 'kycDocumentTable'])->middleware("permission:doc.index")->name('index');
            Route::get('/list', [DocumentController::class, 'kycDocumentTable'])->middleware("permission:doc.kyc-table")->name('kyc-table');
            
            // Create routes
            Route::get('/create', [DocumentController::class, 'create'])->middleware("permission:doc.create")->name('create');
            Route::post('/', [DocumentController::class, 'store'])->middleware("permission:doc.store")->name('store');
            
            // Show/View route
            Route::get('/{id}', [DocumentController::class, 'show'])->middleware("permission:doc.show")->name('show');
            
            // Edit routes
            Route::get('/{id}/edit', [DocumentController::class, 'edit'])->middleware("permission:doc.edit")->name('edit');
            Route::put('/{id}', [DocumentController::class, 'update'])->middleware("permission:doc.update")->name('update');
            Route::patch('/{id}', [DocumentController::class, 'update'])->middleware("permission:doc.update")->name('patch');
            
            // Delete route
            Route::delete('/{id}', [DocumentController::class, 'destroy'])->middleware("permission:doc.delete")->name('destroy');
            
            // Legacy/Custom routes for backward compatibility
            Route::get('/kyc/dashboard', [DocumentController::class, 'kyc'])->middleware("permission:doc.kyc")->name('kyc');
        });

        /**
         * JOin as\
         */
        Route::group(['as'=> 'joinas.','prefix'=>'joinas'], function () {
            Route::get('/restaurant', [RestaurantJoineeController::class , 'joinRequest'])->middleware("permission:joinas.restaurant")->name('restaurant');
            Route::delete('/restaurant/{id}', [RestaurantJoineeController::class , 'deleteJoinRequest'])->middleware("permission:joinas.restaurant-delete")->name('restaurant-delete');
            Route::get('/restaurant-show/{id}', [RestaurantJoineeController::class , 'joinRequestShow'])->middleware("permission:joinas.restaurant-show")->name('restaurant-show');
            Route::put('/restaurant-doc-update', [RestaurantJoineeController::class , 'joinRequestDocUpdate'])->middleware("permission:joinas.restaurant-doc-update")->name('restaurant-doc-update');
            Route::get('/restaurant-doc-update-status/{id}/{status}', [RestaurantJoineeController::class , 'joinRequestDocUpdateStatus'])->middleware("permission:joinas.restaurant-doc-update-status")->name('restaurant-doc-update-status');
            Route::get('/restaurant-kyc-update-status/{id}/{status}', [RestaurantJoineeController::class , 'joinRequestKycUpdateStatus'])->middleware("permission:joinas.restaurant-kyc-update-status")->name('restaurant-kyc-update-status');
            Route::get('/restaurant-form-update-status/{id}/{status}', [RestaurantJoineeController::class , 'joinRequestFormUpdateStatus'])->middleware("permission:joinas.restaurant-form-update-status")->name('restaurant-form-update-status');
            Route::get('/restaurant-create/{id}', [RestaurantJoineeController::class , 'createRestaurant'])->middleware("permission:joinas.restaurant-create")->name('restaurant-create');
            // Route::post('/restaurant', [JoineeCsontroller::class , 'joinAsRestaurantStore']);
            Route::get('/deliveryman', [DeliverymanJoineeController::class , 'joinRequest'])->middleware("permission:joinas.deliveryman")->name('deliveryman');
            Route::delete('/deliveryman/{id}', [DeliverymanJoineeController::class , 'deleteJoinRequest'])->middleware("permission:joinas.deliveryman-delete")->name('deliveryman-delete');
            Route::get('/deliveryman-show/{id}', [DeliverymanJoineeController::class , 'joinRequestShow'])->middleware("permission:joinas.deliveryman-show")->name('deliveryman-show');
            Route::put('/deliveryman-doc-update', [DeliverymanJoineeController::class , 'joinRequestDocUpdate'])->middleware("permission:joinas.deliveryman-doc-update")->name('deliveryman-doc-update');
            Route::get('/deliveryman-doc-update-status/{id}/{status}', [DeliverymanJoineeController::class , 'joinRequestDocUpdateStatus'])->middleware("permission:joinas.deliveryman-doc-update-status")->name('deliveryman-doc-update-status');
            Route::get('/deliveryman-kyc-update-status/{id}/{status}', [DeliverymanJoineeController::class , 'joinRequestKycUpdateStatus'])->middleware("permission:joinas.deliveryman-kyc-update-status")->name('deliveryman-kyc-update-status');
            Route::get('/deliveryman-form-update-status/{id}/{status}', [DeliverymanJoineeController::class , 'joinRequestFormUpdateStatus'])->middleware("permission:joinas.deliveryman-form-update-status")->name('deliveryman-form-update-status');
            Route::get('/deliveryman-create/{id}', [DeliverymanJoineeController::class , 'createDeliveryman'])->middleware("permission:joinas.deliveryman-create")->name('deliveryman-create');
        });
        /**
         * mess
         */

        Route::group(['prefix' => 'mess', 'as' => 'mess.'], function () {
            Route::get('add',[VendorMessController::class, 'index'] )->middleware("permission:mess.add")->name('add');
            Route::get('edit/{id}',[VendorMessController::class, 'edit'] )->middleware("permission:mess.edit")->name('edit');
            Route::post('store', [VendorMessController::class, 'store'])->middleware("permission:mess.store")->name('store');
            Route::post('update', [VendorMessController::class, 'update'])->middleware("permission:mess.update")->name('update');
            Route::get('list', [VendorMessController::class, 'list'])->middleware("permission:mess.list")->name('list');
            Route::get('access/{id}', [VendorMessController::class, 'access'])->middleware("permission:mess.access")->name('access');
            // Route::get('view/{id}', [Addon::class, 'view'])->name('view');
        });

        /**
         * zone
         */
        Route::group(['prefix' => 'zone', 'as' => 'zone.'], function () {
            Route::get('/add', [ZoneController::class, 'add'])->middleware("permission:zone.add")->name('add');
            Route::get('/list', [ZoneController::class, 'list'])->middleware("permission:zone.list")->name('list');
            Route::post('/store', [ZoneController::class, 'store'])->middleware("permission:zone.store")->name('store');
            Route::get('edit/{id}',[ZoneController::class, 'edit'])->middleware("permission:zone.edit")->name('edit');
            Route::post('update/{id}', [ZoneController::class, 'update'])->middleware("permission:zone.update")->name('update');
            Route::get('status/{id}/{status}', [ZoneController::class, 'status'])->middleware("permission:zone.status")->name('status');

            Route::get('set-order-zone',[ZoneController::class, 'setOrderZone'])->middleware("permission:zone.set-order-zone")->name('set-order-zone');
            Route::get('get-order-zone',[ZoneController::class, 'getOrderZone'])->middleware("permission:zone.get-order-zone")->name('get-order-zone');

            // Route::get('settings/{id}', 'ZoneController@zone_settings')->name('settings');
            // Route::post('zone-settings-update/{id}', 'ZoneController@zone_settings_update')->name('zone_settings_update');
            // Route::delete('delete/{zone}', 'ZoneController@destroy')->name('delete');
            // Route::post('search', 'ZoneController@search')->name('search');
            // Route::get('zone-filter/{id}', 'ZoneController@zone_filter')->name('zonefilter');
            // Route::get('get-all-zone-cordinates/{id?}', 'ZoneController@get_all_zone_cordinates')->name('zoneCoordinates');
            // //Route::post('export-zone-cordinates', 'ZoneController@export_zones')->name('export-zones');
            // Route::get('export-zone-cordinates/{type}', 'ZoneController@export_zones')->name('export-zones');
            // Route::post('store-incentive/{zone_id}', 'ZoneController@store_incentive')->name('incentive.store');
            // Route::delete('destroy-incentive/{id}', 'ZoneController@destroy_incentive')->name('incentive.destory');
            // Route::get('get_zones', 'ZoneController@edit')->name('edit');

        });

        /**
         * Zone Business Settings
         */
        Route::group(['prefix' => 'zone', 'as' => 'zone.'], function () {
            Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
                Route::get('/', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'index'])->middleware("permission:zone.business-settings.index")->name('index');
                Route::get('/{zone}/edit', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'edit'])->middleware("permission:zone.business-settings.edit")->name('edit');
                Route::post('/{zone}/update', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'update'])->middleware("permission:zone.business-settings.update")->name('update');
                Route::post('/{zone}/copy-global', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'copyGlobalSettings'])->middleware("permission:zone.business-settings.copy-global")->name('copy-global');
                Route::post('/{zone}/reset-to-global', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'resetToGlobal'])->middleware("permission:zone.business-settings.reset-to-global")->name('reset-to-global');
                Route::post('/clone-settings', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'cloneSettings'])->middleware("permission:zone.business-settings.clone")->name('clone-settings');
                Route::get('/{zone}/compare-global', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'compareWithGlobal'])->middleware("permission:zone.business-settings.compare")->name('compare-global');
                Route::get('/{zone}/settings-api', [\App\Http\Controllers\Admin\ZoneBusinessSettingsController::class, 'getZoneSettings'])->middleware("permission:zone.business-settings.api")->name('settings-api');
            });
        });

        /**
         * pos system
         */

        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::get('/', [POSController::class, 'index'])->middleware("permission:pos.index")->name('index');
            Route::get('/foods', [POSController::class, 'getFoods'])->middleware("permission:pos.get-foods")->name('get-foods');
            Route::get('quick-view', [POSController::class, 'quick_view'])->middleware("permission:pos.quick-view")->name('quick-view');
            Route::get('quick-view-cart-item', [POSController::class, 'quick_view_card_item'])->middleware("permission:pos.quick-view-cart-item")->name('quick-view-cart-item');
            Route::get('get-food-item-details', [POSController::class, 'foodDetails'])->middleware("permission:pos.get-food-item-details")->name('get-food-item-details');
            Route::post('add-to-cart', [POSController::class, 'addToCart'])->middleware("permission:pos.add-to-cart")->name('add-to-cart');
            Route::get('get-cart-items', [POSController::class, 'getCartItems'])->middleware("permission:pos.get-cart-items")->name('get-cart-items');
            Route::get('delete-cart-item',[POSController::class, 'deleteSingleItem'] )->middleware("permission:pos.delete-cart-item")->name('delete-cart-item');
            Route::post('customer-store', [POSController::class, 'customer_store'])->middleware("permission:pos.customer-store")->name('customer-store');
            Route::post('place-order', [POSController::class, 'place_order'])->middleware("permission:pos.order")->name('order');
        });

        /**
         * Vehicle Management
         */
        Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
            // Route::post('contact-store', 'ContactMessages@store')->name('store');
            Route::get('list',[VehicleController::class,'list'])->middleware("permission:vehicle.list")->name('list');
            Route::get('add',[VehicleController::class,'create'])->middleware("permission:vehicle.create")->name('create');
            Route::get('status/{vehicle}/{status}',[VehicleController::class,'status'])->middleware("permission:vehicle.status")->name('status');
            Route::get('edit/{vehicle}',[VehicleController::class,'edit'])->middleware("permission:vehicle.edit")->name('edit');
            Route::post('store',[VehicleController::class,'store'])->middleware("permission:vehicle.store")->name('store');
            Route::post('update/{vehicle}',[VehicleController::class,'update'])->middleware("permission:vehicle.update")->name('update');
            Route::delete('delete',[VehicleController::class,'destroy'])->middleware("permission:vehicle.delete")->name('delete');
            Route::get('view/{vehicle}',[VehicleController::class,'view'])->middleware("permission:vehicle.view")->name('view');

        });

        Route::group(['as' => 'roles.','prefix'=> 'role'], function () {

            Route::get('/add', [RolesAndPermission::class , 'index'])->middleware("permission:roles.add")->name('add');
            Route::post('/add', [RolesAndPermission::class , 'submit'])->middleware("permission:roles.add");
        });

        /**
         * shift
         */
        Route::group(['prefix' => 'shift', 'as' => 'shift.'], function () {
            Route::get('/', [ShiftController::class,'list'])->middleware("permission:shift.list")->name('list');
            Route::post('store', [ShiftController::class,'store'])->middleware("permission:shift.store")->name('store');
            Route::get('edit/{id}', [ShiftController::class,'edit'])->middleware("permission:shift.edit")->name('edit');
            Route::post('update', [ShiftController::class,'update'])->middleware("permission:shift.update")->name('update');
            Route::delete('delete/{shift}', [ShiftController::class,'destroy'])->middleware("permission:shift.delete")->name('delete');
            Route::post('search', [ShiftController::class,'search'])->middleware("permission:shift.search")->name('search');
            Route::get('status/{id}/{status}', [ShiftController::class,'status'])->middleware("permission:shift.status")->name('status');
        });

        /**
         * Payment Management with Approval Workflow
         */
        Route::group(['prefix' => 'payments', 'as' => 'payments.'], function () {
            // Payment Request Listing & Management
            Route::get('/', [PaymentController::class,'list'])->middleware("permission:payments.list")->name('list');
            Route::get('/{id}/show', [PaymentController::class,'show'])->middleware("permission:payments.show")->name('show');
            
            // Payment Processing & Approval Workflow
            Route::get('/pay-form', [PaymentController::class,'payform'])->middleware("permission:payments.pay-form")->name('pay-form');
            Route::get('/process-form/{id}', [PaymentController::class,'payform'])->middleware("permission:payments.pay-form")->name('process-form');
            Route::post('/pay-request/{id}/approve', [PaymentController::class,'approveRequest'])->middleware("permission:payments.approve")->name('approve-request');
            Route::post('/pay-request/{id}/reject', [PaymentController::class,'rejectRequest'])->middleware("permission:payments.reject")->name('reject-request');
            Route::post('/process-payment', [PaymentController::class,'savePaymentRequest'])->middleware("permission:payments.process")->name('process-payment');
            
            // Bulk Operations
            Route::post('/bulk-action', [PaymentController::class,'bulkAction'])->middleware("permission:payments.bulk-action")->name('bulk-action');
            
            // Export & Reporting
            Route::get('/export', [PaymentController::class,'exportPayments'])->middleware("permission:payments.export")->name('export');
            
            // Legacy route for backward compatibility
            Route::post('/pay-form-request', [PaymentController::class,'savePaymentRequest'])->middleware("permission:payments.pay-form-request")->name('pay-form-request');
        });

        /**
         * Delivery-Man
         */
        Route::group(['prefix' => 'delivery-man', 'as' => 'delivery-man.'], function () {
            Route::get('get-account-data/{deliveryman}', [DeliveryManController::class,'get_account_data'])->middleware("permission:delivery-man.restaurantfilter")->name('restaurantfilter');
            // Route::group(['middleware' => ['module:deliveryman']], function () {
                Route::get('add', [DeliveryManController::class,'index'])->middleware("permission:delivery-man.add")->name('add');
                Route::post('store', [DeliveryManController::class,'store'])->middleware("permission:delivery-man.store")->name('store');
                Route::get('show/{id}', [DeliveryManController::class,'show'])->middleware("permission:delivery-man.show")->name('show');
                Route::get('list', [DeliveryManController::class,'list'])->middleware("permission:delivery-man.list")->name('list');
                Route::get('history/{id}', [DeliveryManController::class,'history'])->middleware("permission:delivery-man.list")->name('history');
                Route::get('kyc/{dm_id}', [DeliveryManController::class,'viewKyc'])->middleware("permission:delivery-man.kyc")->name('kyc');
                Route::get('preview/{id}/{tab?}', [DeliveryManController::class,'preview'])->middleware("permission:delivery-man.preview")->name('preview');
                Route::get('status/{id}/{status}', [DeliveryManController::class,'status'])->middleware("permission:delivery-man.status")->name('status');
                Route::get('earning/{id}/{status}', [DeliveryManController::class,'earning'])->middleware("permission:delivery-man.earning")->name('earning');
                Route::get('update-application/{id}/{status}', [DeliveryManController::class,'update_application'])->middleware("permission:delivery-man.application")->name('application');
                Route::get('edit/{id}', [DeliveryManController::class,'edit'])->middleware("permission:delivery-man.edit")->name('edit');
                Route::post('update/{id}', [DeliveryManController::class,'update'])->middleware("permission:delivery-man.update")->name('update');
                Route::delete('delete/{id}', [DeliveryManController::class,'delete'])->middleware("permission:delivery-man.delete")->name('delete');
                Route::post('search', [DeliveryManController::class,'search'])->middleware("permission:delivery-man.search")->name('search');
                Route::get('get-deliverymen', [DeliveryManController::class,'get_deliverymen'])->middleware("permission:delivery-man.get-deliverymen")->name('get-deliverymen');
                Route::get('export-delivery-man', [DeliveryManController::class,'dm_list_export'])->middleware("permission:delivery-man.export-delivery-man")->name('export-delivery-man');
                Route::get('pending/list', [DeliveryManController::class,'pending'])->middleware("permission:delivery-man.pending")->name('pending');
                Route::get('denied/list', [DeliveryManController::class,'denied'])->middleware("permission:delivery-man.denied")->name('denied');

                //fuel price
                Route::post('update-fuel-rate', [DeliveryManController::class,'update_fuel_rate'])->middleware("permission:delivery-man.update-fuel-rate")->name('update-fuel-rate');
                Route::post('add-fuel-balance', [DeliveryManController::class,'add_fuel_balance'])->middleware("permission:delivery-man.add-fuel-balance")->name('add-fuel-balance');
                Route::post('change-password', [DeliveryManController::class,'change_password'])->middleware("permission:delivery-man.update")->name('change-password');


                Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
                    Route::get('list', [DeliveryManController::class,'reviews_list'])->name('list');
                    Route::get('status/{id}/{status}', [DeliveryManController::class,'reviews_status'])->name('status');
                });

                //incentive
                Route::get('incentive', [DeliveryManController::class,'pending_incentives'])->middleware("permission:delivery-man.incentive")->name('incentive');
                Route::get('incentive-history', [DeliveryManController::class,'get_incentives'])->middleware("permission:delivery-man.incentive-history")->name('incentive-history');
                Route::put('incentive', [DeliveryManController::class,'update_incentive_status'])->middleware("permission:delivery-man.update-incentive-status");
                Route::post('incentive_all', [DeliveryManController::class,'update_all_incentive_status'])->middleware("permission:delivery-man.update-all-incentive-status")->name('update-incentive');
                 //bonus
                Route::get('bonus', [DeliveryManController::class,'get_bonus'])->middleware("permission:delivery-man.bonus")->name('bonus');
                Route::post('bonus', [DeliveryManController::class,'add_bonus'])->middleware("permission:delivery-man.add-bonus");
                // message
                Route::get('message/{conversation_id}/{user_id}', [DeliveryManController::class,'conversation_view'])->middleware("permission:delivery-man.message-view")->name('message-view');
                Route::get('{user_id}/message/list', [DeliveryManController::class,'conversation_list'])->middleware("permission:delivery-man.message-list")->name('message-list');
                Route::get('messages/details', [DeliveryManController::class,'get_conversation_list'])->middleware("permission:delivery-man.message-list-search")->name('message-list-search');
            // });
        });
        /**
         * Employee
         */
        Route::group(['prefix' => 'employee', 'as' => 'employee.'], function () {
            Route::get('add-new', [EmployeeController::class,'add_new'])->middleware("permission:employee.add-new")->name('add-new');
            Route::post('add-new',[EmployeeController::class ,'store'])->middleware("permission:employee.store");
            Route::get('list', [EmployeeController::class,'list'])->middleware("permission:employee.list")->name('list');
            Route::get('update/{id}', [EmployeeController::class,'edit'])->middleware("permission:employee.edit")->name('edit');
            Route::post('update/{id}', [EmployeeController::class,'update'])->middleware("permission:employee.update")->name('update');
            Route::delete('delete/{id}', [EmployeeController::class,'distroy'])->middleware("permission:employee.delete")->name('delete');
            Route::post('search', [EmployeeController::class,'search'])->middleware("permission:employee.search")->name('search');
            Route::get('export-employee',[EmployeeController::class ,'employee_list_export'])->middleware("permission:employee.export-employee")->name('export-employee');
        });

        /**
         * subscripition
         */
        Route::group(['prefix' => 'subscription', 'as' => 'subscription.'], function () {
            Route::get('/{for}', [SubscriptionController::class,'package_list'])->middleware("permission:subscription.list")->name('list');
            Route::get('package/add', [SubscriptionController::class,'create'])->middleware("permission:subscription.create")->name('create');
            Route::post('store/', [SubscriptionController::class,'store'])->middleware("permission:subscription.store")->name('subscription_store');
            Route::get('package/details/{id}', [SubscriptionController::class,'details'])->middleware("permission:subscription.package-details")->name('package_details');
        });
        /**
         * Banners
         */
        Route::group(['prefix' => 'banner', 'as' => 'banner.'], function () {
            Route::get('add-new', [BannerController::class ,'index'])->middleware("permission:banner.add-new")->name('add-new');
            Route::get('get-partials', [BannerController::class ,'getPartials'])->middleware("permission:banner.get-partials")->name('get-partials');
            Route::get('get-partials-saved', [BannerController::class ,'getPartialsSaved'])->middleware("permission:banner.get-partials-saved")->name('get-partials-saved');
            Route::post('store', [BannerController::class ,'store'])->middleware("permission:banner.store")->name('store');
            Route::get('edit', [BannerController::class ,'edit'])->middleware("permission:banner.edit")->name('edit');
            Route::post('update', [BannerController::class ,'update'])->middleware("permission:banner.update")->name('update');
            Route::get('status/{id}/{status}', [BannerController::class ,'status'])->middleware("permission:banner.status")->name('status');
            Route::get('delete/{banner}', [BannerController::class ,'delete'])->middleware("permission:banner.delete")->name('delete');
            Route::post('search', [BannerController::class ,'search'])->middleware("permission:banner.search")->name('search');
        });

        /**
         * Banners
         */
        Route::group(['prefix' => 'marquee', 'as' => 'marquee.'], function () {
            Route::get('add-new', [MarqueeController::class ,'index'])->middleware("permission:marquee.add-new")->name('add-new');
            Route::post('store', [MarqueeController::class ,'store'])->middleware("permission:marquee.store")->name('store');
            Route::get('edit', [MarqueeController::class ,'edit'])->middleware("permission:marquee.edit")->name('edit');
            Route::post('update', [MarqueeController::class ,'update'])->middleware("permission:marquee.update")->name('update');
            Route::get('status/{id}/{status}', [MarqueeController::class ,'status'])->middleware("permission:marquee.status")->name('status');
            Route::get('delete/{banner}', [MarqueeController::class ,'delete'])->middleware("permission:marquee.delete")->name('delete');
            Route::post('search', [MarqueeController::class ,'search'])->middleware("permission:marquee.search")->name('search');
        });

        /**
         * Discount  Coupon
         */
        Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
            Route::get('add-new', [CouponController::class,'add_new'])->middleware("permission:coupon.add-new")->name('add-new');
            Route::post('store', [CouponController::class,'store'])->middleware("permission:coupon.store")->name('store');
            Route::get('update/{id}', [CouponController::class,'edit'])->middleware("permission:coupon.update")->name('update');
            Route::post('update/{id}', [CouponController::class,'update'])->middleware("permission:coupon.update-post");
            Route::get('status/{id}/{status}', [CouponController::class,'status'])->middleware("permission:coupon.status")->name('status');
            Route::delete('delete/{id}', [CouponController::class,'delete'])->middleware("permission:coupon.delete")->name('delete');
            Route::post('search', [CouponController::class,'search'])->middleware("permission:coupon.search")->name('search');
            Route::get('uses-details/{id}', [CouponController::class,'usesDetails'])->middleware("permission:coupon.add-new")->name('uses-details');
        });
        /**
         * Discount Coupon
         */
        Route::group(['as' => 'customer.','prefix'=>'customer'], function () {
            Route::get('/add', [CustomerController::class , 'index'])->middleware("permission:customer.add")->name('add');
            Route::post('/add', [CustomerController::class , 'submit'])->middleware("permission:customer.submit");
            Route::get('/list', [CustomerController::class , 'list'])->middleware("permission:customer.list")->name('list');
            Route::get('/edit/{id}', [CustomerController::class , 'edit'])->middleware("permission:customer.edit")->name('edit');
            Route::post('/update/{id}', [CustomerController::class , 'update'])->middleware("permission:customer.update")->name('update');
            Route::get('/view/{id}', [CustomerController::class , 'view'])->middleware("permission:customer.view")->name('view');
            Route::get('/getdata', [CustomerController::class , 'getdata'])->middleware("permission:customer.getdata")->name('getdata');
            Route::get('access/{id}', [CustomerController::class, 'access'])->middleware("permission:customer.access")->name('access');
            Route::get('status', [CustomerController::class, 'status'])->middleware("permission:customer.status")->name('status');
            Route::post('add-wallet-fund', [CustomerController::class, 'addWalletFund'])->middleware("permission:customer.add-wallet-fund")->name('add-wallet-fund');
            Route::post('payments/history',[CustomerController::class,'histories'])->middleware("permission:customer.payments-history")->name('payments.history');
            Route::post('order-search',[CustomerController::class,'orderSearch'])->middleware("permission:customer.view")->name('order-search');
            Route::get('/rating', [CustomerController::class , 'rating'])->middleware("permission:customer.rating")->name('rating');
            Route::post('/clear-stats-cache/{id}', [CustomerController::class, 'clearCustomerStatsCache'])->middleware("permission:customer.clear-stats-cache")->name('clear-stats-cache');
        });

        /**
         * Reviews Management
         */
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::get('grouped-list', [ReviewController::class, 'index'])->middleware("permission:review.list")->name('grouped-list');
            Route::get('grouped-list-optimized', [ReviewController::class, 'indexOptimized'])->middleware("permission:review.list")->name('grouped-list-optimized');
            Route::get('grouped-list-cached', [ReviewController::class, 'indexCached'])->middleware("permission:review.list")->name('grouped-list-cached');
            Route::post('update-restaurant', [ReviewController::class, 'updateRestaurantReview'])->middleware("permission:review.edit")->name('update-restaurant');
            Route::post('update-deliveryman', [ReviewController::class, 'updateDeliverymanReview'])->middleware("permission:review.edit")->name('update-deliveryman');
            Route::get('get-review/{id}', [ReviewController::class, 'getReview'])->middleware("permission:review.view")->name('get-review');
            Route::delete('delete/{id}', [ReviewController::class, 'destroy'])->middleware("permission:review.delete")->name('delete');
            Route::post('clear-cache', [ReviewController::class, 'clearCache'])->middleware("permission:review.edit")->name('clear-cache');
        });

        /**
         * Notifications
         */

        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
            // Sending Notifications (Existing)
            Route::get('add-new',[NotificationController::class, 'index'])->middleware("permission:notification.add-new")->name('add-new');
            Route::post('store',[NotificationController::class, 'store'])->middleware("permission:notification.store")->name('store');
            Route::get('edit/{id}',[NotificationController::class, 'edit'])->middleware("permission:notification.edit")->name('edit');
            Route::post('update/{id}',[NotificationController::class, 'update'])->middleware("permission:notification.update")->name('update');
            Route::get('status/{id}/{status}',[NotificationController::class, 'status'])->middleware("permission:notification.status")->name('status');
            Route::get('delete/{id}',[NotificationController::class, 'delete'])->middleware("permission:notification.delete")->name('delete');
            Route::get('clear-data',[NotificationController::class, 'clearData'])->middleware("permission:notification.clear-data")->name('clear-data');
            Route::get('export/{type}',[NotificationController::class, 'export'])->middleware("permission:notification.export")->name('export');
            Route::get('targetClient',[NotificationController::class, 'targetClient'])->middleware("permission:notification.targetClient")->name('targetClient');
            
            // Receiving Notifications (New Admin Notification System)
            Route::get('inbox',[NotificationController::class, 'inbox'])->middleware("permission:notification.inbox")->name('inbox');
            Route::get('inbox/fetch',[NotificationController::class, 'fetchInboxNotifications'])->middleware("permission:notification.inbox-fetch")->name('inbox.fetch');
            Route::get('inbox/{id}/read',[NotificationController::class, 'markInboxAsRead'])->middleware("permission:notification.inbox-read")->name('inbox.read');
            Route::post('inbox/mark-all-read',[NotificationController::class, 'markAllInboxAsRead'])->middleware("permission:notification.inbox-mark-all-read")->name('inbox.mark-all-read');
            Route::delete('inbox/{id}',[NotificationController::class, 'deleteInboxNotification'])->middleware("permission:notification.inbox-delete")->name('inbox.delete');
            Route::delete('inbox',[NotificationController::class, 'deleteAllInboxNotifications'])->middleware("permission:notification.inbox-delete-all")->name('inbox.delete-all');
            Route::get('inbox/count',[NotificationController::class, 'getInboxUnreadCount'])->middleware("permission:notification.inbox-count")->name('inbox.count');
            Route::get('inbox/settings',[NotificationController::class, 'inboxSettings'])->middleware("permission:notification.inbox-settings")->name('inbox.settings');
            Route::post('inbox/settings',[NotificationController::class, 'updateInboxSettings'])->middleware("permission:notification.inbox-settings-update")->name('inbox.settings.update');
            Route::get('test-send',[NotificationController::class, 'testSendNotification'])->name('test-send');
            Route::get('test',[NotificationController::class, 'testPage'])->name('test');
        });

        Route::group(['prefix' => 'report', 'as' => 'report.' ], function () {
            Route::get('/order', [ReportController::class,'order_report'])->middleware("permission:report.order")->name('order');
            Route::get('/product', [ReportController::class,'product_report'])->middleware("permission:report.product")->name('product');
            Route::get('/tax', [ReportController::class,'tax_report'])->middleware("permission:report.tax")->name('tax');
        });
        Route::group(['prefix' => 'earning', 'as' => 'earning.' ], function () {
            Route::get('/deliveryman', [DmEarningController::class,'index'])->middleware("permission:earning.deliveryman")->name('deliveryman');
            Route::post('dm_save_cash_txn', [DmEarningController::class,'savingCashTransaction'])->middleware("permission:earning.dm-save-cash-txn")->name('dm_save_cash_txn');
            Route::post('dm_save_wallet_txn', [DmEarningController::class,'savingWalletTransaction'])->middleware("permission:earning.dm-save-wallet-txn")->name('dm_save_wallet_txn');
            Route::get('/dm-cash-in-hand', [DmEarningController::class,'getDmCashInHand'])->middleware("permission:earning.dm-cash-in-hand")->name('dm-cash-in-hand');
            Route::get('/dm-wallet-balance', [DmEarningController::class,'getDmWalletBalance'])->middleware("permission:earning.dm-wallet-balance")->name('dm-wallet-balance');
            
            // Payout routes
            Route::get('/payouts', [DmEarningController::class,'payouts'])->middleware("permission:earning.payouts")->name('payouts');
            Route::post('/payouts/create', [DmEarningController::class,'createPayout'])->middleware("permission:earning.create-payout")->name('create-payout');
            Route::put('/payouts/{id}/status', [DmEarningController::class,'updatePayoutStatus'])->middleware("permission:earning.update-payout-status")->name('update-payout-status');
            Route::get('/payouts/by-admin/{adminId}', [DmEarningController::class,'payoutsByAdmin'])->middleware("permission:earning.payouts-by-admin")->name('payouts-by-admin');

        });

        /**
         * business setup
         */
        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
            Route::get('business-setup', [BusinessSettingsController::class,'business_index'])->middleware("permission:business-settings.business-setup")->name('business-setup');
            Route::get('config-setup', [BusinessSettingsController::class,'config_setup'])->middleware("permission:business-settings.config-setup")->name('config-setup');
            Route::post('update-setup',[BusinessSettingsController::class,'business_setup'] )->middleware("permission:business-settings.update-setup")->name('update-setup');
            Route::post('email-setup',[BusinessSettingsController::class,'email_setup'] )->middleware("permission:business-settings.email-setup")->name('email-setup');

            /*=============// page setup //===================*/
            Route::get('pages/about-us', [BusinessSettingsController::class,'about_us'])->middleware("permission:business-settings.about-us")->name('about-us');
            Route::post('pages/about-us', [BusinessSettingsController::class,'about_us_update'])->middleware("permission:business-settings.about-us-update");

            Route::get('pages/privacy-policy', [BusinessSettingsController::class,'privacy_policy'])->middleware("permission:business-settings.privacy-policy")->name('privacy-policy');
            Route::post('pages/privacy-policy', [BusinessSettingsController::class,'privacy_policy_update'])->middleware("permission:business-settings.privacy-policy-update");

            Route::get('pages/terms-and-conditions', [BusinessSettingsController::class,'terms_and_conditions'])->middleware("permission:business-settings.terms-and-conditions")->name('terms-and-conditions');
            Route::post('pages/terms-and-conditions', [BusinessSettingsController::class,'terms_and_conditions_update'])->middleware("permission:business-settings.terms-and-conditions-update");

            Route::get('pages/refund-policy', [BusinessSettingsController::class,'refund_policy'])->middleware("permission:business-settings.refund-policy")->name('refund-policy');
            Route::post('pages/refund-policy', [BusinessSettingsController::class,'refund_policy_update'])->middleware("permission:business-settings.refund-policy-update");
            Route::get('pages/refund-policy/{status}', [BusinessSettingsController::class,'refund_policy_status'])->middleware("permission:business-settings.refund-policy-status")->name('refund-policy-status');

            Route::get('pages/shipping-policy', [BusinessSettingsController::class,'shipping_policy'])->middleware("permission:business-settings.shipping-policy")->name('shipping-policy');
            Route::post('pages/shipping-policy', [BusinessSettingsController::class,'shipping_policy_update'])->middleware("permission:business-settings.shipping-policy-update");
            Route::get('pages/shipping-policy/{status}', [BusinessSettingsController::class,'shipping_policy_status'])->middleware("permission:business-settings.shipping-policy-status")->name('shipping-policy-status');

            Route::get('pages/cancellation-policy', [BusinessSettingsController::class,'cancellation_policy'])->middleware("permission:business-settings.cancellation-policy")->name('cancellation-policy');
            Route::post('pages/cancellation-policy', [BusinessSettingsController::class,'cancellation_policy_update'])->middleware("permission:business-settings.cancellation-policy-update");
            Route::get('pages/cancellation-policy/{status}', [BusinessSettingsController::class,'cancellation_policy_status'])->middleware("permission:business-settings.cancellation-policy-status")->name('cancellation-policy-status');
            /*=============// page setup end //===================*/

        });

         Route::group(['prefix' => 'administration', 'as' => 'administration.' ], function () {
            Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->middleware("permission:administration.roles-permissions")->name('roles.permissions');
            Route::post('/assign-role-to-user', [RolePermissionController::class, 'assignRoleToUser'])->middleware("permission:administration.assign-role-to-user")->name('assign.role.to.user');
            Route::post('/assign-permission-to-role', [RolePermissionController::class, 'assignPermissionToRole'])->middleware("permission:administration.assign-permission-to-role")->name('assign.permission.to.role');
        });
        Route::group(['as' => 'roles.','prefix'=> 'role'], function () {
            Route::get('/add', [RolesAndPermission::class , 'index'])->middleware("permission:roles.add")->name('add');
            Route::post('/add', [RolesAndPermission::class , 'submit'])->middleware("permission:roles.submit");
        });

        // Chat System Routes
        Route::group(['prefix' => 'chat', 'as' => 'chat.'], function () {
            Route::get('/', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->middleware("permission:chat.index")->name('index');
            Route::get('/conversation/{id}', [\App\Http\Controllers\Admin\ChatController::class, 'conversation'])->middleware("permission:chat.conversation")->name('conversation');
            Route::get('/conversation/{id}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->middleware("permission:chat.get-messages")->name('get-messages');
            Route::post('/conversation/{id}/send', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->middleware("permission:chat.send-message")->name('send-message');
            Route::post('/conversation/{id}/mark-read', [\App\Http\Controllers\Admin\ChatController::class, 'markAsRead'])->middleware("permission:chat.mark-read")->name('mark-read');
            Route::get('/customers', [\App\Http\Controllers\Admin\ChatController::class, 'searchCustomers'])->middleware("permission:chat.customers")->name('customers');
            Route::post('/start', [\App\Http\Controllers\Admin\ChatController::class, 'startConversation'])->middleware("permission:chat.start")->name('start');
            // Message deletion routes
            Route::delete('/message/{id}', [\App\Http\Controllers\Admin\ChatController::class, 'deleteMessage'])->middleware("permission:chat.delete-message")->name('delete-message');
            Route::delete('/messages', [\App\Http\Controllers\Admin\ChatController::class, 'deleteMessages'])->middleware("permission:chat.delete-messages")->name('delete-messages');
            Route::delete('/conversation/{id}/clear', [\App\Http\Controllers\Admin\ChatController::class, 'clearConversation'])->middleware("permission:chat.clear-conversation")->name('clear-conversation');
        });

        // Refund System Routes
        Route::group(['prefix' => 'refund', 'as' => 'refund.'], function () {
            Route::get('/', [\App\Http\Controllers\Admin\refund\RefundController::class, 'index'])->middleware("permission:refund.index")->name('index');
            Route::get('/show/{id}', [\App\Http\Controllers\Admin\refund\RefundController::class, 'show'])->middleware("permission:refund.show")->name('show');
            Route::post('/process/{id}', [\App\Http\Controllers\Admin\refund\RefundController::class, 'process'])->middleware("permission:refund.process")->name('process');
            Route::post('/create/{orderId}', [\App\Http\Controllers\Admin\refund\RefundController::class, 'createRefund'])->middleware("permission:refund.create")->name('create');
            Route::post('/update-deduction/{id}', [\App\Http\Controllers\Admin\refund\RefundController::class, 'updateDeduction'])->middleware("permission:refund.update-deduction")->name('update-deduction');

            // Refund reasons management
            Route::get('/reasons', [\App\Http\Controllers\Admin\refund\RefundController::class, 'reasons'])->middleware("permission:refund.reasons")->name('reasons');
            Route::post('/reasons', [\App\Http\Controllers\Admin\refund\RefundController::class, 'storeReason'])->middleware("permission:refund.reasons-store")->name('reasons.store');
            Route::put('/reasons/{id}/toggle', [\App\Http\Controllers\Admin\refund\RefundController::class, 'toggleReasonStatus'])->middleware("permission:refund.reasons-toggle")->name('reasons.toggle');
            Route::delete('/reasons/{id}', [\App\Http\Controllers\Admin\refund\RefundController::class, 'deleteReason'])->middleware("permission:refund.reasons-delete")->name('reasons.delete');
        });

        // Referral System Routes
        Route::group(['prefix' => 'referral', 'as' => 'referral.'], function () {
            Route::get('/', [ReferralController::class, 'index'])->middleware("permission:referral.index")->name('index');
            Route::post('/store', [ReferralController::class, 'store'])->middleware("permission:referral.store")->name('store');
            Route::get('/configurations', [ReferralController::class, 'getConfigurations'])->middleware("permission:referral.configurations")->name('configurations');
            Route::post('/toggle/{id}', [ReferralController::class, 'toggleStatus'])->middleware("permission:referral.toggle")->name('toggle');
            Route::delete('/delete/{id}', [ReferralController::class, 'destroy'])->middleware("permission:referral.delete")->name('delete');
            Route::get('/statistics', [ReferralController::class, 'statistics'])->middleware("permission:referral.statistics")->name('statistics');
            Route::get('/usage-statistics', [ReferralController::class, 'usageStatistics'])->middleware("permission:referral.usage-statistics")->name('usage-statistics');
            Route::get('/usage-details', [ReferralController::class, 'usageDetails'])->middleware("permission:referral.usage-details")->name('usage-details');
        });

        // Contact Us Management Routes
        Route::group(['prefix' => 'contact-us', 'as' => 'contact-us.'], function () {
            Route::get('/', [\App\Http\Controllers\Admin\ContactUsController::class, 'index'])->middleware("permission:contact-us.index")->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admin\ContactUsController::class, 'show'])->middleware("permission:contact-us.show")->name('show');
            Route::post('/{id}/reply', [\App\Http\Controllers\Admin\ContactUsController::class, 'reply'])->middleware("permission:contact-us.reply")->name('reply');
            Route::post('/{id}/update-status', [\App\Http\Controllers\Admin\ContactUsController::class, 'updateStatus'])->middleware("permission:contact-us.update-status")->name('update-status');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\ContactUsController::class, 'delete'])->middleware("permission:contact-us.delete")->name('delete');
            Route::post('/bulk-action', [\App\Http\Controllers\Admin\ContactUsController::class, 'bulkAction'])->middleware("permission:contact-us.bulk-action")->name('bulk-action');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\ContactUsController::class, 'export'])->middleware("permission:contact-us.export")->name('export');
        });

        // System Management Routes
        Route::group(['prefix' => 'system', 'as' => 'system.'], function () {
            Route::get('/backup', [SystemController::class, 'backupIndex'])->middleware("permission:system.backup")->name('backup');
            Route::get('/backup/download', [SystemController::class, 'backupDatabase'])->middleware("permission:system.backup-download")->name('backup-download');
            Route::get('/backup/download-file', [SystemController::class, 'downloadBackup'])->middleware("permission:system.backup-download-file")->name('backup-download-file');
            Route::delete('/backup/delete', [SystemController::class, 'deleteBackup'])->middleware("permission:system.backup-delete")->name('backup-delete');
        });

    });




?>
