<?php

namespace App\Http\Livewire;

use App\Jobs\AppPayment\NotificationAppPaymentPagamento;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventBudget;
use App\Models\ModEvent\EventBudgetItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use stdClass;
use Symfony\Component\HttpFoundation\Request;

class DashboardFinanceiroGestaoOrcamentaria extends Component
{
    //
    public $debug=false;
    public $target_ref;
    public $target_id;
    public $view_type;
    //
    public $target_list;
    public $target_list_ref;
    //
    public $action;
    public $target;

    //
    public $organizer;
    public $organizerId;

    // GESTAO ORCAMENTARIA
    public $alterar_div = false;
    public $modal_budgetAdd = false;
    public $modal_budgetAlt = false;
    public $modal_budgetRem = false;
    public $modal_budgetItemAdd = false;
    public $modal_budgetItemAlt = false;
    //
    public $budget;
    public $budget_id;
    public $budget_item_id;
    public $budget_title;
    public $budget_operation;
    //
    public $item_nome='';
    public $item_nome_provedor='';
    public $item_qtd='';
    public $item_valor='';
    public $item_valor_total='';
    public $item_valor_investmento='';
    public $item_valor_pago='';
    public $item_valor_liquido='';
    public $item_status='';

    public $alterar_add_item_div;
    public $alterar_item_id;

    public $targetTicketsTypes;

    public $receitas;
    public $valorReceitas;
    public $valorReceitasLiquida;
    public $despesas;

    public $valorSaldo;
    public $valorSaldoReal;
    public $valorDespesas;
    public $valorDespesasPagas;

    // protected $messages = [
    //     '*.required' => 'Obrigatório',
    // ];


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($event_id = null)
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

        // GET ORGANIZER
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;

        //
        if(!$this->organizerId)
        {
            // session()->flash('error','É PRECISO SELECIONAR UM ORGANIZADOR');
            return redirect()->route('dashboard');
        }

        $app = sessionApp();

        $this->target_list_ref = $app->modules ?? [];

        // RECUPERA DA SESSAO - target_ref
        $this->target_ref = sessionTargetRef();

        // RECUPERA DA SESSAO - target_id
        $this->target_id = sessionTargetId();

        // SE EXISTE REF E NAO EXISTE ID
        if(!$this->target_ref || !$this->target_id)
        {
            session()->flash('error','Precisa selecionar um item');
            return redirect()->route('dashboard');
        }

