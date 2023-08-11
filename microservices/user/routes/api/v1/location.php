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


    Route::resource(
        'state',
        StateController::class,
        [
            'only' => [ 'index', 'store', 'update', 'destroy' ]
        ]
    );
    Route::delete('state/all/delete', [ StateController::class, 'destroyAll' ])->name('state.destroyAll');


    Route::resource(
        'city',
        CityController::class,
        [
            'only' => [ 'index', 'store', 'update', 'destroy' ]
        ]
    );
    Route::delete('city/all/delete', [ CityController::class, 'destroyAll' ])->name('city.destroyAll');


});
