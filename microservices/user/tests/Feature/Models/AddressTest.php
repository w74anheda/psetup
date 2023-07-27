<?php

namespace Tests\Feature\Models;

use App\Models\Address;
use App\Models\City;
use App\Models\User;
use App\Presenters\Presenter;
use Tests\TestCase;
use App\Presenters\Address\Api as AddressApiPresenter;

class AddressTest extends TestCase
{

    public function testInsertData()
    {
        $user    = User::factory()->create();
        $city    = City::factory()->create();
        $address = Address::factory()->for($user)->for($city)->create();
        $this->assertDatabaseCount($address->getTable(), 1);
        $this->assertEquals($address->user->id, $user->id);
        $this->assertEquals($address->city->id, $city->id);
        $this->assertModelExists($address);
    }

    public function testFactoryData()
    {
        $address = Address::factory()->make();
        $this->assertModelExists($address->user);
        $this->assertModelExists($address->city);
        $this->assertIsString($address->full_address);
        $this->assertIsNumeric($address->house_number);
        $this->assertIsNumeric($address->unit_number);
        $this->assertIsNumeric($address->postalcode);
        $this->assertIsFloat($address->latitude);
        $this->assertIsFloat($address->longitude);
    }

    public function testTimestampWasFalse()
    {
        $address = Address::factory()->make();
        $this->assertFalse($address->timestamps);
    }

    public function testAttributes()
    {
        $address    = Address::factory()->make();
        $attributes = [
            'user_id',
            'city_id',
            'full_address',
            'house_number',
            'unit_number',
            'postalcode',
            'latitude',
            'longitude',
        ];

        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $address->getAttributes());
    }

    public function testCastHiddenFillable(): void
    {
        $address = Address::factory()->make();

        $this->assertSame(
            $address->getCasts(),
            [ 'id' => 'int' ]
        );

        $this->assertSame(
            $address->getHidden(),
            []
        );

        $this->assertSame(
            $address->getFillable(),
            [
                'user_id',
                'city_id',
                'full_address',
                'house_number',
                'unit_number',
                'postalcode',
                'latitude',
                'longitude',
            ]
        );

    }
    public function testRelations(): void
    {
        $addressfactory = Address::factory();
        $address = $addressfactory->for(User::factory())->create();
        $this->assertModelExists( $address->user);
        $this->assertTrue($address->user instanceof User);

        $address = $addressfactory->for(City::factory())->create();
        $this->assertModelExists( $address->city);
        $this->assertTrue($address->city instanceof City);
    }

    public function testHasPresenter()
    {
        $Address = Address::factory()->make();
        $this->assertTrue($Address->present() instanceof Presenter);
        $this->assertTrue($Address->present() instanceof AddressApiPresenter);
    }


}
