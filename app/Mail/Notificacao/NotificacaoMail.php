<?php

namespace App\Mail\Notificacao;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $this->subject(trim(strtoupper(__($this->data['subject'] ?? 'Email sem Assunto'))));

        return $this->markdown('_email.notificacao.notificacao-email', ['data' => $this->data]);
    }
}
