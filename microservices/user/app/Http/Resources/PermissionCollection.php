<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use function PHPSTORM_META\map;

class PermissionCollection extends ResourceCollection
{
    public static $wrap = 'permissions';

    public function toArray(Request $request): array
    {
        return $this->collection->toArray();
    }
}
