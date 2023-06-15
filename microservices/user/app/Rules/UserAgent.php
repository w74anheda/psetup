<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Jenssegers\Agent\Agent;

class UserAgent implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $agent = new Agent();
        $agent->setUserAgent($value);

        !$agent->platform()
            ? $fail('User-Agent invalid!')
            : false;
    }
}
