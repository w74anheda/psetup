<?php

namespace App\Services\Passport;

use Laravel\Passport\Bridge\RefreshTokenRepository;

trait RevokeAble
{
    public function revoke()
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $this->tokens->each(
            fn($token) => $token->revoke() &&
            $token->delete() &&
            $refreshTokenRepository->revokeRefreshToken($token->id)
        );
    }
}
