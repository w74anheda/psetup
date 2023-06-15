<?php

namespace App\Http\Requests\Auth;

use App\Rules\IranianPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class LoginPhoneNumberRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'phone' => [ 'required', 'string', new IranianPhoneNumber ],
        ];
    }

}
