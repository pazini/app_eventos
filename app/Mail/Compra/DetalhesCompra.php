<?php

namespace App\Mail\Compra;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DetalhesCompra extends Mailable
{
    use Queueable, SerializesModels;

    private $order;
    private $label;
    public  $subject;

    public function __construct($order=false,$label='DETALHES DO SEU PEDIDO')
    {
        $this->order = $order;
        $this->label = $label;
    }

    public function build()
    {
        $this->subject($this->label . ' - DETALHES DA COMPRA - ' . $this->order['order_control']);

        if(in_array($this->order['buyer_email'],['admin@empresateste.com','teste@proeventpay.com']))
            return $this->markdown('notifications.Compras.DetalhesCompraV1', ['data' => $this->order]);
        else
            return $this->markdown('notifications.Compras.DetalhesCompraV1', ['data' => $this->order]);
    }
}
