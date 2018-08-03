<?php

namespace App\Listeners;

use App\Events\UserSendMessage;
use App\Model\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSendMessageNotification
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
     * @param  UserSendMessage $event
     * @return void
     */
    public function handle(UserSendMessage $event)
    {
        $message = fauth()->user()->first_name . ' ' . fauth()->user()->last_name . '  send message for you';
        $receiver_id = $event->channel->sender_id == fauth()->id() ? $event->channel->receiver_id : $event->channel->sender_id;
        $notificationData = [
            'seen' => 0,
            'action' => 'messages.send',
            'object_id' => $event->channel->id,
            'sender_id' => fauth()->id(),
            'receiver_id' => $receiver_id,
            'message' => $message
        ];
        Notification::create($notificationData);
    }
}
