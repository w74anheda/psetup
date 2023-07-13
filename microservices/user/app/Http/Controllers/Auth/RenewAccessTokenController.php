<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RefreshAccessTokenRequest;
use App\Services\UserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as PassportClient;

class RenewAccessTokenController extends Controller
{
    public function refreshAccessToken(RefreshAccessTokenRequest $request)
    {
        $response = UserService::refreshAccessToken($request);
        if($response->successful())
        {
            $accessToken = $response->json();
            return response()->json($accessToken);
        }
        return response()->json([ 'error' => 'Refresh Token Invalid' ], Response::HTTP_BAD_REQUEST);
    }

}
