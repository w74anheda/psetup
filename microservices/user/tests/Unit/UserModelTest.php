<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserModelTest extends TestCase
{

    public function test_check_attributes()
    {
        $user       = User::factory()->make();
        $attributes = [
            'first_name',
            'last_name',
            'gender',
            'phone',
            'activated_at',
            'last_online_at',
            'email',
            'registered_ip',
            'is_active',
            'email_verified_at',
            'is_new',
            'personal_info',
        ];

        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $user->getAttributes());
        return $user;
    }

    /**
     * @depends test_check_attributes
     */
    public function test_check_is_new_and_is_active_and_is_completed($user)
    {
        $this->assertTrue($user->is_new);
        $this->assertTrue($user->isNew());
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->isActive());
        $this->assertFalse($user->personal_info['is_completed']);
        return $user;
    }

    /**
     * @depends test_check_is_new_and_is_active_and_is_completed
     */
    public function test_check_user_factory($user)
    {
        dump($user->first_name);
    }
}
