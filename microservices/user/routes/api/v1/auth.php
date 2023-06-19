<?php

use App\Http\Controllers\Auth\InfoController;
use App\Http\Controllers\Auth\Login\PhoneNumber\LoginController as PhoneNumberLoginController;
use App\Http\Controllers\Auth\RenewAccessTokenController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\CompleteProfileController;
use Illuminate\Support\Facades\Route;


Route::prefix('auth')->group(function ()
{
    Route::prefix('login')->group(function ()
    {
        Route::prefix('phonenumber')->group(function ()
        {
            route::post('request', [ PhoneNumberLoginController::class, 'request' ]);
            route::post('verify', [ PhoneNumberLoginController::class, 'verify' ]);
        });
    });

    route::post('refreshAccessToken', [ RenewAccessTokenController::class, 'refreshAccessToken' ]);
    Route::middleware([ 'auth:api' ])->group(function ()
    {
        route::get('me', [ InfoController::class, 'me' ]);
        route::patch('complete-profile', [ CompleteProfileController::class, 'complete' ]);
    });


    Route::middleware([ 'auth:api' ])->prefix('sessions')->group(function ()
    {
        route::get('', [ SessionController::class, 'index' ]);
        route::delete('delete/all', [ SessionController::class, 'deleteAll' ]);
        route::delete('delete/{token}', [ SessionController::class, 'delete' ]);
    });

});




