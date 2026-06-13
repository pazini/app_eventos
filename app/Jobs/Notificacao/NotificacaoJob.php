<?php

namespace App\Jobs\Notificacao;

use App\Mail\Notificacao\NotificacaoMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotificacaoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5; // TENTATIVAS

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        // NOTIFICACAO EMAIL
        if($this->data['bcc'] ?? false)
        {
            Mail::to($this->data['to'])->bcc($this->data['bcc'])->send(new NotificacaoMail($this->data));
        }
        else
        {
            Mail::to($this->data['to'])->send(new NotificacaoMail($this->data));
        }
    }
}
