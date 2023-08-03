<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function PHPSTORM_META\map;

class PassportCustomTokenCollection extends ResourceCollection
{
    public static $wrap = 'sessions';

    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
