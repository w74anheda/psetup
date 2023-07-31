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
        $response = AuthService::refreshAccessToken($request);
        if($response->successful())
        {
            $accessToken = $response->json();
            return response()->json($accessToken);
        }
        return response()->json([ 'error' => 'Refresh Token Invalid' ], Response::HTTP_BAD_REQUEST);
    }

}
