<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class InfoController extends Controller
{

    public function me(Request $request)
    {
        return response(
            $request->user(),
            Response::HTTP_OK
        );
    }
}
