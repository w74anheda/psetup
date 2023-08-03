<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassportCustomTokenResource extends JsonResource
{
    public static $wrap = 'session';

    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->resource->id,
            'os'         => $this->resource->present()->os,
            'browser'    => $this->resource->present()->browser,
            'ip_address' => $this->resource->ip_address,
            'created_at' => $this->resource->created_at,
            'expires_at' => $this->resource->expires_at,
            'current'    => $this->resource->current
        ];
    }
}
