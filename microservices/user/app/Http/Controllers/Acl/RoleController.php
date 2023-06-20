<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use App\Http\Requests\Acl\RoleCreateRequest;
use App\Http\Requests\Acl\RoleDeleteRequest;
use App\Http\Requests\Acl\RoleUpdateRequest;
use App\Models\Role;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:role.list')->only('index');
        $this->middleware('can:role.create')->only('store');
        $this->middleware('can:role.update')->only('update');
    }


    public function index()
    {
        $roles = Role::all();

        return response(
            $roles,
            Response::HTTP_OK
        );
    }

    public function store(RoleCreateRequest $request)
    {
        $role = Role::create($request->only([ 'name' ]));
        return response(
            $role,
            Response::HTTP_ACCEPTED
        );
    }

    public function update(Role $role, RoleUpdateRequest $request)
    {

        $role->update([ 'name' => $request->name ]);

        return response(
            $role,
            Response::HTTP_ACCEPTED
        );
    }


    public function destroy(Role $role, RoleDeleteRequest $request)
    {

        $role->delete();

        return response(
            [ 'successfully deleted' ],
            Response::HTTP_ACCEPTED
        );
    }


}
