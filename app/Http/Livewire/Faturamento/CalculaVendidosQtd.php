<?php

namespace App\Http\Livewire\Faturamento;

use App\Models\ModEvent\Event;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CalculaVendidosQtd extends Component
{
    public $eventId;
    public $totalizador;
    public $valorTeste;

    public function mount($eventId)
    {
        $this->totalizador = DB::table('app_events_orders_tickets')
            ->where('event_id', $this->eventId)
            ->whereIn('ticket_status', ['utilizado','disponivel'])
            ->select(DB::raw('count(*) as total_qtd'))
            ->get();
    }

    public function render()
    {
        return view('livewire.faturamento.calcula-vendidos-qtd');
    }
}
