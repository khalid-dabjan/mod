<?php

namespace App\Listeners;

use App\Events\VerificationMail;
use App\Mail\VerificationMail as MailEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class VerificationMailListener
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
     * @param  VerificationMail $event
     * @return void
     */
    public function handle(VerificationMail $event)
    {
        DB::table('verification_tokens')->insert([
            'user_id' => $event->user->id,
            'token' => $token = str_random(60),
        ]);
        Mail::to($event->user->email)->send(new MailEvent($event->user->email, $token,$event->user));
    }
}
