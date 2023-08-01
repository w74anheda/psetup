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
use Ramsey\Uuid\Lazy\LazyUuidFromString;
use Tests\TestCase;
use App\Presenters\User\Api as UserApiPresenter;
use Carbon\Carbon;

class UserTest extends TestCase
{

    public function testInsertData()
    {
        $data = User::factory()->make()->getAttributes();
        $user = User::create($data);
        $this->assertDatabaseCount($user->getTable(), 1);

        unset($data['personal_info']);
        $this->assertDatabaseHas('users', $data);
        $this->assertModelExists($user);
        $this->assertTrue($user->id instanceof LazyUuidFromString);
    }

    public function testUserFactoryActivatedUser()
    {
        Carbon::setTestNow('2023-01-01 00:00:01');

        $user = User::factory()->make();

        $this->assertIsString($user->first_name);
        $this->assertIsString($user->last_name);
        $this->assertContains($user->gender, [ 'male', 'female', 'both' ]);
        $this->assertIsString($user->phone);
        $this->assertEquals($user->activated_at, now());
        $this->assertTrue(!!filter_var($user->email, FILTER_VALIDATE_EMAIL));
        $this->assertTrue(is_null($user->email_verified_at));
        $this->assertTrue($user->is_active);
        $this->assertFalse($user->is_new);
        $this->assertTrue(!!filter_var($user->registered_ip, FILTER_VALIDATE_IP));
        $this->assertFalse($user->personal_info['is_completed']);
        $this->assertNull($user->personal_info['birth_day']);
        $this->assertNull($user->personal_info['national_id']);

    }

