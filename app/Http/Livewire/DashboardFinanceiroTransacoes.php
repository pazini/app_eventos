<?php

namespace App\Http\Livewire;

use App\Jobs\AppEvent\NotificationDetalhesCompra;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderItem;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Exception;
use stdClass;
use WireUi\Traits\Actions;

class DashboardFinanceiroTransacoes extends Component
{
    use Actions;

    //
    public $myApp;
    public $appUserRole;
    public $user_role = 'user';
    public $target_ref;
    public $target_id;
    //
    public $target_list;
    public $target_list_ref;
    //
    public $orderId;
    public $orderControl;
    //
    public $target;
    public $target_orders;
    public $pedidos;
    public $order;
    public $orderItens;
    public $orderPayments;

    //
    public $btnActions = true;
    public $divAdicionarPagamento;

    //
    public $forma_pagamento;
    public $pagamento_valor = 0;

    //
    public $line_aditional_top_titulo    = '';
    public $line_aditional_top_texto     = '';
    public $line_aditional_botton_titulo = '';
    public $line_aditional_botton_texto  = '';

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
    public $budget_title;
    public $budget_operation;
    //
    public $item_nome;
    public $item_nome_provedor;
    public $item_qtd;
    public $item_valor;
    public $item_valor_total;
    public $item_valor_investmento;
    public $item_valor_pago;
    public $item_valor_liquido;
    public $item_status;
    public $list_status;
    public $list_status_deny;
    public $list_status_selected='all';
    //
    public $alterar_add_item_div;
    public $alterar_item_id;
    //
    public $organizer;
    public $organizerId;

    // ADD PAGAMENTO
    public $addPay = false;
    public $addPayManual = false;
    public $orderPay = false;
    public $paymentId;
    public $app_ref;
    public $app_ref_order_id;
    public $gateway_id;
    public $gateway_slug;
    public $status;
    public $status_old;
    public $description;
    public $paid_label;
    public $paid_description;
    public $value_paid;
    public $value_liquid;
    public $pay_nsu;
    public $pay_type;
    public $pay_datetime;
    public $pay_installments_number;
    public $pay_installment_value;
    public $pay_card_last;
    public $pay_card_name;
    public $pay_card_brand;
    public $pay_pix_key;
    public $editingGatewaySlug;
    public $slipPaymentSlipId;
    public $modalEditPagamentoExibir = false;
    public $editPaymentId;
    public $edit_pay_type;
    public $edit_value_paid;
    public $edit_pay_nsu;
    public $edit_pay_datetime;
    public $edit_pay_card_brand;
    public $edit_pay_card_name;
    public $edit_pay_card_last;
    public $edit_pay_pix_key;
    public $edit_status;
    public $edit_value_fees;
    public $edit_order_slip_id;

    // EDITAR PARCELA CARNÊ
    public $modalEditSlip = false;
    public $editSlipId;
    public $editSlipDescription;
    public $editSlipDateDue;
    public $editSlipValue;
    public $editSlipStatus;

    // CANCELAR ORDER
    public $cancelarOrder=false;
    public $order_cancel_description;

    // FUNCOES GATEWAY
    public $logTrasacao=false;
    public $logTrasacaoDetalhes;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public $controle;
    public $modo;

    public function mount(Request $request, $controle=false, $modo=false, $event_id=null)
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

        // PEGA STATUS QUE DEVEM SER OCULTADOS
        $this->list_status_deny = listOrderStatusHidden();

        // GET ORGANIZER
        $this->organizer   = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;

        if(!$this->list_status_selected = $request->input('status'))
        {
            $this->list_status_selected = 'all';
        }

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

        // SE EXISTE REF E NAO EXISTE ID
        if(($this->target_ref ?? false) && !$this->target_id)
            $this->getTargetList($this->target_ref);

        //
        if ($orderId = sessionOrderId())
        {
            $this->verDetalhes($orderId);
        }

        // SET USER ROLE
        if($app = sessionApp())
        {
            $this->user_role = $app->user_role ?? 'user';
        }

        $this->appUserRole = sessionUserRole();

