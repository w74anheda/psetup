<?php

namespace Tests\Feature\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginByPhoneTest extends TestCase
{
    use RefreshDatabase;

    public function test_request(): void
    {
        $response = $this->post(
            route('auth.login.phonenumber.request'),
            [ 'phone' => '09035919877' ]
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
        $this->assertTrue(true);

    }
}
