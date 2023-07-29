<?php

namespace Tests\Feature\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
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
        $count       = rand(1, 10);
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



}
