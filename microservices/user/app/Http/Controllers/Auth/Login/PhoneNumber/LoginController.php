<?php

namespace App\Http\Controllers\Auth\Login\PhoneNumber;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginPhoneNumberRequest;
use App\Models\User;
use Illuminate\Http\Response;
use App\Events\Auth\Login\PhoneNumber\Request as PhoneNumberRequestEvent;
use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    private string $app_url;

    public function __construct(public UserService $userService)
    {}

    public function request(LoginPhoneNumberRequest $request)
    {
        try
        {
            DB::beginTransaction();
            $user = User::firstOrCreate(
                [ 'phone' => $request->phone ],
                [
                    'registered_ip' => $request->ip(),
                    'is_new'        => true
                ]
            );
            $this->userService->setUser($user);
            $verification = $this->userService->generateVerificationCode();
            PhoneNumberRequestEvent::dispatch($user);
            DB::commit();
        }
        catch (Exception $err)
        {
            DB::rollBack();
            return response(
                [ 'message' => 'Bad Request!' ],
                Response::HTTP_BAD_REQUEST
            );
        }

        return response(
            [
                'message'      => 'verification code successfully sent, use verify route to get token',
                'verification' => [
                    'hash'      => $verification->hash,
                    'code'      => $verification->code,
                    'is_new'    => $user->isNew(),
                    'expire_at' => $verification->expire_at,
                ],
            ],
            Response::HTTP_OK
        );
    }

    public function verify(LoginPhoneNumberVerify $request)
    {
        $user = $request->user;
        try
        {
            DB::beginTransaction();
            $this->userService->setUser($user);
            $tokens = $this->userService->getAccessAndRefreshToken($request->code, $request);
            $this->userService->activateHandler($request);
            $this->userService->clearVerificationCode($request->hash);
            DB::commit();
        }
        catch (Exception $err)
        {
            DB::rollBack();
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


}
