<?php

namespace App\Mail\AppPayment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppEventPagamentoBoletoGerado extends Mailable
{
    use Queueable, SerializesModels;

    private $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order=false)
    {
        $this->order = $order;
    }

    public function build()
    {
        $this->subject($this->order['payment']['description'] . ' - BOLETO GERADO PARA PAGAMENTO - ' . $this->order['order_control']);

        return $this->markdown('notifications.AppEvent.AppPaymentBoletoGeradoV1', ['data' => $this->order]);
    }
}
