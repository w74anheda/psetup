<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Laravel\Passport\Token;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use App\Models\User;

class IsValidTokenForThisRequest extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
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

        return $next($request);
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
}
