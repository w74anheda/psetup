<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\User;
use App\Models\UserPhoneVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LoginByPhoneTest extends TestCase
{
    // use RefreshDatabase;

    public function setup(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }


    public function test_request(): void
    {

        $response = $this->post(
            route('auth.login.phonenumber.request'),
            []
        );
        $response->assertSessionHasErrors([ 'phone' => "The phone field is required." ]);

        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => null ]
        );
        $response->assertSessionHasErrors([ 'phone' => "The phone field is required." ]);


        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => '090359' ]
        );
        $response->assertSessionHasErrors([ 'phone' => "phone number format invalid" ]);

        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => env('SUPER_ADMIN_PHONE_NUMBER') ]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                'message',
                'verification' => [
                    'hash',
                    'code',
                    'is_new',
                    'expire_at'
                ]
            ]
        );

        $data = $response->decodeResponseJson()->json();
        $this->assertTrue($data['verification']['is_new']);
    }

    public function test_verify(): void
    {
        $response    = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => env('SUPER_ADMIN_PHONE_NUMBER') ]
        );
        $requestData = $response->decodeResponseJson()->json();


        $possibilities = [
            [
                'data'       => [],
                'validation' => [ 'code' => "The code field is required." ],
            ],
            [
                'data'       => [ 'code' => 'random invalid code' ],
                'validation' => [ 'code' => "The code field must be between 1 and 128 digits." ],
            ],
            [
                'data'       => [ 'code' => $requestData['verification']['code'] ],
                'validation' => [ 'hash' => "The hash field is required." ],
            ],
            [
                'data'       => [
                    'code' => $requestData['verification']['code'],
                    'hash' => 'random invalid hash'
                ],
                'validation' => [ 'hash' => "The hash field must be a valid UUID." ],
            ],
        ];

        foreach( $possibilities as $possibile )
        {
            $response = $this->post(
                route('auth.login.phonenumber.verify'),
                $possibile['data']
            );
            $response->assertSessionHasErrors($possibile['validation']);
        }
        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code' => $requestData['verification']['code'],
                'hash' => $requestData['verification']['hash']
            ]
        );

        $response->assertSessionHasErrors([
            'first_name' => 'The first name field is required.',
            'last_name'  => 'The last name field is required.',
            'gender'     => 'The gender field is required.',
        ]);


        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['verification']['code'],
                'hash'       => $requestData['verification']['hash'],
                'first_name' => 'in',
                'last_name'  => 'in',
                'gender'     => 'in',
            ]
        );
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must be at least 3 characters.",
            'last_name'  => "The last name field must be at least 3 characters.",
            'gender'     => "The selected gender is invalid.",
        ]);

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['verification']['code'],
                'hash'       => $requestData['verification']['hash'],
                'first_name' => 'masoud',
                'last_name'  => 'nazarpoor',
                'gender'     => 'male',
            ]
        );
        // $data = $response->decodeResponseJson();
        dump($response->json());

    }
}
