<?php

namespace App\Models\Traits;

trait HasCities
{
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
