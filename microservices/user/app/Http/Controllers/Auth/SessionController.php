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
        $this->middleware('can:its-own-token,token')->only('revoke');
    }

    public function index(Request $request)
    {
        $sessions = AuthService::sessions(
            $request->user(),
            $request->bearerToken()
        );
        return new PassportCustomTokenCollection($sessions);
    }

    public function revoke(PassportCustomToken $token)
    {
        AuthService::tokenDestroy($token);
        return Response(
            [ 'message' => 'successfully revoked.' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function revokeAll(Request $request)
    {
        AuthService::allTokensDestroy($request->user());
        return Response(
            [ 'message' => 'successfully revoked.' ],
            Response::HTTP_ACCEPTED
        );
    }

}
