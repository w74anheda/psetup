<?php

namespace Tests\Feature\Controllers\Location;

use App\Http\Resources\StateCollection;
use App\Http\Resources\StateResource;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class StateTest extends TestCase
{

    public function testAuthApiMiddleware(): void
    {
        $this->checkAuthApiMiddleware(
            [
                route('state.index')      => 'get',
                route('state.store', 1)   => 'post',
                route('state.update', 1)  => 'patch',
                route('state.destroy', 1) => 'delete',
                route('state.destroyAll') => 'delete',
            ]
        );
    }

    public function testIndex(): void
    {
        $user = User::factory()->make();

        State::factory()->count(5)->create();

        $response = $this->actingAs($user, 'api')->getJson(
            route('state.index')
        );

        $response->assertJsonFragment(
            (new StateCollection(State::paginate(20)))->toResponse(request())->getData(true)
        );
    }

    public function testStoreValidationRequiredItem(): void
    {
        $this->withoutMiddleware();
        $response = $this->actingAs(User::factory()->make(), 'api')->postJson(
            route('state.store'),
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(
            [
                'name' => [ 'The name field is required.' ],
            ]
        );
    }

    public function testStore(): void
    {
        $this->withoutMiddleware();
        $response = $this->actingAs(User::factory()->make(), 'api')->postJson(
            route('state.store'),
            [
                'name' => fake()->city(),
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(
            (new StateResource($response->json()))->resource
        );
    }

    public function testUpdate(): void
    {
        $this->withoutAuthorization();
        $state    = State::factory()->create();
        $data     = State::factory()->make()->toArray();
        $response = $this->actingAs(User::factory()->make(), 'api')->patchJson(
            route('state.update', $state),
            $data
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(
            (new StateResource($response->json()))->resource
        );
    }

    public function testUpdateValidationRequiredItem(): void
    {
        $this->withoutAuthorization();
        $state    = State::factory()->create();
        $response = $this->actingAs(User::factory()->make(), 'api')->patchJson(
            route('state.update', $state),
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(
            [
                'name' => [ 'The name field is required.' ],
            ]
        );
    }

    public function testDestroy(): void
    {
        $this->withoutAuthorization();
        $user = User::factory()->create();
        State::factory()->count(2)->create();
        $state = State::first();

        $response = $this->actingAs($user, 'api')->deleteJson(
            route('state.destroy', $state)
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(
            [ 'message' => 'successfully deleted' ]
        );
        $this->assertNull(State::find($state->id));
    }

    public function testDestroyAll(): void
    {
        $this->withoutAuthorization();
        $user = User::factory()->create();
        State::factory()->count(5)->create();

        $response = $this->actingAs($user, 'api')->deleteJson(
            route('state.destroyAll')
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(
            [ 'message' => 'successfully deleted' ]
        );
        $this->assertEquals(0, State::count());
    }



}
