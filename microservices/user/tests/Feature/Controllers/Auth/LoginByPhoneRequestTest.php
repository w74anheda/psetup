<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;

class LoginByPhoneRequestTest extends TestCase
{

    public function test_without_phone()
    {
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            []
        );
        $response->assertSessionHasErrors([ 'phone' => "The phone field is required." ]);

    }

    public function test_with_null_phone()
    {
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => null ]
        );
        $response->assertSessionHasErrors([ 'phone' => "The phone field is required." ]);

    }

    public function test_with_wrong_phone()
    {
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => '090359' ]
        );
        $response->assertSessionHasErrors([ 'phone' => "phone number format invalid" ]);

    }

    public function test_with_valid_phone()
    {
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => '09163216412' ]
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

}
