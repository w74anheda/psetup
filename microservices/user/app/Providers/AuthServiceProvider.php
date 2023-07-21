<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Services\Passport\CustomAccessTokenRepository;
use App\Services\Passport\CustomToken;
use App\Services\Passport\Grants\PhoneGrant;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\File;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\RefreshTokenRepository;

use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;

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

        $privateKey = File::exists(storage_path('oauth-private.key'));
        $publicKey  = File::exists(storage_path('oauth-public.key'));
        if($publicKey && $privateKey)
        {
            Passport::tokensExpireIn(now()->addDays(15));
            Passport::refreshTokensExpireIn(now()->addDays(30));
            Passport::useTokenModel(CustomToken::class);


            $this->app->bind(
                AccessTokenRepository::class,
                CustomAccessTokenRepository::class
            );

            app(AuthorizationServer::class)->enableGrantType(
                $this->makePhoneGrant(), Passport::tokensExpireIn()
            );
        }

    }

    protected function makePhoneGrant()
    {
        $grant = new PhoneGrant(
            $this->app->make(RefreshTokenRepository::class)
        );
        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());
        return $grant;
    }
}
