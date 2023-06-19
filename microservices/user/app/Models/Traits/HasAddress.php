<?php

namespace App\Models\Traits;

use App\Models\Address;

trait HasAddress
{
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
