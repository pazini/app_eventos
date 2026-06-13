<?php

namespace App\Http\Livewire\Faturamento;

use App\Models\Faturamento;
use App\Models\FaturamentoPagamento;
use App\Models\ModEvent\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class FaturamentoTable extends Component
{
    //
    public $gerar_fatura;

    //
    public $situacao;
    public $situacao_lista;
    public $events;
    public $events_por_situacao;

    //
    public $organizador_id;
    public $busca_ano;
    public $busca_ano_lista;
    public $organizadores=[];

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedBuscaAno()
    {
        return redirect()->route('plataforma-faturamento',['buscaAno' => $this->busca_ano]);
    }

    public function mount(Request $request,$organizador_id=false)
    {
        $this->busca_ano_lista = Event::selectRaw('EXTRACT(YEAR FROM event_datetime_start) as year')
            ->distinct()
            ->orderBy('year', 'asc')
            ->pluck('year');

        if($request->input('buscaAno'))
            $this->busca_ano = $request->input('buscaAno');
        else
            $this->busca_ano = now()->format('Y');

        if(!in_array($this->busca_ano,$this->busca_ano_lista->toArray()))
        {
            $this->busca_ano = false;
        }

        $this->organizador_id = $organizador_id;
    }

    public function render()
    {
        //
        if($this->organizador_id ?? false)
        {
            $events = Event::with(['faturamento','faturamento.pagamentos','tickets'])->whereNotIn('status',['cancelado','suspenso'])->where('organizer_id',$this->organizador_id);
        }
        else
        {
            $events = Event::with(['faturamento','faturamento.pagamentos','tickets'])->whereNotIn('status',['cancelado','suspenso']);
        }

        //
        if(!empty($this->busca_ano ?? null))
        {
            $events = $events->whereYear('event_datetime_start', $this->busca_ano);
        }

        $this->events = $events->orderBy('event_datetime_start')->get();

        //
        foreach ($this->events as $event)
        {
            if ($event->organizer->customer->generate_invoice ?? false)
            {
                if($event->faturamento->pay_status ?? false)
                {
                    $events_por_situacao[$event->faturamento->pay_status][$event->id] = $event;
                }
                else
                {
                    $events_por_situacao['nao_faturado'][$event->id] = $event;
                }

                $this->organizadores[$event->organizer->id] = $event->organizer->organizer_name_full;
            }
        }

        if($this->organizadores ?? false) asort($this->organizadores);

        $this->situacao_lista = array_merge(['todos'], array_keys($events_por_situacao ?? []));

        // dd($events_por_situacao);

        return view('faturamento.faturamento')->layout('layouts.app-pep-auth');
    }

    public function updatedOrganizadorId()
    {
        return redirect()->route('plataforma-faturamento',['organizador_id' => $this->organizador_id]);
        // dd($this->organizador_id);
    }
}