        $this->calculaDados();
    }

    public function render()
    {
        // if(Auth()->user()->email != 'admin@empresateste.com')
        // {
        //     dd('Ops! Encontramos um erro interno no sistema. Já estamos atuando!');
        // }

        return view('livewire.dashboard.dashboard-financeiro-gestao-orcamentaria')->layout('layouts.app-pep-auth');
    }

    public function calculaDados()
    {
        // GET OS DADOS
        $this->target = Event::with(['ticketsTypes','sponsorshipOrders'])->find($this->target_id);

        //
        if($this->target->organizer_id != $this->organizerId)
            $this->alterarTarget();

        // RECEITAS ----------------------------------------------------------------------------------------------------
        $this->receitas             = [];
        $this->valorReceitas        = 0;
        $this->valorReceitasLiquida = 0;

        // APPEND - BILHETERIA (SE HOUVER)
        $this->receitas['BILHETERIA'] = [
            'title'        => 'BILHETERIA',
            'subtitle'     => 'Venda de ingressos',
            'amount'       => 0,
            'amountLiquid' => 0,
            'items'        => [],
        ];

        //
        $this->targetTicketsTypes = $this->target->ticketsTypes;

        // SE EXISTE TIPOS
        if($this->targetTicketsTypes->count() ?? false)
        {
            $this->receitas['BILHETERIA']['amount']       = 0;
            $this->receitas['BILHETERIA']['amountLiquid'] = 0;

            //
            foreach ($this->targetTicketsTypes as $type_key => $type_values)
            {
                // ORDERS / PEDIDOS
                $type_orders = $type_values->orders->whereIn('status', listOrderStatusPaid());

                // TICKETS VALIDOS
                $tickets = $type_values->tickets->whereIn('ticket_status',ticketStatusCapacidade('tickets_validos',visaoAdmin:false));

                // $type_orders_count = $type_orders->count() ?? 0;
                $type_orders_count = $tickets->count() ?? 0;

                // $type_orders_amount = $type_orders->sum('order_amount') ?? 0;
                $type_orders_amount = $tickets->sum('event_ticket_price_paid') ?? 0;

                //
                $item = new stdClass();
                $item->item_name              = $type_values->ticket_name;
                $item->provider_name          = false;
                $item->price                  = (int) $type_values->price;
                $item->amount                 = (int) $type_values->amount;
                //
                $item->order_qtd              = (int) $type_orders->count() ?? 0;
                $item->item_qtd               = (int) $type_orders_count;
                $item->item_amount            = (int) $type_values->price;
                $item->item_amount_total      = (int) $type_orders_count * (int) $type_values->price;
                $item->item_amount_investment = 0;
                $item->item_amount_discount   = (int) $type_orders_count * (int) $type_values->price;
                $item->item_amount_paid       = (int) $type_orders_count * (int) $type_values->price;
                $item->item_amount_liquid     = (int) $type_orders_count * (int) $type_values->price;
                $item->item_status            = null;

                //
                $this->valorReceitas        += (int) $item->item_amount_paid;
                $this->valorReceitasLiquida += (int) $item->item_amount_liquid;

                //
                $this->receitas['BILHETERIA']['amount'] += (int) $item->item_amount_liquid;
                $this->receitas['BILHETERIA']['items'][$type_values->id] = $item;
            }
        }

        // APPEND - PATROCINIOS (SE HOUVER)
        $this->receitas['PATROCINIO'] = [
            'title'        => 'PATROCÍNIO',
            'subtitle'     => 'Patrocinadores',
            'amount'       => 0,
            'amountLiquid' => 0,
            'items'        => [],
        ];

        // SE EXISTE TIPOS
        if($patrocinios = $this->target->sponsorshipOrders->whereIn("status", listOrderStatusPaid()))
        {
            //
            $this->receitas['PATROCINIO']['amount']       = 0;
            $this->receitas['PATROCINIO']['amountLiquid'] = 0;

            //
            foreach ($patrocinios ?? [] as $patrocinio_values)
            {
                //
                $type_orders_count  = 1;
                $type_orders_amount = $patrocinio_values->order_amount;
                //
                $item = new stdClass();
                $item->item_name              = mb_strtoupper($patrocinio_values->buyer_name . ' - ' . putMask($patrocinio_values->buyer_doc_num,$patrocinio_values->buyer_doc_type));
                $item->provider_name          = false;
                $item->price                  = (int) $patrocinio_values->order_amount;
                $item->amount                 = (int) 1;
                $item->order_qtd              = (int) $type_orders_count;
                $item->item_qtd               = (int) $type_orders_count;
                $item->item_amount            = (int) $type_orders_amount;
                $item->item_amount_total      = $patrocinio_values->order_amount * $type_orders_count;
                $item->item_amount_investment = 0;
                $item->item_amount_paid       = (int) $type_orders_amount;
                $item->item_amount_liquid     = (int) $type_orders_amount;
                $item->item_status            = null;

                //
                $this->valorReceitas        += (int) $type_orders_amount;
                $this->valorReceitasLiquida += (int) $type_orders_amount;

                //
                $this->receitas['PATROCINIO']['amount'] += $type_orders_amount;
                $this->receitas['PATROCINIO']['items'][$patrocinio_values->id] = $item;
            }
        }

        // APPEND - DEMAIS RECEITAS
        foreach ($this->target->budgetsReceita->sortBy('budget_title') ?? [] as $budgetsReceitaKey => $budgetsReceitaValues)
        {
            $this->receitas[$budgetsReceitaValues->id] = [
                'title'    => $budgetsReceitaValues->budget_title ?? null,
                'subtitle' => $budgetsReceitaValues->budget_subtitle ?? null,
                'amount'   => 0,
                'items'    => [],
            ];

            if($budgetsReceitaValues->budgetsItems->count())
            {
                $valorReceitasItem = 0;
                $valorReceitasItemLiquida = 0;

                foreach ($budgetsReceitaValues->budgetsItems as $budgetsItemsKey => $budgetsItemsValues)
                {
                    $valor                       = $budgetsItemsValues->item_amount_total ?? 0;
                    $valorLiquido                = $budgetsItemsValues->item_amount_liquid ?? 0;
                    //
                    $this->valorReceitas        += $valor;
                    $this->valorReceitasLiquida += $valorLiquido;
                    //
                    $valorReceitasItem          += $valor;
                    $valorReceitasItemLiquida   += $valorLiquido;
                    //
                    $this->receitas[$budgetsReceitaValues->id]['items'][$budgetsItemsValues->id] = $budgetsItemsValues;
                }

                $this->receitas[$budgetsReceitaValues->id]['amount'] += $valorReceitasItem ?? 0;
                $this->receitas[$budgetsReceitaValues->id]['amountLiquid'] = $valorReceitasItemLiquida ?? 0;
            }
        }

        // DESPESAS ----------------------------------------------------------------------------------------------------
        $this->despesas           = [];
        $this->valorDespesas      = 0;
        $this->valorDespesasPagas = 0;

        // APPEND - DESPESAS
        foreach ($this->target->budgetsDespesa->sortBy('budget_title') ?? [] as $budgetsDespesaKey => $budgetsDespesaValues)
        {
            $this->despesas[$budgetsDespesaKey] = [
                'title'    => $budgetsDespesaValues->budget_title ?? null,
                'subtitle' => $budgetsDespesaValues->budget_subtitle ?? null,
                'amount'   => 0,
                'items'    => [],
            ];

            if($budgetsDespesaValues->budgetsItems->count())
            {
                $valorDespesasItem = 0;
                $valorDespesasItemPagas = 0;

                foreach ($budgetsDespesaValues->budgetsItems as $budgetsItemsKey => $budgetsItemsValues)
                {
                    $valor                  = $budgetsItemsValues->item_amount_total ?? 0;
                    $valorPago              = $budgetsItemsValues->item_amount_paid ?? 0;
                    //
                    $this->valorDespesas      += $valor;
                    $this->valorDespesasPagas += $valorPago;
                    //
                    $valorDespesasItem      = $valorDespesasItem + $valor;
                    $valorDespesasItemPagas = $valorDespesasItemPagas + $valorPago;
                    //
                    $this->despesas[$budgetsDespesaKey]['items'][$budgetsItemsKey] = $budgetsItemsValues;
                }

                $this->despesas[$budgetsDespesaKey]['amount'] = $valorDespesasItem ?? 0;
                $this->despesas[$budgetsDespesaKey]['amountPaid'] = $valorDespesasItemPagas ?? 0;
            }
        }

        $this->valorSaldoReal = (int)  ($this->valorReceitasLiquida - $this->valorDespesasPagas);
        $this->valorSaldo     = (int)  ($this->valorReceitas - $this->valorDespesas);
    }

    public function removerBudgetItem($budget_item_id)
    {
        if($budgetItem = EventBudgetItem::find($budget_item_id))
        {
            $budgetItem->delete();

            session()->flash('success','Item removido com sucesso');
        }
        else
        {
            session()->flash('error','O item não foi localizado');
        }
    }

    public function removerBudget($budget_id)
    {
        if($budget = EventBudget::with('budgetsItems')->find($budget_id))
        {
            if($budget->budgetsItems->count())
            {
                session()->flash('error',"O tipo '{$budget->budget_title}' possui itens cadastrados. Remova todos e tente novamente.");
                $this->budget_id = false;
                $this->modal_budgetRem = false;
                return;
            }

            $budget->delete();

            session()->flash('success','Tipo removido com sucesso');
        }
        else
        {
            session()->flash('error','O tipo não foi localizado');
        }

        $this->budget_id = false;
        $this->modal_budgetRem = false;
    }

    public function budgetCriar($budget_operation)
    {
        $validatedData = $this->validate([
            'budget_title' => ['required','string'],
        ]);

        $budget = EventBudget::create([
            "event_id"         => $this->target->id,
            "budget_title"     => strtolower($this->budget_title),
            "budget_subtitle"  => strtolower($this->budget_title),
            "budget_operation" => strtolower($budget_operation),
        ]);

        session()->flash('success',"Tipo '{$this->budget_title}' criado com sucesso");

        $this->modal_budgetAdd = false;
        $this->budget_title  = '';
    }

    public function budgetAlterar($budget_id)
    {
        $validatedData = $this->validate([
            'budget_title' => ['required','string'],
        ]);

        if($budget = EventBudget::find($budget_id))
        {
            $budget->budget_title = $validatedData['budget_title'];
            $budget->save();
            //
            session()->flash('success',"Tipo alterado com sucesso");
        }

        $this->budget_id       = false;
        $this->modal_budgetAlt = false;
        $this->budget_title    = '';
    }

    public function budgetItemAlterar($budget_operation, $budget_id, $budget_item_id=false)
    {
        $item = [];
        $validatedData = [];

        switch ($budget_operation) {
            case 'receita':
                $validatedData = $this->validate([
                    'item_nome'  => ['required','string'],
                    'item_qtd'   => ['required','integer'],
                    'item_valor' => ['required','numeric'],
                ]);

                //
                $item = [
                    "event_id"               => $this->target->id,
                    "event_budget_id"        => $budget_id,
                    "item_date"              => now(),
                    "item_name"              => strtolower($validatedData['item_nome']),
                    "item_label"             => null,
                    "item_description"       => null,
                    "item_operation"         => $budget_operation,
                    "provider_id"            => null,
                    "provider_name"          => null,
                    "item_status"            => $budget_operation . '-evento',
                    "item_qtd"               => (int) $validatedData['item_qtd'],
                    "item_amount"            => toMoneyInt($validatedData['item_valor']),
                    "item_amount_total"      => toMoneyInt($validatedData['item_valor']) * $validatedData['item_qtd'],
                    "item_amount_investment" => 0,
                    "item_amount_paid"       => toMoneyInt($validatedData['item_valor']) * $validatedData['item_qtd'],
                    "item_amount_liquid"     => toMoneyInt($validatedData['item_valor']) * $validatedData['item_qtd'],
                ];
                break;

            case 'despesa':

                $validatedData = $this->validate([
                    'item_nome'              => ['required','string'],
                    'item_nome_provedor'     => ['nullable','string'],
                    'item_qtd'               => ['required','integer'],
                    'item_valor'             => ['required','numeric'],
                    'item_valor_investmento' => ['nullable','numeric'],
                    'item_valor_pago'        => ['nullable','numeric'],
                    'item_status'            => ['nullable','string'],
                ]);

                //
                $item = [
                    "event_id"               => $this->target->id,
                    "event_budget_id"        => $budget_id,
                    "item_date"              => now(),
                    "item_name"              => strtolower($validatedData['item_nome']),
                    "item_label"             => null,
                    "item_description"       => null,
                    "item_operation"         => $budget_operation,
                    "provider_id"            => null,
                    "provider_name"          => strtolower($validatedData['item_nome_provedor']),
                    "item_status"            => $validatedData['item_status'] ? strtolower($validatedData['item_status']) : null,
                    "item_qtd"               => (int) $validatedData['item_qtd'],
                    "item_amount"            => toMoneyInt($validatedData['item_valor']),
                    "item_amount_total"      => toMoneyInt($validatedData['item_valor']) * $validatedData['item_qtd'],
                    "item_amount_investment" => toMoneyInt($validatedData['item_valor_investmento']),
                    "item_amount_paid"       => toMoneyInt($validatedData['item_valor_pago']),
                    "item_amount_liquid"     => toMoneyInt($validatedData['item_valor_pago']),
                ];
                break;

            default:
                session()->flash('error','Budget Operation não foi definida');
                return;
        }

        if($budget_item_id && $eventBudgetItem = EventBudgetItem::find($budget_item_id))
        {
            $eventBudgetItem->update($item);
        }
        else
        {
            $eventBudgetItem = EventBudgetItem::create($item);
        }

        session()->flash('success','Executado com sucesso');

        $this->resetAll();
    }

    public function resetAll()
    {
        $this->modal_budgetItemAdd    = false;
        $this->modal_budgetItemAlt    = false;
        $this->item_nome              = '';
        $this->item_nome_provedor     = '';
        $this->item_qtd               = '';
        $this->item_valor             = '';
        $this->item_valor_pago        = '';
        $this->item_valor_investmento = '';
        $this->item_status            = '';
    }

    public function modalBudgetItemAlt($budget_id, $budget_item_id)
    {
        $this->resetAll();
        //
        $this->budget_id           = $budget_id;
        $this->budget_item_id      = $budget_item_id;
        $this->modal_budgetItemAlt = true;
        //
        if($budgetItem = EventBudgetItem::find($budget_item_id))
        {
            $this->item_nome              = $budgetItem->item_name;
            $this->item_qtd               = $budgetItem->item_qtd;
            $this->item_valor             = $budgetItem->item_amount / 100;
            $this->item_nome_provedor     = $budgetItem->provider_name;
            $this->item_valor_investmento = $budgetItem->item_amount_investment / 100;
            $this->item_valor_pago        = $budgetItem->item_amount_paid / 100;
            $this->item_status            = $budgetItem->item_status;
        }
    }

    public function modalBudgetItemAdd($budget_id)
    {
        $this->resetAll();
        //
        $this->budget_id           = $budget_id;
        $this->modal_budgetItemAdd = true;
    }

    public function modalBudgetAlt($budget_operation, $budget_id)
    {
        if (in_array($budget_operation,['receita'])) {
            $budget = $this->target->budgetsReceita->find($budget_id);
        }

        if (in_array($budget_operation,['despesa'])) {
            $budget = $this->target->budgetsDespesa->find($budget_id);
        }

        $this->budget_id       = $budget_id;
        $this->modal_budgetAlt = true;
        $this->budget_title    = $budget->budget_title ?? '';
    }

    // public function getTarget($target_ref, $target_id)
    // {
    //     switch ($target_ref) {
    //         case 'app_event':
    //             $target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.itens','orders.tickets','ticketsTypes','tickets'])->find($target_id);
    //             break;

    //         default:
    //             $target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.itens','orders.tickets','ticketsTypes','tickets'])->find($target_id);
    //             break;
    //     }

    //     //
    //     if(!$target)
    //     {
    //         $target = false;
    //         session()->flash('error','Parametros da URL estão incorretos');
    //     }

    //     return $target;
    // }

    // public function updatedTargetId()
    // {
    //     $this->target = false;
    //     sessionTargetId($this->target_id);
    // }

    // public function updatedTargetRef()
    // {
    //     $this->target = false;
    //     $this->target_id = false;
    //     //
    //     sessionClear('target');
    //     sessionTargetRef($this->target_ref);
    //     //
    //     $this->getTargetList($this->target_ref);
    // }

    // public function getTargetList($target_ref=false)
    // {
    //     switch ($target_ref) {
    //         case 'app_event':
    //             $this->target_list = Event::where('organizer_id',$this->organizerId)->get();
    //             break;

    //         case 'app_workshop':
    //             $this->target_list = Event::where('organizer_id',$this->organizerId)->get();
    //             break;

    //         default:
    //             $this->target_list = [];
    //             break;
    //     }

    //     return $this->target_list;
    // }

    // public function alterarTarget()
    // {
    //     $this->target     = false;
    //     $this->target_id  = false;
    //     $this->target_ref = false;
    //     //
    //     sessionClear('target');
    //     return redirect()->route('dashboard');
    // }
}

