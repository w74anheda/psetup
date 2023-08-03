<?php

use App\Http\Controllers\Auth\InfoController;
use App\Http\Controllers\Auth\RenewAccessTokenController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\CompleteProfileController;
use App\Http\Controllers\Auth\Login\PhoneNumber\RequestLogin;
use App\Http\Controllers\Auth\Login\PhoneNumber\VerifyLogin;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->name('auth.')->group(function ()
{
    Route::prefix('login')->name('login.')->group(function ()
    {
        Route::prefix('phonenumber')->name('phonenumber.')->group(function ()
        {
            route::post('request', RequestLogin::class)->name('request');
            route::post('verify', VerifyLogin::class)->name('verify');
        });

    });

    route::post(
        'refreshAccessToken',
        [ RenewAccessTokenController::class, 'refreshAccessToken' ]
    )->name('refreshAccessToken');

    Route::middleware([ 'auth:api' ])->name('profile.')->group(function ()
    {
        route::get('me', [ InfoController::class, 'me' ])->name('me');
        route::patch('complete-profile', [ CompleteProfileController::class, 'complete' ])->name('complete');
    });


    Route::middleware([ 'auth:api' ])->prefix('sessions')->name('sessions.')->group(function ()
    {
        route::get('', [ SessionController::class, 'index' ])->name('index');
        route::delete('delete/{token}', [ SessionController::class, 'delete' ])->name('delete');
        route::delete('delete/all', [ SessionController::class, 'deleteAll' ])->name('delete.all');
    });

});
