<?php

use App\Http\Controllers\Acl\PermissionController;
use App\Http\Controllers\Acl\RoleController;
use Illuminate\Support\Facades\Route;


Route::prefix('acl')->middleware('auth:api')->group(function ()
{

    Route::resource(
        'role',
        RoleController::class,
        [
            'only' => [ 'index', 'store', 'update', 'destroy' ]
        ]
    );

    Route::prefix('permission')->group(function ()
    {
        Route::patch('role/{role}', [ PermissionController::class, 'attachPermissionToRole' ]);

    });

});
