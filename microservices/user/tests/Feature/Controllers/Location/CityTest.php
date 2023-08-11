<?php

namespace Tests\Feature\Controllers\Location;

use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Http\Resources\StateResource;
use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class CityTest extends TestCase
{

    public function testAuthApiMiddleware(): void
    {
        $this->checkAuthApiMiddleware(
            [
                route('city.index')      => 'get',
                route('city.store', 1)   => 'post',
                route('city.update', 1)  => 'patch',
                route('city.destroy', 1) => 'delete',
                route('city.destroyAll') => 'delete',
            ]
        );
    }

    public function testIndex(): void
    {
        $user = User::factory()->make();

        City::factory()->count(5)->create();

        $response = $this->actingAs($user, 'api')->getJson(
            route('city.index')
        );
        $response->assertJsonFragment(
            (new CityCollection(City::paginate(20)))->toResponse(request())->getData(true)
        );
    }

    public function testStoreValidationRequiredName(): void
    {
        $this->withoutMiddleware();
        $response = $this->actingAs(User::factory()->make(), 'api')->postJson(
            route('city.store'),
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(
            [
                'name'     => [ 'The name field is required.' ],
                'state_id' => [ 'The state id field is required.' ],
            ]
        );
    }

    public function testStore(): void
    {
        $this->withoutMiddleware();
        $response = $this->actingAs(User::factory()->make(), 'api')->postJson(
            route('city.store'),
            [
                'name'     => fake()->city(),
                'state_id' => State::factory()->create()->id,
            ]
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(
            (new CityResource($response->json()))->resource
        );
    }

    public function testUpdate(): void
    {
        $this->withoutAuthorization();
        $city     = City::factory()->create();
        $data     = City::factory()->make()->toArray();
        $response = $this->actingAs(User::factory()->make(), 'api')->patchJson(
            route('city.update', $city),
            $data
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(
            (new StateResource($response->json()))->resource
        );
        $city->refresh();
        $this->assertEquals($data['name'], $response->json('city.name'));
        $this->assertEquals($data['state_id'], $response->json('city.state.id'));
    }

    public function testUpdateValidationRequiredItem(): void
    {
        $this->withoutAuthorization();
        $city     = City::factory()->create();
        $response = $this->actingAs(User::factory()->make(), 'api')->patchJson(
            route('city.update', $city),
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(
            [
                'name'     => [ 'The name field is required.' ],
            ]
        );
    }

    public function testDestroy(): void
    {
        $this->withoutAuthorization();
        $user = User::factory()->create();
        City::factory()->count(2)->create();
        $city = State::first();

        $response = $this->actingAs($user, 'api')->deleteJson(
            route('city.destroy', $city)
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(
            [ 'message' => 'successfully deleted' ]
        );
        $this->assertNull(City::find($city->id));
    }

    public function testDestroyAll(): void
    {
        $this->withoutAuthorization();
        $user = User::factory()->create();
        City::factory()->count(5)->create();

        $response = $this->actingAs($user, 'api')->deleteJson(
            route('city.destroyAll')
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(
            [ 'message' => 'successfully deleted' ]
        );
        $this->assertEquals(0, City::count());
    }



}
