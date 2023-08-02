<?php

namespace Tests\Feature\Models;

use App\Models\PassportCustomToken;
use Laravel\Passport\Token;
use Tests\TestCase;

class PassportCustomTokenTest extends TestCase
{

    public function testInsertData()
    {
        $token = PassportCustomToken::factory()->create();
        $this->assertDatabaseCount($token->getTable(), 1);
        $this->assertDatabaseHas($token->getTable(), $token->getAttributes());
        $this->assertModelExists($token);
    }

    public function testParentClass()
    {
        $this->assertEquals(
            get_parent_class(PassportCustomToken::class),
            Token::class
        );
    }

    public function testRevokedAndNotRevokedScope()
    {
        $count = rand(1, 10);
        PassportCustomToken::factory()->count($count)->create();

        $Revokeds = PassportCustomToken::revoked()->get();
        foreach( $Revokeds as $token )
        {
            $this->assertFalse($token->revoked);
        }

        $notRevokeds = PassportCustomToken::NotRevoked()->get();
        foreach( $notRevokeds as $token )
        {
            $this->assertTrue($token->revoked);
        }

        $this->assertEquals(
            $count,
            $Revokeds->count() + $notRevokeds->count()
        );
    }

    public function testExpiredAndActiveScope()
    {

        $expireds = PassportCustomToken::factory()->expired()->count(rand(1, 10))->create();
        $actives  = PassportCustomToken::factory()->active()->count(rand(1, 10))->create();


        foreach( $expiredsFromScope = PassportCustomToken::expired()->get() as $token )
        {
            $this->assertTrue($token->expires_at < now());
        }

        $this->assertEquals($expiredsFromScope->count(), $expireds->count());


        foreach( $activeFromScope = PassportCustomToken::active()->get() as $token )
        {
            $this->assertTrue($token->expires_at >= now());
        }

        $this->assertEquals($activeFromScope->count(), $actives->count());
    }

    public function testAllExceptScope()
    {

        $tokens      = PassportCustomToken::factory()->count(rand(2, 10))->create();
        $randomToken = PassportCustomToken::inRandomOrder()->first();

        $allTokenExcept = PassportCustomToken::allExcept($randomToken->id);

        $this->assertFalse(
            in_array(
                $randomToken->id,
                $allTokenExcept->pluck('id')->toArray()
            )
        );

        $this->assertEquals(
            $allTokenExcept->count(),
            $tokens->count() - 1
        );

    }

}
