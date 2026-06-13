<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class NotificaPagamentoLembreteCarne extends Command
{
    protected $signature = 'notificacao:lembreteCarne'; // Nome do comando
    protected $description = 'Envia lembrete para pagamentos';

    public function handle()
    {
        $this->info('Iniciando ...');

        $dadosFormatados = [];

        foreach (notificaPagamentoLembreteCarne() as $uuid => $notifica)
        {
            foreach ($notifica as $tipo => $email)
            {
                $dadosFormatados[] = [$uuid, $tipo, $email];
            }
        }

        $this->table(['SLIP ID','TIPO','EMAIL'], $dadosFormatados);

        $this->info('Finalizado!');
    }
}
