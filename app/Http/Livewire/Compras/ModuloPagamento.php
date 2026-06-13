<?php

namespace App\Http\Livewire\Compras;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\EventTicketCodePromo;
use App\Services\Order\OrderService;
use App\Services\safe2pay\Safe2PayService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use stdClass;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;
use WireUi\Traits\Actions;

class ModuloPagamento extends Component
{
    use Actions;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    protected $messages = [
        '*.required' => 'Obrigatório',
    ];

    public $order;
    public $orderId;
    public $orderControl;
    public $payment;
    public $payments;
    public $order_amount;
    public $order_amount_payment;
    public $target;
    public $gatewayPay;
    public $payType;
    public $pixValido;
    public $orderSlip;
    public $slipPayment;
    public $debug;

    public function mount(Request $request,$orderId)
    {
        //
        $this->debug = $request->input('debug') ? true : false;

        $this->orderId = $orderId ?? false;

        //
        if($this->order = $this->getOrder($this->orderId))
        {
            $this->orderControl = $this->order->order_control;

            // SE ORDER JA FOI PAGA
            if(in_array($this->order->status,listOrderStatusPaid()))
            {
                // NOTIFICAÇÃO
                $this->dialog()->show([
                    'icon'        => 'success',
                    'title'       => 'PARABÉNS',
                    'description' => 'Está Compra Confirmada!',
                ]);

                $this->reset('order');

                return session()->flash('success','Pedido já está pago');
            }

            $this->target = $this->order->event;

            $this->gatewayPay = $this->order->event->gatewayPay;

            // SE CARNE > PEGA CARNE
            if(($this->order->slip_id ?? false) && $this->orderSlip = $this->order->paymentsSlip)
            {

                // SE EXISTIR CARNE
                if($this->orderSlip->count())
                {
                    // SE PAGAENTO
                    $this->slipPayment = $this->selecionaSlipPayment();
                }
            }
            else
            {
                $this->getFormasPagento();

                $this->order_amount_payment = $this->order->order_amount;

                $this->order_amount = $this->calculaOrderAmount($this->order);

                // SE EXISTEM PAGAMENTOS
                if($this->payments = $this->order->payments)
                {
                    if($this->order->payment_id ?? false)
                    {
                        if($this->payment = $this->payments->where('id',$this->order->payment_id)->whereNotIn("status",listPaymentStatusPaidCanceled())->first())
                        {
                            $this->payType = $this->payment->pay_type;

                            // SE PIX
                            if(in_array($this->payment->pay_type,['pix']))
                            {
                                $this->pixValido = $this->paymentCheckValidate();
                            }
                        }
                    }
                }
            }
        }
    }

    function selecionaSlipPayment($slipPaymentId=false)
    {
        $this->slipPayment = false;

        if($this->orderSlip ?? false)
        {
            if ($slipPaymentId ?? false)
            {
                $this->slipPayment = $this->orderSlip->find($slipPaymentId);
            }
            else
            {
                $now = now();

                // PEGA PAGAMENTO PENDENTE VENCIDO
                if(!$this->slipPayment = $this->orderSlip->whereIn('status',listPaymentStatusEmPagamento())->where('installment_date_due', '<=', $now->addDays(7)->format('Y-m-d 23:59:59'))->sortBy('installment_date_due')->first())
                {
                    if($slipPaymentSelectId = Session::get('slipPaymentSelectId'))
                    {
                        $this->slipPayment = $this->orderSlip->find($slipPaymentSelectId);
                    }
                }
            }

            if($this->slipPayment ?? false)
            {
                if(in_array($this->slipPayment->status,listPaymentStatusPaid()))
                {

                    $this->reset('slipPayment','pixValido','payment');
                    sessionClear('slipPaymentSelectId');
                }
                else
                {
                    Session::put('slipPaymentSelectId', $this->slipPayment->id);

                    $this->payType = $this->slipPayment->installment_pay_type ?? 'pix';

                    if($this->payment = $this->slipPayment->payment)
                    {
                        $this->payType = $this->payment->pay_type;

                        // SE PIX
                        if(in_array($this->payment->pay_type,['pix','pending_pix','slip_pix','pending_slip_pix']))
                        {
                            $this->pixValido = $this->paymentCheckValidate();
                        }
                    }
                }
            }
            else
            {
                sessionClear('slipPaymentSelectId');
            }
        }

        return $this->slipPayment;
    }

    function calculaOrderAmount($order): mixed
    {
        $order_amount = 0;

        // NESSA ORDEM > POSSUI DESCONTO EM TEMPO DE TELA
        if($this->code_promo_id ?? false)
        {
            $order_amount = ($this->code_promo_price_new ?? ($order->order_amount - $order->code_promo_price_less)) - ($order->order_amount_pay ?? 0);
        }
        elseif($order->code_promo_id ?? false) // NESSA ORDEM > POSSUI DESCONTO CADASTRADO SOLICITAÇÃO
        {
            $order_amount = ($order->code_promo_price_new ?? ($order->order_amount - $order->code_promo_discount_amount)) - ($order->order_amount_pay ?? 0);
        }
        else // SEM DESCONTO
        {
            $order_amount = $order->order_amount - ($order->order_amount_pay ?? 0);
        }

        // AJUSTA SE NAO FOI PAGO A MAIS
        if($order_amount > $order->order_amount)
        {
            $order_amount = $order->order_amount;
        }

        return $order_amount;
    }

    function paymentReset($paymentValidateStatus=null)
    {
        $now = now();

        $this->payment->update([
            'status_old' => $this->payment->status,
            'status'     => $paymentValidateStatus ?? 'expired',
        ]);

        $this->order->update([
            'status_old'                  => $this->order->status,
            'status_old_datetime'         => $now->format('Y-m-d H:i:s'),
            'status'                      => 'pendente_pagamento',
            'reservation_expiration_date' => $now->addMinutes(30)->format('Y-m-d H:i:s'),
            'payment_id'                  => null,
        ]);

        session()->flash('forma_pagamento_warning', 'O pagamento anterior expirou');
        session()->flash('forma_pagamento_warning_sub', 'Selecione uma forma de pagamento para tentar novamente');
    }

    function paymentCheckProcessed($forceUpdate=false)
    {
        try
        {
            // VALIDA ORDER
            $orderStatus = consolidaOrderPayments(orderId:$this->order->id,paymentId:($this->payment->id ?? false),forceUpdate:($forceUpdate ? true : false));

            $this->order = $this->getOrder($this->order->id);

            // SE ORDER PAGAMENTO TOTAL
            if(in_array($this->order->status,listOrderStatusPaid()))
            {
                sessionClear('slipPaymentSelectId');

                $this->reset('payType','payment');

                return redirect()->route('compra-exibir',[
                    'localizador' => $this->order->order_control,
                    'timestamp'   => now()->format('His')
                ]);
            }

            // SE PAYMENT
            if($this->payment ?? false)
            {
                // PEGA PAGAMENTO ATUALIZADO
                if($this->payment = $this->order->payments->find($this->payment->id))
                {
                    if(in_array($this->payment->status,listPaymentStatusPaid()))
                    {
                        if($this->order->payment_id)
                        {
                            $this->order->payment_id = null;
                            $this->order->save();
                        }

                        $this->reset('payType','payment');

                        $this->order_amount = $this->calculaOrderAmount($this->order);

                        return redirect()->route('compra-exibir',[
                            'localizador' => $this->order->order_control,
                            'timestamp'   => now()->format('His'),
                            'orderStep'   => __FUNCTION__ . __LINE__,
                        ]);

                        return true;
                    }
                }
            }

            return false;
        }
        catch (\Throwable $th)
        {
            if(($this->order ?? false) && ($this->order->buyer_email == "proeventpay@gmail.com"))
            {
                dd('Throwable by Debug',__FUNCTION__,__LINE__, $th,);
            }

            $this->error = TRUE;
            session()->flash('error',$th->getMessage());
            session()->flash('error_sub','('.$th->getCode().')');
            return false;
        }
    }

