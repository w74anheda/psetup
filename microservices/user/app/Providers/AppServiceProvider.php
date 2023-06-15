<?php

namespace App\Providers;

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

        $this->app->bind(
            'PHONE_VERIFICATION_CODE_LENGTH',
            fn() => (int) env('PHONE_VERIFICATION_CODE_LENGTH', 6)
        );

        $this->app->bind(
            'PHONE_VERIFICATION_CODE_LIFETIME_SECONDS',
            fn() => (int) env('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS', 120)
        );
    }
}
