<?php

namespace App\Providers;

use App\Services\Http\CustomHttp;
use App\State\Contracts\BaseState;
use App\State\User\NewUserState;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        $this->app->bind('CustomHttp', function ()
        {
            return new CustomHttp();
        });

    }
}
