<?php

namespace App\Events;

use App\Cheque;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ChequeCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Cheque $cheque;

    /**
     * Create a new event instance.
     *
     * @param Cheque $cheque
     * @return void
     */
    public function __construct(Cheque $cheque)
    {
        $this->cheque = $cheque;
    }
}
