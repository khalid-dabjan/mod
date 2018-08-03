<?php

namespace App\Listeners;

use App\Events\UserFollowing;
use App\Model\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FollowNotification
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
     * @param  UserFollowing $event
     * @return void
     */
    public function handle(UserFollowing $event)
    {

        $message = fauth()->user()->first_name . ' ' . fauth()->user()->first_name . '  follows your profile';
        $notificationData = [
            'seen' => 0,
            'action' => 'user.follow',
            'object_id' => fauth()->id(),
            'sender_id' => fauth()->id(),
            'receiver_id' => $event->id,
            'message' => $message
        ];
        Notification::create($notificationData);
    }
}
