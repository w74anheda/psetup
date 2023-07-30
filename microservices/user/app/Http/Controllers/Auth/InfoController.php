<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class InfoController extends Controller
{

    public function me(Request $request)
    {
        $user = $request->user();
        return response(
            [ ...$user->toArray(), 'permissions' => UserService::allPermissions($user) ],
            Response::HTTP_OK
        );
    }

}
