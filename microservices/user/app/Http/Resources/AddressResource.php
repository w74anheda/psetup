<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    public static $wrap = 'address';

    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->resource->id,
            'user'         => new UserResource($this->whenLoaded('user')),
            'city'         => new CityResource($this->whenLoaded('city')),
            'full_address' => $this->resource->full_address,
            'house_number' => $this->resource->house_number,
            'unit_number'  => $this->resource->unit_number,
            'postalcode'   => $this->resource->postalcode,
            'point'        => [
                'latitude'  => $this->resource->latitude,
                'longitude' => $this->resource->longitude
            ],
        ];
    }
}
