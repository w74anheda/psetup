<?php

namespace App\Http\Requests\Acl;

use Illuminate\Foundation\Http\FormRequest;

class RoleCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'min:3', 'max:30','unique:roles,name' ]
        ];
    }
}
