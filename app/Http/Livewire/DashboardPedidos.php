<?php

namespace App\Http\Livewire;

use App\Http\Middleware\Authenticate;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\ModEvent\Event;
use App\Services\GenderPtBr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardPedidos extends Component
{
    //
    public $target_ref;
    public $target_slug;

    //
    public $target_id;
    public $view_status;

    //
    public $orders;
    public $statusList;

    //
    public $target;
    public $target_detalhe;
    public $target_tickets;
    public $pedidos;
    public $pedidosPayTypes;

    //
    protected $gender;

    public function getPedidos()
    {
        switch ($this->target_ref) {
            case 'evento':
            case 'app_event':
                //
                $this->target = Event::with(['ticketsTypes'])->find($this->target_id);
                $this->target_tickets = AppEventOrderTicket::with(['order','type'])->where('event_id',$this->target_id)->get();
                break;

            default:
                # code...
                break;
        }

        //
        if(!$this->target || ($this->target->event_slug ?? false) != $this->target_slug)
        {
            $this->target = false;
            session()->flash('error','Parametros da URL estão incorretos');
            return;
        }
    }

    public function mount($target_ref, $target_slug, $target_id, $view_status='paid')
    {
        $this->target_ref  = $target_ref;
        $this->target_id   = $target_id;
        $this->target_slug = $target_slug;
        $this->view_status = $view_status;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {

        switch ($this->view_status ?? false) {
            case 'participantes':
                $this->getPedidos();
                return view('livewire.dashboard.vendas.dashboard-vendas-participantes-v2')->layout('layouts.app-pep-guest');
            default:
                $this->getPedidos();
                return view('livewire.dashboard.dashboard-vendas')->layout('layouts.app-pep-guest');
        }

    }
}
