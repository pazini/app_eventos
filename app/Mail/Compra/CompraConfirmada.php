<?php

namespace App\Mail\Compra;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CompraConfirmada extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $uuid;
    public $dados;
    public $subject;
    public $reply;

    public function __construct($uuid,$reply=false)
    {
        $this->uuid = $uuid;

        $this->reply = ($reply) ? [$reply] : [];

        $this->dados = AppEventOrder::with(['payments','event','event.customer','event.organizer','itens','tickets'])->find($uuid);

        // dd(
        //     $this->dados->toArray(),
        // );

        // DEFINE
        if($this->dados->event->organizer->customer->name_short ?? false)
        {
            $prefixo = $this->dados->event->organizer->customer->name_short . ' ' . $this->dados->event->organizer->organizer_name;
        }
        else
        {
            $prefixo = $this->dados->event->organizer->organizer_name;
        }

        $this->subject = trim(mb_strtoupper('✅ ' . $prefixo . ' | ' . $this->dados->event->event_name . ' | COMPRA CONFIRMADA | ' . $this->dados->order_control));
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
            view: '_email.compra.compra-confirmada',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
