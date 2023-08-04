<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
    public static $wrap = 'cities';

    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