    public function testUserFactoryNewUser()
    {
        $user = User::factory()->isNew()->make();
        $this->assertNull($user->first_name);
        $this->assertNull($user->last_name);
        $this->assertNull($user->gender);
        $this->assertIsString($user->phone);
        $this->assertNull($user->activated_at);
        $this->assertNull($user->email);
        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->is_active);
        $this->assertTrue($user->is_new);
        $this->assertNull($user->registered_ip);
        $this->assertFalse($user->personal_info['is_completed']);
        $this->assertNull($user->personal_info['birth_day']);
        $this->assertNull($user->personal_info['national_id']);
    }

    public function testUserFactoryMaleUser()
    {
        $user = User::factory()->male()->make();
        $this->assertEquals($user->gender, 'male');
    }

    public function testUserFactorySuerAdminUser()
    {
        $user = User::factory()->super()->make();
        $this->assertEquals($user->phone, env('SUPER_ADMIN_PHONE_NUMBER'));
    }

    public function testUserFactoryNotActiveUser()
    {
        $user = User::factory()->notActive()->make();
        $this->assertNull($user->first_name);
        $this->assertNull($user->last_name);
        $this->assertNull($user->gender);
        $this->assertNull($user->activated_at);
        $this->assertNull($user->registered_ip);
        $this->assertNull($user->last_online_at);
        $this->assertNull($user->email_verified_at);
        $this->assertEquals($user->is_active, 0);
        $this->assertEquals($user->is_new, 1);
    }

    public function testUserFactoryCompletedUser()
    {
        Carbon::setTestNow('2023-01-01 00:00:01');

        $user = User::factory()->completed()->make();
        $this->assertTrue($user->personal_info['is_completed']);
        $this->assertEquals($user->personal_info['birth_day'], now());
        $this->assertIsString($user->personal_info['national_id']);
    }

    public function testAttributes(): void
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
    }

    public function testCastHiddenFillable(): void
    {
        $user = User::factory()->make();
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

    public function testRelations(): void
    {
        $user        = User::factory()->make();
        $count       = rand(1, 10);
        $userfactory = User::factory();

        $user = $userfactory->has(UserIp::factory()->count($count), 'ips')->create();

        $this->assertCount($count, $user->ips);
        $this->assertTrue($user->ips()->first() instanceof UserIp);

        $user = $userfactory->has(Address::factory()->count($count), 'addresses')->create();
        $this->assertCount($count, $user->addresses);
        $this->assertTrue($user->addresses()->first() instanceof Address);

        $user = $userfactory
            ->has(UserPhoneVerification::factory(), 'phoneVerifications')
            ->create();
        $this->assertTrue($user->phoneVerifications instanceof UserPhoneVerification);

        $user = $userfactory
            ->has(Role::factory()->count($count), 'roles')
            ->create();
        $this->assertTrue($user->roles()->first() instanceof Role);

        $user = $userfactory
            ->has(Permission::factory()->count($count), 'permissions')
            ->create();
        $this->assertTrue($user->permissions()->first() instanceof Permission);


    }

    public function testHasPresenter()
    {
        $user = User::factory()->make();
        $this->assertTrue($user->present() instanceof Presenter);
        $this->assertTrue($user->present() instanceof UserApiPresenter);
    }

    public function testIdHashType()
    {
        $user = User::factory()->make();
        $this->assertFalse($user->incrementing);
        $this->assertEquals($user->getKeyType(), 'string');
    }

    public function testHasAndAddPermission()
    {
        $user       = User::factory()->create();
        $permission = Permission::factory()->create();

        $this->assertFalse(
            $user->hasPermission($permission->name)
        );

        $user = $user->addPermissions($permission->name);
        $this->assertTrue($user instanceof User);
        $user->load([ 'permissions' ]);

        $this->assertTrue(
            $user->hasPermission($permission->name)
        );
    }

    public function testRemovePeremissions()
    {
        $permissions = Permission::factory()->count(rand(1, 10))->create();
        $user        = User::factory()->create();
        $user->addPermissions(...$permissions->pluck('name'));

        $user = $user->removePermissions(...$permissions->pluck('name'));
        $this->assertTrue($user instanceof User);

        $user->load([ 'permissions' ]);
        foreach( $permissions as $permissions )
        {
            $this->assertFalse(
                $user->hasPermission($permissions->name)
            );
        }

    }

    public function testRefreshPeremissions()
    {
        $user        = User::factory()->create();
        $permissions = Permission::factory()->count(rand(1, 10))->create();
        $user->addPermissions(...$permissions->pluck('name'));

        $new_permissions  = Permission::factory()->count(rand(1, 10))->create();

        $user = $user->refreshPermissions(...$new_permissions->pluck('name'));

        $this->assertTrue($user instanceof User);

        $this->assertFalse(
            $user->permissions->contains('name', $permissions->first()->name)
        );

        $this->assertTrue(
            $user->permissions->contains('name', $new_permissions->first()->name)
        );

    }

    public function testHasAndAddRoles()
    {
        $user        = User::factory()->create();
        $role       = Role::factory()->create();

        $this->assertFalse(
            $user->hasRole($role->name)
        );

        $user = $user->addRoles($role->name);
        $this->assertTrue($user instanceof User);
        $user->load([ 'roles' ]);
        $this->assertTrue(
            $user->hasRole($role->name)
        );
    }

    public function testRemoveRoles()
    {
        $user        = User::factory()->create();
        $roles      = Role::factory()->count(rand(1, 10))->create();
        $user->addRoles(...$roles->pluck('name'));

        $user = $user->removeRoles(...$roles->pluck('name'));
        $this->assertTrue($user instanceof User);
        $user->load([ 'roles' ]);
        foreach( $roles as $role )
        {
            $this->assertFalse(
                $user->hasRole($role->name)
            );
        }

    }

    public function testRefreshRoles()
    {
        $user        = User::factory()->create();
        $roles      = Role::factory()->count(rand(1, 4))->create();
        $user->addRoles(...$roles->pluck('name'));

        $new_roles  = Role::factory()->count(rand(1, 4))->create();
        $user = $user->refreshRoles(...$new_roles->pluck('name'));
        $this->assertTrue($user instanceof User);

        $this->assertFalse(
            $user->roles->contains('name', $roles->first()->name)
        );
        $this->assertTrue(
            $user->roles->contains('name', $new_roles->first()->name)
        );

    }

    public function testMethodIsProfileCompleted()
    {
        $user        = User::factory()->isNew()->create();
        $this->assertFalse($user->isProfileCompleted());
        $user        = User::factory()->completed()->create();
        $this->assertTrue($user->isProfileCompleted());
    }





}
