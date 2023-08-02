<?php

namespace Tests\Feature\Models;

use App\Models\City;
use App\Models\State;
use App\Presenters\Presenter;
use Tests\TestCase;
use App\Presenters\City\Api as CityApiPresenter;

class CityTest extends TestCase
{
    public function testInsertData()
    {
        $state = State::factory()->create();
        $city  = City::factory()->for($state)->create();

        $this->assertDatabaseCount($city->getTable(), 1);
        $this->assertEquals($city->state->id, $state->id);
        $this->assertModelExists($city->state);
    }

    public function testFactoryData()
    {
        $address = City::factory()->make();
        $this->assertModelExists($address->state);
        $this->assertIsString($address->name);
    }

    public function testTimestampWasFalse()
    {
        $city = City::factory()->make();
        $this->assertFalse($city->timestamps);
    }

    public function testAttributes()
    {
        $city       = City::factory()->make();
        $attributes = [
            'state_id',
            'name'
        ];

        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $city->getAttributes());
    }

    public function testCastHiddenFillable(): void
    {
        $city = City::factory()->make();

        $this->assertSame(
            $city->getCasts(),
            [ 'id' => 'int' ]
        );

        $this->assertSame(
            $city->getHidden(),
            []
        );

        $this->assertSame(
            $city->getFillable(),
            [ 'state_id', 'name' ]
        );

    }

    public function testRelations(): void
    {
        $cityfactory = City::factory();
        $city        = $cityfactory->for(State::factory())->create();
        $this->assertModelExists($city->state);
        $this->assertTrue($city->state instanceof State);
    }

    public function testHasPresenter()
    {
        $city = City::factory()->make();
        $this->assertTrue($city->present() instanceof Presenter);
        $this->assertTrue($city->present() instanceof CityApiPresenter);
    }


}
