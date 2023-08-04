<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class InfoTest extends TestCase
{

    public function testWithoutUser()
    {
        $response = $this->getJson(route('auth.profile.me'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testResponseApiResourse()
    {
        $user     = User::factory()->has(Permission::factory()->count(10))->create();
        $response = $this->actingAs($user, 'api')->getJson(route('auth.profile.me'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                'user' => [
                    "id",
                    "first_name",
                    "last_name",
                    "gender",
                    "phone",
                    "email",
                    "personal_info",
                    "is_active",
                    "created_at",
                    "permissions"
                ]
            ]
        );
    }
}
