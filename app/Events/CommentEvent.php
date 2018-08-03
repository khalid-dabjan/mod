<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Comment Model
     * @var null
     */
    public $comment = null;

    /**
     * Type of comment set or collection
     * @var string
     */
    public $type = null;

    /**
     * Create a new event instance.
     *
     * @param Model $comment
     * @param $type
     */
    public function __construct(Model $comment, $type)
    {
        $this->comment = $comment;
        $this->type = $type;
    }

}
