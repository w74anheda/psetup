<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Resources\UserWithRelationResource;
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
        $user        = User::factory()->create();
        $response    = $this->actingAs($user, 'api')->getJson(route('auth.profile.me'));
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson((new UserWithRelationResource($user))->toArray(request()));
    }
}
