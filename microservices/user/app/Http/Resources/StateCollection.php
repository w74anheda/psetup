<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StateCollection extends ResourceCollection
{
    public static $wrap = 'states';

    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
