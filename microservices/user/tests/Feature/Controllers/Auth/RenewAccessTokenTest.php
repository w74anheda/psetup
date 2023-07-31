<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

    public function test_example(): void
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
        );

        dd($requestData);
    }
}
