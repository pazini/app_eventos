<?php

namespace App\Http\Livewire\Financeiro;

use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventBudget;
use App\Models\ModEvent\EventBudgetItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use stdClass;

class GestaoOrcamentariaGrid extends Component
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

    public $saldos;

    // protected $messages = [
    //     '*.required' => 'Obrigatório',
    // ];


    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
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
        return view('livewire.dashboard.financeiro.gestao-orcamentaria-planilha')->layout('layouts.app-pep-auth');
    }

    public function calculaDados()
    {
        // GET OS DADOS
        $this->target = Event::with(['ticketsTypes','sponsorshipOrders'])->find($this->target_id);

        //
        if($this->target->organizer_id != $this->organizerId)
            $this->alterarTarget();

        //
        $this->targetTicketsTypes = $this->target->ticketsTypes;

        // BILHETERIA
        $this->saldos        = [];
        $this->receitas      = [];
        $this->valorReceitas = 0;

        if($this->targetTicketsTypes->count() ?? 0)
        {
            foreach ($this->targetTicketsTypes as $type_values)
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

                $this->receitas[] = [
                    'tipo'        => 'RECEITA',
                    'grupo'       => 'BILHETERIA',
                    'descricao'   => mb_strtoupper('LOTE '.$item->item_name),
                    'valor'       => toMoney((int) $item->price, 'R$ '),
                    'quantidade'  => $item->item_qtd,
                    'total'       => toMoney((int) $item->item_amount_total, 'R$ '),
                ];

                $this->valorReceitas += $item->item_amount_total;
            }
        }
        else
        {
            $this->receitas[] = [
                'tipo'        => 'RECEITA',
                'grupo'       => 'BILHETERIA',
                'descricao'   => '--',
                'valor'       => toMoney((int) 0, 'R$ '),
                'quantidade'  => 0,
                'total'       => toMoney((int) 0, 'R$ '),
            ];
        }

        // PATROCINIOS
        $patrocinios = $this->target->sponsorshipOrders->whereIn("status", listOrderStatusPaid());
        if($patrocinios->count() ?? 0)
        {
            foreach ($patrocinios ?? [] as $patrocinio_values)
            {
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

                $this->receitas[] = [
                    'tipo'        => 'RECEITA',
                    'grupo'       => 'PATROCÍNIO',
                    'descricao'   => mb_strtoupper($item->item_name),
                    'valor'       => toMoney((int) $item->price, 'R$ '),
                    'quantidade'  => $item->item_qtd,
                    'total'       => toMoney((int) $item->item_amount_total, 'R$ '),
                ];

                $this->valorReceitas += $item->item_amount_total;
            }
        }
        else
        {
            $this->receitas[] = [
                'tipo'        => 'RECEITA',
                'grupo'       => 'PATROCÍNIO',
                'descricao'   => '--',
                'valor'       => toMoney((int) 0, 'R$ '),
                'quantidade'  => 0,
                'total'       => toMoney((int) 0, 'R$ '),
            ];
        }

        // APPEND - DEMAIS RECEITAS
        foreach ($this->target->budgetsReceita->sortBy('budget_title') ?? [] as $budgetsReceitaKey => $budgetsReceitaValues)
        {
            if($budgetsReceitaValues->budgetsItems->count())
            {
                foreach ($budgetsReceitaValues->budgetsItems as $budgetsItemsKey => $budgetsItemsValues)
                {
                    $this->receitas[] = [
                        'tipo'        => 'RECEITA',
                        'grupo'       => mb_strtoupper($budgetsReceitaValues->budget_title),
                        'descricao'   => mb_strtoupper($budgetsItemsValues->item_name),
                        'valor'       => toMoney((int) $budgetsItemsValues->item_amount, 'R$ '),
                        'quantidade'  => $budgetsItemsValues->item_qtd,
                        'total'       => toMoney((int) $budgetsItemsValues->item_amount_total, 'R$ '),
                    ];

                    $this->valorReceitas += $budgetsItemsValues->item_amount_total;
                }
            }
        }

        // DESPESAS ----------------------------------------------------------------------------------------------------
        $this->despesas           = [];
        $this->valorDespesas      = 0;

        // APPEND - DESPESAS
        if($this->target->budgetsDespesa->count() ?? 0)
        {
            foreach ($this->target->budgetsDespesa->sortBy('budget_title') ?? [] as $budgetsDespesaKey => $budgetsDespesaValues)
            {
                if($budgetsDespesaValues->budgetsItems->count())
                {
                    foreach ($budgetsDespesaValues->budgetsItems as $budgetsItemsKey => $budgetsItemsValues)
                    {
                        $this->despesas[] = [
                            'tipo'        => 'DESPESA',
                            'grupo'       => mb_strtoupper($budgetsDespesaValues->budget_title),
                            'descricao'   => mb_strtoupper($budgetsItemsValues->item_name),
                            'valor'       => toMoney($budgetsItemsValues->item_amount, 'R$ '),
                            'quantidade'  => $budgetsItemsValues->item_qtd,
                            'total'       => toMoney($budgetsItemsValues->item_amount_total, '-R$ '),
                        ];

                        $this->valorDespesas += $budgetsItemsValues->item_amount_total;
                    }
                }
                else
                {
                    $this->despesas[] = [
                        'tipo'        => 'DESPESA',
                        'grupo'       => mb_strtoupper($budgetsDespesaValues->budget_title),
                        'descricao'   => '--',
                        'valor'       => toMoney((int) 0, 'R$ '),
                        'quantidade'  => 0,
                        'total'       => toMoney((int) 0, 'R$ '),
                    ];
                }
            }
        }
        else
        {
            $this->despesas[] = [
                'tipo'        => 'DESPESA',
                'grupo'       => '--',
                'descricao'   => '--',
                'valor'       => toMoney((int) 0, 'R$ '),
                'quantidade'  => 0,
                'total'       => toMoney((int) 0, 'R$ '),
            ];
        }

        $this->saldos[] = [
            'tipo'        => null,
            'grupo'       => null,
            'descricao'   => null,
            'valor'       => null,
            'quantidade'  => 'RECEITA',
            'total'       => toMoney($this->valorReceitas, 'R$ '),
        ];

        $this->saldos[] = [
            'tipo'        => null,
            'grupo'       => null,
            'descricao'   => null,
            'valor'       => null,
            'quantidade'  => 'DESPESA',
            'total'       => toMoney($this->valorDespesas, '-R$ '),
        ];

        $calculoSaldo = $this->valorReceitas - $this->valorDespesas;

        $this->saldos[] = [
            'tipo'        => null,
            'grupo'       => null,
            'descricao'   => null,
            'valor'       => null,
            'quantidade'  => 'SALDO',
            'total'       => toMoney($calculoSaldo, ($calculoSaldo < 0) ? '-R$ ' :  'R$ '),
        ];


        // dd(
        //     $this->receitas,
        //     $this->despesas,
        // );
    }
}

