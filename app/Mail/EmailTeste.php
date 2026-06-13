<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class EmailTeste extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $title;
    public $textBody;
    public $reply;

    public function __construct($subject='ASSUNTO',$textBody='CORPO DO EMAIL',$reply=false)
    {
        $this->subject  = trim(mb_strtoupper($subject .' ['.now()->format('ymdHis').']'));
        $this->title    = trim(mb_strtoupper($subject));
        $this->textBody = $textBody;

        //
        $this->reply = ($reply) ? [$reply] : [];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
            replyTo: $this->reply,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: '_email.teste.email-teste',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
