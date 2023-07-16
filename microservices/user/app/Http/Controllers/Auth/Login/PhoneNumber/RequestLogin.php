<?php

namespace App\Http\Controllers\Auth\Login\PhoneNumber;

use App\Http\Requests\Auth\LoginPhoneNumberRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Response;

class RequestLogin
{
    public function __invoke(LoginPhoneNumberRequest $request)
    {
        [ $user, $verification ] = UserService::loginRequest($request);

        return response(
            [
                'message'      => 'verification code successfully sent, use verify route to get token',
                'verification' => [
                    'hash'      => $verification->hash,
                    'code'      => $verification->code,
                    'is_new'    => $user->isNew(),
                    'expire_at' => $verification->expire_at,
                ],
            ],
            Response::HTTP_OK
        );
    }
}