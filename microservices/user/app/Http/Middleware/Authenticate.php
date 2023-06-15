<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as Auth;


class Authenticate extends Middleware
{
    public function __construct(Auth $auth,private IsValidTokenForThisRequest $isValidTokenForThisRequest)
    {
        parent::__construct($auth);
    }
    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);

        return $this->isValidTokenForThisRequest->handle($request, $next, $guards);
    }
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }


}
