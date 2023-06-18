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
        route::get('', [ SessionController::class, 'all' ]);
        route::delete('delete/all', [ SessionController::class, 'deleteAll' ]);
        route::delete('delete/{token}', [ SessionController::class, 'delete' ]);
    });

});


// addresses with ostan-shahr-pelak-vahed-codeposti-girande sefaresh(nam-mobile)-/locations
// create api for ACL + gate and middleware(is admin)
// create first admin factory
// activate/deactivate user + middleware

// create state and city (is admin)


// ********** send (user ip + user agent) from user interface to user microservice
    // user microservice
    // block ips


// app
    // add user role permission to user microserivce interface
    // add middleware and gate for ACL


    // set email and activate
    // log every where
