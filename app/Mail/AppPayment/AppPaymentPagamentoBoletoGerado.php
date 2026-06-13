<?php

namespace App\Mail\AppPayment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppPaymentPagamentoBoletoGerado extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $this->subject($this->data['order']['payment']['description'] . ' - BOLETO GERADO PARA PAGAMENTO - ' . $this->data['order']['order_control']);

        return $this->markdown('notifications.AppPayment.AppPaymentBoletoGeradoV1', ['data' => $this->data]);
    }
}
