<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use App\Models\User;
use App\Models\UserIp;
use App\Services\Auth\AuthService;

class Authenticate extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        if(auth()->check())
        {
            $this->validateTokenForThisRequest($request, $next, $guards);
            $this->storeUserIP($request->user(), $request->ip());
            $this->checkUserWasActivate($request->user());
        }

        return $next($request);
    }


    /* Validate Token For This Request */

    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }


    protected function isValidTokenForThisRequest(Request $request, Token $token)
    {
        return true;
        return
            ($request->header('User-Agent') === $token->user_agent);
        //  and ($request->ip() === $token->ip_address)
    }

    protected function validateTokenForThisRequest($request, Closure $next, ...$guards)
    {
        if($request->expectsJson())
        {
            $token = app(AuthService::class)->getTokenModelByAccessToken(
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

    protected function storeUserIP(User $user, string $ip): UserIp
    {
        $ip = UserIp::updateOrCreate([
            'user_id' => $user->id,
            'ip'      => $ip,
        ]);
        return $ip;
    }

    /* Check User Activ status */

    protected function checkUserWasActivate(User $user)
    {
        if(
            $user->isNew() and
            $user->phone != env('SUPER_ADMIN_PHONE_NUMBER')
        ) abort(403);
    }
}
