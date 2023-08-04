<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;


class InfoController extends Controller
{
    public function me(Request $request)
    {
        $request->user()->load([ 'permissions' ]);
        return new UserResource($request->user(), true);
    }

}
