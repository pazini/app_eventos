<?php

namespace App\Jobs\AppEvent;

use App\Mail\AppEvent\AppEventCompraSucesso;
use App\Mail\AppEvent\AppEventCompraSucessoBoletoGerado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificationAppEventCompra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $tries = 5; // TENTATIVAS

    private $order;
    private $mailTo;
    private $mailBcc;

    public function __construct($order, $mailTo, $mailBcc=false)
    {
        $this->order   = $order;
        $this->mailTo  = $mailTo;
        $this->mailBcc = $mailBcc;

        if(isset($this->order['payment']['pay_boleto_expiration_date']) && !empty($this->order['payment']['pay_boleto_expiration_date']))
        {
            $this->order['payment']['pay_boleto_expiration_date'] = date_format(date_create($this->order['payment']['pay_boleto_expiration_date']),"d/m/Y");
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // PEGA PRIMEIRO NOME
        $this->order['nome'] = strtoupper(explode(" ",$this->order['buyer_name'] ?? " ")[0]);

        if(in_array(strtoupper($this->order['payment']['status'] ?? false), ['PENDING_BOLETO']))
        {
            //
            if($this->mailBcc ?? false)
                Mail::to($this->mailTo)->bcc($this->mailBcc)->send(new AppEventCompraSucessoBoletoGerado($this->order));
            else
                Mail::to($this->mailTo)->send(new AppEventCompraSucessoBoletoGerado($this->order));
        }
        else
        {
            //
            if($this->mailBcc ?? false)
                Mail::to($this->mailTo)->bcc($this->mailBcc)->send(new AppEventCompraSucesso($this->order));
            else
                Mail::to($this->mailTo)->send(new AppEventCompraSucesso($this->order));

        }
    }
}
