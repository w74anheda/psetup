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

    Route::prefix('permission')
        ->controller(PermissionController::class)
        ->group(function ()
        {
            Route::get('', 'index');

            Route::prefix('role')->group(function ()
            {
                Route::patch('{role}', 'attachPermissionToRole');
                Route::patch('{role}/dettach', 'dettachPermissionOfRole');
                Route::delete('{role}', 'dropAllPermissionOfRole');
            });

            Route::prefix('user')->group(function ()
            {
                Route::patch('{user}', 'attachPermissionToUser');
                Route::patch('{user}/dettach', 'dettachPermissionOfUser');
                Route::delete('{user}', 'dropAllPermissionOfUser');
            });

        });

});
