<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class AclSeeder extends Seeder
{
    use RefreshDatabase;

    public function run(): void
    {
        $permissions_name = [
            'role.list',
            'role.create',
            'role.delete',
            'role.update',
            'role.attach.to.user',
            'permission.list',
            'permission.attach.to.role',
            'permission.dettach.to.role',
            'permission.drop.all.of.role',
            'permission.attach.to.user',
            'permission.dettach.of.user',
            'permission.drop.all.of.user',
            'user.activation',
            'state.list',
            'state.create',
            'state.update',
            'state.delete',
            'state.delete.all',
            'city.list',
            'city.create',
            'city.update',
            'city.delete',
            'city.delete.all',
        ];

        $permissions_mapped = collect($permissions_name)
            ->map(fn($permission) => [ 'name' => $permission ])
            ->toArray();

        Permission::factory()->createMany($permissions_mapped);

        User::factory()
            ->has(Role::factory([ 'name' => 'super' ]))
            ->create([ 'phone' => env('SUPER_ADMIN_PHONE_NUMBER') ]);
        Role::first()->addPermissions(...$permissions_name);
    }
}
