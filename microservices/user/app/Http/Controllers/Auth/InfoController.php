<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserWithRelationResource;
use Illuminate\Http\Request;


class InfoController extends Controller
{
    public function me(Request $request)
    {
        return new UserWithRelationResource($request->user());
    }

}
