<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * User Model
     * @var null
     */
    protected $user = null;

    /**
     * Rest token
     * @var null
     */
    protected $token = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        //
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@modasti.com',"Modasti Support")->view('emails.reset', ['url' => route('_password.reset', ['token' => $this->token]), 'user' => $this->user]);
    }
}
