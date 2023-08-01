<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class RenewAccessTokenTest extends TestCase
{
    protected function request(User $user = null)
    {
        $user     = $user ?? User::factory()->isNew()->create();
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => $user->phone ]
        );

        $data = $response->decodeResponseJson()->json();
        return [ $user, $data['verification'] ];
    }

    public function testRefreshAccessTokenWithValidRefreshToken(): void
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->postJson(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => fake()->firstName(),
                'last_name'  => fake()->lastName(),
                'gender'     => fake()->randomElement([ 'male', 'female', 'both' ]),
            ]
        )->json();

        $response = $this->postJson(
            route('auth.refreshAccessToken'),
            [ 'refresh_token' => $response['refresh_token'] ],
            [ 'User-Agent' => env('DEVELOPE_USER_AGENT') ]
        );

        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($response->json()['token_type'], 'Bearer');
        $this->assertEquals($response->json()['expires_in'], 1296000);

    }

    public function testRefreshAccessTokenWithInvalidRefreshToken(): void
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->postJson(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => fake()->firstName(),
                'last_name'  => fake()->lastName(),
                'gender'     => fake()->randomElement([ 'male', 'female', 'both' ]),
            ]
        )->json();

        $response = $this->postJson(
            route('auth.refreshAccessToken'),
            [ 'refresh_token' => 'invalid Token' ],
            [ 'User-Agent' => env('DEVELOPE_USER_AGENT') ]
        );

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([ 'error' ]);
        $this->assertEquals($response->json()['error'], 'Refresh Token Invalid');

    }
}
