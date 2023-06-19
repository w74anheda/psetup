<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $super = Role::firstOrCreate([ 'name' => 'super' ]);

        $permissionsTag = collect([
            'role.list',
            'role.create',
            'role.delete',
            'role.attach.to.user',
            'permission.list',
            'permission.create',
            'permission.delete',
            'permission.attach.to.role',
            'permission.attach.to.user',


            
        ])
            ->map(fn($permission) => [ 'name' => $permission ])
            ->toArray();

        $permissions = [];
        foreach( $permissionsTag as $tag )
        {
            $permissions[] = Permission::firstOrcreate($tag)->pluck('name');
        }

        $super->addPermissions(...$permissions);

    }
}
