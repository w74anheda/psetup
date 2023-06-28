<?php

namespace App\Models\Traits;

use App\Models\City;

trait HasCity
{
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
