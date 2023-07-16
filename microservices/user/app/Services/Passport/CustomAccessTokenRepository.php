<?php
namespace App\Services\Passport;


use DateTime;
use Illuminate\Contracts\Events\Dispatcher;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\TokenRepository;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

class CustomAccessTokenRepository extends AccessTokenRepository
{
    public function __construct(TokenRepository $tokenRepository, Dispatcher $events)
    {
        parent::__construct($tokenRepository, $events);

    }

    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {

        $this->tokenRepository->create([
            'id'         => $accessTokenEntity->getIdentifier(),
            'user_id'    => $accessTokenEntity->getUserIdentifier(),
            'client_id'  => $accessTokenEntity->getClient()->getIdentifier(),
            'scopes'     => $this->scopesToArray($accessTokenEntity->getScopes()),
            'revoked'    => false,
            'created_at' => new DateTime(),
            'updated_at' => new DateTime,
            'expires_at' => $accessTokenEntity->getExpiryDateTime(),
            'user_agent' => request()->header('User-Agent'),
            'ip_address' => request()->header('ip-address')
        ]);

        $this->events->dispatch(
            new AccessTokenCreated(
                $accessTokenEntity->getIdentifier(),
                $accessTokenEntity->getUserIdentifier(),
                $accessTokenEntity->getClient()->getIdentifier()
            )
        );
    }
}
