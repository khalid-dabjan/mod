<?php

namespace App\Events;

use App\Model\Channel;
use Illuminate\Queue\SerializesModels;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserSendMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     *  Message Channel
     * @var Channel
     */
    public $channel;


    /**
     * Create a new event instance.
     *
     * @param Channel $channel
     */
    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }
}
