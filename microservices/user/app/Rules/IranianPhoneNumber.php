<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IranianPhoneNumber implements ValidationRule
{
    private $attribute;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->attribute = $attribute;
        !preg_match("/^(09([0-9]{1}[0-9]{1})([0-9]{7}))*$/", $value)
            ? $fail('phone number format invalid')
            : false;
    }
}
