<?php

namespace App\Jobs\AppEvent;

use App\Mail\Compra\DetalhesCompra;
use App\Models\AppEvent\AppEventOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificationDetalhesCompra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $tries = 5; // TENTATIVAS

    private $target;
    private $orderId;
    private $order;
    private $mailTo;
    private $mailBcc;
    private $label;

    public function __construct($target, $orderId, $mailTo, $mailBcc)
    {
        $this->target  = $target;
        $this->orderId = $orderId;
        $this->mailTo  = $mailTo;
        $this->mailBcc = $mailBcc;

        switch ($this->target) {
            case 'event':
            case 'evento':
            case 'app_event':
                $this->order = AppEventOrder::with(['event','itens','payments'])->find($this->orderId);
                //
                if($this->order ?? false)
                {
                    $this->label = strtoupper($this->order->event->event_name);
                }
                else
                {
                    throw new \Exception("order_404:::" . $this->orderId, 404);
                }
                break;

            default:
                throw new \Exception("target_404:::" . $this->target, 404);
                return;
        }
    }

    public function handle()
    {
        // TO ARRAY
        $order = $this->order->toArray();

        // PEGA PRIMEIRO NOME
        $order['name'] = strtoupper(explode(" ",$order['buyer_name'] ?? " ")[0]);

        Mail::to($this->mailTo)->bcc($this->mailBcc)->send(new DetalhesCompra($order,$this->label));
    }
}
