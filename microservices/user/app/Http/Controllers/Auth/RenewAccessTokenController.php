<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RefreshAccessTokenRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as PassportClient;

class RenewAccessTokenController extends Controller
{
    private string|null $app_url;

    public function __construct()
    {
        $this->app_url = env('APP_URL');
    }

    public function refreshAccessToken(RefreshAccessTokenRequest $request)
    {
        $passportClient = PassportClient::where('password_client', 1)->first();

        $response =  Http::post("$this->app_url/oauth/token", [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $passportClient->id,
            'client_secret' => $passportClient->secret,
            'scope' => '',
        ]);

        if ($response->successful()) {
            $accessToken = $response->json();
            return response()->json($accessToken);
        }
        return response()->json(['error' => 'Refresh Token Invalid'], Response::HTTP_BAD_REQUEST);
    }

}
