<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\AttachPermissionToRoleRequest;
use App\Http\Requests\Acl\AttachPermissionToUserRequest;
use App\Http\Requests\Acl\DettachPermissionOFRoleRequest;
use App\Http\Requests\Acl\DettachPermissionOfUserRequest;
use App\Http\Requests\Acl\DropAllPermissionOfRoleRequest;
use App\Http\Requests\Acl\DropAllPermissionOfUserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Response;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:permission.list')->only('index');
        $this->middleware('can:permission.attach.to.role')->only('attachPermissionToRole');
        $this->middleware('can:permission.dettach.to.role')->only('dettachPermissionOfRole');
        $this->middleware('can:permission.drop.all.of.role')->only('dropAllPermissionOfRole');
        $this->middleware('can:permission.attach.to.user')->only('attachPermissionToUser');
        $this->middleware('can:permission.dettach.of.user')->only('dettachPermissionOfUser');
        $this->middleware('can:permission.drop.all.of.user')->only('dropAllPermissionOfUser');
    }

    public function index()
    {
        $permissions = Permission::all();

        return response(
            $permissions,
            Response::HTTP_ACCEPTED
        );
    }

    public function attachPermissionToRole(Role $role, AttachPermissionToRoleRequest $request)
    {
        $role->addPermissions(...$request->permissions);

        return response(
            [ 'successfully attached' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function dettachPermissionOfRole(Role $role, DettachPermissionOFRoleRequest $request)
    {
        $role->removePermissions(...$request->permissions);

        return response(
            [ 'successfully dettached' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function dropAllPermissionOfRole(Role $role, DropAllPermissionOfRoleRequest $request)
    {
        $role->refreshPermissions();

        return response(
            [ 'successfully dropped' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function attachPermissionToUser(User $user, AttachPermissionToUserRequest $request)
    {
        $user->addPermissions(...$request->permissions);

        return response(
            [ 'successfully attached' ],
            Response::HTTP_ACCEPTED
        );
    }

    public function dettachPermissionOfUser(User $user, DettachPermissionOfUserRequest $request)
    {
        $user->removePermissions(...$request->permissions);

        return response(
            [ 'successfully dettached' ],
            Response::HTTP_ACCEPTED
        );
    }


    public function dropAllPermissionOfUser(User $user, DropAllPermissionOfUserRequest $request)
    {
        $user->refreshPermissions();

        return response(
            [ 'successfully dropped' ],
            Response::HTTP_ACCEPTED
        );
    }

}
