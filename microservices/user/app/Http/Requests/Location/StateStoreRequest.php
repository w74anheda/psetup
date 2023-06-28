<?php
namespace App\Http\Requests\Location;


use Illuminate\Foundation\Http\FormRequest;

class StateStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => [ 'required', 'string', 'min:3', 'max:60', 'unique:states,name' ],
        ];
    }
}
