<?php

namespace App\Http\Requests\Auth;

use App\Rules\UserAgent;
use Illuminate\Foundation\Http\FormRequest;

class RefreshAccessTokenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'refresh_token' => ['required', 'string'],
            'User-Agent' => [ 'required', 'string', new UserAgent],
        ];
    }

    public function validationData()
    {
        return [
            'User-Agent' => $this->header('User-Agent'),
            ...$this->all()
        ];
    }
}
