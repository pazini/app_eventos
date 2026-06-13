<?php

namespace App\Http\Livewire;

use App\Models\AppEvent\AppEventOrder;
use Livewire\Component;

class AppEventoIngresso extends Component
{
    public $event_slug;
    public $order_control;
    public $order_id;
    public $order_control_dv;
    //
    public $event;
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
    }

    public function render()
    {
        try
        {
            $this->order = AppEventOrder::with(['event','tickets'])->where('id',$this->order_id)->first();

            if($this->order ?? false)
            {
                if($this->order->order_control != $this->order_control)
                {
                    $this->order = false;
                }
            }

            if($this->order ?? false)
            {
                if ($this->order->tickets->count())
                {
                    $this->event        = $this->order->event;
                    $this->orderTickets = $this->order->tickets;
                }
                else
                {
                    session()->flash('error','Poxa, não localizamos nenhum voucher! 😢');
                    session()->flash('error_sub','entre em contato com o vendedor');
                }
            }
            else
            {
                session()->flash('error','Poxa, não localizamos essa compra! 😢');
                session()->flash('error_sub','entre em contato com o vendedor');
            }

        }
        catch (\Throwable $th)
        {
            session()->flash('status','Poxa, ainda não temos nada para te mostrar! 😢');
        }

        return view('livewire.app-evento-ingresso')->layout('layouts.app-pep-guest');
    }
}
