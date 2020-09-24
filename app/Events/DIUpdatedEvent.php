<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class DIUpdatedEvent implements ShouldBroadcastNow
{
    use SerializesModels;

    public $location;
    public $debug;

    public function __construct(string $location, string $debug)
    {
        $this->location = $location;
        $this->debug = $debug;
    }

    public function broadcastOn()
    {
        return ['item-updated-channel'];
    }

    public function broadcastAs()
    {
        return 'di-updated';
    }
  }
