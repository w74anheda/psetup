<?php

namespace Tests\Feature\Models;

use App\Casts\PersonalInfoCast;
use App\Models\Address;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserIp;
use App\Models\UserPhoneVerification;
use App\Presenters\Presenter;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Tests\TestCase;
use App\Presenters\User\Api as UserApiPresenter;
use App\Presenters\User\Api2;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_insert_data(): User
    {
        $data = User::factory()->make()->getAttributes();
        $user = User::create($data);
        $this->assertDatabaseCount('users', 1);

        unset($data['personal_info']);
        $this->assertDatabaseHas('users', $data);
        $this->assertModelExists($user);
        return $user;
    }

    /**
     * @depends test_insert_data
     */
    public function test_check_user_factory($user)
    {
        $this->assertTrue($user->id instanceof LazyUuidFromString);
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
     * @depends test_insert_data
     */
    public function test_check_attributes($user): void
    {
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
    }

    /**
     * @depends test_insert_data
     */
    public function test_cast_hidden_fillable($user): void
    {
        $this->assertSame($user::GENDERS, [ 'male', 'female', 'both' ]);
        $this->assertSame(
            $user->getCasts(),
            [
                'is_new'            => 'bool',
                'is_active'         => 'bool',
                'email_verified_at' => 'datetime',
                'activated_at'      => 'datetime',
                'last_online_at'    => 'datetime',
                'password'          => 'hashed',
                'personal_info'     => PersonalInfoCast::class,
            ]
        );

        $this->assertSame(
            $user->getHidden(),
            [
                'registered_ip',
                'password',
            ]
        );

        $this->assertSame(
            $user->getFillable(),
            [
                'first_name',
                'last_name',
                'gender',
                'phone',
                'activated_at',
                'last_online_at',
                'email',
                'email_verified_at',
                'registered_ip',
                'is_active',
                'is_new',
                'personal_info',
            ]
        );

    }

    /**
     * @depends test_insert_data
     */
    public function test_relations($user): void
    {
        $count       = rand(1, 10);
        $userfactory = User::factory();

        $user = $userfactory->has(UserIp::factory()->count($count), 'ips')->create();

        $this->assertCount($count, $user->ips);
        $this->assertTrue($user->ips()->first() instanceof UserIp);


        // $this->assertTrue($user->addresses() instanceof HasMany);
        // $this->assertTrue($user->addresses()->getModel() instanceof Address);

        // $this->assertTrue($user->phoneVerifications() instanceof HasOne);
        // $this->assertTrue($user->phoneVerifications()->getModel() instanceof UserPhoneVerification);

        // $this->assertTrue($user->roles() instanceof BelongsToMany);
        // $this->assertTrue($user->roles()->getModel() instanceof Role);

        // $this->assertTrue($user->permissions() instanceof BelongsToMany);
        // $this->assertTrue($user->permissions()->getModel() instanceof Permission);

    }

    /**
     * @depends test_insert_data
     */
    public function test_has_presenter($user)
    {
        $this->assertTrue($user->present() instanceof Presenter);
        $this->assertTrue($user->present() instanceof UserApiPresenter);
    }

    /**
     * @depends test_insert_data
     */
    public function test_id_hash_type($user)
    {
        $this->assertFalse($user->incrementing);
        $this->assertEquals($user->getKeyType(), 'string');
    }
}
