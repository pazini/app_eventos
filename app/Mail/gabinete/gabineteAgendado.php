<?php

namespace App\Mail\gabinete;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class gabineteAgendado extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    public function __construct($emailData = false)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.gabinetes.gabinete-agendado', ['emailData' => $this->emailData]);
    }
}
