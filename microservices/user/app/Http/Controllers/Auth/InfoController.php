<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class InfoController extends Controller
{

    public function me(Request $request)
    {
        $user = $request->user();

        return response(
            $this->makeUserWithWithCombinedPermissions($user),
            Response::HTTP_OK
        );
    }

    private function getAllPermissionsCombineRolesPermissions(User $user)
    {
        $user->load([ 'permissions', 'roles.permissions' ]);
        $permissions          = $user->permissions->pluck('name');
        $roles_permissions    = $user->roles->map(fn($role) => $role->permissions()->pluck('name'))->flatten();
        $combined_permissions = $permissions->merge($roles_permissions);
        return [ ...$combined_permissions->unique()->toArray() ];
    }

    private function makeUserWithWithCombinedPermissions(User $user): array
    {
        $permissions = $this->getAllPermissionsCombineRolesPermissions($user);
        $user->makeHidden([ 'roles', 'permissions' ]);
        $user = array_merge($user->toArray(), [ 'permissions' => $permissions ]);
        return $user;
    }
}
