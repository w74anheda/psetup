<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginPhoneNumberRequest;
use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Http\Requests\Auth\RefreshAccessTokenRequest;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Client as PassportClient;
use App\Events\Auth\Login\PhoneNumber\Request as PhoneNumberRequestEvent;
use DB;
use Exception;

class UserService
{
    private User $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public static function generateVerificationCode(User $user, string $code = null)
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

    public static function activateHandler(User $user, string $firstname, string $lastname, string $gender)
    {
        if($user->isNew())
        {
            $user->first_name   = $firstname;
            $user->last_name    = $lastname;
            $user->gender       = $gender;
            $user->is_new       = false;
            $user->activated_at = now();
            $user->save();
        }

    }

    public function revoke()
    {
        $refreshTokenRepository = resolve(RefreshTokenRepository::class);
        $this->user->tokens->each(
            fn($token) => $token->revoke() &&
            $token->delete() &&
            $refreshTokenRepository->revokeRefreshToken($token->id)
        );
    }

    public function setLastOnlineAt(DateTime $dateTime = null)
    {
        $dateTime                   = $dateTime ?? now();
        $this->user->last_online_at = $dateTime;
        $this->user->save();
    }

    public static function firstOrCreateUser(string $phone, string $ip)
    {
        return User::firstOrCreate(
            [ 'phone' => $phone ],
            [
                'registered_ip' => $ip,
                'is_new'        => true
            ]
        );
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

    public static function loginRequest(LoginPhoneNumberRequest $request)
    {
        $user         = self::firstOrCreateUser($request->phone, $request->ip());
        $verification = self::generateVerificationCode($user);
        PhoneNumberRequestEvent::dispatch($user);
        return [ $user, $verification ];
    }

    public static function loginVerify(LoginPhoneNumberVerify $request)
    {
        $user = $request->user;
        $isOK = true;
        try
        {
            DB::beginTransaction();
            $tokens = self::getAccessAndRefreshTokenByPhone($user, $request->hash, $request->code, $request);
            self::activateHandler(
                $user,
                $request->first_name,
                $request->last_name,
                $request->gender,
            );
            self::clearVerificationCode($user, $request->hash);

            DB::commit();
        }
        catch (Exception $err)
        {
            DB::rollBack();
            $isOK = false;
        }

        return [ $isOK, $tokens ?? null ];
    }

}
