<?php

use App\Events\JoinUser;
use App\Events\SendMessage;
use App\Http\Controllers\Auth\Passport\StoreUserAgentIntoAccessTokenController;
use App\Models\User;
use App\Notifications\RequestPhoneVerificationCode;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Cache;
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

    $user = User::factory()->create();
    Auth::login($user);

    return view('welcome');
});


Route::get('/chatroom', function ()
{

    $user = User::factory()->create();
    Auth::login($user);
    // event(new JoinUser($user));

    return view('chatroom');
});
