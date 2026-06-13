<?php

namespace App\Jobs\AppPayment;

use App\Mail\AppPayment\AppPaymentPagamentoBoletoGerado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificationAppPaymentPagamento implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $tries = 5; // TENTATIVAS

    private $data;
    private $mailTo;
    private $mailBcc;

    public function __construct($data, $mailTo, $mailBcc)
    {
        $this->data    = $data;
        $this->mailTo  = $mailTo;
        $this->mailBcc = $mailBcc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // PEGA PRIMEIRO NOME
        $this->data['nome'] = strtoupper(explode(" ",$this->data['order']['buyer_name'] ?? " ")[0]);

        Mail::to($this->mailTo)->bcc($this->mailBcc)->send(new AppPaymentPagamentoBoletoGerado($this->data));
    }
}
