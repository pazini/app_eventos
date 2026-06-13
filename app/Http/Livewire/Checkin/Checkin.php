<?php

namespace App\Http\Livewire\Checkin;

use App\Models\AppEvent\AppEvent;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\ModEvent\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkin extends Component
{
    public $target;
    public $ref_target;
    public $ref_target_slug;
    public $control;
    public $controlCheck;
    public $targetCheckin;
    //
    public $referer;

    public function concluirCheckin($controlCkeck)
    {
        if($controlCkeck != $this->controlCkeckNum)
        {
            session()->flash('error','Validação incorreta 😢');
            // $this->control = false;
            return;
        }

        $ticket = AppEventOrderTicket::where('ticket_control',$this->control)->first();
        $ticket->ticket_status = 'utilizado';
        $ticket->ticket_checkin_datetime = now()->format('Y-m-d H:i:s');
        $ticket->save();

        session()->flash('success','Validado com sucesso');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount(Request $request, $ref_target=false, $ref_target_slug=false)
    {
        $this->ref_target      = $ref_target;
        $this->ref_target_slug = $ref_target_slug;

        if($request->input('control'))
        {
            $this->control = strtoupper($request->input('control'));
        }

        $this->referer = sessionReferer() ?? false;
    }

    public function render()
    {
        if($this->control ?? false)
        {
            $explode               = explode('.',$this->control);
            $controlCkeckNum       = $explode[1] ?? $explode[0];
            $this->controlCkeckNum = substr($controlCkeckNum, 0, 2);

            switch ($this->ref_target) {
                case 'evento':
                case 'app_event':
                default:
                    $this->targetCheckin = AppEventOrderTicket::with('event')
                        ->where('ticket_control',$this->control)
                        ->first();
                    break;
            }

            if(!$this->targetCheckin || $this->targetCheckin->event->event_slug != $this->ref_target_slug)
            {
                $this->targetCheckin = false;
                session()->flash('error','Não temos dados para esse controle 😢');
            }

            return view('livewire.checkin.checkin-concluir')->layout('layouts.app-pep-guest');
        }

        if ($this->ref_target ?? false)
        {
            if ($this->ref_target_slug ?? false)
            {
                switch ($this->ref_target) {
                    case 'evento':
                    case 'app_event':
                    default:
                        $this->target = Event::where('event_slug',$this->ref_target_slug)->first();
                        break;
                }
            }

            return view('livewire.checkin.checkin-target')->layout('layouts.app-pep-guest');
        }

        return view('livewire.checkin.checkin-index')->layout('layouts.app-pep-guest');
    }
}
