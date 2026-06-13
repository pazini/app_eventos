<?php

namespace App\Http\Livewire;

use App\Models\AppEvent\AppEventOrder;
use Livewire\Component;

class Dashboard extends Component
{
    public $event_slug;
    public $order_control;
    public $order_id;
    public $order_control_dv;
    //
    public $order;
    public $orderTickets;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($order_control, $order_id, $order_control_dv=false)
    {
        $this->order_control    = $order_control;
        $this->order_id         = $order_id;
        $this->order_control_dv = $order_control_dv;

        // dd(
        //     $this->event_slug,
        //     $this->order_control,
        //     $this->order_id,
        //     $this->order_control_dv,
        // );
    }

    public function render()
    {
        if($this->order = AppEventOrder::with(['event','tickets'])->find($this->order_id))
        {
            if($this->order->order_control != $this->order_control)
            {
                $this->order = false;
            }
        }

        if($this->order ?? false)
        {
            if ($this->order->tickets->count()) {

                $this->orderTickets = $this->order->tickets;

            } else {
                session()->flash('error','Nenhum ingresso localizado');
                session()->flash('error_sub','Verifique os dados informados');
            }
        }
        else
        {
            session()->flash('error','Nenhum pedido de ingressos foi localizado');
            session()->flash('error_sub','Verifique os dados informados');
        }

        // dd($this->order->event->toArray());

        return view('livewire.app-evento-ingresso')->layout('layouts.home');
    }
}