    function paymentCheckValidate($reset=true)
    {
        // START
        $paymentValidate = true;
        $this->pixValido = false;

        //
        if(!in_array($this->payment->status,listPaymentStatusPaid()))
        {
            // SE PIX
            if(in_array($this->payment->pay_type,['pix','slip_pix']))
            {
                if($this->payment->pay_pix_expires_at ?? false)
                {
                    //
                    if(!$this->pixValido = (calculaSegundosDif($this->payment->pay_pix_expires_at) > 0))
                    {
                        session()->flash('pix_alert','O PIX anterior excedeu o prazo limite');
                        session()->flash('pix_alert_sub','Gere uma nova chave para pagamento');

                        $paymentValidate = false;
                        $paymentValidateStatus = 'expired';
                    }
                }
            }

            if(!$paymentValidate ?? false)
            {
                // RESERTA O PAGAMENTO + ORDER
                if($reset ?? false)
                {
                    $this->paymentReset($paymentValidateStatus ?? false);
                }

                return false;
            }
        }

        return true;
    }

    function getOrder($orderId)
    {
        return AppEventOrder::with(['payments','event','event.gatewayPay'])->find($orderId);
    }

    public $formaPagamento;
    public $formaPagamentoDisponivel;
    public $formaPagamentoSelecionada;
    public function getFormasPagento()
    {
        //
        $this->formaPagamentoDisponivel = [];

        // PIX
        if(($this->gatewayPay->pay_pix ?? false) && ($this->target->pay_pix ?? false))
        {
            $this->formaPagamentoDisponivel['pix'] = [
                'slug'        => 'pix',
                'label'       => 'PIX' ,
                'descricao'   => 'Liberação imediata',
                'parcelado'   => 1,
                'valorMinimo' => 100,
            ];
        }

        // CARTAO DE CREDITO
        if(($this->gatewayPay->pay_card_credit ?? false) && ($this->target->pay_card_credit ?? false))
        {
            $this->formaPagamentoDisponivel['card_credit'] = [
                'slug'        => 'card_credit',
                'label'       => 'Cartão de Crédito' ,
                'descricao'   => 'Liberação imediata',
                'parcelado'   => $this->target->pay_card_credit_installment_max ?? 1,
                'valorMinimo' => $this->target->pay_card_credit_installment_amount_min ?? 1,
            ];
        }

        // BOLETO
        if(($this->gatewayPay->pay_boleto ?? false) && ($this->target->pay_boleto ?? false) && (\Carbon\Carbon::parse($this->target->event_datetime_start)->format('Ymd') - now()->format('Ymd')) >= 4)
        {
            $this->formaPagamentoDisponivel['boleto'] = [
                'slug'        => 'boleto',
                'label'       => 'Boleto' ,
                'descricao'   => 'Dois ou mais dias úteis para liberação',
                'parcelado'   => 1,
                'valorMinimo' => 1,
            ];
        }

        // CARNE ONLINE
        if(($this->gatewayPay->pay_slip_pix ?? false) && ($this->target->pay_slip_pix ?? false))
        {
            $this->formaPagamentoDisponivel['slip_pix'] = [
                'slug'        => 'slip_pix',
                'label'       => 'CARNÊ' ,
                'descricao'   => 'Liberação após quitação',
                'parcelado'   => 1,
                'valorMinimo' => 100,
            ];
        }

        if ($this->order->order_control == "EV.25050159.6A570CD8")
        {
            $this->formaPagamentoDisponivel['slip_boleto'] = [
                'slug'        => 'slip_boleto',
                'label'       => 'CARNÊ BOLETO' ,
                'descricao'   => 'Liberação após quitação',
                'parcelado'   => 1,
                'valorMinimo' => 100,
            ];
        }


        // INICIA PARCELADO
        if(!$this->pay_installments_number) $this->pay_installments_number = 1;

        // SE EXISTE payType
        if($this->payType && array_key_exists($this->payType, $this->formaPagamentoDisponivel))
        {
            $this->formaPagamentoSelecionada = $this->formaPagamentoDisponivel[$this->payType];
            $this->updated('payType', $this->payType);
        }

        return $this->formaPagamento;
    }

