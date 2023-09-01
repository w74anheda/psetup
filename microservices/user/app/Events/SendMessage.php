<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public function __construct()
    {
        //
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('chating'),
        ];
    }


    public function broadcastAs()
    {
        return 'SendMessage';
    }


    public function broadcastWith()
    {
        return [ 'title' => 'This notification from ItSolutionStuff.com' ];
    }

    // public function broadcastQueue(): string
    // {
    //     return 'chatQueue';
    // }
}
