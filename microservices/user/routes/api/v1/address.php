<?php

use App\Http\Controllers\Address\AddressController;
use Illuminate\Support\Facades\Route;

Route::resource(
    'address',
    AddressController::class,
    [
        'only' => [ 'index', 'store', 'update', 'destroy' ]
    ]
)->middleware('auth:api');
