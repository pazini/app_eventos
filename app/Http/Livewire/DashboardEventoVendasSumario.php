<?php

namespace App\Http\Livewire;

use App\Models\AppEvent\AppEventOrder;
use App\Models\ModEvent\Event;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DashboardEventoVendasSumario extends Component
{
    public $debug;
    public $target_ref;
    public $target_id;

    //
    public $referer;
    public $target;
    public $pedidos;
    public $pedidosPayTypes;

    public function mount(Request $request, $event_id = null)
    {
        // Resolução via UUID na URL
        if ($event_id) {
            $org   = sessionOrganizer();
            $orgId = $org->id ?? null;
            $query = \App\Models\ModEvent\Event::where('id', $event_id);
            if ($orgId) $query->where('organizer_id', $orgId);
            $ev = $query->first();
            if (! $ev) {
                session()->flash('error', 'Evento não encontrado ou sem permissão.');
                return redirect()->route('dashboard-eventos');
            }
            if (! $orgId) sessionOrganizer($ev->organizer_id);
            sessionTargetRef('evento');
            sessionTargetId($ev->id);
            sessionOrderIdClear();
        }

        // DEBUG
        if(($request ?? false) && $request->input('debug'))
            $this->debug = sessionDebug(true);
        else
            $this->debug = sessionDebug(false);

        $this->target_ref  = sessionTargetRef();
        $this->target_id   = sessionTargetId();

        // GET REFERER
        $this->referer = sessionReferer();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        if($this->target = Event::with(['orders','orders.payments','orders.itens','orders.itens.ticketType'])->find($this->target_id))
        {
            $this->orders = $this->target->orders->whereIn('status',listOrderStatusPaid());

            $dataColumn   = ['data','Qtd.','total_valor_bruto','descontos'];
            $payTypesUsed = [];
            $tempOrders   = [];
            $totais       = [
                'status'          => [],
                'vendidos_qtd'    => 0,
                'vendidos_valor'  => 0,
                'descontos_valor' => 0,
            ];

            foreach ($this->orders ?? [] as $orderValues)
            {
                $orderDate       = $orderValues->created_at->format('Y-m-d');
                $orderItensQtd   = $orderValues->itens->count() ?? 0;
                $orderItensValor = $orderValues->itens->sum('item_amount') ?? 0;
                $orderDesconto   = $orderValues->code_promo_discount_amount ?? 0;

                if(!isset($tempOrders[$orderDate]))
                {
                    $tempOrders[$orderDate]['data']                  = $orderValues->created_at;
                    $tempOrders[$orderDate]['total_vendidos']        = 0;
                    $tempOrders[$orderDate]['total_valor_bruto']     = 0;
                    $tempOrders[$orderDate]['total_valor_descontos'] = 0;
                }

                //
                $tempOrders[$orderDate]['total_vendidos']        += $orderItensQtd;
                $tempOrders[$orderDate]['total_valor_bruto']     += $orderItensValor;
                $tempOrders[$orderDate]['total_valor_descontos'] += $orderDesconto;

                //
                $totais['vendidos_qtd']    += $orderItensQtd;
                $totais['vendidos_valor']  += $orderItensValor;
                $totais['descontos_valor'] += $orderDesconto;

                // PERCORRE PAGAMENTOS
                $orderPayments = $orderValues->payments->whereIn('status', ['paid','paid_cupom_full','paid_after_deadline']);
                //
                foreach ($orderPayments ?? [] as $paymentValues)
                {
                    // DEFINE PAY TYPE
                    $itemPayType = $paymentValues->pay_type ?? 'nd';

                    // INICIA
                    if(!isset($payTypesUsed[$itemPayType]))
                    {
                        $payTypesUsed[$itemPayType] = $itemPayType;
                        //
                        $totais['status'][$orderValues->status] = 0;
                        $totais['types'][$itemPayType]['qtd']   = 0;
                        $totais['types'][$itemPayType]['valor'] = 0;
                    }

                    // APPEND
                    $totais['status'][$orderValues->status] += 1;

                    // SE NAO EXISTE AINDA
                    if (!isset($tempOrders[$orderDate]['pagamentos'][$itemPayType]))
                    {
                        $tempOrders[$orderDate]['pagamentos'][$itemPayType]['qtd']   = 0;
                        $tempOrders[$orderDate]['pagamentos'][$itemPayType]['valor'] = 0;
                    }

                    // CALCULA QTD
                    $itemPayTypeQtd = ((int) $orderItensQtd ?? 0) / ((int) $orderPayments->count() ?? 1); // DIVISAO POR ZERO = ERRO
                    $itemPayTypeQtd = number_format($itemPayTypeQtd, 2, '.', '');

                    // APPEND
                    $tempOrders[$orderDate]['pagamentos'][$itemPayType]['qtd']   += $itemPayTypeQtd ?? 0;
                    $tempOrders[$orderDate]['pagamentos'][$itemPayType]['valor'] += $paymentValues->value_liquid ?? 0;

                    // APPEND
                    $totais['types'][$itemPayType]['qtd']   += $itemPayTypeQtd ?? 0;
                    $totais['types'][$itemPayType]['valor'] += $paymentValues->value_liquid ?? 0;
                }
            }

            // PERCORRE DATA x TIPOS EXISTENTES
            foreach ($tempOrders as $orderKey => $orderValues)
            {
                foreach ($payTypesUsed as $payTypesUsedKey => $payTypesUsedValue)
                {
                    $tempOrders[$orderKey][$payTypesUsedKey] = '--';
                    //
                    if($orderValues['pagamentos'][$payTypesUsedKey] ?? false)
                    {
                        $tempOrders[$orderKey][$payTypesUsedKey] = toMoney($orderValues['pagamentos'][$payTypesUsedKey]['valor'] ?? 0) . ' (' . ($orderValues['pagamentos'][$payTypesUsedKey]['qtd'] ?? 0) .')';
                    }
                }
            }

            // PREPARA DADOS
            $this->pedidos = [
                'columns'         => array_values(array_merge($dataColumn, $payTypesUsed)),
                'columnsPayTypes' => $payTypesUsed,
                'data'            => $tempOrders,
                'totais'          => $totais,
            ];
        }

        return view('livewire.dashboard.dashboard-vendas-sumario')->layout('layouts.app-pep-auth');
    }
}