        //
        if($controle ?? false)
        {
            $this->controle = $controle;
            $this->modo = $modo ?: request()->route('modo');
            $this->verDetalhes(orderId:false,controle:$controle);

            if (
                $this->modo === 'modificar-pagamentos'
                || request()->routeIs('dashboard-evento-vendas-controle-modificar')
            ) {
                $this->addPay = true;
            }
        }
    }

    public function verDetalhes($orderId,$controle=false)
    {
        //
        if($controle ?? false)
        {
            if(!$order = AppEventOrder::where('order_control',trim(mb_strtoupper($controle)))->first())
            {
                session()->flash('error','Pedido não localizado');
                return redirect()->route('dashboard');
            }

            //
            $orderId = $order->id;
        }

        // ORDER ID
        $this->orderId = $orderId;
        sessionOrderId($this->orderId);

        //
        switch ($this->target_ref)
        {
            case 'evento':
            case 'app_event':
                if($this->order = AppEventOrder::with(['event','codePromo','payments','payments.eventCodePromo'])->find($this->orderId))
                {
                    $this->orderItens    = $this->order->itens;
                    $this->orderPayments = $this->order->payments;
                }
                break;

            case 'workshop':
            case 'app_workshop':
                break;

            default:
                if($this->order = AppEventOrder::with(['event','codePromo','payments','payments.eventCodePromo'])->find($this->orderId))
                {
                    $this->orderItens    = $this->order->itens;
                    $this->orderPayments = $this->order->payments;
                }
                break;
        }

        //
        if(!$this->target)
        {
            $this->target = $this->getTarget($this->target_ref, $this->target_id);
        }

        $this->defineAction(false);

        $this->myApp = sessionApp();
        $this->target_list_ref = $this->myApp->modules ?? [];
    }

    public function render()
    {
        // SE CANCELAR ORDER
        if($this->cancelarOrder ?? false)
        {
            return view('livewire.dashboard.dashboard-financeiro-transacoes-cancelarOrder')->layout('layouts.app-pep-auth');
        }

        // SE ADICIONAR PAGAMENTO
        if($this->addPay ?? false)
        {
            return view('livewire.dashboard.dashboard-financeiro-transacoes-addPagamentoManual')->layout('layouts.app-pep-auth');
        }

        // SE ORDER >> VER DETALHES
        if($this->order ?? false)
        {
            // BUSCA POR PAGAMENTOS CONFIRMADOS
            if($orderPayments = $this->order->payments->whereIn('status', listOrderStatusPaid()))
            {
                // INICIA
                $paymentAmount = 0;

                // PERCORRE PAGAMENTOS
                foreach ($orderPayments ?? [] as $paymentsValues)
                {
                    $paymentAmount += $paymentsValues->value_liquid ?? 0;
                }

                // CHECK SE ORDER PAY
                $this->orderPay = ($paymentAmount >= $this->order->order_amount_pay) ? true : false;
            }

            return view('livewire.dashboard.dashboard-financeiro-transacoes-detalhes')->layout('layouts.app-pep-auth');
        }

        //
        if(($this->target_ref ?? false) && ($this->target_id ?? false))
        {
            // $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.codePromo','orders.payments','orders.payments.eventCodePromo'])->find($this->target_id);
            // $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.codePromo','orders.payments','orders.payments.eventCodePromo'])->find($this->target_id);

            $this->target  = Event::find($this->target_id);

            //
            if($this->target->organizer_id != $this->organizerId)
                $this->alterarTarget();

            //
            if(($this->list_status_selected ?? false) && $this->list_status_selected != 'all')
            {
                $this->pedidos = AppEventOrder::with(['payments'])->where('event_id',$this->target_id)->where('status', $this->list_status_selected)->get();
            }
            else
            {
                $this->pedidos = AppEventOrder::with(['payments'])->where('event_id',$this->target_id)->get();
            }
        }

        return view('livewire.dashboard.dashboard-financeiro-transacoes-v3')->layout('layouts.app-pep-auth');
    }

    function paymentCheckProcessed($paymentId=false,$forceUpdate=false)
    {
        // dd($this->order->id,$paymentId);

        // VALIDA ORDER
        $orderStatus = consolidaOrderPayments(orderId:$this->order->id,paymentId:($paymentId ?? false),forceUpdate:($forceUpdate ? true : false));

        //
        if($orderStatus ?? false)
        {
            // NOTIFICAÇÃO
            $this->dialog()->show([
                'title'       => __($orderStatus),
                'description' => 'Pedido Atualizado',
                'icon'        => 'success'
            ]);
        }
        else
        {
            // NOTIFICAÇÃO
            $this->dialog()->show([
                'title'       => 'Pagamento',
                'description' => 'Informações Atualizadas',
                'icon'        => 'info'
            ]);
        }

        $this->verDetalhes($this->order->id);
    }

    public function cancelarOrderSubmit($orderId)
    {
        $this->validate([
            'order_cancel_description' => ['required','string']
        ]);

        try
        {
            DB::beginTransaction();

            // GET PEDIDO
            $pedido = $this->target->orders->find($orderId);

            if($pedido ?? false)
            {
                // CANCELA TIKETS
                foreach ($pedido->tickets ?? [] as $ticketKey => $ticketValues)
                {
                    $pedido->tickets[$ticketKey]->ticket_status = 'canceled';
                    $pedido->tickets[$ticketKey]->ticket_cancel_datetime = now()->format('Y-m-d H:i:s');
                    $pedido->tickets[$ticketKey]->ticket_cancel_description = $this->order_cancel_description ?? null;
                    $pedido->tickets[$ticketKey]->save();
                }

                // CANCELA ORDER ITENS
                foreach ($pedido->itens ?? [] as $orderItemKey => $orderItemValues)
                {
                    $pedido->itens[$orderItemKey]->item_status = 'canceled';
                    $pedido->itens[$orderItemKey]->save();
                }

                // CANCELA ORDER
                $pedido->status                      = 'canceled';
                $pedido->order_cancel_datetime       = now()->format('Y-m-d H:i:s');
                $pedido->order_cancel_description    = $this->order_cancel_description ?? null;
                $pedido->reservation_expiration_date = null;
                $pedido->save();

                session()->flash('success','COMPRA CANCELADA');

                DB::commit();
            }
            else
            {
                session()->flash('error', 'Pedido não localizado');
            }

            $this->verDetalhes($orderId);
            $this->order_cancel_description = '';
            $this->cancelarOrder            = false;
        }
        catch (\Throwable $th)
        {
            session()->flash('error', $th->getMessage());
        }
    }

    public function cancelarOrder()
    {
        $this->cancelarOrder = true;
    }

    public function atualizarOrder($orderId)
    {
        $this->paymentCheckProcessed(forceUpdate:true);

        $this->verDetalhes($orderId);

        consolidaOrderPayments($orderId);
    }

    public function enviaDetalhesCompra($orderId=false,$msg=false)
    {
        // SE NAO EXISTIR ORDER
        if($orderId)
        {
            $this->order = AppEventOrder::with(['event','codePromo','itens','payments'])->find($orderId);
        }

        // SE ORDER
        if($this->order)
        {
            $job = NotificationDetalhesCompra::dispatch($this->target_ref,$this->order->id, $this->order->buyer_email, config('mail.bcc_noreply'));

            $this->verDetalhes($this->order->id);

            //
            if($msg ?? false)
            {
                session()->flash('success','Email preparado com sucesso');
            }

            session()->flash('success_sub_lc','EMAIL COM OS DETALHES DA COMPRA FOI ENVIADO PARA ' . $this->order->buyer_email . ' - ' . now()->format('d/m/Y H:i'));
        }
    }

    public function removerPagamentoManualSubmit($paymentId)
    {
        // BUSCA PAGAMENTO
        if ($payment = AppPayment::find($paymentId))
        {
            if (!$this->canEditPayment($payment)) {
                return session()->flash('error', 'Você não possui permissão para remover este pagamento');
            }

            $payment->delete();

            // VERIFICA PEDIDO
            consolidaOrderPayments($this->order->id);

            // ATUALIZA ORDER
            $this->verDetalhes($this->order->id);

            $this->resetPagamentoManual();

            $this->addPay           = true;
            $this->addPayManual     = false;

            return session()->flash('success','Pagamento removido');
        }

        session()->flash('error','Pagamento não localizado');
    }

    public function alterarPagamentoManual($paymentId=false)
    {
        $this->paymentId = $paymentId;

        //
        if ($payment = $this->orderPayments->find($paymentId))
        {
            if (!$this->canEditPayment($payment)) {
                return session()->flash('error', 'Você não possui permissão para alterar este pagamento');
            }

            $this->pay_type         = $payment->pay_type;
            $this->value_paid       = toMoney($payment->value_paid);
            $this->pay_nsu          = $payment->pay_nsu;
            $this->pay_datetime     = $payment->pay_datetime ? \Carbon\Carbon::parse($payment->pay_datetime)->format('Y-m-d') : null;
            $this->status           = $payment->status;
            $this->app_ref          = $payment->app_ref;
            $this->app_ref_order_id = $payment->app_ref_order_id;
            $this->gateway_id       = $payment->gateway_id;
            $this->gateway_slug     = $payment->gateway_slug;
            $this->editingGatewaySlug = $payment->gateway_slug;
            $this->pay_card_brand   = $payment->pay_card_brand;
            $this->pay_card_name    = $payment->pay_card_name;
            $this->pay_card_last    = $payment->pay_card_last;
            $this->pay_pix_key      = $payment->pay_pix_key;
            //
            $this->addPayManual = true;
        }
    }

    public function addPagamentoManual()
    {
        if ($this->order && $this->order->order_control) {
            return redirect()->route('dashboard-evento-vendas-controle-modificar', [
                'controle' => $this->order->order_control,
            ]);
        }

        $this->addPay = true;
        $this->resetPagamentoManual();
    }

    public function addPagamentoManualSlip($slipId)
    {
        $slip = \App\Models\AppPayment\AppPaymentSlip::find($slipId);
        if (!$slip) {
            return session()->flash('error', 'Parcela não localizada');
        }

        $this->resetPagamentoManual();
        $this->slipPaymentSlipId = $slipId;
        $this->value_paid = toMoney($slip->installment_value);
        $this->addPayManual = true;
    }

    public function fecharModificarPagamentos()
    {
        if ($this->order && $this->order->order_control) {
            return redirect()->route('dashboard-evento-vendas-controle', [
                'controle' => $this->order->order_control,
            ]);
        }

        $this->addPay = false;
    }

    public function abrirEditarPagamentoNoExibir($paymentId)
    {
        if (!isAdmin()) {
            return session()->flash('error', 'Você não possui permissão para alterar este pagamento');
        }

        if (!$this->order) {
            return session()->flash('error', 'Pedido não localizado');
        }

        $payment = $this->order->payments->find($paymentId);
        if (!$payment) {
            return session()->flash('error', 'Pagamento não localizado');
        }

        $this->editPaymentId = $payment->id;
        $this->edit_pay_type = $payment->pay_type;
        $this->edit_value_paid = toMoney($payment->value_paid);
        $this->edit_pay_nsu = $payment->pay_nsu;
        $this->edit_pay_datetime = $payment->pay_datetime
            ? \Carbon\Carbon::parse($payment->pay_datetime)->format('Y-m-d')
            : null;
        $this->edit_pay_card_brand = $payment->pay_card_brand;
        $this->edit_pay_card_name = $payment->pay_card_name;
        $this->edit_pay_card_last = $payment->pay_card_last;
        $this->edit_pay_pix_key = $payment->pay_pix_key;
        $this->edit_status = $payment->status;
        $this->edit_value_fees = toMoney($payment->value_fees ?? 0);
        $this->edit_order_slip_id = $payment->order_slip_id;

        $this->modalEditPagamentoExibir = true;
    }

    public function salvarEditarPagamentoNoExibir()
    {
        if (!isAdmin()) {
            return session()->flash('error', 'Você não possui permissão para alterar este pagamento');
        }

        $payment = AppPayment::find($this->editPaymentId);
        if (!$payment || (int) $payment->app_ref_order_id !== (int) ($this->order->id ?? 0)) {
            return session()->flash('error', 'Pagamento não localizado');
        }

        $isEditingNonManual = !in_array($payment->gateway_slug ?? null, ['user_dashboard', 'manual', 'presencial'], true);

        $rules = [
            'edit_pay_type' => ['required'],
            'edit_value_paid' => ['required'],
            'edit_value_fees' => ['nullable'],
            'edit_pay_nsu' => ['nullable'],
            'edit_pay_datetime' => $isEditingNonManual ? ['nullable', 'date'] : ['required', 'date'],
            'edit_status' => ['required', 'string'],
        ];

        if (in_array($this->edit_pay_type, ['card_credit', 'CREDIT_CARD'], true)) {
            $rules = array_merge($rules, [
                'edit_pay_card_brand' => ['nullable', 'string'],
                'edit_pay_card_name' => ['nullable', 'string'],
                'edit_pay_card_last' => ['nullable', 'string'],
            ]);
        }

        if ($this->edit_pay_type === 'transfer_pix') {
            $rules = array_merge($rules, [
                'edit_pay_pix_key' => ['nullable', 'string'],
            ]);
        }

        $this->validate($rules);

        try {
            $this->edit_value_paid = str_replace('.', '', (string) $this->edit_value_paid);
            $this->edit_value_paid = str_replace(',', '.', (string) $this->edit_value_paid);
            $this->edit_value_paid = str_contains($this->edit_value_paid, '.') ? $this->edit_value_paid : $this->edit_value_paid . '.00';
            $valuePaid = toMoneyInt($this->edit_value_paid);
            $editValueFees = str_replace('.', '', (string) ($this->edit_value_fees ?? '0'));
            $editValueFees = str_replace(',', '.', $editValueFees);
            $editValueFees = str_contains($editValueFees, '.') ? $editValueFees : $editValueFees . '.00';
            $valueFees = toMoneyInt($editValueFees);

            if ($valuePaid < 1) {
                return session()->flash('error', 'O valor precisa ser maior que 0,00');
            }

            if ($valueFees < 0 || $valueFees > $valuePaid) {
                return session()->flash('error', 'Encargos inválidos para o valor pago');
            }

            $valueLiquid = $valuePaid - $valueFees;

            $data = [
                'pay_type' => $this->edit_pay_type,
                'pay_nsu' => empty($this->edit_pay_nsu) ? null : $this->edit_pay_nsu,
                'pay_datetime' => empty($this->edit_pay_datetime) ? null : $this->edit_pay_datetime,
                'status' => $this->edit_status,
                'value_paid' => $valuePaid,
                'value_liquid' => $valueLiquid,
                'value_fees' => $valueFees,
                'pay_value_paid' => $valuePaid,
                'value_amortization' => $valueLiquid,
                'pay_installment_value' => $payment->pay_installments_number > 1
                    ? ($payment->pay_installment_value ?? $valuePaid)
                    : $valuePaid,
                'paid_label' => toMoney($valuePaid, 'R$ '),
                'pay_card_brand' => $this->edit_pay_card_brand,
                'pay_card_name' => $this->edit_pay_card_name,
                'pay_card_last' => $this->edit_pay_card_last,
                'pay_pix_key' => $this->edit_pay_pix_key,
                'order_slip_id' => $this->edit_order_slip_id ?: null,
            ];

            if ($isEditingNonManual && empty($data['pay_datetime'])) {
                $data['pay_datetime'] = $payment->pay_datetime;
            }

            // SE MUDOU A PARCELA ASSOCIADA, ATUALIZA SLIPS
            $oldSlipId = $payment->order_slip_id;
            $newSlipId = $this->edit_order_slip_id ?: null;

            DB::beginTransaction();
            $payment->update($data);

            // SE DESASSOCIOU DA PARCELA ANTIGA
            if ($oldSlipId && $oldSlipId !== $newSlipId) {
                $oldSlip = \App\Models\AppPayment\AppPaymentSlip::find($oldSlipId);
                if ($oldSlip) {
                    // Verifica se ainda há outros pagamentos pagos nessa parcela
                    $otherPaidPayments = \App\Models\AppPayment\AppPayment::where('order_slip_id', $oldSlipId)
                        ->where('id', '!=', $payment->id)
                        ->whereIn('status', listPaymentStatusPaid())
                        ->exists();
                    if (!$otherPaidPayments) {
                        $oldSlip->update([
                            'paid_datetime' => null,
                            'paid_value' => null,
                            'paid_label' => null,
                            'installment_value_amortization' => null,
                            'status' => 'aguardando_pagamento',
                            'payment_id' => null,
                        ]);
                    }
                }
            }

            // SE ASSOCIOU A UMA NOVA PARCELA E ESTÁ PAGO
            if ($newSlipId && in_array($this->edit_status, listPaymentStatusPaid())) {
                $newSlip = \App\Models\AppPayment\AppPaymentSlip::find($newSlipId);
                if ($newSlip) {
                    $newSlip->update([
                        'paid_datetime' => $data['pay_datetime'] ?? now()->format('Y-m-d H:i:s'),
                        'paid_value' => $valuePaid,
                        'paid_label' => $data['paid_label'],
                        'installment_value_amortization' => $valueLiquid,
                        'status' => 'paid',
                        'payment_id' => $payment->id,
                    ]);

                    // Abre próxima parcela
                    if ($paymentNext = $newSlip->paymentNext) {
                        if (!in_array($paymentNext->status, listPaymentStatusPaid())) {
                            $paymentNext->update(['status' => 'aguardando_pagamento']);
                        }
                    }
                }
            }

            DB::commit();

            consolidaOrderPayments($this->order->id);
            $this->verDetalhes(orderId: $this->order->id);
            $this->fecharEditarPagamentoNoExibir();

            session()->flash('success', 'Pagamento alterado com sucesso');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }

    public function fecharEditarPagamentoNoExibir()
    {
        $this->modalEditPagamentoExibir = false;
        $this->editPaymentId = null;
        $this->edit_pay_type = null;
        $this->edit_value_paid = null;
        $this->edit_pay_nsu = null;
        $this->edit_pay_datetime = null;
        $this->edit_pay_card_brand = null;
        $this->edit_pay_card_name = null;
        $this->edit_pay_card_last = null;
        $this->edit_pay_pix_key = null;
        $this->edit_status = null;
        $this->edit_value_fees = null;
        $this->edit_order_slip_id = null;
    }

    public function abrirEditarSlip($slipId)
    {
        $slip = \App\Models\AppPayment\AppPaymentSlip::find($slipId);
        if (!$slip) {
            return session()->flash('error', 'Parcela não localizada');
        }

        $this->editSlipId = $slip->id;
        $this->editSlipDescription = $slip->installment_description;
        $this->editSlipDateDue = $slip->installment_date_due ? \Carbon\Carbon::parse($slip->installment_date_due)->format('Y-m-d') : null;
        $this->editSlipValue = toMoney($slip->installment_value);
        $this->editSlipStatus = $slip->status;
        $this->modalEditSlip = true;
    }

    public function salvarEditarSlip()
    {
        $this->validate([
            'editSlipDescription' => ['required', 'string'],
            'editSlipDateDue' => ['required', 'date'],
            'editSlipValue' => ['required'],
            'editSlipStatus' => ['required', 'string'],
        ]);

        $slip = \App\Models\AppPayment\AppPaymentSlip::find($this->editSlipId);
        if (!$slip) {
            return session()->flash('error', 'Parcela não localizada');
        }

        try {
            $valueStr = str_replace('.', '', (string) $this->editSlipValue);
            $valueStr = str_replace(',', '.', $valueStr);
            $valueStr = str_contains($valueStr, '.') ? $valueStr : $valueStr . '.00';
            $valueInt = toMoneyInt($valueStr);

            if ($valueInt < 1) {
                return session()->flash('error', 'O valor precisa ser maior que 0,00');
            }

            DB::beginTransaction();

            $slip->update([
                'installment_description' => $this->editSlipDescription,
                'installment_date_due' => $this->editSlipDateDue,
                'installment_value' => $valueInt,
                'status' => $this->editSlipStatus,
            ]);

            DB::commit();

            $this->fecharEditarSlip();
            $this->verDetalhes(orderId: $this->order->id);

            session()->flash('success', 'Parcela alterada com sucesso');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', $th->getMessage());
        }
    }

    public function fecharEditarSlip()
    {
        $this->modalEditSlip = false;
        $this->editSlipId = null;
        $this->editSlipDescription = null;
        $this->editSlipDateDue = null;
        $this->editSlipValue = null;
        $this->editSlipStatus = null;
    }

    public function extenderExpiracao(int $horas)
    {
        if (!isAdmin()) {
            return session()->flash('error', 'Você não possui permissão para esta ação');
        }

        if (!in_array($horas, [1, 6, 12, 24])) {
            return session()->flash('error', 'Valor de extensão inválido');
        }

        if (!$this->order) {
            return session()->flash('error', 'Pedido não localizado');
        }

        try {
            $order = AppEventOrder::find($this->order->id);

            if (!$order) {
                return session()->flash('error', 'Pedido não localizado');
            }

            $base = $order->reservation_expiration_date ?? now();
            $novaExpiracao = $base->addHours($horas);

            $data = ['reservation_expiration_date' => $novaExpiracao];

            // Se estiver expirado, reativar para aguardando pagamento
            if ($order->status === 'expired_order') {
                $data['status'] = 'aguardando_pagamento';
            }

            $order->update($data);

            $this->verDetalhes($order->id);

            $this->dialog()->show([
                'title'       => 'Prazo Estendido',
                'description' => "+{$horas}h adicionados. Nova expiração: " . $novaExpiracao->format('d/m/Y H:i'),
                'icon'        => 'success',
            ]);
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
        }
    }

    public function addPagamentoManualSubmit($paymentId=false)
    {
        $paymentToEdit = $paymentId ? AppPayment::find($paymentId) : null;
        $isEditingNonManual = $paymentToEdit
            && !in_array($paymentToEdit->gateway_slug ?? null, ['user_dashboard', 'manual', 'presencial'], true);

        $rules = [
            'pay_type'          => ['required'],
            'pay_nsu'           => ['nullable'],
            'pay_datetime'      => $isEditingNonManual ? ['nullable', 'date'] : ['required', 'date'],
            'value_paid'        => ['required'],
        ];

        //
        if(in_array($this->pay_type,['card_credit','CREDIT_CARD']))
        {
            $rules = array_merge($rules,[
                'pay_card_brand' => ['required','string'],
                'pay_card_name'  => ['nullable','string'],
                'pay_card_last'  => ['nullable','string'],
            ]);
        }

        //
        if($this->pay_type == 'transfer_pix')
        {
            $rules = array_merge($rules,[
                'pay_pix_key'  => ['nullable','string'],
            ]);
        }

        // VALIDATE
        $validatedData = $this->validate($rules);

        try
        {
            //
            $this->value_paid = str_replace('.','', (string) $this->value_paid);
            $this->value_paid = str_replace([','],'.', (string) $this->value_paid);
            $this->value_paid = str_contains($this->value_paid,'.') ? $this->value_paid : $this->value_paid . '.00';
            $value_paid       = toMoneyInt($this->value_paid);

            //
            if($value_paid < 1)
            {
                return session()->flash('error','O valor precisa ser maior que 0,00');
            }

            //
            $validatedData['pay_installments_number'] = 1;
            $validatedData['pay_installment_value']   = $value_paid;
            $validatedData['value_paid']              = $value_paid;
            $validatedData['value_liquid']            = $value_paid;
            $validatedData['pay_value_paid']          = $value_paid;
            $validatedData['value_amortization']      = $value_paid;

            //
            $validatedData['status']                  = 'paid';
            $validatedData['app_ref']                 = $this->target_ref;
            $validatedData['app_ref_order_id']        = $this->order->id;
            $validatedData['gateway_id']              = Auth::user()->id ?? null;
            $validatedData['gateway_slug']            = 'manual';
            $validatedData['pay_nsu']                 = empty($validatedData['pay_nsu']) ? null : $validatedData['pay_nsu'];
            $validatedData['value_fees']              = 0;
            $validatedData['paid_label']              = toMoney(toMoneyInt($this->value_paid),'R$ ');
            $validatedData['description']             = 'LANÇAMENTO MANUAL';
            $validatedData['paid_description']        = 'PAGAMENTO MANUAL via DASHBOARD';

            // SE VINCULADO A UMA PARCELA DE CARNÊ
            if ($this->slipPaymentSlipId ?? false) {
                $validatedData['order_slip_id'] = $this->slipPaymentSlipId;
                $validatedData['description'] = 'LANÇAMENTO MANUAL - PARCELA CARNÊ';
                $validatedData['paid_description'] = 'PAGAMENTO MANUAL PARCELA via DASHBOARD';
            }

            DB::beginTransaction();

            // SALVA PAGAMENTO
            if ($paymentId && $payment = $paymentToEdit)
            {
                if (!$this->canEditPayment($payment)) {
                    DB::rollBack();
                    return session()->flash('error', 'Você não possui permissão para alterar este pagamento');
                }

                if ($isEditingNonManual && empty($validatedData['pay_datetime'])) {
                    $validatedData['pay_datetime'] = $payment->pay_datetime;
                }

                // Se admin editar pagamento não-manual, mantém os dados de origem do gateway.
                if (
                    isAdmin()
                    && !in_array($payment->gateway_slug ?? null, ['user_dashboard', 'manual', 'presencial'], true)
                ) {
                    $validatedData['app_ref'] = $payment->app_ref;
                    $validatedData['app_ref_order_id'] = $payment->app_ref_order_id;
                    $validatedData['gateway_id'] = $payment->gateway_id;
                    $validatedData['gateway_slug'] = $payment->gateway_slug;
                    $validatedData['description'] = $payment->description ?? 'PAGAMENTO AJUSTADO VIA DASHBOARD';
                    $validatedData['paid_description'] = $payment->paid_description ?? 'PAGAMENTO AJUSTADO VIA DASHBOARD';
                }

                $payment->update($validatedData);
                $payment->save();

                session()->flash('success','Pagamento alterado com sucesso');
            }
            else
            {
                $payment = AppPayment::create($validatedData);
                session()->flash('success','Pagamento adicionado com sucesso');
            }

            // SE VINCULADO A UMA PARCELA, ATUALIZA STATUS DA PARCELA
            if ($this->slipPaymentSlipId ?? false) {
                $slip = \App\Models\AppPayment\AppPaymentSlip::find($this->slipPaymentSlipId);
                if ($slip) {
                    $slip->update([
                        'paid_datetime' => $validatedData['pay_datetime'] ?? now()->format('Y-m-d H:i:s'),
                        'paid_value' => $value_paid,
                        'paid_label' => $validatedData['paid_label'],
                        'installment_value_amortization' => $value_paid,
                        'status' => 'paid',
                        'payment_id' => $payment->id,
                    ]);

                    // ABRE O PRÓXIMO PAGAMENTO
                    if ($paymentNext = $slip->paymentNext) {
                        if (!in_array($paymentNext->status, listPaymentStatusPaid())) {
                            $paymentNext->update([
                                'status' => 'aguardando_pagamento',
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            // da($payment);

            // VERIFICA PEDIDO
            $s = consolidaOrderPayments($this->order->id);

            // ATUALIZA ORDER
            $this->verDetalhes(orderId:$this->order->id);

            // RESET
            $this->addPay              = true;
            $this->addPayManual        = false;
            $this->paymentId           = null;
            $this->slipPaymentSlipId   = null;
            $this->status              = null;
            $this->app_ref             = null;
            $this->app_ref_order_id    = null;
            $this->gateway_id          = null;
            $this->gateway_slug        = null;
            $this->value_paid          = null;
            $this->pay_type            = null;
            $this->pay_nsu             = null;
            $this->pay_datetime        = null;
        }
        catch (\Throwable $th)
        {
            //throw $th;
            // dd($th);

            session()->flash('session_addPagamento_error', $th->getMessage());
        }
    }

    public function resetPagamentoManual()
    {
        // RESET
        $this->status           = null;
        $this->app_ref          = null;
        $this->app_ref_order_id = null;
        $this->gateway_id       = null;
        $this->gateway_slug     = null;
        $this->value_paid       = 0;
        $this->pay_type         = null;
        $this->pay_nsu          = null;
        $this->pay_datetime     = null;
        $this->pay_card_brand   = null;
        $this->pay_card_name    = null;
        $this->pay_card_last    = null;
        $this->paymentId        = null;
        $this->editingGatewaySlug = null;
        $this->slipPaymentSlipId  = null;
    }

    public function updatedPayType()
    {
        $this->pay_datetime = now()->format('Y-m-d');
    }

    public function updatedLogTrasacao()
    {
        if($this->logTrasacao ?? false)
        {
            if($this->logTrasacaoDetalhes = $this->order->payments->find($this->logTrasacao))
            {
                $array = $this->logTrasacaoDetalhes->toArray();

                $array['pay_json_request_end'] = $array['pay_json_request'];

                unset($array['pay_json_request']);

                $array['pay_json_request'] = $array['pay_json_request_end'];

                unset($array['pay_json_request_end']);

                $this->logTrasacaoDetalhes = $array;

                return;
            }
        }

        $this->logTrasacaoDetalhes = '';
    }

    public function updatedAddPayManual()
    {
        $this->resetPagamentoManual();

        if(($this->addPayManual ?? false) && 'true')
        {
            $this->addPayManual = true;
        }
        else
        {
            $this->addPayManual = false;
        }
    }

    protected function canEditPayment($payment): bool
    {
        if (!$payment) {
            return false;
        }

        if (isAdmin()) {
            return true;
        }

        return in_array($payment->gateway_slug ?? null, ['user_dashboard', 'manual', 'presencial'], true);
    }

    public function resetAll()
    {
        $this->modal_budgetItemAdd    = false;
        $this->modal_budgetItemAlt    = false;
        $this->item_nome              = '';
        $this->item_qtd               = '';
        $this->item_valor             = '';
        $this->item_nome_provedor     = '';
        $this->item_valor_investmento = '';
        $this->item_valor_pago        = '';
        $this->item_status            = '';
    }

    public function defineAction($action=false)
    {
        $this->addPay = false;
        $this->action = $action;

        switch ($this->action)
        {
            case 'adicionarPagamento':
                $this->addPay = true;
                break;
            default:
                break;
        }
    }

    public function updatedTargetId()
    {
        $this->target = false;
        sessionTargetId($this->target_id);
    }

    public function updatedTargetRef()
    {
        $this->target = false;
        $this->target_id = false;
        //
        sessionClear('target');
        sessionTargetRef($this->target_ref);
        //
        $this->getTargetList($this->target_ref);
    }

    public function getTargetList($target_ref=false)
    {
        switch ($target_ref)
        {
            case 'evento':
            case 'app_event':
                $this->target_list = Event::where('organizer_id',$this->organizerId)->get();
                break;

            case 'app_workshop':
                $this->target_list = Event::where('organizer_id',$this->organizerId)->get();
                break;

            default:
                $this->target_list = [];
                break;
        }

        return $this->target_list;
    }

    public function alterarTarget()
    {
        $this->target     = false;
        $this->target_id  = false;
        $this->target_ref = false;
        //
        sessionClear('target');
        return redirect()->route('dashboard');
    }

    public function transacoesVoltar()
    {
        $this->order   = null;
        $this->orderId = null;
        $this->defineAction(false);
        //
        sessionOrderIdClear();

        return redirect()->route('dashboard-evento-vendas');
    }

    public function getTarget($target_ref, $target_id)
    {
        switch ($target_ref) {
            case 'evento':
            case 'app_event':
                $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.payments.eventCodePromo','orders.tickets','orders.itens','ticketsTypes','tickets'])->find($target_id);
                break;

            case 'app_workshop':
                $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.payments.eventCodePromo','orders.tickets','ticketsTypes','tickets'])->find($target_id);
                break;

            default:
                $this->target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.payments.eventCodePromo','orders.tickets','ticketsTypes','tickets'])->find($target_id);
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
}
