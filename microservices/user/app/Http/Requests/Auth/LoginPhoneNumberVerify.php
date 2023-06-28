<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Models\UserPhoneVerification;
use App\Rules\UserAgent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginPhoneNumberVerify extends FormRequest
{

    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        $this->request->add([
            'user' => (UserPhoneVerification::where([ 'code' => $this->code, 'hash' => $this->hash ])->first())->user
        ]);
        return true;
    }

    public function rules(): array
    {
        $code = $this->code;
        $user = UserPhoneVerification::where(['hash'=>$this->hash,'code'=>$code])->first()->user??null;
        return [
            'code' => [ 'required', 'digits_between:1,128' ],
            'hash' => [
                'required',
                'uuid',
                Rule::exists('user_phone_verifications', 'hash')
                    ->where(
                        function ($query) use ($code)
                        {
                            $query->where('code', $code)->where('expire_at', '>', date('Y-m-d H:i:s'));
                        }
                    )
            ],
            'first_name' => [
                'string','min:3','max:60',
                Rule::requiredIf(fn () => !($user->is_active??null))
            ],
            'last_name' => [
                'string','min:3','max:60',
                Rule::requiredIf(fn () => !($user->is_active??null))
            ],
            'gender'=> [
                'string',Rule::in(User::GENDERS),
                Rule::requiredIf(fn () => !($user->is_active??null))
            ],
            'User-Agent' => [ 'required', 'string', new UserAgent],
            // 'password_confirm' => ['required', 'same:password'],
        ];
    }

    public function validateResolved()
    {

        $validator = $this->getValidatorInstance();

        if($validator->fails())
        {
            $this->failedValidation($validator);
        }

        if(!$this->passesAuthorization())
        {
            $this->failedAuthorization();
        }
    }

    public function validationData()
    {
        return [
            'User-Agent' => $this->header('User-Agent'),
            ...$this->all()
        ];
    }

}
