<?php

namespace Tests\Feature\Controllers\Auth;

use App\Http\Resources\UserBaseResource;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CompleteProfileTest extends TestCase
{

    public function testWithoutUser()
    {
        $response = $this->patchJson(
            route('auth.profile.complete'),
            []
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    public function testCompletedUser()
    {
        $user     = User::factory()->completed()->create();
        $response = $this->actingAs($user, 'api')->patchJson(
            route('auth.profile.complete'),
            []
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'This action is unauthorized.'
        ]);
    }

    public function testValidationData()
    {
        $user = User::factory()->isNew()->create();


        $response = $this->actingAs($user, 'api')->patchJson(
            route('auth.profile.complete'),
            []
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJson([
            'message' => 'The birth day field is required. (and 1 more error)',
            'errors'  => [
                'birth_day'   => [ 'The birth day field is required.' ],
                'national_id' => [ 'The national id field is required.' ],
            ]
        ]);

        // -----------------------------------

        $response = $this->actingAs($user, 'api')->patchJson(
            route('auth.profile.complete'),
            [
                'birth_day'   => 'invalid date format',
                'national_id' => generate_random_digits_with_specefic_length(10),
            ]
        );
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonFragment([
            'errors' => [
                'birth_day'   => [ 'The birth day field must be a valid date.' ],
                'national_id' => [ 'Iranian National ID invalid' ],
            ]
        ]);
    }

    public function testNewUser()
    {
        $user     = User::factory()->isNew()->create();
        $response = $this->actingAs($user, 'api')->patchJson(
            route('auth.profile.complete'),
            [
                'birth_day'   => now(),
                'national_id' => '1741995108'
            ]
        );
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        $response->assertJson([
            'exception' => 'InvalidArgumentException'
        ]);
    }

    public function testRegisteredUser()
    {
        $birth_day   = now();
        $national_id = '1741995108';
        $user        = User::factory()->registered()->create();
        $response    = $this->actingAs($user, 'api')->patchJson(
            route('auth.profile.complete'),
            [ 'birth_day' => $birth_day, 'national_id' => $national_id ]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment([
            'personal_info' => [
                'is_completed' => true,
                'birth_day'    => $birth_day,
                'national_id'  => $national_id
            ]
        ]);
    }

    public function testResponseApiResourse()
    {
        $birth_day   = now();
        $national_id = '1741995108';
        $user        = User::factory()->registered()->create();
        $response    = $this->actingAs($user, 'api')->patchJson(
            route('auth.profile.complete'),
            [ 'birth_day' => $birth_day, 'national_id' => $national_id ]
        );
        $response->assertStatus(Response::HTTP_OK);


        $response->assertJsonStructure([
            'user' => [
                'id',
                'first_name',
                'last_name',
                'gender',
                'phone',
                'email',
                'personal_info',
                'is_active',
                'created_at',
            ]
        ]);
    }
}
