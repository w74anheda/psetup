<?php

namespace Tests\Feature\Controllers\Location;

use App\Http\Resources\AddressResource;
use App\Models\Address as ModelsAddress;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class StateTest extends TestCase
{

    public function testAuthApiMiddleware(): void
    {
        $this->checkAuthApiMiddleware(
            [
                route('address.index')      => 'get',
                route('address.store', 1)   => 'post',
                route('address.update', 1)  => 'patch',
                route('address.destroy', 1) => 'delete',
            ]
        );
    }

    public function testIndex(): void
    {
        $user = User::factory()->has(ModelsAddress::factory()->count(1))->create();

        $response = $this->actingAs($user, 'api')->getJson(
            route('address.index')
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(
            [
                "addresses" => [
                    [
                        "id",
                        "city"  => [ "id", "name", "state" => [ "id", "name" ] ],
                        "full_address",
                        "house_number",
                        "unit_number",
                        "postalcode",
                        "point" => [ "latitude", "longitude" ]
                    ]
                ]
            ]

        );
    }

    public function testStoreValidationRequiredItem(): void
    {
        $response = $this->actingAs(User::factory()->make(), 'api')->postJson(
            route('address.store')
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(
            [
                'city_id'      => [ 'The city id field is required.' ],
                'full_address' => [ 'The full address field is required.' ],
                'house_number' => [ 'The house number field is required.' ],
                'unit_number'  => [ 'The unit number field is required.' ],
                'postalcode'   => [ 'The postalcode field is required.' ],
                'latitude'     => [ 'The latitude field is required.' ],
                'longitude'    => [ 'The longitude field is required.' ],
            ]
        );
    }

    public function testStore(): void
    {
        $addressData = ModelsAddress::factory()->make()->toArray();
        unset($addressData['user_id']);

        $response = $this->actingAs(User::factory()->make(), 'api')->postJson(
            route('address.store'),
            $addressData
        );

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson(
            (new AddressResource($response->json()))->resource
        );
    }

    public function testUpdate(): void
    {
        $user     = User::factory()->has(ModelsAddress::factory()->count(1))->create();
        $address  = $user->addresses()->first();
        $city     = City::factory()->create();
        $newData  = [
            'city_id'      => $city->id,
            'full_address' => 'new address',
            'house_number' => 2,
            'unit_number'  => 3,
            'postalcode'   => 1234567890,
            'latitude'     => 24.2,
            'longitude'    => 25.2,
        ];
        $response = $this->actingAs($user, 'api')->patchJson(
            route('address.update', $address),
            $newData
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(
            (new AddressResource($response->json()))->resource
        );
        $address->refresh();
        $this->assertTrue($address->city_id == $newData['city_id']);
        $this->assertTrue($address->full_address == $newData['full_address']);
        $this->assertTrue($address->house_number == $newData['house_number']);
        $this->assertTrue($address->unit_number == $newData['unit_number']);
        $this->assertTrue($address->postalcode == $newData['postalcode']);
        $this->assertTrue($address->latitude == $newData['latitude']);
        $this->assertTrue($address->longitude == $newData['longitude']);
    }

    public function testUpdateItsOwn(): void
    {
        $user     = User::factory()->has(ModelsAddress::factory()->count(1))->create();
        $address  = ModelsAddress::factory()->create();

        $response = $this->actingAs($user, 'api')->patchJson(
            route('address.update', $address)
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testDestroy(): void
    {
        $user     = User::factory()->has(ModelsAddress::factory()->count(1))->create();
        $address  = $user->addresses()->first();

        $response = $this->actingAs($user, 'api')->deleteJson(
            route('address.destroy', $address)
        );

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJson(
            [ 'message' => 'successfully deleted' ]
        );
    }

    public function testDestroyItsOwn(): void
    {
        $user     = User::factory()->has(ModelsAddress::factory()->count(1))->create();
        $address  = ModelsAddress::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson(
            route('address.destroy', $address)
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }


}