    public $calculoParcelas;
    public $installment_max;
    public $installment_amount_min;
    public $pay_installments_number;
    public $pay_installments_number_slip;
    public $pagamento_parcelas;
    public function calculaParcelas($payTypeEnviado=false,$orderAmountEnviado=false,$preserveInstallments=false,$slipQtdSelecao=false)
    {
        //
        $calculaPayType     = $payTypeEnviado ? $payTypeEnviado : $this->payType;
        $calculaOrderAmount = $orderAmountEnviado ? $orderAmountEnviado : $this->order_amount;

        //
        if($calculaPayType ?? false)
        {
            //
            $this->formaPagamentoSelecionada = $this->formaPagamentoDisponivel[$calculaPayType] ?? false;

            //
            if ($calculaPayType == 'card_credit')
            {
                // GET TAXAS
                $taxas = json_decode($this->gatewayPay->pay_gateway_installment_fees_json, true);

                // APLICA TAXAS
                $apply_installment_fees = $this->gatewayPay->apply_installment_fees;

                //
                if($this->target->pay_card_credit_installment_max ?? false)
                {
                    $this->installment_max = $this->target->pay_card_credit_installment_max ?? false;
                }
                else
                {
                    $this->installment_max = $this->gatewayPay->pay_card_credit_installment_max ?? false;
                }

                $this->pagamento_parcelas = [];

                //
                foreach (range(1, $this->installment_max ?? 1) as $parcelaKey => $parcela)
                {
                    $amount           = toMoneyDot($calculaOrderAmount);
                    $order_amount     = toMoneyDot($calculaOrderAmount);
                    $parcela_valor    = toMoneyDot($calculaOrderAmount) / $parcela;
                    $amortiza_valor   = toMoneyDot($calculaOrderAmount) / $parcela;
                    $taxa             = 0;
                    $encargos         = 0;
                    $taxaAntecipacao  = 0;

                    //
                    $aplicaTaxa = false;

                    if($parcela != 1)
                    {
                        // APLICAR TAXAS CLIENTE
                        if($apply_installment_fees ?? false)
                        {
                            // Taxa em porcentagem
                            $taxa = (float) $taxas[$parcela] ?? 0;

                            // Cálculo do valor inicial necessário
                            $order_amount = $amount / (1 - ($taxa / 100));

                            //
                            $aplicaTaxa = true;
                        }

                        // CALCULA ENGARGOS
                        $encargos = $order_amount - $amount;

                        // PROCESSA OS VALORES
                        $parcela_valor  = $order_amount / $parcela;

                        // PROCESSA OS VALORES
                        $parcela_valor  = $order_amount / $parcela;

                        // PARCELA MINIMA
                        if(toMoneyInt($parcela_valor) < ($this->formaPagamentoSelecionada['valorMinimo'] ?? $this->gatewayPay->pay_card_credit_installment_amount_min))
                        {
                            continue;
                        }
                    }

                    // SET LABEL
                    $label = ($parcela . 'x ' .toMoney(toMoneyInt($parcela_valor), 'R$ '));

                    // DFINE PARCELA
                    $this->pagamento_parcelas[$parcela] = [
                        'parcela_qtd'               => $parcela,
                        'label'                     => $label,
                        'taxa'                      => $taxa,
                        'order_amount'              => toMoneyInt($order_amount),
                        'order_amount_amortization' => $calculaOrderAmount,
                        'encargos'                  => toMoneyInt($encargos),
                        'parcela_valor'             => toMoneyInt($parcela_valor),
                        'parcela_valor_encargos'    => toMoneyInt($encargos / $parcela),
                        'parcela_valor_amortiza'    => toMoneyInt($amortiza_valor),
                        'aplicaTaxa'                => $aplicaTaxa,
                    ];
                }

                //
                $this->calculoParcelas = $this->pagamento_parcelas;
            }
            elseif ($calculaPayType == 'slip_pix')
            {
                // APLICA TAXAS
                $apply_installment_fees = $this->gatewayPay->apply_installment_fees;

                // GET TAXAS
                if($apply_installment_fees ?? false)
                {
                    $taxas = json_decode($this->gatewayPay->pay_slip_pix_fees_json, true);
                }
                else
                {
                    $taxas = false;
                }

                // MAXIMO DE PARCELAS PERMITIDO
                if($this->target->pay_slip_pix_installment_max ?? false)
                {
                    $this->installment_max = $this->target->pay_slip_pix_installment_max ?? false;
                }
                else
                {
                    $this->installment_max = $this->gatewayPay->pay_slip_pix_installment_max ?? false;
                }

                // SE installment_max CABE NO EVENTO
                if($this->target->pay_slip_pix_installment_max_days_before ?? false)
                {
                    // DATA EVENTO
                    $dateNow = now();

                    // SE 999 > APOS EVENTO, VALE O MÁXIMO DE PARCELAS
                    if(($this->target->pay_slip_pix_installment_max_event_date_finish ?? false) != 999)
                    {
                        // SE LIMITE FOR O FINAL DO EVENTO
                        if($this->target->pay_slip_pix_installment_max_event_date_finish ?? false)
                        {
                            // Fallback para event_datetime_start se event_datetime_finish for null
                            $refDate   = $this->target->event_datetime_finish ?? $this->target->event_datetime_start;
                            $eventDateCalc = Carbon::parse(\Carbon\Carbon::parse($refDate)->format('Y-m-d'));
                        }
                        else
                        {
                            $eventDateCalc = Carbon::parse(\Carbon\Carbon::parse($this->target->event_datetime_start)->format('Y-m-d'));
                        }

                        // CALCULA MESES
                        $diffMonths = (now()->diffInMonths($eventDateCalc) ?? 0) + 1; // MES ATUAL

                        // SE MAX DEFINIDO MAIOR QUE QTD MESES // QTD MESES
                        if($this->installment_max > $diffMonths)
                        {
                            $this->installment_max = $diffMonths;
                        }
                    }
                }

                // SE USUARIO SELECIONOU PARCELAMENTO
                if($slipQtdSelecao ?? false)
                {
                    // SE SELECAO MENOR QUE MAX
                    // if($slipQtdSelecao < $this->installment_max)
                    // {
                    //     $this->installment_max = $slipQtdSelecao;
                    // }
                }

                // PEGA VALOR PARCELA MINIMA
                if($this->installment_amount_min = $this->target->pay_slip_pix_installment_amount_min ?? FALSE)
                {
                    // DEFINE A QTD REAL DE PARCELAS MAXIMA
                    $qtdParcelaPorValorMin = (int) ((toMoneyInt($calculaOrderAmount) / $this->installment_amount_min) / 100); // PARA ANDAR DUAS CASAS

                    // AJUSTA PARCELAMENTO MAXIMO
                    if($qtdParcelaPorValorMin < $this->installment_max)
                    {
                        $this->installment_max = $qtdParcelaPorValorMin;
                    }
                }

                // GARANTE NAO SER MENOR QUE ZERO
                if($this->installment_max < 1)
                {
                    $this->installment_max = 1;

                    session()->flash('forma_pagamento_info','Valor mínimo ' . toMoney($calculaOrderAmount,'R$ '));
                    session()->flash('forma_pagamento_info_sub','Parcelamento máximo ' . ($this->installment_max) . 'x');
                }

                // SE EXISTIR
                if($this->pay_installments_number_slip ?? false)
                {
                    // SE FOR MAIOR QUE O MAX
                    if($this->pay_installments_number_slip > $this->installment_max)
                    {
                        $this->pay_installments_number_slip = $this->installment_max;
                    }
                }
                else
                {
                    $this->pay_installments_number_slip = $this->installment_max;
                }

                //
                $amount          = toMoneyDot($calculaOrderAmount);
                $order_amount    = toMoneyDot($calculaOrderAmount);
                $parcela_valor   = toMoneyDot($calculaOrderAmount) / ($this->pay_installments_number_slip ?? $this->installment_max);
                $amortiza_valor  = toMoneyDot($calculaOrderAmount) / ($this->pay_installments_number_slip ?? $this->installment_max);
                $taxa            = 0;
                $encargos        = 0;

                //
                $aplicaTaxa = false;

                // SE APLICAR TAXAS
                if($apply_installment_fees ?? false)
                {
                    // Taxa em porcentagem
                    $taxa = (float) $taxas[$this->installment_max] ?? 0;

                    // Cálculo do valor inicial necessário
                    $order_amount = $amount / (1 - ($taxa / 100));

                    //
                    $encargos = $order_amount - $amount;

                    // PEGA VALOR PARCELA
                    $parcela_valor = $order_amount / ($this->pay_installments_number_slip ?? $this->installment_max);

                    //
                    $aplicaTaxa = true;
                }

                $now = now();

                $this->pagamento_parcelas = [];

                //
                foreach (range(1, $this->pay_installments_number_slip ?? ($this->installment_max ?? 1)) as $parcelaKey => $parcela)
                {
                    // SE NAO FOR ZERO
                    if($parcelaKey ?? false)
                    {
                        $now->addMonth();
                    }

                    $vencimento = $now->format('d/m/Y');
                    $date_due   = $now->format('Y-m-d 23:59:59');

                    // SET LABEL
                    $label = ('Parcela ' . $parcela . ' de ' . ($this->pay_installments_number_slip ?? $this->installment_max));

                    // DFINE PARCELA
                    $this->pagamento_parcelas[$parcela] = [
                        'parcela'                   => $parcela,
                        'parcela_qtd'               => ($this->pay_installments_number_slip ?? $this->installment_max),
                        'label'                     => $label,
                        'taxa'                      => $taxa,
                        'order_amount'              => toMoneyInt($order_amount),
                        'order_amount_amortization' => $calculaOrderAmount,
                        'encargos'                  => toMoneyInt($encargos),
                        'parcela_valor'             => toMoneyInt($parcela_valor),
                        'parcela_valor_encargos'    => toMoneyInt($encargos / ($this->pay_installments_number_slip ?? $this->installment_max)),
                        'parcela_valor_amortiza'    => toMoneyInt($amortiza_valor),
                        'aplicaTaxa'                => $aplicaTaxa,
                        'vencimento'                => $vencimento,
                        'date_due'                  => $date_due,
                    ];
                }
            }
            else // OUTRAS FORMA DE PAGAMENTO
            {
                $parcela        = 1;
                $order_amount   = toMoneyDot($calculaOrderAmount);
                $amortiza_valor = toMoneyDot($calculaOrderAmount / $parcela);
                $parcela_valor  = toMoneyDot($calculaOrderAmount);
                $encargos       = 0;
                $taxa           = 0;
                $label          = $parcela . 'x ' .toMoney(toMoneyInt($parcela_valor), 'R$ ');

                $this->pagamento_parcelas[$parcela] = [
                    'parcela'                   => $parcela,
                    'parcela_qtd'               => $parcela,
                    'label'                     => $label,
                    'taxa'                      => $taxa,
                    'order_amount'              => toMoneyInt($order_amount),
                    'order_amount_amortization' => $calculaOrderAmount,
                    'encargos'                  => toMoneyInt($encargos),
                    'parcela_valor'             => toMoneyInt($parcela_valor),
                    'parcela_valor_encargos'    => toMoneyInt($encargos) / $parcela,
                    'parcela_valor_amortiza'    => toMoneyInt($amortiza_valor),
                    'aplicaTaxa'                => false,
                ];
            }
        }

        //
        if(!$preserveInstallments ?? false)
        {
            $this->pay_installments_number = 1;
        }

        $this->calculoParcelas = $this->pagamento_parcelas;

        return $this->pagamento_parcelas;
    }

