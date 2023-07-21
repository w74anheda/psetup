<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginPhoneNumberRequest;
use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Models\User;
use DateTime;
use App\Events\Auth\Login\PhoneNumber\Request as PhoneNumberRequestEvent;
use DB;
use Exception;

class UserService
{
    protected static $authService;

    protected static function authService()
    {
        if(!self::$authService)
            self::$authService = app(AuthService::class);
        return self::$authService;
    }


    public static function completePhoneVerification(
        User $user,
        string $firstname,
        string $lastname,
        string $gender
    )
    {
        $user->first_name   = $firstname;
        $user->last_name    = $lastname;
        $user->gender       = $gender;
        $user->is_new       = false;
        $user->is_active    = true;
        $user->activated_at = now();
        $user->save();
    }

    public static function setLastOnlineAt(User $user, DateTime $dateTime = null)
    {
        $dateTime             = $dateTime ?? now();
        $user->last_online_at = $dateTime;
        $user->save();
    }

    public static function firstOrCreateUser(string $phone, string $ip): User
    {
        DB::beginTransaction();
        try
        {
            $user = User::firstOrCreate(
                [ 'phone' => $phone ],
                [
                    'registered_ip' => $ip,
                    'is_new'        => true
                ]
            );
            DB::commit();
        }
        catch (Exception $err)
        {
            DB::rollBack();
            return self::firstOrCreateUser($phone, $ip);
        }

        return $user;
    }

    public static function loginRequest(LoginPhoneNumberRequest $request)
    {
        $user         = self::firstOrCreateUser($request->phone, $request->ip());
        $verification = self::authService()::generateVerificationCode($user);
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
            $tokens = self::authService()::getAccessAndRefreshTokenByPhone(
                $user,
                $request->hash,
                $request->code,
                $request
            );
            if($user->isNew())
            {
                self::completePhoneVerification(
                    $user,
                    $request->first_name,
                    $request->last_name,
                    $request->gender,
                );
            }
            self::authService()::clearVerificationCode($user, $request->hash);

            DB::commit();
        }
        catch (Exception $err)
        {
            dd($err->getMessage(), 2);
            DB::rollBack();
            $isOK = false;
        }

        return [ $isOK, $tokens ?? null ];
    }

}
