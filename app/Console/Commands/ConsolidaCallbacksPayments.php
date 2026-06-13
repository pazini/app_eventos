<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ConsolidaCallbacksPayments extends Command
{
    protected $signature = 'consolida:callbacksPayments'; // Nome do comando
    protected $description = 'Consolida pagamentos recebidos via callbacks';

    public function handle()
    {
        $this->info('Iniciando consolidação de pagamentos...');

        $dadosFormatados = [];

        foreach (consolidaCallbacksPayments() as $uuid => $status)
        {
            $dadosFormatados[] = [$uuid, $status];
        }

        $this->table(['CALLBACK ID','STATUS'], $dadosFormatados);

        $this->info('Consolidação finalizada!');
    }
}
