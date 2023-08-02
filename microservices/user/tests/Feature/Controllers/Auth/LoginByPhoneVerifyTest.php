<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Requests\Auth\LoginPhoneNumberVerify;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginByPhoneVerifyTest extends TestCase
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

    public function testWithoutData()
    {
        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            []
        );
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([ 'code' => "The code field is required." ]);
    }

    public function testWithInvalidCodeType()
    {
        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [ 'code' => 'random invalid code' ]
        );
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'code' => "The code field must be between 1 and 128 digits."
        ]);
    }

    public function testWithJustValidCode()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [ 'code' => $requestData['code'] ]
        );
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([ 'hash' => "The hash field is required." ]);
    }

    public function testWithValidCodeAndInvalidHashType()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code' => $requestData['code'],
                'hash' => 'random invalid hash'
            ]
        );
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([ 'hash' => "The hash field must be a valid UUID." ]);
    }


    public function testWithValidCodeAndHash()
    {
        [ $user, $requestData ] = $this->request();

        $response = $this->post(
            route('auth.login.phonenumber.verify'),
            [
                'code' => $requestData['code'],
                'hash' => $requestData['hash']
            ]
        );
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'first_name' => 'The first name field is required.',
            'last_name'  => 'The last name field is required.',
            'gender'     => 'The gender field is required.',
        ]);
    }

    public function testWithMinLengthFirstAndLastName()
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
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must be at least 3 characters.",
            'last_name'  => "The last name field must be at least 3 characters.",
        ]);
    }

    public function testWithMaxLengthFirstAndLastName()
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
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must not be greater than 120 characters.",
            'last_name'  => "The last name field must not be greater than 120 characters.",
        ]);
    }

    public function testWithInvalidTypeFirstAndLast_name()
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
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'first_name' => "The first name field must be a string.",
            'last_name'  => "The last name field must be a string.",
        ]);
    }

    public function testWithInvalidTypeGender()
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
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertSessionHasErrors([
            'gender' => "The selected gender is invalid.",
        ]);
    }

    public function testWithValidData()
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

        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals($response->json()['token_type'], 'Bearer');

        $user->refresh();
        $this->assertFalse($user->isNew());
        $this->assertTrue($user->isActive());
    }

    public function testReturnedUserFromLoginPhoneNumberVerifyRequest()
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

    public function testIsNotNewUserWithNullData()
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

        $user->refresh();
        [ $user, $requestData ] = $this->request($user);
        $response = $this->postJson(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => null,
                'last_name'  => null,
                'gender'     => null,
            ]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);


        $user->refresh();
        [ $user, $requestData ] = $this->request($user);
        $response = $this->postJson(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
            ]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);


    }

    public function testIsNotNewUserWithNewData()
    {
        $first_name = fake()->firstName();
        $last_name = fake()->lastName();
        $gender = fake()->randomElement([ 'male', 'female', 'both' ]);
        [ $user, $requestData ] = $this->request();

        $response = $this->postJson(
            route('auth.login.phonenumber.verify'),
            [
                'code'       => $requestData['code'],
                'hash'       => $requestData['hash'],
                'first_name' => $first_name,
                'last_name'  => $last_name,
                'gender'     => $gender,
            ]
        );
        $user->refresh();
        $this->assertEquals($user->first_name,$first_name);
        $this->assertEquals($user->last_name,$last_name);
        $this->assertEquals($user->gender,$gender);



        [ $user, $requestData ] = $this->request($user);
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

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJsonStructure([
            'token_type',
            'expires_in',
            'access_token',
            'refresh_token',
        ]);

        $user->refresh();
        $this->assertEquals($user->first_name,$first_name);
        $this->assertEquals($user->last_name,$last_name);
        $this->assertEquals($user->gender,$gender);

    }


}
