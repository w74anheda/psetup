<?php

namespace App\Services;

use App\Http\Requests\Auth\RefreshAccessTokenRequest;
use App\Models\User;
use App\Models\UserPhoneVerification;
use App\Services\Passport\CustomToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Client as PassportClient;

class AuthService
{
    public static function tokenDestroy(CustomToken $token): string
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshToken($token->id);
        $token->revoke();
        // $token->delete();
        return $token->id;
    }

    public static function tokensDestroy(User $user)
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $user->tokens->each(
            fn($token) => $token->revoke() &&
            // $token->delete() &&
            $refreshTokenRepository->revokeRefreshToken($token->id)
        );
    }

    public static function generateVerificationCode(User $user, string $code = null): UserPhoneVerification
    {
        $code = $code ??
            generate_random_digits_with_specefic_length(
                app('PHONE_VERIFICATION_CODE_LENGTH')
            );

        return $user->phoneVerifications()->create([
            'code'      => $code,
            'expire_at' => now()->addSeconds(
                app('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS')
            ),
            'hash'      => Str::uuid()
        ]);
    }
    public static function getAccessAndRefreshTokenByPhone(User $user, string $hash, string $code, Request $request = null)
    {
        $request        = $request ?? request();
        $passportClient = PassportClient::where('password_client', 1)->first();
        $response       = Http::withHeaders(
            [
                'User-Agent' => $request->header('User-Agent'),
                'ip-address' => $request->ip(),
            ]
        )
            ->post(env('APP_URL') . "/oauth/token", [
                'grant_type'    => 'phone',
                'client_id'     => $passportClient->id,
                'client_secret' => $passportClient->secret,
                'phone'         => $user->phone,
                'hash'          => $hash,
                'code'          => $code,
            ]);
        return $response->json();
    }

    public static function clearVerificationCode(User $user, string $hash)
    {
        return $user->phoneVerifications()->where('hash', $hash)->delete();
    }
    public static function refreshAccessToken(RefreshAccessTokenRequest $request)
    {
        $passportClient = PassportClient::where('password_client', 1)->first();

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
        return $response;
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
