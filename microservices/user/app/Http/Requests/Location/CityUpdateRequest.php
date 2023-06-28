<?php
namespace App\Http\Requests\Location;


use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'state_id' => [ 'integer', 'exists:states,id' ],
            'name'     => [ 'required', 'string', 'min:3', 'max:60', "unique:cities,name,{$this->city->id},id" ],
        ];
    }
}
