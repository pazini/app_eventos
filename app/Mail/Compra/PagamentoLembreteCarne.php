<?php

namespace App\Mail\Compra;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentSlip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class PagamentoLembreteCarne extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $slipInstallmentId;
    public $slip;
    public $subject;
    public $reply;
    public $dados;

    public function __construct($slipInstallmentId)
    {
        $this->slipInstallmentId = $slipInstallmentId;

        $this->slip = AppPaymentSlip::with([
                'order',
                'order.event',
                'order.event.organizer',
                'order.event.organizer.customer',
            ])->find($slipInstallmentId);

        $this->dados = $this->slip;

        // dd(
        //     $this->slip->order->toArray(),
        //     $this->slip->toArray(),
        // );

        // DEFINE
        if($this->slip->order->event->organizer->customer->name_short ?? false)
        {
            $prefixo = $this->slip->order->event->organizer->customer->name_short . ' ' . $this->slip->order->event->organizer->organizer_name;
        }
        else
        {
            $prefixo = $this->slip->order->event->organizer->organizer_name;
        }

        if (($this->slip->installment_date_due ?? false) && (dataDiferencaDias($this->slip->installment_date_due) < 0))
        {
            $this->subject = trim(mb_strtoupper('🚨 ' . $prefixo . ' | ' . $this->slip->order->event->event_name . ' // ' . $this->slip->installment_description . ' LEMBRETE PAGAMENTO | ' . $this->slip->order->order_control));
        }
        else
        {
            $this->subject = trim(mb_strtoupper('📅 ' . $prefixo . ' | ' . $this->slip->order->event->event_name . ' // ' . $this->slip->installment_description . ' LEMBRETE PAGAMENTO | ' . $this->slip->order->order_control));
        }

        // SET REPLY
        $this->reply = [$this->slip->order->event->organizer->owner_email ?? ($this->slip->order->event->customer->comercial_contact_email ?? null)];
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
            view: '_email.compra.pagamento-lembrete',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
