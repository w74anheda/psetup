<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class AddressUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'city_id'      => [ 'integer', 'exists:cities,id' ],
            'full_address' => [ 'string', 'min:5', 'max:200' ],
            'house_number' => [ 'integer' ],
            'unit_number'  => [ 'integer' ],
            'postalcode'   => [ 'digits:10' ],
            'latitude'     => [ 'between:-90,90' ],
            'longitude'    => [ 'between:-180,180' ]
            //Lat = Y Long = X
        ];
    }
}
