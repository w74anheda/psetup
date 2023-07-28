<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserIp;
use Tests\TestCase;

class UserIpTest extends TestCase
{
    public function testInsertData()
    {
        $userIP = UserIp::factory()->create();
        $this->assertDatabaseCount($userIP->getTable(), 1);
        $this->assertCompositeKeyModelExists($userIP);
    }

    public function testCompositePrimaryKey()
    {
        $userIP = UserIp::factory()->make();
        $this->assertEquals(
            $userIP->getKeyName(),
            [ 'user_id', 'ip' ]
        );
        $this->assertFalse($userIP->incrementing);

    }

    public function testFactoryData()
    {
        $userIp = UserIp::factory()->make();
        $this->assertTrue(!!filter_var($userIp->ip, FILTER_VALIDATE_IP));
        $this->assertTrue($userIp->user instanceof User);
    }

    public function testAttributes()
    {
        $userIp     = UserIp::factory()->make();
        $attributes = [ 'user_id', 'ip' ];
        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $userIp->getAttributes());
    }

    public function testCastHiddenFillable()
    {
        $userIp = UserIp::factory()->make();

        $this->assertSame(
            $userIp->getCasts(),
            []
        );

        $this->assertSame(
            $userIp->getHidden(),
            []
        );

        $this->assertSame(
            $userIp->getFillable(),
            [ 'user_id', 'ip' ]
        );

    }

    public function testRelations()
    {
        $userIpfactory = UserIp::factory();
        $userIp        = $userIpfactory->for(User::factory())->create();
        $this->assertModelExists($userIp->user);
        $this->assertDatabaseCount($userIp->user, 1);
        $this->assertTrue($userIp->user instanceof User);
    }

}
