<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\Token;

class PassportCustomToken extends Token
{
    use HasFactory;

    public function scopeRevoked($query)
    {
        return $query->where('revoked', 0);
    }

    public function scopeNotRevoked($query)
    {
        return $query->where('revoked', 1);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>=', now());
    }

    public function scopeAllExcept($query, string $tokenID)
    {
        return $query
            ->where('id', '!=', $tokenID);
    }

}
