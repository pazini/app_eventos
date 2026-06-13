<?php

namespace App\Console\Commands;

use App\Models\AppEvent\AppEventOrder;
use Illuminate\Console\Command;

class NotificacaoEmailEnvia extends Command
{
    protected $signature = 'notificacao:emailEnvia';

    protected $description = 'Envia emails - tb_notificacoes';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try
        {
            $this->line('=============================================');

            // // AJUSTES DATA BOLETO
            // $r = AppEventOrder::whereIn('status', ['pending_boleto'])
            //     ->whereNotNull('reservation_expiration_date')
            //     ->get(['id','order_control','status','reservation_expiration_date','updated_at']);

            // // PERCORRE E AJUSTA
            // foreach ($r as $key => $value)
            // {
            //     $dt = $value->updated_at->addDays(5);
            //     $value->reservation_expiration_date = $dt;
            //     $value->save();
            // }

            // $this->table([
            //     'id','order_control','status','reservation_expiration_date','updated_at'
            //     ], $r->toArray()
            // );

            $this->info('>> concluído');

            $this->line('=============================================');

            return Command::SUCCESS;
        }
        catch (\Throwable $th)
        {
            $this->error('Error: ' . $th->getMessage());
        }
    }
}
