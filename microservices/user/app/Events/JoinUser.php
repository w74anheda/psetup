<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JoinUser implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct(public User $user)
    {
        //
    }


    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('online-users'),
        ];
    }


    public function broadcastAs()
    {
        return 'JoinUser';
    }


    public function broadcastWith()
    {
        return $this->user->toArray();
    }

    // public function broadcastQueue(): string
    // {
    //     return 'chatQueue';
    // }
}
