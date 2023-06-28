<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Passport\CustomToken;
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
        $fields                    = [ 'id', 'user_agent', 'created_at' ];
        $currentSession            = $request->token->only($fields);
        $currentSession['current'] = true;
        $sessions                  = $request->user()
            ->tokens()
            ->AllExcept($currentSession['id'])
            ->select($fields)
            ->get()
            ->map(fn($session) => [ ...$session->toArray(), 'current' => false ])
            ->prepend($currentSession);

        return Response(
            $sessions,
            Response::HTTP_OK
        );
    }

    public function delete(CustomToken $token)
    {
        $token->revokeAndDelete();
        return Response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function deleteAll(Request $request)
    {
        $request
            ->user()
            ->tokens()
            ->get()
            ->each(fn($token) => $token->revokeAndDelete());

        return Response(
            [ 'message' => 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }

}
