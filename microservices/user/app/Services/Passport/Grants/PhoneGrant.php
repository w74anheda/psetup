<?php
namespace App\Services\Passport\Grants;


use Laravel\Passport\Bridge\User;
use App\Models\UserPhoneVerification;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\RequestEvent;

class PhoneGrant extends AbstractGrant
{

    public function __construct(
        RefreshTokenRepositoryInterface $refreshTokenRepository
    )
    {
        $this->setRefreshTokenRepository($refreshTokenRepository);

        $this->refreshTokenTTL = new \DateInterval('P1M');
    }

    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    )
    {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request));
        $user   = $this->validateUser($request, $client);


        // Finalize the requested scopes
        $scopes = $this->scopeRepository->finalizeScopes(
            $scopes, $this->getIdentifier(),
            $client,
            $user->getIdentifier()
        );

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken(
            $accessTokenTTL,
            $client,
            $user->getIdentifier(),
            $scopes
        );

        $refreshToken = $this->issueRefreshToken($accessToken);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $code  = $this->getRequestParameter('code', $request);
        $hash  = $this->getRequestParameter('hash', $request);
        $phone = $this->getRequestParameter('phone', $request);

        if(is_null($code))
        {
            throw OAuthServerException::invalidRequest($code);
        }

        if(is_null($hash))
        {
            throw OAuthServerException::invalidRequest($hash);
        }

        if(is_null($phone))
        {
            throw OAuthServerException::invalidRequest($phone);
        }


        $verificationCode = UserPhoneVerification::where([ 'hash' => $hash, 'code' => $code ])->first();

        if(!$verificationCode instanceof UserPhoneVerification)
            throw OAuthServerException::invalidCredentials();

        $user = $verificationCode->user;
        if(is_null($verificationCode) || $user->phone != $phone)
        {
            throw OAuthServerException::invalidCredentials();
        }

        return new User($user->getAuthIdentifier());
    }



    public function getIdentifier()
    {
        return 'phone';
    }
}
