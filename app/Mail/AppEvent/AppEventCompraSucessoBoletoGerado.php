<?php

namespace App\Mail\AppEvent;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppEventCompraSucessoBoletoGerado extends Mailable
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
        // $this->subject($this->order['payment']['description'] . ' - BOLETO GERADO PARA PAGAMENTO - ' . $this->order['order_control']);

        $subject = mb_strtoupper($this->order['event']['event_name'] . ' | BOLETO GERADO | AGUARDANDO PAGAMENTO | ' . $this->order['order_control']);

        $this->subject($subject);

        return $this->markdown('notifications.AppEvent.AppEventCompraBoletoGeradoV2', ['data' => $this->order]);

        // return $this->markdown('notifications.AppEvent.AppEventCompraBoletoGerado', ['data' => $this->order]);
    }
}
