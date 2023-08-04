<?php

namespace App\Http\Resources;

use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';


    public function __construct(public $resource, public bool $loadPermissions = false)
    {
        parent::__construct($resource);
    }


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
            'permissions'   => new PermissionCollection($this->whenLoaded('permissions')),
        ];
    }
}
