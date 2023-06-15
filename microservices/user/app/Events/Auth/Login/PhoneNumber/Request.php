<?php

namespace App\Events\Auth\Login\PhoneNumber;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Jobs\Auth\Login\PhoneNumber\SendVerificationCode;
use Illuminate\Support\Facades\Bus;

class Request
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public User $user)
    {
        Bus::chain([
            new SendVerificationCode($user)
        ])->dispatch();
    }
}