    public function validaOrder($orderId,$redirect=false)
    {
        $return = false;

        // VALIDA ORDER PAGA TOTAL
        $statusOrder = consolidaOrderPayments($orderId,forceUpdate:true);

        if(in_array($statusOrder,listOrderStatusPaid()))
        {
            // NOTIFICAÇÃO
            $this->dialog()->show([
                'title'       => 'SUCESSO',
                'description' => 'Compra Finalizada',
                'icon'        => 'success'
            ]);

            // LIMPA CACHE PEDIDO
            sessionClear('pedido');
            sessionClear('slipPaymentSelectId');

            // RESET VARIAVEL
            $this->reset('payType','payment','order');

            session()->flash('success','Compra Realizada com Sucesso!');

            $return = true;

            // SE REDIRECT
            if($redirect ?? false)
            {
                redirect()->route('compra-exibir',[
                    'localizador' => $this->orderControl,
                    'timestamp'   => now()->format('His'),
                    // 'returnInfo'  => 'paymentOrderFull',
                ]);
            }
        }

        return $return;
    }

    // FORMA CREDITO
    public $card_credit_cpf;
    public $card_credit_num;
    public $card_credit_nome;
    public $card_credit_validade_mm;
    public $card_credit_validade_aaaa;
    public $card_credit_cvv;
    public $card_credit_parcelas;

    // FORMA PIX
    public $pix_cpf_comprador;
    public $pix_cpf;
    protected $return;
    //
    protected $error;
    protected $conclusao_error;
    protected $conclusao_success;

