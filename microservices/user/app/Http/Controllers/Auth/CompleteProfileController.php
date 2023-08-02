<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserCompleteProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteProfileRequest;
use Illuminate\Http\Response;

class CompleteProfileController extends Controller
{
    public function complete(CompleteProfileRequest $request)
    {
        $dto  = (new UserCompleteProfileDTO)
            ->setBirthDay($request->input('birth_day'))
            ->setNationalId($request->input('national_id'));
        $user = $request->user();
        $user->state()->completeProfile($dto);

        // create controller test
        // create api resourse
        // add userService test for verify login and check user name and ...

        return response(
            $user,
            Response::HTTP_ACCEPTED
        );
    }
}
