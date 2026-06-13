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

class AppEventClearReservaTemp extends Command
{
    protected $signature = 'appEvent:clearReservaTemp';

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

            $this->info('>> Buscando Reservas');

            $loop = [];

            $reservas = AppEventOrder::whereIn('status', listOrderStatusPendente())
                ->whereNotNull('reservation_expiration_date')
                ->where('reservation_expiration_date', '<', now())
                ->get(['id','order_control','status','reservation_expiration_date']);

            $this->info('>> Localizados: ' . $reservas->count());

            foreach ($reservas ?? [] as $key => $value)
            {
                if($value->reservation_expiration_date ?? false)
                {
                    $loop[$value->order_control] = [
                        'LOCALIZADOR' => $value->order_control,
                        'STATUS'      => null,
                        'ITEMS'       => null,
                        'TICKETS'     => null,
                    ];

                    DB::beginTransaction();

                    // ORDER
                    $order = AppEventOrder::with(['itens','tickets'])->find($value->id);
                    $order->status_old               = $order->status;
                    $order->status_old_datetime      = now();
                    $order->status                   = 'expired_order';
                    $order->order_cancel_datetime    = now()->format('Y-m-d H:i:s');
                    $order->order_cancel_description = 'Pedido cancelado - Ultrapassar o tempo da reserva ' . $value->reservation_expiration_date;
                    $order->save();

                    $loop[$value->order_control]['STATUS'] = "{$value->status} >> {$order->status}";

                    // ITEMS
                    if($order->itens->count() ?? false)
                    {
                        foreach ($order->itens as $key => $item)
                        {
                            $order->itens[$key]->item_status = 'expired';
                            $order->itens[$key]->save();
                        }

                        $loop[$value->order_control]['ITEMS'] = $order->itens->count();
                    }

                    // TICKETS
                    if($order->tickets->count() ?? false)
                    {
                        foreach ($order->tickets as $key => $ticket)
                        {
                            $order->tickets[$key]->delete();
                        }

                        $loop[$value->order_control]['TICKETS'] = $order->tickets->count();
                    }

                    DB::commit();
                }
            }

            //
            if(count($loop ?? []))
            {
                $this->table(['LOCALIZADOR','STATUS','ITEMS','TICKETS'], $loop);
            }

            $this->info('>> Sanitizados: ' . count($loop));

            $this->info('>> Concluído');

            $this->line('=============================================');

            return Command::SUCCESS;
        }
        catch (\Throwable $th)
        {
            $this->error('Error: ' . $th->getMessage());
        }
    }
}
