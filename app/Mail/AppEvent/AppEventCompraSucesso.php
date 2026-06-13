<?php

namespace App\Mail\AppEvent;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppEventCompraSucesso extends Mailable
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
        if(isset($this->order['customer']) && isset($this->order['customer']['prefix_url']))
        {
            $subject = mb_strtoupper($this->order['customer']['prefix_url'] . ' | ' . $this->order['event']['event_name'] . ' | COMPRA CONFIRMADA | ' . $this->order['order_control']);
        }
        else
        {
            $subject = mb_strtoupper($this->order['event']['event_name'] . ' | COMPRA CONFIRMADA | ' . $this->order['order_control']);
        }

        $this->subject($subject);

        return $this->markdown('notifications.AppEvent.AppEventCompraV2', ['data' => $this->order]);
    }
}
