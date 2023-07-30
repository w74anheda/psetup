<?php

namespace Tests\Feature\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class PermissionTest extends TestCase
{

    public function testInsertData()
    {

        $permission = Permission::factory()
            ->has(User::factory())
            ->has(Role::factory())
            ->create();
        ;
        $this->assertDatabaseCount($permission->getTable(), 1);
        $this->assertDatabaseHas($permission->getTable(), $permission->getAttributes());
        $this->assertModelExists($permission);

        $this->assertTrue($permission->roles()->first() instanceof Role);
        $this->assertTrue($permission->users()->first() instanceof User);
    }

    public function testFactoryData()
    {
        $permission = Permission::factory()->make();
        $this->assertIsString($permission->name);
    }

    public function testAttributes(): void
    {
        $permission = Permission::factory()->make();
        $attributes = [ 'name' ];

        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $permission->getAttributes());
    }

    public function testCastHiddenFillable(): void
    {
        $permission = Permission::factory()->make();

        $this->assertSame(
            $permission->getCasts(),
            [
                'id' => 'int',
            ]
        );

        $this->assertSame(
            $permission->getHidden(),
            []
        );

        $this->assertSame(
            $permission->getFillable(),
            [ 'name' ]
        );

    }

    public function testRelations(): void
    {
        $count             = rand(1, 10);
        $permissionfactory = Permission::factory();

        $permissions = $permissionfactory->has(User::factory()->count($count))->create();
        $this->assertCount($count, $permissions->users);
        $this->assertTrue($permissions->users()->first() instanceof User);


        $permissions = $permissionfactory->has(Role::factory()->count($count))->create();
        $this->assertCount($count, $permissions->roles);
        $this->assertTrue($permissions->roles()->first() instanceof Role);

    }

    public function testTimestampWasFalse()
    {
        $permission = Permission::factory()->make();
        $this->assertFalse($permission->timestamps);
    }

    public function testHasAndAddRoles()
    {
        $role       = Role::factory()->create();
        $permission = Permission::factory()->create();

        $this->assertFalse(
            $permission->hasRole($role->name)
        );

        $permission = $permission->addRoles($role->name);
        $this->assertTrue($permission instanceof Permission);
        $permission->load([ 'roles' ]);
        $this->assertTrue(
            $permission->hasRole($role->name)
        );
    }

    public function testRemoveRoles()
    {
        $roles      = Role::factory()->count(rand(1, 10))->create();
        $permission = Permission::factory()->create();
        $permission->addRoles(...$roles->pluck('name'));

        $permission = $permission->removeRoles(...$roles->pluck('name'));
        $this->assertTrue($permission instanceof Permission);
        $permission->load([ 'roles' ]);
        foreach( $roles as $role )
        {
            $this->assertFalse(
                $permission->hasRole($role->name)
            );
        }

    }

    public function testRefreshRoles()
    {
        $roles      = Role::factory()->count(rand(1, 4))->create();
        $permission = Permission::factory()->create();
        $permission->addRoles(...$roles->pluck('name'));

        $new_roles  = Role::factory()->count(rand(1, 4))->create();
        $permission = $permission->refreshRoles(...$new_roles->pluck('name'));
        $this->assertTrue($permission instanceof Permission);

        $this->assertFalse(
            $permission->roles->contains('name', $roles->first()->name)
        );
        $this->assertTrue(
            $permission->roles->contains('name', $new_roles->first()->name)
        );

    }











}
