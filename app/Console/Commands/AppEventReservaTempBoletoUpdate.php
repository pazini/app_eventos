<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentCallback;
use App\Services\EventOrderClear;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AppEventReservaTempBoletoUpdate extends Command
{
    protected $signature = 'appEvent:reservaTempBoletoUpdate';

    protected $description = 'Executa rotina de limpeza de reservas temporárias de eventos';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try
        {
            $this->line('=============================================');

            // AJUSTES DATA BOLETO
            $r = AppEventOrder::whereIn('status', ['pending_boleto'])
                ->whereNotNull('reservation_expiration_date')
                // ->where('reservation_expiration_date', '<', now())
                ->get(['id','order_control','status','reservation_expiration_date','updated_at']);

            // PERCORRE E AJUSTA
            foreach ($r as $key => $value)
            {
                $dt = $value->updated_at->addDays(5);
                $value->reservation_expiration_date = $dt;
                $value->save();
            }

            $this->table(['id','order_control','status','reservation_expiration_date','updated_at'],$r->toArray());

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
