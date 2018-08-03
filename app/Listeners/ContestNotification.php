<?php

namespace App\Listeners;

use App\Events\ContestEvents;
use App\Model\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContestNotification
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
     * @param  ContestEvents $event
     * @return void
     */
    public function handle(ContestEvents $event)
    {

        $expired_message = $event->contest->title . ' has expired"';
        $winner_message = 'Winners is announced for ' . $event->contest->title;
        foreach ($event->contest->items as $item) {
            $notificationData = [
                'seen' => 0,
                'action' => 'contest.expired',
                'object_id' => $event->contest->id,
                'sender_id' => 0,
                'receiver_id' => $item->user_id,
                'message' => $expired_message
            ];
            Notification::create($notificationData);

            // Winner announced
            $notificationData = [
                    'seen' => 0,
                    'action' => 'contest.winner',
                    'object_id' => $event->contest->id,
                    'sender_id' => 0,
                    'receiver_id' =>  $item->user_id,
                    'message' => $winner_message
                ];
            Notification::create($notificationData);
        }

        // Winner Conditions
//        if (!empty($event->winners)) {
//            foreach ($event->winners as $winner) {
//                $notificationData = [
//                    'seen' => 0,
//                    'action' => 'contest.winner',
//                    'object_id' => $event->contest->id,
//                    'sender_id' => 0,
//                    'receiver_id' => $winner->user_id,
//                    'message' => $winner_message
//                ];
//                Notification::create($notificationData);
//            }
//
//        }


    }
}
