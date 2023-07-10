<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LastOnlineAt
{
    public function __construct(public UserService $userService)
    {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if($user = $request->user())
        {
            $this->userService->setUser($user);
            $this->userService->setLastOnlineAt();
        }
        return $next($request);
    }
}
