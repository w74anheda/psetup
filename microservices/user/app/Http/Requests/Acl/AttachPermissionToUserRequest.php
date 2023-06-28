<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;

class AttachPermissionToUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user->phone == env('SUPER_ADMIN_PHONE_NUMBER') ? false : true;
    }


    public function rules(): array
    {
        return [
            'permissions'   => [ 'required', 'array' ],
            'permissions.*' => [ 'string', 'exists:permissions,name' ]
        ];
    }
}
