<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ItemUpdatedEvent implements ShouldBroadcastNow
{
    use SerializesModels;

    public $item;
    public $human_name;
    public $qty;

    public function __construct(string $item, string $human_name, int $qty)
    {
        $this->item = $item;
        $this->human_name = $human_name;
        $this->qty = $qty ?? 0;
    }

    public function broadcastOn()
    {
        return ['item-updated-channel'];
    }

    public function broadcastAs()
    {
        return 'item-updated';
    }
  }