    // SLIP
    public $aceite_termos;
    public $executed;
    public function processarPagamento($forceSandbox=false)
    {
        //
        if(($this->order->buyer_email == "proeventpay@gmail.com") && (!$this->executed ?? false))
        {
            session()->flash('forma_pagamento_warning','Debug Executed');

            $this->executed = true;

            return; // RETURN
        }

        $validatedData = [];

        // PIX
        if($this->payType == 'pix')
        {
            $validatedData = $this->validate([
                'pix_cpf' => ['required','cpf_cnpj'],
            ]);

            // PIX SEMPRE SERA UM
            $this->pay_installments_number = 1;
        }

        // SLIP PIX
        if($this->payType == 'slip_pix')
        {
            $validatedData = $this->validate([
                'pix_cpf' => ['required','cpf_cnpj'],
            ]);

            // SE EXISTE TERMOS
            if($this->aceite_termos ?? false)
            {
                // USADO NA CRIAÇÃO DO CARNE
                foreach ($this->aceite_termos['slip_pix'] as $termo_values)
                {
                    if(!$termo_values['check'])
                    {
                        return session()->flash('error','Um ou mais termos não foram aceitos');
                    }
                }
            }

            //
            $this->pay_installments_number = 1;
        }

        // CARTAO DE CREDITO
        if($this->payType == 'card_credit')
        {
            $validatedData = $this->validate([
                'pay_installments_number'          => ['required'],
                'card_credit_cpf'                  => ['required','cpf_cnpj'],
                'card_credit_num'                  => ['required'],
                'card_credit_nome'                 => ['required'],
                'card_credit_validade_mm'          => ['required'],
                'card_credit_validade_aaaa'        => ['required'],
                'card_credit_cvv'                  => ['required','min:3'],
            ]);
        }

        // BOLETO
        if($this->payType == 'boleto')
        {
            // BOLETO SEMPRE SERA UM
            $this->pay_installments_number = 1;
        }

        // SE PAYMENT >> VERIFICA PARA NAO DUPLICIDADE
        if($payment ?? FALSE)
        {
            // CHECK PRE PAYMENT
            $this->validaOrder($this->order->id,redirect:true);
        }

        // SE SLIP
        if($this->slipPayment ?? false)
        {
            // VALOR DA PARCELA
            $this->payType = $this->slipPayment->installment_pay_type;
            $this->order_amount = $this->slipPayment->installment_value;
            $this->pay_installments_number_slip = $this->slipPayment->slip_installment_available;

            // FORÇA RECALCULO PARCELAS
            $this->pagamento_parcelas = $this->calculaParcelas($this->payType,$this->order_amount,preserveInstallments:true,slipQtdSelecao:$this->pay_installments_number_slip);

            // PEGA VALORES PARCELA
            $valorPagamento = $this->pagamento_parcelas[1];
        }
        else
        {
            // REINICIA VALOR ORDER
            $this->reset('order_amount');

            // RECALCULA VALOR ORDEM // AJUSTA SE EXISTIREM PAGAMENTOS ANTERIORES
            $this->order_amount = $this->calculaOrderAmount($this->order);

            // FORÇA RECALCULO PARCELAS
            $this->pagamento_parcelas = $this->calculaParcelas($this->payType,$this->order_amount,preserveInstallments:true);

            // SE NAO EXISTE PARCELA
            if(!isset($this->pagamento_parcelas[$this->pay_installments_number ?? 1]))
            {
                //
                return $this->dialog()->show([
                    'icon'        => 'error',
                    'title'       => 'ERRO AO CARREGAR PARCELAS',
                    'description' => 'Não existe um plano com ' . $this->pay_installments_number . ' parcelas.',
                ]);
            }

            // PEGA VALORES PARCELA
            $valorPagamento = $this->pagamento_parcelas[$this->pay_installments_number ?? 1];
        }

        try
        {
            // DB::beginTransaction();

            // SE SLIP
            if($this->payType == 'slip_pix')
            {
                // SE NAO EXISTIR CARNE
                if($this->slipPayment ?? false)
                {
                    // ASSOCIA A PARCELA DA VEZ
                    $this->orderSlip = $this->slipPayment;

                    // AJUSTA VALORES POR SER CARNE
                    $fee_percentage_used     = $this->slipPayment->installment_fee_percentage_used ?? 0;
                    $pay_installments_number = 1;
                    $pay_installment_value   = $this->slipPayment->installment_value ?? 0;
                    $value_amortization      = $this->slipPayment->installment_value_amortization ?? 0;
                    $value_liquid            = $this->slipPayment->installment_value_liquid ?? 0;
                    $value_fees              = $this->slipPayment->installment_value_fees;
                    $value_paid              = $this->slipPayment->installment_value;
                }
                else
                {
                    // CRIA CARNE
                    $slipService = new OrderService();
                    $slipService->gerarCarne($this->order->id,'app_event',$this->pagamento_parcelas,'slip_pix');

                    // SE NAO CRIOU CARNE
                    if(!$slipService->orderSlip ?? false)
                    {
                        //
                        $this->dialog()->show([
                            'icon'        => 'error',
                            'title'       => 'ERRO AO GERAR CARNÊ',
                            'description' => 'Tente novamente mais tarde',
                        ]);

                        session()->flash('error','ERRO AO GERAR CARNÊ');
                        session()->flash('error_sub','Tente novamente mais tarde');
                        return;
                    }

                    // ATUALIZA ORDER SLIP
                    $this->orderSlip = $slipService->orderSlip->where('status',"aguardando_pagamento")->first();
                    $this->order     = $slipService->order;
                    $this->order->save();

                    // AJUSTA VALORES POR SER CARNE
                    $fee_percentage_used     = $valorPagamento['taxa'] ?? 0;
                    $pay_installments_number = 1;
                    $pay_installment_value   = $valorPagamento['parcela_valor'] ?? 0;
                    $value_amortization      = $valorPagamento['parcela_valor_amortiza'] ?? 0;
                    $value_liquid            = $valorPagamento['parcela_valor_amortiza'] ?? 0;
                    $value_fees              = $valorPagamento['parcela_valor_encargos'] ?? 0;
                    $value_paid              = $valorPagamento['parcela_valor'];

                }
            }

            // DEFINE
            $pay_installments_number = $pay_installments_number ?? ($valorPagamento['parcela_qtd'] ?? 0);
            $pay_installment_value   = $pay_installment_value ?? ($valorPagamento['parcela_valor'] ?? 0);
            $value_amortization      = $value_amortization ?? ($valorPagamento['order_amount_amortization'] ?? 0);
            $value_liquid            = $value_liquid ?? ($valorPagamento['order_amount_amortization'] ?? 0);
            $value_fees              = $value_fees ?? ($valorPagamento['encargos'] ?? 0);
            $value_paid              = $value_paid ?? ($valorPagamento['order_amount'] ?? 0);
            $fee_percentage_used     = $fee_percentage_used ?? ($valorPagamento['taxa'] ?? 0);

            // CREATE
            $paymentCreate = [
                'app_ref'                        => 'app_event',
                'app_ref_order_id'               => $this->order->id,
                'gateway_id'                     => $this->gatewayPay->id,
                'gateway_slug'                   => $this->gatewayPay->pay_gateway_slug,
                'gateway_sandbox'                => $this->target->pay_sandbox ?? false,
                'status'                         => 'sending_provider',
                'description'                    => strtoupper('ENVIANDO PARA PROCESSAMENTO'),
                'paid_label'                     => toMoney($value_paid,'R$ '),
                'paid_description'               => strtoupper('PAGANDO POR ' . __($this->payType ?? null)),
                'value_amortization'             => $value_amortization,       // VALOR A SER AMOTIZADO
                'value_liquid'                   => $value_liquid,             // VALOR DA COMPRA
                'value_fees'                     => $value_fees,               // VALOR DOS ENCARGOS
                'value_paid'                     => $value_paid,               // TOTAL A PAGAR
                'fee_percentage_used'            => $fee_percentage_used ?? 0, // TAXA DE CALCULO
                'pay_installments_number'        => $pay_installments_number ?? 1,
                'pay_installment_value'          => $pay_installment_value ?? 0,
                'pay_integration_type'           => $this->target->pay_sandbox ? 'sandbox' : 'live',
                'pay_value_paid'                 => 0,
                'pay_value_fees'                 => 0,
                'pay_value_liquid'               => 0,
                'pay_type'                       => strtolower($this->payType ?? null),
                'order_slip_id'                  => $this->orderSlip->id ?? null, // ID DA PARCELA
            ];

            // CRIA TRANSACAO
            $this->payment = AppPayment::create($paymentCreate);

            // ASSOCIA PAGAMENTO
            $this->order->update([
                'payment_id' => $this->payment->id,
            ]);

            // SOMENTE SE NAO FOR PARCELA CARNÊ - PROTEGE DESCONTO DOBRADO
            if(!$this->slipPayment ?? FALSE)
            {
                // SE CUPOM = PROCESSSA CODES PROMO
                if($this->code_promo_id ?? false)
                {
                    $codePromo = new stdClass();
                    $codePromo->code_promo_id = $this->code_promo_id;
                    $codePromo->code_promo_discount_amount = $this->code_promo_discount_amount;
                    $codePromo->code_promo_label           = $this->code_promo_label ?? null;
                    $codePromo->code_promo_price_old       = $this->code_promo_price_old ?? null;
                    $codePromo->code_promo_price_less      = $this->code_promo_price_less ?? null;
                    $codePromo->code_promo_price_new       = $this->code_promo_price_new ?? null;
                }
                elseif($this->order->code_promo_id ?? false) // GARANTE CUPONS JA APLICADOS
                {
                    $codePromo = new stdClass();
                    $codePromo->code_promo_id = $this->order->code_promo_id;
                    $codePromo->code_promo_discount_amount = $this->order->code_promo_discount_amount;
                    $codePromo->code_promo_label           = $this->order->code_promo_label ?? null;
                    $codePromo->code_promo_price_old       = $this->order->code_promo_price_old ?? null;
                    $codePromo->code_promo_price_less      = $this->order->code_promo_price_less ?? null;
                    $codePromo->code_promo_price_new       = $this->order->code_promo_price_new ?? null;
                }
            }

            // INSTANCIA GATEWAY
            switch ($this->gatewayPay->appGateway->gateway_slug ?? false)
            {
                case 'safe2pay':
                    $gatewayReturn = $this->processarSafe2pay($this->payment, $this->order, $this->gatewayPay, $validatedData,$codePromo ?? false, $this->target->pay_sandbox ?? false);
                    break;

                case 'cupom_full':
                    dd('EM CONSTRUÇÃO');
                    break;

                default:
                    $this->conclusao_error = true;
                    session()->flash('conclusao_error', 'Ops!');
                    session()->flash('conclusao_error_sub','Nenhum provedor de pagamento disponível');
                    return;
            }

            // SE NÃO HOUVE RETORNO DO GATEWAY
            if(!$gatewayReturn)
            {
                $this->conclusao_error = true;
                session()->flash('conclusao_error', 'Sem retorno do Gateway de Pagamento');
                session()->flash('conclusao_error_sub', 'Tente novamente');
                return;
            }

            // SE ERROR
            if($gatewayReturn->error ?? false)
            {
                // TRANSAÇÃO
                $this->payment->status_old        = $this->payment->status;
                $this->payment->status            = 'return-error';
                $this->payment->pay_nsu           = $gatewayReturn->nsu ?? null;
                $this->payment->description       = 'Código: '. ($gatewayReturn->code ?? 0) .' - '. ($gatewayReturn->msg ?? 'Erro ao processar pagamento');
                $this->payment->pay_json_request  = json_encode($gatewayReturn->payload ?? '{}');
                $this->payment->pay_json_response = json_encode($gatewayReturn);
                $this->payment->save();

                // ORDER
                $this->order->status = $this->payment->status;
                $this->order->save();

                //
                $this->dialog()->show([
                    'title'       => 'PAGAMENTO COM ERRO',
                    'description' => $gatewayReturn->msg ?? 'Erro ao processar seu pagamento',
                    'icon'        => 'error'
                ]);

                //
                session()->flash('conclusao_error', $gatewayReturn->msg);
                session()->flash('conclusao_error_sub', $gatewayReturn->msg_sub ?? 'Tente novamente mais tarde');
                return false;
            }

            // SALVA RETORNO PAGAMENTO
            $this->payment->status_old          = $this->payment->status;
            $this->payment->status              = $gatewayReturn->status;
            $this->payment->description         = $gatewayReturn->msg;
            $this->payment->paid_label          = toMoney($gatewayReturn->pagamento_valor,'R$ ');
            $this->payment->paid_description    = strtoupper('PAGO COM ' . $gatewayReturn->pagamento_forma);
            $this->payment->pay_nsu             = $gatewayReturn->nsu;
            $this->payment->pay_value_paid      = $gatewayReturn->pagamento_valor ?? 0;
            $this->payment->pay_value_fees      = $gatewayReturn->pagamento_taxa ?? 0;
            $this->payment->pay_value_liquid    = $gatewayReturn->pagamento_liquido ?? 0;
            $this->payment->pay_card_first      = $gatewayReturn->pay_card_first ?? null;
            $this->payment->pay_card_last       = $gatewayReturn->pay_card_last ?? null;
            $this->payment->pay_card_name       = $gatewayReturn->pay_card_name ?? null;
            $this->payment->pay_card_brand      = $gatewayReturn->pay_card_brand ?? null;
            $this->payment->pay_pix_key         = $gatewayReturn->pay_pix_key ?? null;
            $this->payment->pay_pix_qr_code     = $gatewayReturn->pay_pix_qr_code ?? null;
            $this->payment->pay_pix_qr_code_url = $gatewayReturn->pay_pix_qr_code_url ?? null;
            $this->payment->pay_pix_expires_at  = $gatewayReturn->datahoraExpiracao ?? null;
            $this->payment->pay_json_request    = json_encode($gatewayReturn->payload ?? '{}');
            $this->payment->pay_json_response   = json_encode($gatewayReturn);
            $this->payment->save();

            //
            if($this->order->buyer_email == "proeventpay@gmail.com")
            {
                dd(
                    $this->order_amount,
                    $valorPagamento,
                    [
                        'value_amortization'             => $value_amortization,       // VALOR A SER AMOTIZADO
                        'value_liquid'                   => $value_liquid,             // VALOR DA COMPRA
                        'value_fees'                     => $value_fees,               // VALOR DOS ENCARGOS
                        'value_paid'                     => $value_paid,               // TOTAL A PAGAR
                        'fee_percentage_used'            => $fee_percentage_used ?? 0, // TAXA DE CALCULO
                        'paid_label'                     => toMoney($value_paid,'R$ '),
                        'paid_description'               => strtoupper('PAGANDO POR ' . __($this->payType ?? null)),
                        'pay_type'                       => strtolower($this->payType ?? null),
                        'pay_installments_number'        => $pay_installments_number ?? 1,
                        'pay_installment_value'          => $pay_installment_value ?? 0,
                    ],
                    $gatewayReturn,
                    $this->payment->toArray(),
                    $paymentCreate,
                );
            }

            // SE CUPOM = PROCESSSA CODES PROMO
            if($codePromo ?? false)
            {
                $codePromoOrder = EventTicketCodePromo::find($codePromo->code_promo_id);
                $codePromoOrder->code_use_amount_used = $codePromoOrder->code_use_amount_used + 1;

                // SE UTILIZAÇÃO UNICA
                if($codePromoOrder->code_use_amount == 1)
                {
                    $codePromoOrder->code_used_order_id = $this->order->id;
                }

                // SE ATINGIU A QTD DE USOS DO CUPOM
                if($codePromoOrder->code_use_amount_used >= $codePromoOrder->code_use_amount)
                {
                    $codePromoOrder->code_used = true;
                }

                // SAVE PROMO
                $codePromoOrder->save();

                // UPDATE ORDER
                $this->order->code_promo_id = $codePromo->code_promo_id;
                $this->order->code_promo_discount_amount = $codePromo->code_promo_discount_amount;
                $this->order->code_promo_label           = $codePromo->code_promo_label ?? null;
                $this->order->code_promo_price_old       = $codePromo->code_promo_price_old ?? null;
                $this->order->code_promo_price_less      = $codePromo->code_promo_price_less ?? null;
                $this->order->code_promo_price_new       = $codePromo->code_promo_price_new ?? null;
                $this->order->save();
            }

            // SE PIX
            if(in_array($this->payment->pay_type,['pix','slip_pix']))
            {
                // FORÇA PIX VÁLIDO - ABRIR PARA PAGAMENTO
                $this->pixValido = $this->paymentCheckValidate(reset:false);
            }

            // SE PENDENTE
            if(in_array($gatewayReturn->status ?? false,["pendente"]))
            {
                //
                if(in_array($gatewayReturn->pagamento_forma_slug,['pix','slip_pix']))
                {
                    // ATUALIZA RETORNO
                    $this->payment->status = mb_strtolower('pending_' . $gatewayReturn->pagamento_forma_slug);
                    $this->payment->save();

                    // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                    $this->order->reservation_expiration_date = now()->addHours(48);
                    $this->order->status = $this->payment->status;
                    $this->order->save();

                    // SET VARIAVEIS
                    $ticket_status           = 'reserva_temp';
                    $pagamento_success       = 'PIX gerado';
                    $pagamento_success_sub   = 'Use a chave para efetuar o pagamento';
                }
                elseif($gatewayReturn->pagamento_forma_slug == 'boleto')
                {
                    DD('AINDA EM CONSTRUÇÃO');

                    $this->pay_boleto_barcode = $this->payment->pay_boleto_barcode ?? '---';

                    // SALVA RETORNO
                    $this->payment->status_old          = $this->payment->status;
                    $this->payment->status              = 'pending_boleto';
                    $this->payment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key'] ?? null;
                    $this->payment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                    $this->payment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                    $this->payment->save();

                    // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                    $this->order->reservation_expiration_date = Carbon::create($this->payment->pay_boleto_expiration_date)->subHours(3)->addDays(5);
                    $this->order->status = $this->payment->status;
                    $this->order->save();

                    // SET VARIAVEIS
                    $ticket_status           = 'reserva_temp';
                    $pagamento_success       = 'Boleto gerado com sucesso';
                    $pagamento_success_sub   = 'Efetue o pagamento do Boleto';
                }
                else
                {
                    // ORDER
                    $this->order->status = $this->payment->status;
                    $this->order->save();
                }

                // RODAR TICKETS
                trataTicketsEvento($this->order->id,$ticket_status ?? 'reserva_temp');

                // NOTIFICAÇÃO
                $this->dialog()->show([
                    'icon'        => 'info',
                    'title'       => mb_strtoupper($pagamento_success),
                    'description' => $pagamento_success_sub ?? null,
                ]);

                //
                session()->flash('forma_pagamento_info',$pagamento_success);
                session()->flash('forma_pagamento_info_sub',$pagamento_success_sub);

                // SE SLIP - SALVA NA SESSAO
                if($this->orderSlip ?? false)
                {
                    Session::put('slipPaymentSelectId',$this->orderSlip->id);

                    $this->orderSlip->update([
                        'payment_id' => $this->payment->id,
                        'status' => 'aguardando_pagamento',
                    ]);
                }

                return redirect()->route('compra-exibir',[
                    'localizador' => $this->order->order_control,
                ]);
            }
            else
            {
                // SE SLIP - ASSOCIA PAGAMENTO
                if($this->orderSlip ?? false)
                {
                    $this->orderSlip->update([
                        'payment_id' => $this->payment->id,
                    ]);
                }

                // SE PAGAMENTO OK
                if($gatewayReturn->pagamento_ok ?? false)
                {
                    $this->validaOrder($this->order->id,redirect:true);
                }
            }
        }
        catch (\Throwable $th)
        {
            if(($this->order ?? false) && ($this->order->buyer_email == "proeventpay@gmail.com"))
            {
                dd('Throwable by Debug',__FUNCTION__,__LINE__, $th,);
            }

            $this->error = TRUE;
            session()->flash('error',$th->getMessage());
            session()->flash('error_sub','('.$th->getCode().')');
            return;
        }
    }

