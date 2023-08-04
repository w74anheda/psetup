<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public static $wrap = 'city';

    public function toArray(Request $request): array
    {
        return [
            'id'    => $this->resource->id,
            'name'  => $this->resource->name,
            'state' => new StateResource($this->whenLoaded('state'))
        ];
    }
}
