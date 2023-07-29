<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PassportCustomToken;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:its-own-token,token')->only('delete');
    }

    public function index(Request $request)
    {
        $sessions = AuthService::sessions(
            $request->token->user,
            $request->token,
            [ 'id', 'user_agent', 'created_at' ]
        );

        return Response(
            $sessions,
            Response::HTTP_OK
        );
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
