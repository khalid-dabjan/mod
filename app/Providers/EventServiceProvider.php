<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\LikesEvent' => [
            'App\Listeners\LikesNotification',
        ],
        'App\Events\CommentEvent' => [
            'App\Listeners\CommentNotification',
        ],
        'App\Events\UserFollowing' => [
            'App\Listeners\FollowNotification',
        ],
        'App\Events\UserSendMessage' => [
            'App\Listeners\UserSendMessageNotification',
        ],
        'App\Events\ContestEvents' => [
            'App\Listeners\ContestNotification',
        ],
        'App\Events\VerificationMail' => [
            'App\Listeners\VerificationMailListener',
        ],


    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
