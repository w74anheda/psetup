<?php

namespace App\Models\Traits;

use App\Models\City;

trait HasCities
{
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
