<?php
namespace App\Http\Requests\Location;


use Illuminate\Foundation\Http\FormRequest;

class AddressStoreRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'city_id'      => [ 'required', 'integer', 'exists:cities,id' ],
            'full_address' => [ 'required', 'string', 'min:5', 'max:200' ],
            'house_number' => [ 'required', 'integer' ],
            'unit_number'  => [ 'required', 'integer' ],
            'postalcode'   => [ 'required', 'digits:10' ],
            'latitude'     => [ 'required', 'between:-90,90' ],
            'longitude'    => [ 'required', 'between:-180,180' ]
            //Lat = Y Long = X
        ];
    }
}
