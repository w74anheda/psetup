<?php

namespace App\Http\Controllers\Auth\Login\PhoneNumber;

use App\DTO\UserCompleteRegisterDTO;
use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Services\UserService;
use Illuminate\Http\Response;

class VerifyLogin
{
    public function __invoke(LoginPhoneNumberVerify $request)
    {
        $dto = (new UserCompleteRegisterDTO);
        $dto->setFirstName($request->first_name)
            ->setLastName($request->last_name)
            ->setGender($request->gender);

        [ $isOK, $tokens ] = app(UserService::class)::loginPhoneVerify(
            $request->user,
            $request->hash,
            $request->code,
            $dto
        );

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
