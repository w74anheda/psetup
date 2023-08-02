<?php

namespace Tests\Feature\Models;

use App\Models\User;
use App\Models\UserPhoneVerification;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Tests\TestCase;

class UserPhoneVerificationTest extends TestCase
{

    public function testInsertData()
    {
        $user         = User::factory()->create();
        $verification = UserPhoneVerification::factory()->for($user)->create();
        $this->assertDatabaseCount($verification->getTable(), 1);
        $this->assertCompositeKeyModelExists($verification);
        $this->assertEquals($verification->user->id, $user->id);
        $this->assertTrue($verification->user instanceof User);
    }

    public function testCompositePrimaryKey()
    {
        $verification = UserPhoneVerification::factory()->make();
        $this->assertEquals(
            $verification->getKeyName(),
            [ 'code', 'hash' ]
        );
        $this->assertFalse($verification->incrementing);

    }

    public function testTimestampWasFalse()
    {
        $verification = UserPhoneVerification::factory()->make();
        $this->assertFalse($verification->timestamps);
    }

    public function testFactoryData()
    {
        $verification = UserPhoneVerification::factory()->make();
        $this->assertIsNumeric($verification->code);
        $this->assertTrue($verification->hash instanceof LazyUuidFromString);
        $this->assertIsDateTime($verification->expire_at);
    }

    public function testAttributes()
    {
        $verification = UserPhoneVerification::factory()->make();
        $attributes   = [
            'user_id',
            'code',
            'expire_at',
            'hash'
        ];
        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $verification->getAttributes());
    }

    public function testCastHiddenFillable()
    {
        $verification = UserPhoneVerification::factory()->make();

        $this->assertSame(
            $verification->getCasts(),
            [
                'expire_at' => 'datetime',
                'code'      => 'int'
            ]
        );

        $this->assertSame(
            $verification->getHidden(),
            []
        );

        $this->assertSame(
            $verification->getFillable(),
            [
                'user_id',
                'code',
                'expire_at',
                'hash'
            ]
        );

    }

    public function testRelations()
    {
        $verification = UserPhoneVerification::factory()->for(User::factory())->create();
        $this->assertModelExists($verification->user);
        $this->assertDatabaseCount($verification->user, 1);
        $this->assertTrue($verification->user instanceof User);
    }


}
