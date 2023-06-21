<?php
namespace App\Http\Requests\Location;


use Illuminate\Foundation\Http\FormRequest;

class CityStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'state_id' => [ 'required', 'integer', 'exists:states,id' ],
            'name'     => [ 'required', 'string', 'min:3', 'max:60', 'unique:cities,name' ],
        ];
    }
}
