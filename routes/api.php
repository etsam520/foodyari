<?php

use App\CentralLogics\Helpers;
use App\Models\Customer;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/get-directions', function (Request $request) {
   return Helpers::getDirections($request);
})->name('get-directions');

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'passkey'], function () {
    Route::get('user', [App\Http\Controllers\API\UserController::class, 'userInfo']);
    Route::post('fetch-saved-location', [App\Http\Controllers\API\UserController::class, 'getSavedLocations']);
    Route::post('save-current-address', [App\Http\Controllers\API\UserController::class, 'storeLocation']);
    Route::get("live-orders", [App\Http\Controllers\API\UserController::class, 'liveOrders']);

    
});

// MQTT Test Routes
Route::prefix('mqtt-test')->group(function () {
    Route::get('connection', [App\Http\Controllers\Api\V1\MqttTestController::class, 'testConnection']);
    Route::post('order', [App\Http\Controllers\Api\V1\MqttTestController::class, 'testOrder']);
    Route::post('custom-order', [App\Http\Controllers\Api\V1\MqttTestController::class, 'testCustomOrder']);
});

