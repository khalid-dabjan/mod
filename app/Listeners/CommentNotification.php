<?php

namespace App\Listeners;

use App\Events\CommentEvent;
use App\Model\Notification;
use App\Model\Set;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentEvent $event
     * @return void
     */
    public function handle(CommentEvent $event)
    {
        if ($event->type == 'set') {
            $this->addSetCommentNotification($event);
        }
    }


    /**
     *
     * @param CommentEvent $event
     * @return bool
     */
    private function addSetCommentNotification(CommentEvent $event)
    {
        $set = Set::where('id', $event->comment->set_id)->first();
        // Must set Exists
        if (!$set) {
            return false;
        }

        // User Can't make notification for him self
        if (fauth()->id() == $set->user_id) {
            return false;
        }

        $message = fauth()->user()->first_name . ' ' .fauth()->user()->first_name. ' commented on your Set';
        // Add Notification row
        $notificationData = [
            'seen' => 0,
            'action' => 'set.comment',
            'object_id' => $event->comment->set_id,
            'sender_id' => fauth()->id(),
            'receiver_id' => $set->user_id,
            'message' => $message
        ];
        Notification::create($notificationData);
    }

}
