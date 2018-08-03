<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mesg = '';
    public $user = null;
    public $report = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$report)
    {
        //
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@modasti.com','Modasti Support ')->view('emails.report', ['user' => $this->user,'format'=>$this->report->format]);
    }
}
