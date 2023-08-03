<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Resources\PassportCustomTokenCollection;
use App\Models\User;
use App\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
