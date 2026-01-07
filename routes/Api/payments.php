<?php

use App\Http\Controllers\Payment\PhonePayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/**
 * phonePay
 */
Route::group(['as'=>'payments.'],function(){
    Route::get('/pay',[PhonePayController::class, 'pay'])->name('pay');
    Route::post('/response',[PhonePayController::class, 'response'])->name('response');

    Route::get('/customer/fcmToken', function (Request $request) {
        // Session::put('name', "my name is Md Ehtesham");
        return Session::all('userInfo');

    })->name('customer-token');
});


?>