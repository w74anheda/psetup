<?php

namespace App\Services\Auth;

use App\Models\PassportCustomToken;
use App\Models\User;
use App\Models\UserPhoneVerification;
use App\Services\Http\Facade\CustomHttp;
use Exception;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;

class AuthService
{
    public static function tokenDestroy(PassportCustomToken $token): bool
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
        $passportClient = app('PassportAuthPhoneClient');
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
            array_merge(
                [
                    'User-Agent' => request()->header('User-Agent'),
                    'ip-address' => request()->ip(),
                ],
                $headers
            )
        );
        return $response->json();
    }

    public static function clearVerificationCode(User $user, string $hash): bool
    {
        return $user->phoneVerifications()->where('hash', $hash)->delete();
    }

    public static function refreshAccessToken(string $refresh_token, array $headers = [])
    {
        $passportClient = app('PassportAuthPhoneClient');

        $response = CustomHttp::postJson(
            env('APP_URL') . "/oauth/token",
            [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id'     => $passportClient->id,
                'client_secret' => $passportClient->secret,
                'scope'         => '',
            ],
            array_merge(
                [
                    'User-Agent' => request()->header('User-Agent'),
                    'ip-address' => request()->ip(),
                ],
                $headers
            )
        );
        return $response->json();
    }

    public static function sessions(User $user, string $access_token = null, array $fields = [])
    {
        $fields = array_merge(
            [ 'id', 'user_agent', 'ip_address', 'created_at', 'expires_at' ],
            $fields
        );

        if($currentSession = self::getTokenModelByAccessToken($access_token))
        {
            $currentSession               = $currentSession->only($fields);
            $currentSession['current']    = true;
            $currentSession['created_at'] = $currentSession['created_at']->toString();
            $sessions                     = $user
                ->tokens()
                ->active()
                ->AllExcept($currentSession['id'])
                ->select($fields)
                ->get()
                ->map(fn($session) => [ ...$session->toArray(), 'current' => false ])
                ->prepend($currentSession);
        } else
        {
            $sessions = $user
                ->tokens()
                ->active()
                ->select($fields)
                ->get()
                ->map(fn($session) => [ ...$session->toArray(), 'current' => false ]);
        }
        return $sessions->toArray();
    }

    public static function getTokenModelByAccessToken(string $accessToken)
    {
        try
        {
            $tokenID = (new Parser(new JoseEncoder()))->parse($accessToken)->claims()->all()['jti'];
            $token   = PassportCustomToken::where('id', $tokenID)->first();
            return $token;
        }
        catch (Exception $err)
        {
            return null;
        }
    }

}
