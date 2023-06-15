<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteProfileRequest;
use Carbon\Carbon;
use Illuminate\Http\Response;

class CompleteProfileController extends Controller
{
    public function complete(CompleteProfileRequest $request)
    {
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
