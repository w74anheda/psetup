<?php

namespace Tests\Unit;

use App\Console\Kernel;
use App\Models\User;
use App\Models\UserIp;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
// use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;


class UserModelTest extends TestCase
{

    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function setup(): void
    {
        parent::setUp();
        // $this->artisan('migrate:fresh --seed');
    }

    public function test_check_user_attributes()
    {
        $user       = User::factory()->create();
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
     * @depends test_check_user_attributes
     */
    public function test_check_user_factory($user)
    {
        $this->assertIsString($user->first_name);
        $this->assertIsString($user->last_name);
        $this->assertContains($user->gender, [ 'male', 'female', 'both' ]);
        $this->assertIsString($user->phone);
        $this->assertTrue(is_null($user->activated_at));
        $this->assertTrue(!!filter_var($user->email, FILTER_VALIDATE_EMAIL));
        $this->assertTrue(is_null($user->email_verified_at));
        $this->assertFalse($user->is_active);
        $this->assertTrue($user->is_new);
        $this->assertTrue(!!filter_var($user->registered_ip, FILTER_VALIDATE_IP));
        $this->assertTrue(is_array($user->personal_info));
        $this->assertArrayHasKey('is_completed', $user->personal_info);
        $this->assertArrayHasKey('birth_day', $user->personal_info);
        $this->assertArrayHasKey('is_completed', $user->personal_info);
        $this->assertArrayHasKey('national_id', $user->personal_info);
        $this->assertFalse($user->personal_info['is_completed']);
        $this->assertTrue(is_null($user->personal_info['birth_day']));
        $this->assertTrue(is_null($user->personal_info['national_id']));
    }

    /**
     * @depends test_check_user_attributes
     */
    public function test_user_check_setLastOnlineAt($user)
    {
        $datetime = today()->subHours(10);
        $this->assertTrue(is_null($user->last_online_at));
        $user->setLastOnlineAt($datetime);
        $this->assertTrue($datetime->eq($user->last_online_at));
    }

    /**
     * @depends test_check_user_attributes
     */
    public function test_user_relations($user)
    {
        $this->assertTrue($user->ips() instanceof HasMany);
        $this->assertTrue($user->ips()->getModel() instanceof UserIp);
    }
}
