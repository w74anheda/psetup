<?php

namespace Tests\Feature\Models;

use App\Models\City;
use App\Models\State;
use App\Presenters\Presenter;
use Tests\TestCase;
use App\Presenters\State\Api as StateApiPresenter;

class StateTest extends TestCase
{
    public function testInsertData()
    {
        $state = State::factory()->create();
        $this->assertDatabaseCount($state->getTable(), 1);
        $this->assertModelExists($state);
    }

    public function testFactoryData()
    {
        $state = State::factory()->make();
        $this->assertIsString($state->name);
    }

    public function testTimestampWasFalse()
    {
        $state = State::factory()->make();
        $this->assertFalse($state->timestamps);
    }

    public function testAttributes()
    {
        $state      = State::factory()->make();
        $attributes = [ 'name' ];
        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $state->getAttributes());
    }

    public function testCastHiddenFillable()
    {
        $state = State::factory()->make();

        $this->assertSame(
            $state->getCasts(),
            [ 'id' => 'int' ]
        );

        $this->assertSame(
            $state->getHidden(),
            []
        );

        $this->assertSame(
            $state->getFillable(),
            [ 'name' ]
        );

    }

    public function testRelations()
    {
        $statefactory = State::factory();
        $count        = rand(1, 10);
        $state        = $statefactory->has(City::factory()->count($count))->create();
        $this->assertModelExists($state->cities()->first());
        $this->assertDatabaseCount($state->cities()->first()->getTable(), $count);
        $this->assertTrue($state->cities()->first() instanceof City);
    }

    public function testHasPresenter()
    {
        $state = State::factory()->make();
        $this->assertTrue($state->present() instanceof Presenter);
        $this->assertTrue($state->present() instanceof StateApiPresenter);
    }


}
