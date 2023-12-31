<?php

namespace App\Http\Controllers\Auth\Login\PhoneNumber;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginPhoneNumberRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client as PassportClient;
use App\Events\Auth\Login\PhoneNumber\Request as PhoneNumberRequestEvent;
use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use Exception;

class LoginController extends Controller
{
    private string $app_url;

    public function __construct()
    {
        $this->app_url = env('APP_URL');
    }

    private function getAccessAndRefreshToken(string $phone, string $code)
    {

        $passportClient = PassportClient::where('password_client', 1)->first();

        $response = Http::withHeaders(
            [
                'User-Agent' => request()->header('User-Agent'),
                'ip-address' => request()->ip(),
            ]
        )->post("$this->app_url/oauth/token", [
                'grant_type'    => 'password',
                'client_id'     => $passportClient->id,
                'client_secret' => $passportClient->secret,
                'username'      => $phone,
                'password'      => $code,
                'scope'         => '*'
            ]);
        return $response->json();
    }

    public function request(LoginPhoneNumberRequest $request)
    {
        $user = User::firstOrCreate(
            [ 'phone' => $request->phone ],
            [ 'registered_ip' => $request->ip() ]
        );

        $verification = $user->generateVerificationCode();

        PhoneNumberRequestEvent::dispatch($user);

        return response(
            [
                'message'      => 'verification code successfully sent, use verify route to get token',
                'verification' => [
                    'hash'      => $verification->hash,
                    'code'      => $verification->code,
                    'expire_at' => $verification->expire_at,
                ],
            ],
            Response::HTTP_OK
        );
    }

    public function verify(LoginPhoneNumberVerify $request)
    {
        try
        {
            $user   = $request->user;
            $tokens = $this->getAccessAndRefreshToken($user->phone, $request->code);
            $this->activateHandler($request, $user);
            $user->clearVerificationCode($request->hash);
        }
        catch (Exception $err)
        {
            return response(
                [ 'message' => 'Bad Request!' ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response(
            $tokens,
            Response::HTTP_OK
        );
    }

    private function activateHandler(LoginPhoneNumberVerify $request, User $user)
    {
        $user->first_name   = $request->first_name;
        $user->last_name    = $request->last_name;
        $user->gender       = $request->gender;
        $user->is_active    = true;
        $user->is_active    = true;
        $user->activated_at = now();
        $user->save();
    }
}
