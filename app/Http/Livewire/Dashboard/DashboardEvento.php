<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\ModEvent\Event;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class DashboardEvento extends Component
{
    //
    public $organizer;
    public $organizerId;
    public $target_ref='app_event';
    public $target_list;
    public $target_id;
    public $target;

    //
    public $questions_user_json;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($event_id = null)
    {
        // Se chegou via URL com UUID, resolve o evento e seta sessions
        if ($event_id) {
            $this->organizer   = sessionOrganizer();
            $this->organizerId = $this->organizer->id ?? false;

            $query = Event::where('id', $event_id);
            if ($this->organizerId) {
                $query->where('organizer_id', $this->organizerId);
            }

            $event = $query->first();

            if (! $event) {
                session()->flash('error', 'Evento não encontrado ou sem permissão de acesso.');
                return redirect()->route('dashboard-eventos');
            }

            if (! $this->organizerId) {
                sessionOrganizer($event->organizer_id);
                $this->organizerId = $event->organizer_id;
                $this->organizer   = sessionOrganizer();
            }

            sessionTargetRef('evento');
            sessionTargetId($event->id);
            sessionOrderIdClear();

            $this->target_id = $event->id;
            return;
        }

        // GET via session (fluxo legado: /evento sem UUID)
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
        $this->target_id   = sessionTargetId();

        // SE ORGANIZER / TARGET_ID
        if(!$this->organizerId || !$this->target_id)
            return redirect()->route('dashboard');

        // LIMPA ORDER ID
        sessionOrderIdClear();
    }

    public function render()
    {
        //
        if(!$this->target = Event::with([
            'place',
            'gatewayPay',
            'gatewayPay.appGateway',
            'creator',
            'ticketsTypes',
            'ticketsTypes.creator',
            'ticketsTypes.tickets',
        ])->where('organizer_id',$this->organizerId)->where('id',$this->target_id)->first())
            $this->alterarTarget();

        //
        if($this->target->questions_user_json ?? false)
        {
            $questions_user_json       = json_decode($this->target->questions_user_json ?? '{}', true);
            $this->questions_user_json = [];

            $this->questions_user_json = $questions_user_json['campos'] ?? [];
        }

        return view('livewire.dashboard.dashboard-evento')->layout('layouts.app-pep-auth');
    }

    public function alterarTarget()
    {
        $this->target     = false;
        $this->target_id  = false;
        //
        sessionClear('target');
        return redirect()->route('dashboard-eventos');
    }
}
