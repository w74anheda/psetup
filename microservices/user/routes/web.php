<?php

use App\Http\Controllers\Auth\Passport\StoreUserAgentIntoAccessTokenController;
use App\Models\User;
use App\Notifications\RequestPhoneVerificationCode;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function ()
{
    // dd('users microservices');
    return view('welcome');
});
