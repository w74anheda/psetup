<?php

namespace App\Http\Controllers\Auth;

use App\DTO\UserCompleteProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteProfileRequest;
use App\Http\Resources\UserResource;

class CompleteProfileController extends Controller
{
    public function complete(CompleteProfileRequest $request)
    {
        $user = $request->user();
        $dto  = (new UserCompleteProfileDTO)
            ->setBirthDay($request->input('birth_day'))
            ->setNationalId($request->input('national_id'));

        $user->state()->completeProfile($dto);
        return new UserResource($user);
    }
}
