<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteProfileRequest;
use Illuminate\Http\Response;

class CompleteProfileController extends Controller
{
    public function complete(CompleteProfileRequest $request)
    {
        //create tdo
        // call complete profile in user service
        // call action from user state
        // create api resourse
        $user = $request->user();

        if(!$user->personal_info['is_completed'] ?? false)
        {
            $user->personal_info = array_merge(
                [ 'is_completed' => true ],
                $request->only([ 'birth_day', 'national_id' ]),
            );
            $user->save();
        }

        return response(
            $user,
            Response::HTTP_ACCEPTED
        );
    }
}
