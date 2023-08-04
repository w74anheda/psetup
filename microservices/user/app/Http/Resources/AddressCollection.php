<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AddressCollection extends ResourceCollection
{
    public static $wrap = 'addresses';

    public function CityCollection(Request $request): array
    {
        return $this->collection->toArray();
    }
}
