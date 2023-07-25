<?php

namespace App\Services;

use App\Http\Requests\Auth\RefreshAccessTokenRequest;
use App\Models\User;
use App\Models\UserPhoneVerification;
use App\Services\Http\Facade\CustomHttp;
use App\Services\Passport\CustomToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Client as PassportClient;

class AuthService
{
    public static function tokenDestroy(CustomToken $token): bool
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshToken($token->id);
        return $token->revoke();
    }

    public static function allTokensDestroy(User $user)
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $user->tokens->each(
            fn($token) => $token->revoke() &&
            $refreshTokenRepository->revokeRefreshToken($token->id)
        );
    }

    public static function generateVerificationCode(User $user, string $code = null): UserPhoneVerification
    {
        $code = $code ??
            generate_random_digits_with_specefic_length(
                env('PHONE_VERIFICATION_CODE_LENGTH')
            );
        return $user->phoneVerifications()->create([
            'code'      => $code,
            'expire_at' => now()->addSeconds(
                env('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS')
            ),
            'hash'      => Str::uuid()
        ]);
    }

    public static function getAccessAndRefreshTokenByPhone(User $user, string $hash, string $code, array $headers = [])
    {

        $passportClient = PassportClient::first();
        $response       = CustomHttp::postJson(
            env('APP_URL') . "/oauth/token",
            [
                'grant_type'    => 'phone',
                'client_id'     => $passportClient->id,
                'client_secret' => $passportClient->secret,
                'phone'         => $user->phone,
                'hash'          => $hash,
                'code'          => $code,
                'scope'         => '*',
            ],
            $headers
        );
        return $response->json();
    }

    public static function clearVerificationCode(User $user, string $hash): bool
    {
        return $user->phoneVerifications()->where('hash', $hash)->delete();
    }

    public static function refreshAccessToken(RefreshAccessTokenRequest $request)
    {
        $passportClient = PassportClient::first();

        $response = Http::withHeaders(
            [
                'User-Agent' => $request->header('User-Agent'),
                'ip-address' => $request->ip(),
            ]
        )->post(env('APP_URL') . "/oauth/token", [
                    'grant_type'    => 'refresh_token',
                    'refresh_token' => $request->refresh_token,
                    'client_id'     => $passportClient->id,
                    'client_secret' => $passportClient->secret,
                    'scope'         => '',
                ]);
        return $response->json();
    }

    public static function getAllSessionsWithCurrent(User $user, CustomToken $token, array $fields)
    {

        $currentSession            = $token->only($fields);
        $currentSession['current'] = true;
        $sessions                  = $user
            ->tokens()
            ->active()
            ->AllExcept($currentSession['id'])
            ->select($fields)
            ->get()
            ->map(fn($session) => [ ...$session->toArray(), 'current' => false ])
            ->prepend($currentSession);
        return $sessions;
    }

}