    public $payload;
    public $sandbox;
    public $token;
    public $pagamento;
    public $transaction;
    public $service;
    public function processarSafe2pay($payment, $order, $gateway,$data,$codePromo=false,$sandbox=false)
    {
        try
        {
            if(in_array($payment->pay_type,['pix','slip_pix']))
            {
                $sandbox = false; // FORÇA SER LIVE
                $token = $gateway->token_live; // PIX SOMENTE PRODUÇÃO
                $postback_url = route('payment-callback-safe2pay', ['callbackType' => $payment->app_ref, 'orderId' => $order->id, 'paymentId' => $payment->id]);
            }
            else
            {
                $token  = $sandbox ? $gateway->token_test : $gateway->token_live;
                $postback_url = route('payment-callback-safe2pay', ['callbackType' => $payment->app_ref, 'orderId' => $order->id, 'paymentId' => $payment->id]);
            }

            // MONTA VENDOR
            if($order->event->organizer->organizer_name_full ?? false)
            {
                $vendor = mb_strtoupper($order->event->organizer->organizer_name_full);
            }
            else
            {
                $vendor = mb_strtoupper(toSlug($order->event->organizer->customer->name_corporate . ' ' . $order->event->organizer->organizer_name,'-'));
            }

            // INICIA SERVICE
            $service = new Safe2PayService($token,$sandbox);

            // SET APLICATION
            $service->Application = trim(mb_strtoupper(toSlug($gateway->pay_gateway_slug,'-') . '.' . ($sandbox ? "SANDBOX" : "LIVE")));
            $service->Vendor      = trim($vendor ?? 'PROEVENTPAY');
            $service->CallbackUrl = $postback_url;
            $service->Reference   = mb_strtoupper($order->order_control);
            $service->Valor       = $payment->value_amortization;
            $service->setAplication();

            // SET META
            $service->order_id              = $order->id;
            $service->localizador           = $order->order_control;
            $service->payment_id            = $payment->id;
            $service->gateway_id            = $gateway->id;
            $service->app_ref               = $payment->app_ref;
            $service->order_amount          = $payment->value_amortization ?? 0;
            $service->order_amount_discount = 0; // NAO SALVA MAIS DESCONTO NO PAGAMENTO
            $service->order_amount_pay      = $payment->value_amortization ?? 0;
            $service->setMeta();

            // SET CUSTOMER
            $service->Name     = mb_strtoupper($order->buyer_name);
            $service->Identity = $order->buyer_doc_num;
            $service->Phone    = $order->buyer_contact_ddd.$order->buyer_contact_num;
            $service->Email    = mb_strtolower($order->buyer_email);
            $service->setCustomer();

            // SE FORMA PAGAMENTO
            if(in_array($payment->pay_type,['pix','slip_pix','boleto']))
            {
                // SET CUSTOMER ADDRESS
                $service->IsSandbox     = false;
                $service->ZipCode       = $order->event->organizer->customer->zip_code;
                $service->Street        = mb_strtoupper($order->event->organizer->customer->address);
                $service->Number        = mb_strtoupper($order->event->organizer->customer->address_number);
                $service->Complement    = mb_strtoupper($order->event->organizer->customer->address_complement);
                $service->District      = mb_strtoupper($order->event->organizer->customer->city_neighborhood);
                $service->CityName      = mb_strtoupper($order->event->organizer->customer->city);
                $service->StateInitials = mb_strtoupper($order->event->organizer->customer->state);
                $service->CountryName   = $order->event->organizer->customer->country ?? "Brasil";
                $service->setCustomerAddress();
            }
            else
            {
                // CLEAR CUSTOMER ADDRESS
                $service->setCustomerAddress(clear:true);
            }

            // APPEND PRODUCTS
            $service->appendProducts(
                Code:$payment->id,
                UnitPrice:$payment->value_liquid,
                Quantity:1,
                Description:'COMPRA ' . $order->order_control
            );

            // SE PAGAMENTO FOR CARNÊ
            if(($payment->order_slip_id ?? false) && in_array($payment->pay_type,['slip_pix']))
            {
                // LIMPA PRODUTOS
                $service->setProducts(clearFull:true);

                // APPEND PRODUCTS
                $service->appendProducts(
                    Code:'ORDERSLIP.'.$this->orderSlip->id,
                    UnitPrice:$payment->value_liquid,
                    Quantity:1,
                    Description:'CARNÊ ' . $this->orderSlip->installment_description . ' / CONTROLE: ' . $this->orderSlip->slip_installment_control
                );

                // ADICIONA META
                $service->pay_slip             = true;
                $service->pay_slip_id          = $this->orderSlip->id;
                $service->pay_slip_description = $this->orderSlip->installment_description;
                $service->setMeta();
            }

            // SE EXISTIR REPASSE DE TAXA
            if($payment->fee_percentage_used ?? false)
            {
                // ADICIONA ENCARGOS
                $service->appendProducts(
                    Code:999,
                    UnitPrice:$payment->value_fees,
                    Quantity:1,
                    Description:'ENCARGOS ' . $payment->fee_percentage_used . '%',
                );

                // COBRAR JUROS
                $service->IsApplyInterest = true;

                // // REM.20250214 - ADICIONARIA MAIS JUROS
                // $service->InterestRate = $payment->fee_percentage_used;
            }

            // SET PRODUCTS PARA PAGAMENTO
            $service->setProducts();

            // APPEND PRODUCTS EM META
            $service->setProductsToMeta();

            // SET PAYMENTS
            switch ($payment->pay_type ?? false)
            {
                case 'pix':
                    $payload = $service->setPaymentPix($this->pix_cpf);

                    // SALVA PAYLOAD
                    $this->payment->pay_json_request   = json_encode($payload);
                    $this->payment->pay_pix_expires_at = $service->ExpirationDateTime;
                    $this->payment->save();
                    break;
                case 'slip_pix':
                    // PARA O CASO DE SLIP, REPASSE DE ENCARGOS PARA SAFE2PAY
                    // Só aplica split se houver taxa a repassar (value_fees > 0), caso contrário a API rejeita com "valor de repasse deve ser maior que 0"
                    if(($gateway->appGateway->pay_slip_pix_split_receiver_id ?? false) && ($payment->value_fees > 0))
                    {
                        $service->ReceiverId   = $gateway->appGateway->pay_slip_pix_split_receiver_id;   // SAFE2PAY - ID ACCOUNT
                        $service->ReceiverName = $gateway->appGateway->pay_slip_pix_split_receiver_name ?? 'REPASSE TAXA CARNÊ'; // SAFE2PAY - NAME SPLIT

                        $service->applySplitPayment(splitValor:toMoneyDot($payment->value_fees));
                    }

                    $payload = $service->setPaymentPix($data['pix_cpf']);

                    // SALVA PAYLOAD
                    $this->payment->pay_json_request   = json_encode($payload);
                    $this->payment->pay_pix_expires_at = $service->ExpirationDateTime;
                    $this->payment->save();
                    break;
                case 'card_credit':
                case 'credit_card':
                    $service->Holder              = $data['card_credit_nome'];
                    $service->CardNumber          = $data['card_credit_num'];
                    $service->ExpirationDateMM    = $data['card_credit_validade_mm'];
                    $service->ExpirationDateAAAA  = $data['card_credit_validade_aaaa'];
                    $service->SecurityCode        = $data['card_credit_cvv'];
                    $service->InstallmentQuantity = $data['pay_installments_number'] ?? 1;

                    $payload = $service->setPaymentCredit($data['card_credit_cpf']);

                    // SALVA PAYLOAD
                    $this->payment->paid_description = $payload['PaymentObject']['CardNumber'] ? ($payload['PaymentObject']['CardNumber']) : $payment->paid_description;
                    $this->payment->pay_json_request = json_encode($payload);
                    $this->payment->save();
                    break;

                case 'boleto':
                    dd($data);
                    break;

                default:
                    // RETURN ERROR SEM MODULO DE PAGAMENTO DEFINIDO
                    throw new Exception('payment_type_notfound');
            }

            // EXECUTE
            $r = $service->executeTransaction();

            // SE ERROR
            if($r->error ?? false)
            {
                return $r;
            }

            // SENAO CONSULTA TRANSACAO
            return $service->consultaTransacao($r->nsu,true);

        }
        catch (\Throwable $th)
        {
            $return = new stdClass();
            $return->error             = true;
            $return->function          = __FUNCTION__;
            $return->file              = $th->getFile();
            $return->line              = $th->getLine();
            $return->error_getMessage  = $th->getMessage();
            $return->msg               = $th->getMessage();
            $return->msg_sub           = null;
            $return->pay_json_request  = json_encode($post ?? []);
            $return->pay_json_response = json_encode($transaction ?? []);
            $return->response          = $transaction ?? false;

            return $return;
        }
    }

