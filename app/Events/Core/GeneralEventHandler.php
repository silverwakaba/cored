<?php

namespace App\Events\Core;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;      // => Queue
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;   // => Immediately
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;  // => Only broadcast if db trans is success/committed
use Illuminate\Queue\SerializesModels;

// For future me: If you reuse this event handler all over in different places, it might be considered tight coupling, and it isn't good.
// Consider creating different events for different purposes, alongside with their listeners if you need to process the data later on.
// Ref: https://www.perplexity.ai/search/i-have-several-controller-that-VJtjOwZ6TfinMvcvzT.M.w#1
class GeneralEventHandler implements ShouldBroadcast, ShouldDispatchAfterCommit{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $datas;

    public function __construct($datas = null, $event = null){
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
