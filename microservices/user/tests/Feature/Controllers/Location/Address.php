<?php

namespace Tests\Feature\Controllers\Location;

use App\Models\Address as ModelsAddress;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Response;
use Tests\TestCase;

class Address extends TestCase
{

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




}
