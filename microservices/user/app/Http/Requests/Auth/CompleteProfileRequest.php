<?php

namespace App\Http\Requests\Auth;

use App\Rules\IranianNationalID;
use Illuminate\Foundation\Http\FormRequest;

class CompleteProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        return ! $this->user()->isProfileCompleted();
    }


    public function rules(): array
    {
        return [
            'birth_day'   => [ 'required', 'string', 'date' ],
            'national_id' => [ 'required', 'numeric', 'digits:10', new IranianNationalID ],
        ];
    }
}
