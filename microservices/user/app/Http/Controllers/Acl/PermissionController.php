<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\AttachPermissionToRoleRequest;
use App\Models\Role;
use Illuminate\Http\Response;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:permission.attach.to.role')->only('attachPermissionToRole');
    }

    public function attachPermissionToRole(Role $role, AttachPermissionToRoleRequest $request)
    {
        $role->addPermissions(... $request->permissions);

        return response(
            [ 'successfully attached' ],
            Response::HTTP_ACCEPTED
        );
    }
}
