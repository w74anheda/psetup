<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\CustomToken;
use App\Models\User;
use Gate;
use Illuminate\Support\ServiceProvider;

class GateServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }


    public function boot(): void
    {
        Gate::define('its-own-address', fn(User $user, Address $address) => $address->user->id == $user->id);
        Gate::define('its-own-token', fn(User $user, CustomToken $token) => $token->user->id == $user->id);
    }
}
