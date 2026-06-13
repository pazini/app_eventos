<?php

namespace App\Http\Livewire;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderItem;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Livewire\Component;
use stdClass;

class DashboardEventoVendasCadastroManual extends Component
{
    // //
    // public $target_list;
    // public $target_list_ref;
    // //
    // public $orderId;
    // public $orderControl;
    // //
    // public $target;
    // public $pedidos;
    // public $order;
    // public $orderItens;
    // public $orderPayments;

    // //
    // public $btnActions = true;
    // public $divAdicionarPagamento;

    // //
    // public $forma_pagamento;
    // public $pagamento_valor = 600;

    // //
    // public $line_aditional_top_titulo    = '';
    // public $line_aditional_top_texto     = '';
    // public $line_aditional_botton_titulo = '';
    // public $line_aditional_botton_texto  = '';

    // // GESTAO ORCAMENTARIA
    // public $alterar_div = false;
    // public $modal_budgetAdd = false;
    // public $modal_budgetAlt = false;
    // public $modal_budgetRem = false;
    // public $modal_budgetItemAdd = false;
    // public $modal_budgetItemAlt = false;
    // //
    // public $budget;
    // public $budget_id;
    // public $budget_title;
    // public $budget_operation;
    // //
    // public $item_nome;
    // public $item_nome_provedor;
    // public $item_qtd;
    // public $item_valor;
    // public $item_valor_total;
    // public $item_valor_investmento;
    // public $item_valor_pago;
    // public $item_valor_liquido;
    // public $item_status;
    // public $list_status;
    // public $list_status_selected='all';
    // //
    // public $alterar_add_item_div;
    // public $alterar_item_id;
    // //
    // public $organizer;
    // public $organizerId;

    // // ADD PAGAMENTO
    // public $addPay = false;
    // public $orderPay = false;
    // public $app_ref;
    // public $app_ref_order_id;
    // public $gateway_id;
    // public $gateway_slug;
    // public $status;
    // public $status_old;
    // public $description;
    // public $paid_label;
    // public $paid_description;
    // public $value_paid;
    // public $value_liquid;
    // public $pay_nsu;
    // public $pay_type;
    // public $pay_datetime;
    // public $pay_installments_number;
    // public $pay_installment_value;
    // public $pay_card_last;
    // public $pay_card_name;
    // public $pay_card_brand;
    // public $pay_pix_key;

    //
    public $target_ref;
    public $target_id;

    // COMPRADOR
    public $buyer_name;
    public $buyer_email;
    public $buyer_birth_date;
    public $buyer_doc_type;
    public $buyer_doc_num;
    public $buyer_contact_ddd;
    public $buyer_contact_num;

    // ORDER
    public $order;

    // ITENS
    public $compraItens = [];
    public $user_name;
    public $item_ticket_type_id;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount(Request $request)
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

        // RECUPERA DA SESSAO - target_ref
        $this->target_ref = sessionTargetRef();

        // RECUPERA DA SESSAO - target_id
        $this->target_id = sessionTargetId();

        // GET TARGET
        $this->getTarget($this->target_ref,$this->target_id);

        // INICIA ORDER
        $this->order = [
            'event_id'                   => $this->target->id,
            'order_amount'               => 0,
            'order_amount_pay'           => 0,
            'code_promo_id'              => null,
            'code_promo_discount_amount' => 0,
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-vendas-cadastrar')->layout('layouts.app-pep-auth');
    }

    public function getTarget($target_ref, $target_id)
    {
        switch ($target_ref) {
            case 'evento':
            case 'app_event':
                $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','ticketsTypes'])->find($target_id);
                break;

            case 'app_workshop':
                $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','ticketsTypes'])->find($target_id);
                break;

            default:
                $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','ticketsTypes'])->find($target_id);
                break;
        }

        //
        if(!$this->target)
        {
            $this->target = false;
            session()->flash('error','Parametros da URL estão incorretos');
        }

        return $this->target;
    }

    public function atualizarTarget()
    {
        $this->getTarget($this->target_ref,$this->target_id);
    }

