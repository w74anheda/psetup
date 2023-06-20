<?php

namespace App\Http\Requests\Acl;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->role->name == 'super' ? false : true;
    }


    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'min:3', 'max:30', "unique:roles,name,{$this->role->id},id" ]
        ];
    }
}
