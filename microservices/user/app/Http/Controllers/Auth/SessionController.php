<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\PassportCustomTokenCollection;
use App\Models\PassportCustomToken;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:its-own-token,token')->only('delete');
        // add middleware to deleteAll method
        // test authService sessions
        // test passportCustomTokenMOdel presenter test
    }

    public function index(Request $request)
    {
        $sessions = AuthService::sessions(
            $request->user(),
            $request->bearerToken()
        );
        return new PassportCustomTokenCollection($sessions);
    }

    public function delete(PassportCustomToken $token)
    {
        AuthService::tokenDestroy($token);
        return Response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function deleteAll(Request $request)
    {
        AuthService::allTokensDestroy($request->user());
        return Response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

}
