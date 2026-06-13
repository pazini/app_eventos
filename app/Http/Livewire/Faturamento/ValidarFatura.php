<?php

namespace App\Http\Livewire\Faturamento;

use App\Models\CustomerOrganizer;
use App\Models\FaturamentoPagamento;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class ValidarFatura extends Component
{
    public $vencimentoDias=7;
    public $faturas;
    public $faturasVencidas;
    public $faturasVencidasBloqueio;
    public $faturas_pendentes=false;

    //
    public $bloquear;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        if(in_array((Route::currentRouteName() ?? null), ['evento-home1']))
            $this->bloquear = true;
        else
            $this->bloquear = false;
    }

    public function buscaFaturas()
    {
        $dataHoje           = now();
        $dataLimite         = now()->subDays($this->vencimentoDias);
        $dataLimiteBloqueio = now()->subDays($this->vencimentoDias * 2);

        if($this->faturas = FaturamentoPagamento::with(['faturamento','faturamento.event','faturamento.event.organizer'])->where('pay_data_vencimento','<',$dataHoje->format('Y-m-d 00:00:00'))->whereNotIn('pay_status',['realizado'])->get())
        {
            $this->faturasVencidas         = $this->faturas->where('pay_data_vencimento','<',$dataLimite->format('Y-m-d 00:00:00'));
            $this->faturasVencidasBloqueio = $this->faturas->where('pay_data_vencimento','<',$dataLimiteBloqueio->format('Y-m-d 00:00:00'));
            
            //
            if(isAdmin() || (auth()->user()->organizers->pluck('id') ?? false))
            {
                $customersFaturar = ['77376273-91fd-4137-a984-45195ebdd6cf'];
                $userOrganizers   = auth()->user()->organizers->pluck('id')->toArray() ?? [];
                $userCustomersIds = CustomerOrganizer::whereIn('id',$userOrganizers)->pluck('customer_id')->toArray();
                
                //
                if(count($userCustomersIds ?? []))
                {
                    foreach ($userCustomersIds as $customerId)
                    {
                        if(in_array($customerId,$customersFaturar))
                        {
                            // SE BLOQEUIO
                            if($this->faturasVencidasBloqueio->count() ?? false)
                            {
                                if($this->bloquear ?? false)
                                {
                                    session()->flash('error','Ops, plataforma suspensa temporariamente para novos pedidos deste cliente');
                                }
                            }
                    
                            return $this->faturasVencidas->count() ? true : false;
                        }
                    }
                }
            }
        }

        return false;
    }

    public function render()
    {
        if($this->buscaFaturas() ?? false)
        {
            // GARANTIA DE ABERTURA DO MODAL
            $this->faturas_pendentes = true;

            session()->flash('faturas_pendentes','Existem ' . ($this->faturasVencidas->count() ?? 0) . ' faturas em aberto');
            session()->flash('faturas_pendentes_sub','Ultrapassando ' . ($this->vencimentoDias * 2) . ', as vendas serão suspensas!');
        }

        return view('faturamento.validar-faturas')->layout('layouts.guest');
    }
}
