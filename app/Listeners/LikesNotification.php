<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\LikesEvent;
use App\Model\Notification;
use App\Model\Set;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LikesNotification
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
     * @param LikesEvent $event
     * @return bool
     */
    public function handle(LikesEvent $event)
    {
        if (empty($event->data['type'])) {
            return false;
        }
        if ($event->data['type'] == "set") {
            $this->addSetNotification($event);
        }
    }


    /**
     * Add set notification
     * @param LikesEvent $event
     * @return bool
     */
    private function addSetNotification(LikesEvent $event)
    {
        $set = Set::where('id', $event->data['object_id'])->first();
        // Must set Exists
        if (!$set) {
            return false;
        }

        // User Can't make notification for him self
        if ($set->user_id == $event->data['user_id']) {
            return false;
        }

        $message = fauth()->user()->first_name . ' ' .fauth()->user()->first_name.' likes on your Set';
        $notificationData = [
            'seen' => 0,
            'action' => 'set.like',
            'object_id' => $event->data['object_id'],
            'sender_id' => $event->data['user_id'],
            'receiver_id' => $set->user_id,
            'message' => $message
        ];
        Notification::create($notificationData);
    }
}
