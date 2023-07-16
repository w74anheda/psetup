<?php

namespace App\Http\Controllers\Auth\Login\PhoneNumber;

use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Services\UserService;
use Illuminate\Http\Response;

class VerifyLogin
{
    public function __invoke(LoginPhoneNumberVerify $request)
    {
        [ $isOK, $tokens ] = UserService::loginVerify($request);

        return $isOK
            ? response(
                $tokens,
                Response::HTTP_OK
            )
            : response(
                [ 'message' => 'Bad Request!' ],
                Response::HTTP_BAD_REQUEST
            );

    }
}
