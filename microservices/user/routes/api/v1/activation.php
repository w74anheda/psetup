<?php

use App\Http\Controllers\Activation\UserActivationController;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->middleware('auth:api')->group(function ()
{

    Route::patch('activation/{user}',[UserActivationController::class,'activation']);

});
