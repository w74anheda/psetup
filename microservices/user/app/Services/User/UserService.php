<?php

namespace App\Services\User;

use App\DTO\UserCompleteRegisterDTO;
use App\Models\User;
use DateTime;
use App\Events\Auth\Login\PhoneNumber\Request as PhoneNumberRequestEvent;
use App\Services\Auth\AuthService;
use DB;
use Exception;
use InvalidArgumentException;

class UserService
{

    public static function completeRegister(
        User $user,
        UserCompleteRegisterDTO $dto,
        DateTime $activated_at = null,
    ): User
    {

        if($user->isNew())
        {
            $dto->validate([ 'first_name', 'last_name', 'gender' ]);
            $activated_at       = $activated_at ?? now();
            $user->first_name   = $dto->first_name;
            $user->last_name    = $dto->last_name;
            $user->gender       = $dto->gender;
            $user->is_new       = false;
            $user->is_active    = true;
            $user->activated_at = $activated_at;
            $user->save();
        }
        return $user;
    }

    public static function setLastOnlineAt(User $user, DateTime $dateTime = null): User
    {
        $dateTime             = $dateTime ?? now();
        $user->last_online_at = $dateTime;
        $user->save();
        return $user;
    }

    public static function firstOrCreateUser(string $phone, string $ip = null): User
    {
        $ip = $ip ?? request()->ip();
        DB::beginTransaction();
        try
        {
            $ip   = $ip ?? request()->ip();
            $user = User::firstOrCreate(
                [ 'phone' => $phone ],
                [
                    'registered_ip' => $ip,
                    'is_new'        => true,
                ]
            );
            DB::commit();
        }
        catch (Exception $err)
        {
            report($err);
            DB::rollBack();
            return self::firstOrCreateUser($phone, $ip);
        }

        return $user;
    }

    public static function loginPhoneRequest(string $phone)
    {
        $user         = self::firstOrCreateUser($phone);
        $verification = app(AuthService::class)->generateVerificationCode($user);
        PhoneNumberRequestEvent::dispatch($user);
        return [ $user, $verification ];
    }

    public static function loginPhoneVerify(
        User $user,
        string $hash,
        string $code,
        UserCompleteRegisterDTO $dto
    )
    {
        try
        {
            DB::beginTransaction();
            $tokens = AuthService::getAccessAndRefreshTokenByPhone($user, $hash, $code);
            if(!isset($tokens['token_type']))
                throw new InvalidArgumentException(implode(' ', $tokens));

            app(AuthService::class)->clearVerificationCode($user, $hash);
            app(self::class)->completeRegister($user, $dto);
            DB::commit();
        }
        catch (Exception $err)
        {
            DB::rollBack();
            report($err);
            return [ false, null ];
        }

        return [ true, $tokens ];
    }

}