    public function criarOrder()
    {
        //return;

        $validatedData = $this->validate([
            'buyer_name'          => ['required','string'],
            'buyer_email'         => ['required','email'],
            'buyer_birth_date'    => ['required','date'],
            'buyer_doc_type'      => ['required','string'],
            'buyer_doc_num'       => ['required','cpf_cnpj'],
            'buyer_contact_ddd'   => ['required','integer'],
            'buyer_contact_num'   => ['required','integer'],
        ]);

        // SE EXISTEM ITENS
        if(empty($this->compraItens ?? []))
        {
            return session()->flash('error','Nenhum item foi adicionado');
        }

        try
        {
            DB::beginTransaction();

            $orderCreate = array_merge($this->order, [
                'status'                     => 'pending_payment',
                'order_control'              => "EV." . now()->format('ymd') . "." . strtoupper(hash('adler32', $this->target->event_slug . $this->buyer_doc_num . now()->timestamp)) . '.M',
                'order_generation_datetime'  => now()->format('Y-m-d H:i:s'),
                'channel_user_id'            => Auth::user()->id,
                'channel_order'              => 'dashboard',
                'buyer_name'                 => mb_strtolower($this->buyer_name),
                'buyer_email'                => mb_strtolower($this->buyer_email),
                'buyer_doc_type'             => mb_strtoupper($this->buyer_doc_type),
                'buyer_doc_num'              => $this->buyer_doc_num,
                'buyer_contact_country'      => 55,
                'buyer_contact_ddd'          => (int) preg_replace('/\D/', '', $this->buyer_contact_ddd),
                'buyer_contact_num'          => (int) preg_replace('/\D/', '', $this->buyer_contact_num),
                'buyer_birth_date'           => $this->buyer_birth_date,
                'order_items_ticket_type_id' => null,
                'order_items_qtd'            => count($this->compraItens ?? []),
                'order_items_amount'         => $this->order['order_amount'],
                'order_items_amount_total'   => $this->order['order_amount'],
            ]);

            // CREATE ORDER
            $order = AppEventOrder::create($orderCreate);

            // PERCORRE ITENS
            $orderItens   = [];
            $orderTickets = [];

            foreach ($this->compraItens as $itemKey => $itemValues)
            {
                // CRIA ITEM DE COMPRA
                $orderItemCreate = array_merge($itemValues,[
                    'order_id'               => $order->id,
                    'user_email'             => strtolower($this->buyer_email),
                    'user_doc_type'          => $this->buyer_doc_type,
                    'user_doc_num'           => $this->buyer_doc_num,
                    'user_contact_country'   => 55,
                    'user_contact_ddd'       => (int) $this->buyer_contact_ddd,
                    'user_contact_num'       => (int) $this->buyer_contact_num,
                    'user_birth_date'        => $this->buyer_birth_date,
                    'item_amount_pay'        => $itemValues['item_amount'],
                ]);

                // CRIA ORDER ITEM
                $orderIten = AppEventOrderItem::create($orderItemCreate);
                $orderItens[$itemKey] = $orderIten->toarray();

                // PEGA TICKET TYPE
                $ticketsType = $this->target->ticketsTypes->find($itemValues['item_ticket_type_id']);
                $description = mb_strtoupper(($this->target->event_name ?? 'EVENTO') . ' ' . ($ticketsType->ticket_name ?? 'TICKET'));

                // CRIA TICKETS
                $ticket = AppEventOrderTicket::create([
                    'order_id'                   => $order->id,
                    'organizer_id'               => $this->target->organizer_id,
                    'organizer_name'             => $this->target->organizer->organizer_name ?? null,
                    'event_id'                   => $this->target->id,
                    'event_name'                 => $this->target->event_name ?? null,
                    'event_description'          => $description,
                    'event_datetime'             => $this->target->event_datetime_start ?? null,
                    'event_ticket_id'            => $ticketsType->id ?? null,
                    'event_ticket_slug'          => $ticketsType->ticket_slug ?? null,
                    'event_ticket_name'          => $ticketsType->ticket_name ?? null,
                    'event_ticket_price'         => $ticketsType->price ?? null,
                    'ticket_control'             => $order->order_control . '-' . ($itemKey + 1),
                    'ticket_status'              => "gerado",
                    'ticket_generation_datetime' => now()->format('Y-m-d H:i:s'),
                    'user_name'                  => strtolower(trim($orderItemCreate['user_name'])),
                    'user_email'                 => strtolower(trim($orderItemCreate['user_email'])),
                    'user_doc_type'              => $orderItemCreate['user_doc_type'],
                    'user_doc_num'               => $orderItemCreate['user_doc_num'],
                    'user_contact_country'       => 55,
                    'user_contact_ddd'           => (int) $orderItemCreate['user_contact_ddd'],
                    'user_contact_num'           => (int) $orderItemCreate['user_contact_num'],
                    'user_json_answers'          => null,
                    'event_ticket_price_paid'    => $orderItens[$itemKey]['item_amount_pay'],
                    'order_item_id'              => $orderItens[$itemKey]['id'],
                ]);

                //
                $orderTickets[$itemKey] = json_encode($ticket->toArray() ?? []);
            }

            // SET ORDER JSON
            $order_json                    = [];
            $order_json['localizador']     = $order->order_control;
            $order_json['status']          = $order->status;
            $order_json['order_type']      = 'evento';
            // SANTOS - NAO USADO - BD CLEAR // $this->order_json['order_type_data'] = $this->target->toArray();
            $order_json['order_data']      = $order->toArray();

            // PERCORRE PARTICIPANTES
            $loopCount = 0;
            $order_json['order_data']['itens']   = $orderItens;
            $order_json['order_data']['tickets'] = $orderTickets;

            // SAVE JSON
            $order->order_json = json_encode($order_json);
            $order->save();

            DB::commit();

            session()->flash('success','CADASTRADA COM SUCESSO');
            session()->flash('success_sub','Pendente de Pagamento');

            // SAVE SESSION ORDER ID
            sessionOrderId($order->id);

            return redirect()->route('dashboard-evento-vendas');
        }
        catch (\Throwable $th)
        {
            DB::rollBack();
            return session()->flash('error', $th->getMessage());

            // dd('throw',$th,);
        }
    }

    public function removeItem($key)
    {
        //
        if ($item = $this->compraItens[$key])
        {
            $this->order['order_amount'] -= $item['item_amount'] ?? 0;
            unset($this->compraItens[$key]);
        }
    }

    public function adicionaItem()
    {
        $validatedData = $this->validate([
            'user_name'           => ['required','string'],
            'item_ticket_type_id' => ['required','uuid'],
        ]);

        // AJUSTE
        $validatedData['user_name'] = strtolower($this->user_name);

        // GET TICKER TYPE
        $ticketType = $this->target->ticketsTypes->find($this->item_ticket_type_id);

        // APPEND
        $append = array_merge($validatedData,[
            'item_amount' => (int) $ticketType->price ?? 0,
            'item_status' => 'adicionado',
            'item_description' => $ticketType->ticket_name ?? null,
        ]);

        $this->compraItens[] = $append;

        $this->order['order_amount']     += $ticketType->price ?? 0;
        $this->order['order_amount_pay'] += $ticketType->price ?? 0;

        $this->user_name = null;
        $this->item_ticket_type_id = null;
    }
}


