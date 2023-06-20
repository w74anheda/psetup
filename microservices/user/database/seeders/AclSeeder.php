<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AclSeeder extends Seeder
{

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('users_roles')->truncate();
        DB::table('users_permissions')->truncate();
        DB::table('roles_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $user  = User::firstOrcreate([
            'phone' => env('SUPER_ADMIN_PHONE_NUMBER', '09035919877')
        ]);
        $super = Role::create([ 'name' => 'super' ]);

        $permissionsTag = collect([
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
        ])
            ->map(fn($permission) => [ 'name' => $permission ])
            ->toArray();

        Permission::insert($permissionsTag);
        $permissions = Permission::all()->pluck('name');

        $super->addPermissions(...$permissions);
        $user->addRoles($super->name);

    }
}
