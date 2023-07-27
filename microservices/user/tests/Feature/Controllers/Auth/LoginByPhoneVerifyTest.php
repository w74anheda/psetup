<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Controllers\Auth\Login\PhoneNumber\VerifyLogin;
use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Models\User;
use App\Models\UserPhoneVerification;
use App\Services\AuthService;
use App\Services\User\UserService;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Passport;
use Mockery\MockInterface;
use Tests\TestCase;

class LoginByPhoneVerifyTest extends TestCase
{

    protected function request(User $user = null)
    {
        $user     = $user ?? User::factory()->isNew()->super()->create();
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => $user->phone ]
        );

        $data = $response->decodeResponseJson()->json();
        $this->assertTrue($data['verification']['is_new']);
        return [ $user, $data['verification'] ];
    }

    public function test_without_data()
    {
        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            []
        );
        $response->assertSessionHasErrors([ 'code' => "The code field is required." ]);
    }

    public function test_with_invalid_code_type()
    {
        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [ 'code' => 'random invalid code' ]
        );
        $response->assertSessionHasErrors([
            'code' => "The code field must be between 1 and 128 digits."
        ]);
    }

    public function test_with_just_valid_code()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [ 'code' => $requestData['code'] ]
        );
        $response->assertSessionHasErrors([ 'hash' => "The hash field is required." ]);
    }

    public function test_with_valid_code_and_invalid_hash_type()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code' => $requestData['code'],
                'hash' => 'random invalid hash'
            ]
        );
        $response->assertSessionHasErrors([ 'hash' => "The hash field must be a valid UUID." ]);
    }


    public function test_with_valid_code_and_hash()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code' => $requestData['code'],
                'hash' => $requestData['hash']
            ]
        );
        $response->assertSessionHasErrors([
            'first_name' => 'The first name field is required.',
            'last_name'  => 'The last name field is required.',
            'gender'     => 'The gender field is required.',
        ]);
    }

    public function test_with_min_length_first_and_last_name()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => 'in',
                'last_name'  => 'in',
                'gender'     => 'in',
            ]
        );
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must be at least 3 characters.",
            'last_name'  => "The last name field must be at least 3 characters.",
        ]);
    }

    public function test_with_max_length_first_and_last_name()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => Str::random(121),
                'last_name'  => Str::random(121),
            ]
        );
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must not be greater than 120 characters.",
            'last_name'  => "The last name field must not be greater than 120 characters.",
        ]);
    }

    public function test_with_invalid_type_first_and_last_name()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => 1212,
                'last_name'  => [],
            ]
        );
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must be a string.",
            'last_name'  => "The last name field must be a string.",
        ]);
    }

    public function test_with_invalid_type_gender()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code'   => $requestData['code'],
                'hash'   => $requestData['hash'],
                'gender' => 'asdsads',
            ]
        );
        $response->assertSessionHasErrors([
            'gender' => "The selected gender is invalid.",
        ]);
    }

    public function test_with_valid_data()
    {
        $date = Carbon::parse('2023-07-14 10:00:00');

        Carbon::setTestNow($date);

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
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);
        $this->assertEquals($response->json()['token_type'], 'Bearer');
        $this->assertEquals($response->json()['expires_in'], 1296000);
    }

    public function test_LoginPhoneNumberVerify_form_request_return_user()
    {
        [ $user, $requestData ] = $this->request();

        $request = new LoginPhoneNumberVerify(
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => fake()->firstName(),
                'last_name'  => fake()->lastName(),
                'gender'     => fake()->randomElement([ 'male', 'female', 'both' ]),
            ]
        );
        $request->authorize();


        $this->assertEquals($user->id, $request->user->id);
    }

}
