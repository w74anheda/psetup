<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RefreshAccessTokenRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Response;

class RenewAccessTokenController extends Controller
{
    public function refreshAccessToken(RefreshAccessTokenRequest $request)
    {
        $response = AuthService::refreshAccessToken($request->input('refresh_token'));

        if(isset($response['error']))
        {
            $response = [ 'error' => 'Refresh Token Invalid' ];
            $status   = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($response, ($status ?? Response::HTTP_OK));
    }

}
