<?php

namespace Tests\Feature\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleTest extends TestCase
{

    public function testInsertData()
    {
        $role = Role::factory()
            ->has(User::factory())
            ->has(Permission::factory())
            ->create();

        $this->assertDatabaseCount($role->getTable(), 1);
        $this->assertDatabaseHas($role->getTable(), $role->getAttributes());
        $this->assertModelExists($role);

        $this->assertTrue($role->users()->first() instanceof User);
        $this->assertTrue($role->permissions()->first() instanceof Permission);
    }
    public function testFactoryData()
    {
        $role =  Role::factory()->make();
        $this->assertIsString($role->name);
    }

    public function testAttributes(): void
    {
        $role = Role::factory()->make();
        $attributes = [ 'name' ];

        foreach( $attributes as $key ) $this->assertArrayHasKey($key, $role->getAttributes());
    }

    public function testCastHiddenFillable(): void
    {
        $role = Role::factory()->make();

        $this->assertSame(
            $role->getCasts(),
            [
                'id' => 'int',
            ]
        );

        $this->assertSame(
            $role->getHidden(),
            []
        );

        $this->assertSame(
            $role->getFillable(),
            [ 'name' ]
        );

    }

    public function testRelations(): void
    {
        $count             = rand(1, 10);
        $roleFactory = Role::factory();

        $role = $roleFactory->has(User::factory()->count($count))->create();
        $this->assertCount($count, $role->users);
        $this->assertTrue($role->users()->first() instanceof User);


        $role = $roleFactory->has(Permission::factory()->count($count))->create();
        $this->assertCount($count, $role->permissions);
        $this->assertTrue($role->permissions()->first() instanceof Permission);

    }

    public function testTimestampWasFalse()
    {
        $role = Role::factory()->make();
        $this->assertFalse($role->timestamps);
    }

    public function testHasAndAddPermission()
    {
        $role       = Role::factory()->create();
        $permission = Permission::factory()->create();

        $this->assertFalse(
            $role->hasPermission($permission->name)
        );

        $role = $role->addPermissions($permission->name);
        $this->assertTrue($role instanceof Role);
        $role->load([ 'permissions' ]);

        $this->assertTrue(
            $role->hasPermission($permission->name)
        );
    }

    public function testRemovePeremissions()
    {
        $role      = Role::factory()->create();
        $permissions = Permission::factory()->count(rand(1, 10))->create();
        $role->addPermissions(...$permissions->pluck('name'));

        $role = $role->removePermissions(...$permissions->pluck('name'));
        $this->assertTrue($role instanceof Role);

        $role->load([ 'permissions' ]);
        foreach( $permissions as $permission )
        {
            $this->assertFalse(
                $role->hasPermission($permission->name)
            );
        }

    }

    public function testRefreshPeremissions()
    {
        $role      = Role::factory()->create();
        $permissions = Permission::factory()->count(rand(1, 10))->create();
        $role->addPermissions(...$permissions->pluck('name'));

        $new_permissions  = Permission::factory()->count(rand(1, 10))->create();

        $role = $role->refreshPermissions(...$new_permissions->pluck('name'));

        $this->assertTrue($role instanceof Role);

        $this->assertFalse(
            $role->permissions->contains('name', $permissions->first()->name)
        );

        $this->assertTrue(
            $role->permissions->contains('name', $new_permissions->first()->name)
        );

    }





}
