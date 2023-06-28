<?php

namespace App\Http\Requests\Acl;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class RoleDeleteRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->role->name == 'super' ? false : true;
    }


    public function rules(): array
    {
        return [

        ];
    }
}
