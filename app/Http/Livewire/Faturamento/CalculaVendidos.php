<?php

namespace App\Http\Livewire\Faturamento;

use App\Models\ModEvent\Event;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CalculaVendidos extends Component
{
    public $eventId;
    public $totalizador;
    public $valorTeste;

    public function mount($eventId)
    {
        $this->totalizador = DB::table('app_events_orders_tickets')
            ->where('event_id', $this->eventId)
            ->whereIn('ticket_status', ['utilizado','disponivel'])
            ->select(DB::raw('SUM(event_ticket_price_paid) as total_amount'))
            ->get();
    }

    public function render()
    {
        return view('livewire.faturamento.calcula-vendidos');
    }
}
