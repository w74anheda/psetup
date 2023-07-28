<?php

namespace Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPhoneVerificationTest extends TestCase
{


    public function testTimestampWasFalse()
    {
        $state = State::factory()->make();
        $this->assertFalse($state->timestamps);
    }


}
