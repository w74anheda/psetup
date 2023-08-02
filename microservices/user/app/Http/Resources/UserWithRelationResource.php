<?php

namespace App\Http\Resources;

use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWithRelationResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'gender'        => $this->gender,
            'phone'         => $this->phone,
            'email'         => $this->email,
            'personal_info' => $this->personal_info,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at,
            'permissions'   => UserService::allPermissions($this->resource),
        ];
    }
}
