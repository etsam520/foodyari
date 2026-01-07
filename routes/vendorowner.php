<?php

use App\Http\Controllers\vendorOwner\auth\LoginController;
use App\Http\Controllers\vendorOwner\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(['as' => 'vendorOwner.'], function () {
    Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::post('login', [LoginController::class, 'submit']);
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');
        // Route::get('/profile', [LoginController::class, 'profile'])->name('profile');
        // Route::post('/profile', [LoginController::class, 'profileUpdate']);
    });

    Route::get('/dashboard', [DashboardController::class , 'dashboard'])->name('dashboard');
    Route::get('/my-mess/{id}', [DashboardController::class , 'myMess'])->name('myMess');


});