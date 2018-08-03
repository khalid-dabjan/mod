<?php

namespace App\Events;

use App\Model\Contest;
use Illuminate\Queue\SerializesModels;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ContestEvents
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $contest = null;
    public $winners = null;

    public function __construct(Contest $contest, $winners)
    {
        $this->contest = $contest;
        $this->winners = $winners;
    }
}
