<?php

namespace App\Http\Requests\Auth;

use App\Rules\IranianNationalID;
use Illuminate\Foundation\Http\FormRequest;

class CompleteProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return ! $this->user()->isProfileCompleted();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'birth_day'   => [ 'required', 'string', 'date' ],
            'national_id' => [ 'required', 'string', 'digits:10', new IranianNationalID ],
        ];
    }
}
