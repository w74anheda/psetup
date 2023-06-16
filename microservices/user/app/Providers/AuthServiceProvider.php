<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Services\Passport\CustomAccessTokenRepository;
use App\Services\Passport\CustomToken;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::useTokenModel(CustomToken::class);

        $this->app->bind(AccessTokenRepository::class, CustomAccessTokenRepository::class);

    }
}
