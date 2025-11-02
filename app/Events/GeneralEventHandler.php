<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;      // => Queue
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;   // => Immediately
use Illuminate\Foundation\Events\Dispatchable;
// use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Queue\SerializesModels;

class GeneralEventHandler implements ShouldBroadcast{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $datas;

    public function __construct($datas){
        $this->datas = $datas;
    }

    public function broadcastOn() : array{
        return [
            new Channel('generalChannel'),
        ];
    }

    public function broadcastAs() : string{
        return 'generalEvent';
    }
}
