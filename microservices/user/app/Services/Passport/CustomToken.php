<?php

namespace App\Services\Passport;

use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Token;

class CustomToken extends Token
{
    public function scopeActive($query)
    {
        return $query->where('revoked', 0);
    }

    public function scopeAllExcept($query, string $tokenID)
    {
        return $query
            ->active()
            ->where('id', '!=', $tokenID);
    }

    public function revokeAndDelete()
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($this->id);
        $this->revoke();
        $this->delete();
    }

}
