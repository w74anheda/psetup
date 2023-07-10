<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Client as PassportClient;

class UserService
{
    private User $user;

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function generateVerificationCode(string $code = null)
    {
        $code = $code ??
            generate_random_digits_with_specefic_length(
                app('PHONE_VERIFICATION_CODE_LENGTH')
            );

        return $this->user->phoneVerifications()->create([
            'code'      => $code,
            'expire_at' => now()->addSeconds(
                app('PHONE_VERIFICATION_CODE_LIFETIME_SECONDS')
            ),
            'hash'      => Str::uuid()
        ]);
    }

    public function getAccessAndRefreshTokenByPhone(string $hash, string $code, Request $request = null)
    {
        $request        = $request ?? request();
        $passportClient = PassportClient::first();
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
                'phone'         => $this->user->phone,
                'hash'          => $hash,
                'code'          => $code,
            ]);
        return $response->json();
    }
    public function old_getAccessAndRefreshToken(string $code, Request $request = null)
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
                'grant_type'    => 'password',
                'client_id'     => $passportClient->id,
                'client_secret' => $passportClient->secret,
                'username'      => $this->user->phone,
                'password'      => $code,
                'scope'         => '*'
            ]);
        return $response->json();
    }

    public function clearVerificationCode(string $hash)
    {
        return $this->user->phoneVerifications()->where('hash', $hash)->delete();
    }

    public function activateHandler(LoginPhoneNumberVerify $request)
    {
        if($this->user->isNew())
        {
            $this->user->first_name   = $request->first_name;
            $this->user->last_name    = $request->last_name;
            $this->user->gender       = $request->gender;
            $this->user->is_new       = false;
            $this->user->activated_at = now();
            $this->user->save();
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

    public function firstOrCreateUser(string $phone, string $ip)
    {
        return User::firstOrCreate(
            [ 'phone' => $phone ],
            [
                'registered_ip' => $ip,
                'is_new'        => true
            ]
        );
    }

}
