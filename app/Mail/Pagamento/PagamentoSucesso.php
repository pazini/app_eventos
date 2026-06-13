<?php

namespace App\Mail\Pagamento;

use App\Models\AppPayment\AppPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PagamentoSucesso extends Mailable implements ShouldQueue
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

        $this->dados = AppPayment::with(['order','order.event','order.event.customer','order.event.organizer','order.itens','order.tickets'])->find($uuid);

        // DEFINE
        if($this->dados->order->event->organizer->customer->name_short ?? false)
        {
            $prefixo = $this->dados->order->event->organizer->customer->name_short . ' ' . $this->dados->order->event->organizer->organizer_name;
        }
        else
        {
            $prefixo = $this->dados->order->event->organizer->organizer_name;
        }

        $this->subject = trim(mb_strtoupper($prefixo . ' | ' . $this->dados->order->event->event_name . ' | COMPRA CONFIRMADA | ' . $this->dados->order->order_control));
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
            view: '_email.pagamento.pagamento-sucesso',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