    public $code_promo_id;
    public $ticket_code_promo;
    public $code_promo_discount_amount;
    public $code_promo_label;
    public $code_promo_price_old;
    public $code_promo_price_new;
    public $code_promo_price_less;
    public $code_promo_selected;
    public function aplicarCupom()
    {
        $this->resetCupom();

        $this->validate([
            'ticket_code_promo' => ['required']
        ]);

        // BUSCA CUPOM
        $code_promo_selected = DB::table('tev_events_tickets_codes_promo')
            ->whereRaw('UPPER(code_name) = UPPER(?)', [trim($this->ticket_code_promo)])
            ->first();

        // SE CODIGO NÃO LOCALIZADO
        if(!$code_promo_selected ?? false)
        {
            return session()->flash('ticket_code_promo_erro','Cupom Inexistente');
        }

        // SE EXISTIR EVENTO ASSOCIADO
        if(($code_promo_selected->event_id ?? false) && ($this->order->event_id != $code_promo_selected->event_id))
        {
            $code_promo_selected = null;
            return session()->flash('ticket_code_promo_erro','Cupom Inválido para esse evento');
        }

        // SE EXISTIR LOTE ASSOCIADO
        if(($code_promo_selected->event_ticket_id ?? false) && ($this->order->order_items_ticket_type_id != $code_promo_selected->event_ticket_id))
        {
            $code_promo_selected = null;
            return session()->flash('ticket_code_promo_erro','Cupom Inválido para esse lote');
        }

        // SE ATIVO
        if(!$code_promo_selected->code_active ?? false)
        {
            $code_promo_selected = null;
            return session()->flash('ticket_code_promo_erro','Cupom Inválido');
        }

        // SE DATA INICIAL < NOW
        if(($code_promo_selected->code_datetime_validade_start ?? false)  && (dataCarbon($code_promo_selected->code_datetime_validade_start,'YmdHi') > now()->format('YmdHi')))
        {
            $code_promo_selected = null;
            return session()->flash('ticket_code_promo_erro','Cupom não está vigente');
        }

        // SE DATA NOW > FINAL
        if(($code_promo_selected->code_datetime_validade_finish ?? false) && (dataCarbon($code_promo_selected->code_datetime_validade_finish,'YmdHi') < now()->format('YmdHi')))
        {
            $code_promo_selected = null;
            return session()->flash('ticket_code_promo_erro','Cupom expirado');
        }

        // SE QTD PARA USO e USADA
        if(($code_promo_selected->code_use_amount ?? false) && ($code_promo_selected->code_use_amount_used ?? false) && ($code_promo_selected->code_use_amount_used >= $code_promo_selected->code_use_amount))
        {
            $code_promo_selected = null;
            return session()->flash('ticket_code_promo_erro','Cupom já utilizado');
        }

        // TIPO DE CUPOM
        switch ($code_promo_selected->discount_type ?? false)
        {
            case 'porcentagem':
                $this->code_promo_discount_amount = (int) round(($this->order->order_amount / 100) * $code_promo_selected->discount_value);
                $newTicketPrice = $this->order->order_amount - $this->code_promo_discount_amount;
                break;
            case 'valor':
                $this->code_promo_discount_amount = (int) $code_promo_selected->discount_value;
                $newTicketPrice = $this->order->order_amount - $this->code_promo_discount_amount;
                break;
            default:
                return session()->flash('ticket_code_promo_erro','Cupom não possui desconto válido');
        }

        //
        $this->code_promo_id         = $code_promo_selected->id;
        $this->code_promo_label      = 'Cupom ' . (mb_strtoupper($code_promo_selected->code_name)) . ' - ' . $code_promo_selected->code_description;
        $this->code_promo_price_old  = (int) $this->order->order_amount;
        $this->code_promo_price_less = (int) $this->code_promo_discount_amount;
        $this->code_promo_price_new  = (int) $newTicketPrice;

        //
        $this->order_amount = (int) $newTicketPrice;

        //
        $this->calculaParcelas(orderAmountEnviado:(int) $newTicketPrice);

        //
        session()->flash('ticket_code_promo_sucesso', 'Cupom aplicado');
        session()->flash('ticket_code_promo_ok', $this->code_promo_label);

        // SE EXISTIR PAGAMENTO ASSOCIADO
        if($this->order->payment_id ?? false)
        {
            $this->order->payment_id = null;
            $this->order->save();

            // GARANTE NOVO PAGAMENTO
            $this->reset('payType','payment','pixValido');
        }

        return true;
    }

