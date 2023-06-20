<?php

use App\Http\Controllers\Acl\RoleController;
use Illuminate\Support\Facades\Route;


Route::prefix('acl')->group(function ()
{

    Route::resource(
        'role',
        RoleController::class,
        [
            'only' => [ 'index', 'store', 'update', 'destroy' ]
        ]
    )->middleware('auth:api');


});




