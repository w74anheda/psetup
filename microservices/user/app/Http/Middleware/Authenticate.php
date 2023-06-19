<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use App\Models\User;
use App\Models\UserIp;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if(auth()->check())
        {
            $this->validateTokenForThisRequest($request, $next, $guards);
            $this->storeUserIP($request->user(), $request->ip());
        }

        return $next($request);
    }


    /* Validate Token For This Request */

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }


    private function getTokenByAccessToken(User $user, string $accessToken)
    {
        $tokenID = (new Parser(new JoseEncoder()))->parse($accessToken)->claims()->all()['jti'];
        $token   = $user->tokens()->where('id', $tokenID)->first();
        return $token;
    }

    private function isValidTokenForThisRequest(Request $request, Token $token)
    {
        return
            ($request->header('User-Agent') === $token->user_agent) and
            ($request->ip() === $token->ip_address);
    }

    public function validateTokenForThisRequest($request, Closure $next, ...$guards)
    {
        if($request->expectsJson())
        {
            $token = $this->getTokenByAccessToken(
                $request->user(),
                $request->bearerToken()
            );

            if(!$this->isValidTokenForThisRequest($request, $token))
            {
                $this->unauthenticated($request, $guards);
            }

            $request->token = $token;
        }
    }

    /* Store User IP */

    public function storeUserIP(User $user, string $ip): UserIp
    {
        $ip = $user->ips()->create([
            'ip' => $ip
        ]);
        return $ip;
    }
}
