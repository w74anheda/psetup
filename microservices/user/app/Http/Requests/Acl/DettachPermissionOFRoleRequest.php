<?php

namespace App\Http\Requests\Acl;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class DettachPermissionOFRoleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->role->name == 'super' ? false : true;
    }


    public function rules(): array
    {
        return [
            'permissions'   => [ 'required', 'array' ],
            'permissions.*' => [ 'string', 'exists:permissions,name' ]
        ];
    }
}
