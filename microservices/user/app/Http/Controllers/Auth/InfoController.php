<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class InfoController extends Controller
{

    public function me(Request $request)
    {
        $user = $request->user();
        return response(
            [ ...$user->toArray(), 'permissions' => $user->allPermissions()->get() ],
            Response::HTTP_OK
        );
    }

}
