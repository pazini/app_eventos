<?php

namespace App\Mail\Compra;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PagamentoConfirmado extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $paymentId;
    public $payment;
    public $subject;
    public $reply;
    public $dados;

    public function __construct($paymentId,$reply=false)
    {
        $this->paymentId = $paymentId;

        $this->reply = ($reply) ? [$reply] : [];

        $this->payment = AppPayment::with(['order','order.event','order.event.customer','order.event.organizer'])->find($paymentId);

        $this->dados = $this->payment;

        // dd(
        //     $this->payment->slip->toArray(),
        //     $this->payment->toArray(),
        // );

        // DEFINE
        if($this->payment->order->event->organizer->customer->name_short ?? false)
        {
            $prefixo = $this->payment->order->event->organizer->customer->name_short . ' ' . $this->payment->order->event->organizer->organizer_name;
        }
        else
        {
            $prefixo = $this->payment->order->event->organizer->organizer_name;
        }

        if ($this->payment->slip ?? false)
        {
            $this->subject = trim(mb_strtoupper('💲 PAGAMENTO RECEBIDO | ' . $this->payment->order->event->event_name . ' // ' . $this->payment->slip->installment_description . ' | ' . $this->payment->order->order_control));
        }
        else
        {
            $this->subject = trim(mb_strtoupper('💲 PAGAMENTO RECEBIDO | ' . $this->payment->order->event->event_name . ' | ' . $this->payment->order->order_control));
        }
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
            view: '_email.compra.pagamento-confirmado',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
