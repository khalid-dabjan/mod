<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class VerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Verification email
     * @var null
     */
    public $email = null;

    /**
     * Verification token
     * @var null
     */
    public $token = null;

    /**
     * @var \App\User
     */
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $token,$user)
    {
        $this->email = $email;
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@modasti.com',"Modasti Support")->view('emails.verification', ['url' => route('verification.mail', [
            'token' => $this->token]),'user'=>$this->user]);
    }
}