    public function removeCupom()
    {
        $this->ticket_code_promo = '';
        $this->resetCupom();
        $this->calculaParcelas();
    }

    public function resetCupom()
    {
        $this->order_amount               = $this->order->order_amount;
        $this->code_promo_discount_amount = 0;
        $this->code_promo_id              = '';
        $this->code_promo_label           = '';
        $this->code_promo_price_old       = '';
        $this->code_promo_price_new       = '';
        $this->code_promo_price_less      = '';
        $this->code_promo_selected        = false;
    }

    public $aceiteTermos = [];
    public function updated($name, $value)
    {
        //
        if ($name == 'pay_installments_number_slip')
        {
            // VALIDA SE REALIZADO DE FATO
            $this->calculaParcelas($this->payType);
        }

        //
        if ($name == 'payType')
        {
            $this->aceiteTermos = [];

            // TERMOS
            if($value == 'slip_pix')
            {
                $this->reset('pay_installments_number_slip');

                $this->aceiteTermos['slip_pix'] = [
                    't1' => [
                        'termo' => 'Ciente que as prestações precisam ser pagas em dia.',
                        'check' => false,
                    ],
                    't2' => [
                        'termo' => 'Me comprometo a acessar mensalmente página online, gerar a chave PIX e realizar os pagamentos.',
                        'check' => false,
                    ],
                    't3' => [
                        'termo' => 'Entendo que se não houver quitação das parcelas, meus vouchers serão cancelados.',
                        'check' => false,
                    ],
                    't4' => [
                        'termo' => 'Estou ciente que passados 62 dias de atraso do pagamento de qualquer parcela, minha compra será totalmente cancelada e a minha vaga será disponibilizada para outro comprador.',
                        'check' => false,
                    ],
                    't5' => [
                        'termo' => 'Entendo que passados 7 dias desta compra não tenho direito a rembolso do que já foi pago.',
                        'check' => false,
                    ],
                ];
            }

            // VALIDA SE REALIZADO DE FATO
            $this->calculaParcelas($value);
        }
    }

    public function render()
    {
        if(($this->order->slip_id ?? false) && $this->order->paymentsSlip->count())
            return view('livewire.compras.modulo-pagamento-slip');
        else
            return view('livewire.compras.modulo-pagamento');
    }
}

