<?php

use App\Http\Controllers\Location\AddressController;
use App\Http\Controllers\Location\CityController;
use App\Http\Controllers\Location\StateController;
use Illuminate\Support\Facades\Route;


Route::prefix('')->middleware('auth:api')->group(function ()
{

    Route::resource(
        'address',
        AddressController::class,
        [
            'only' => [ 'index', 'store', 'update', 'destroy' ]
        ]
    );


    Route::prefix('state')->group(function ()
    {
        Route::resource(
            '',
            StateController::class,
            [
                'only' => [ 'index', 'store', 'update', 'destroy' ]
            ]
        );
        Route::delete('all/delete', [ StateController::class, 'destroyAll' ]);
    });


    Route::prefix('city')->group(function ()
    {
        Route::resource(
            '',
            CityController::class,
            [
                'only' => [ 'index', 'store', 'update', 'destroy' ]
            ]
        );
        Route::delete('all/delete', [ CityController::class, 'destroyAll' ]);
    });




});
