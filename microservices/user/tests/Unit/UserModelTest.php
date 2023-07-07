<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function test_check_attributes(): void
    {
        $user = User::factory()->make([ 'phone' => '09163254565' ]);
        dd($user);
        $this->assertTrue($user->phone == '09163254565');
    }
}
