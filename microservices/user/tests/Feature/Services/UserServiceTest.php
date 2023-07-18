<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_first_or_create_user_and_activation_handler()
    {
        $phone = fake()->phoneNumber();
        $ip    = fake()->ipv4();
        $user  = UserService::firstOrCreateUser($phone, $ip);

        $this->assertTrue($user instanceof User);
        $this->assertTrue($user->phone == $phone);
        $this->assertTrue($user->registered_ip == $ip);
        $this->assertTrue($user->isNew());

        $date = Carbon::parse('2023-07-14 10:00:00');

        Carbon::setTestNow($date);
        UserService::completePhoneVerification(
            $user,
            'masoud_test',
            'nazarporr_test',
            'male'
        );

        $this->assertFalse($user->isNew());
        $this->assertTrue($user->is_active);
        $this->assertTrue($user->first_name == 'masoud_test');
        $this->assertTrue($user->last_name == 'nazarporr_test');
        $this->assertTrue($user->gender == 'male');
        $this->assertTrue($user->activated_at == $date);
        Carbon::setTestNow();

    }
}
