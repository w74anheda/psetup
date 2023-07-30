<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PersonalInfoCast implements CastsAttributes
{

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $value = json_decode($value, true);
        return [
            'is_completed' => $value['is_completed'] ?? false,
            'birth_day'    =>
            ($value['birth_day'] ?? null)
            ? Carbon::parse($value['birth_day'])->format('Y-m-d') : null,
            'national_id'  => $value['national_id'] ?? null,
        ];
    }


    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {

        return json_encode([
            'is_completed' => $value['is_completed'] ?? false,
            'birth_day'    => $value['birth_day'] ?? null,
            'national_id'  => $value['national_id'] ?? null,
        ]);
    }
}
