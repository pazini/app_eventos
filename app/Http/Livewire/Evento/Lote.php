<?php

namespace App\Http\Livewire\Evento;

use App\Models\CustomerOrganizationPlace;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventTicketType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class Lote extends Component
{
    //
    public $organizer;
    public $organizerId;
    public $target;
    public $target_id;
    public $target_ref = 'app_event';

    //
    public $ticket_type_id;
    public $listSalePeriodType;
    public $event_datetime_start;
    public $event_datetime_finish;

    //
    PUBLIC $ticket_name;
    PUBLIC $ticket_gerados;
    PUBLIC $ticket_description;
    PUBLIC $amount;
    PUBLIC $price;
    PUBLIC $sale_period_type;
    PUBLIC $sale_start_datetime;
    PUBLIC $sale_finish_datetime;
    PUBLIC $sale_amount_min;
    PUBLIC $sale_amount_max;
    PUBLIC $sale_label_title;
    PUBLIC $sale_label_btn;
    PUBLIC $lote_publico;

    //
    public $tickets_type;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($ticket_type_id=false)
    {
        // GET
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_id   = sessionTargetId();

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');

        // LIMPA ORDER ID
        sessionOrderIdClear();

        //
        if(!$this->target = Event::with(['ticketsTypes'])->where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
        {
            returnEventoDashboard('Evento não localizado','error');
        }

        // da($this->target);

        //
        $this->event_datetime_start  = $this->target->event_datetime_start;
        $this->event_datetime_finish = $this->target->event_datetime_finish;

        // START
        $this->listSalePeriodType   = ['data' => 'Data'];
        $this->amount               = $this->target->sales_amount_max;
        $this->sale_finish_datetime = \Carbon\Carbon::parse($this->target->event_datetime_start)->format('Y-m-d H:i:s');
        $this->sale_amount_min      = 1;
        $this->sale_amount_max      = 1;
        $this->ticket_name          = 'LOTE ÚNICO';
        $this->ticket_description   = 'Entrada ao evento';
        $this->sale_label_title     = 'INGRESSO';
        $this->sale_label_btn       = 'COMPRAR';
        $this->lote_publico         = '1'; // Default: visível

        // SE ENVIADO SLUG
        if($ticket_type_id ?? false)
        {
            // SE NAO EXISTIR TIIPO
            if (!$this->tickets_type = $this->target->ticketsTypes->where('id',$ticket_type_id)->first())
            {
                return returnEventoDashboard("Lote não localizado",'error');
            }

            //
            $this->ticket_gerados = $this->tickets_type->tickets->whereIn('ticket_status',ticketStatusCapacidade())->count() ?? 0;

            //
            $this->ticket_type_id = $ticket_type_id;

            // SET DADOS PARA ATUALIZAÇÃO
            $this->ticket_name          = $this->tickets_type->ticket_name;
            $this->ticket_description   = $this->tickets_type->ticket_description;
            $this->amount               = $this->tickets_type->amount;
            $this->price                = toMoneyDot($this->tickets_type->price);
            $this->sale_period_type     = $this->tickets_type->sale_period_type;
            $this->sale_start_datetime  = $this->tickets_type->sale_start_datetime->format('Y-m-d H:i:s');
            $this->sale_finish_datetime = $this->tickets_type->sale_finish_datetime->format('Y-m-d H:i:s');
            $this->sale_amount_min      = $this->tickets_type->sale_amount_min;
            $this->sale_amount_max      = $this->tickets_type->sale_amount_max;
            $this->sale_label_title     = $this->tickets_type->sale_label_title;
            $this->sale_label_btn       = $this->tickets_type->sale_label_btn;
            $this->lote_publico         = $this->tickets_type->lote_publico ? '1' : '0';

            // dd($this->sale_finish_datetime);
        }

        // SIMULA DADOS
        // $this->simula();
    }

    public function render()
    {
        return view('livewire.evento.lote')->layout('layouts.app-pep-auth');
    }

    public function simula()
    {

    }

    public function loteSubmit()
    {
        // return;

        $validateData = $this->validate([
            'ticket_name'          => ['required', 'string'],
            'ticket_description'   => ['required', 'string'],
            'amount'               => ['required', 'integer'],
            'price'                => ['required'],
            'sale_period_type'     => ['required', 'string'],
            'sale_start_datetime'  => ['required','date','before:sale_finish_datetime'],
            'sale_finish_datetime' => ['required','date','after:sale_start_datetime'],
            'sale_amount_min'      => ['required', 'integer'],
            'sale_amount_max'      => ['required', 'integer'],
            'sale_label_title'     => ['required', 'string'],
            'sale_label_btn'       => ['required', 'string'],
            'lote_publico'         => ['required', 'in:0,1'],
        ]);

        // VALIDA DATAS BASE EVENTO
        $this->validate([
            'sale_start_datetime'  => ['required','date','before:event_datetime_start'],
            'sale_finish_datetime' => ['required','date','before:event_datetime_finish'],
        ]);

        try
        {
            // APPEND
            $validateData['price']       = toMoneyInt($this->price);
            $validateData['ticket_slug'] = Str::slug($this->target->event_name . ' ' . $validateData['ticket_name']);
            $validateData['lote_publico'] = (bool) $validateData['lote_publico']; // Converte string para boolean

            //
            if((int) ($validateData['price'] ?? 0) != (int) ($this->tickets_type->price ?? 0))
            {
                if ($this->ticket_gerados ?? false)
                {
                    return session()->flash('error','Valor não pode ser alterado - existem cadastros para esse lote');
                }
            }

            // TRANSACTION
            DB::beginTransaction();

            //
            if($tickets_type = EventTicketType::find($this->tickets_type->id ?? null))
            {
                $tickets_type->update($validateData);
                session()->flash('success','Lote Alterado');
            }
            else
            {
                $validateData['visible'] = true;
                $this->target->ticketsTypes()->create($validateData);
                session()->flash('success','Lote Cadastrado');
            }

            // TRANSACTION FIM
            DB::commit();

            return redirect()->route('dashboard-evento');
        }
        catch (\Throwable $th)
        {
            // TRANSACTION ERROR
            DB::rollBack();

            // dd($th);

            return session()->flash('error',$th->getMessage());
        }
    }

    public function loteRemove($loteId)
    {
        try
        {
            if ($tickets_type = EventTicketType::with(['orders','tickets'])->find($loteId))
            {
                $tickets_type->visible = false;
                $tickets_type->save();
                session()->flash('success','Lote removido');
            }
            else
            {
                session()->flash('error','Lote não localizado');
            }

            return redirect()->route('dashboard-evento');
        }
        catch (\Throwable $th)
        {
            // dd($th);
            return session()->flash('error',$th->getMessage());
        }
    }
}
