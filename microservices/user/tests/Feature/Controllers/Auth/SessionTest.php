<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\PassportCustomToken;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Http\Response;
use Tests\TestCase;

class SessionTest extends TestCase
{

    public function testIndexAuthApi(): void
    {
        $response = $this->getJson(
            route('auth.sessions.index')
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testIndexSessions(): void
    {
        $user = User::factory()->create();

        $this->createPassportToken($user);
        $this->createPassportToken($user);

        [ $token, $request ] = $this->requestWithToken($user);

        $response = $request->getJson(
            route('auth.sessions.index')
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(3, $response->json('sessions'));
        $response->assertJsonStructure(
            [
                'sessions' => [
                    [
                        'id',
                        'os',
                        'browser',
                        'ip_address',
                        'created_at',
                        'expires_at',
                        'current'
                    ]
                ]
            ]
        );
    }


    public function testRevokeTokenWithInvalidTokenID(): void
    {
        $response = $this->postJson(
            route('auth.sessions.revoke', 'invalid token id')
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([ 'message' => 'Unauthenticated.' ]);
    }


    public function testRevokeTokenWithValidTokenIdNotOwned(): void
    {
        $user = User::factory()->create();
        $this->createPassportToken();
        $NotOwnedToken = PassportCustomToken::first();

        [ $_, $request ] = $this->requestWithToken($user);
        $response        = $request->postJson(
            route('auth.sessions.revoke', [ 'token' => $NotOwnedToken ])
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([ 'message' => 'This action is unauthorized.' ]);
    }

    public function testRevokeTokenWithValidTokenId(): void
    {
        $user = User::factory()->create();
        $this->createPassportToken($user);
        $this->createPassportToken($user);
        $tokens = AuthService::sessions($user);
        $this->assertCount(2, $tokens);

        [ $_, $request ] = $this->requestWithToken($user);

        $tokens = AuthService::sessions($user);
        $this->assertCount(3, $tokens);

        $response        = $request->postJson(
            route('auth.sessions.revoke',$tokens->first())
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([ 'message' => 'successfully revoked.' ]);
        $tokens = AuthService::sessions($user);
        $this->assertCount(2, $tokens);
    }

    public function testRevokeAllToken(): void
    {
        $user = User::factory()->create();
        $this->createPassportToken($user);
        $this->createPassportToken($user);
        $tokens = AuthService::sessions($user);
        $this->assertCount(2, $tokens);

        [ $_, $request ] = $this->requestWithToken($user);

        $tokens = AuthService::sessions($user);
        $this->assertCount(3, $tokens);

        $response        = $request->postJson(
            route('auth.sessions.revoke.all')
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson([ 'message' => 'successfully revoked.' ]);
        $tokens = AuthService::sessions($user);
        $this->assertCount(0, $tokens);
    }

}
