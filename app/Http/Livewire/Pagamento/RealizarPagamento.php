<?php

namespace App\Http\Livewire\Pagamento;

use App\Jobs\AppEvent\NotificationAppEventCompra;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderSponsorship;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentSlip;
use App\Models\ModEvent\EventTicketCodePromo;
use App\Services\Order\OrderService;
use App\Services\safe2pay\Safe2PayService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use stdClass;
use Illuminate\Support\Facades\Route;

use WireUi\Traits\Actions;

class RealizarPagamento extends Component
{
    use Actions;

    //
    public $localizador;
    public $target_type;

    //
    public $reservation_expiration=false;
    public $reservation_expiration_date;
    public $reservation_expiration_date_seconds = 0;
    public $target;
    public $order;
    public $order_amount;
    public $itens;
    public $codesPromo;
    public $gateway_pay;

    public $ticket_code_promo;

    public $code_promo_id;
    public $code_promo_discount_amount;
    public $code_promo_label;
    public $code_promo_price_old;
    public $code_promo_price_new;
    public $code_promo_price_less;
    public $code_promo_selected;

    public $forma_pagamento_disponivel;
    public $forma_pagamento_selecionada;
    public $pagamento_parcelas;

    public $pay_type;
    public $pay_code_promo_id;
    public $pay_code_promo_discount_amount;

    public $payment;
    public $card_credit_endereco_cep;
    public $card_credit_endereco;
    public $card_credit_endereco_num;
    public $card_credit_endereco_complemento;
    public $card_credit_endereco_bairro;
    public $card_credit_endereco_cidade;
    public $card_credit_endereco_estado;

    public $gatewayReturn;
    public $pay_boleto_barcode;
    public $pay_pix_key;
    public $pay_pix_code;
    public $pay_pix_code_url;
    public $pay_pix_validade;
    public $error;
    public $conclusao_error;
    public $conclusao_success;

    protected $messages = [
        '*.required' => 'Obrigatório',
    ];

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public $pix;
    public $pg_timestamp;
    public $debug=false;
    public $slipInstallmentControl;

    public function mount(Request $request, $targetType=false,$localizador=false,$timestamp=false)
    {
        $this->localizador  = strtoupper($localizador);
        $this->target_type  = strtolower($targetType);
        $this->pg_timestamp = ($timestamp == 'debug') ? false : $timestamp;

        //
        $this->slipInstallmentControl = $request->input('slipInstallmentControl') ?? false;

        //
        switch ($this->target_type)
        {
            case 'event':
            case 'evento':
            case 'app_event':

                if(!$this->order = AppEventOrder::where('order_control',$this->localizador)->first())
                {
                    session()->flash('error',"Localizador {$localizador} inexistente");
                    return redirect()->route('home');
                }

                // FORÇA IR PARA O EXIBIR COMPRA
                return redirect()->route('compra-exibir',['localizador' => $this->localizador,'referer' => 'realizarPagamento']);

                $this->itens       = $this->order->itens;
                $this->target      = $this->order->event;
                $this->codesPromo  = $this->order->event->codesPromo;
                $this->gateway_pay = $this->target->gatewayPay;

                // DEFINE
                $this->order_amount = $this->order->order_amount;
                $this->pay_installments_number = 1;
                //
                $this->formasPagamento($this->target);
                break;

            case 'evento_patrocinador':

                // SE NÃO LOCALIZAR
                if(!$this->order = AppEventOrderSponsorship::with(['plano','event','event.gatewayPay','event.gatewayPay.appGateway','payments'])->where('order_control',$this->localizador)->first())
                {
                    session()->flash('error',"Localizador {$localizador} inexistente");
                    return redirect()->route('home');
                }

                $this->target      = $this->order->event;
                $this->gateway_pay = $this->target->gatewayPay;

                // DEFINE
                $this->order_amount = $this->order->order_amount;
                $this->pay_installments_number = 1;
                $this->formasPagamentoPatrocinio($this->target);
                break;

            default:
                session()->flash('error','Ops, não achamos nada com esse localizador ' . $this->localizador);
                return redirect()->route('home');
        }

        //fase_pagamento
        if(in_array($this->order->status ?? false,['fase_pagamento','pending_boleto','pending_pix','pending_slip_pix']))
        {
            // SE PIX
            if(in_array($this->order->status,['pending_pix','pending_slip_pix']))
            {
                //
                if($this->pix = $this->order->payments->whereIn('status',['pending_pix','pending_slip_pix'])->last())
                {
                    $this->pay_type = 'pix';
                    $this->updated('pay_type', $this->pay_type);
                }
            }

            //
            if(in_array($this->order->status,['pending_boleto']))
            {
                $this->pay_type = 'boleto';
                $this->updated('pay_type', $this->pay_type);
            }

            // SE EXPIRADO
            if($this->reservation_expiration ?? false)
            {
                // CANCELAR PEDIDO
                $this->order->status_old = $this->order->status;
                $this->order->status_old_datetime = now();
                $this->order->status = 'expired_order';
                $this->order->save();

                // CANCELAR TICKETS
                foreach ($this->order->tickets ?? [] as $ticketLKey => $ticket)
                    $this->order->tickets[$ticketLKey]->delete();

                // LIMPA CACHE
                sessionClear('pedido');
            }
        }
    }

    public function checkExpiration($delay=false)
    {
        //
        if($this->order ?? false)
        {
            if(in_array($this->order->status,listOrderStatusPaid()))
            {
                // LIMPA CACHE
                sessionClear('pedido');
            }
            else
            {
                $this->validarPagamento($this->order->id);
            }

            //
            if($this->order->reservation_expiration_date ?? false)
            {
                //
                if($delay && $delay > 0 && in_array($this->order->status ?? false,['expired_order','fase_pagamento']))
                {
                    // PEGA DATA HORA ATUAL + ADD MINUTOS
                    $agora = now();
                    $this->reservation_expiration_date = $agora->addMinutes($delay);

                    // ABRE O PEDIDO NOVAMENTE
                    $this->order->status = 'fase_pagamento';
                    $this->order->save();
                }
                else
                {
                    $this->reservation_expiration_date = $this->order->reservation_expiration_date ?? now();
                }

                // CALCULA SEGUNDOS
                $this->reservation_expiration_date_seconds = calculaSegundosDif($this->reservation_expiration_date, now());

                // SE EXPIRADO
                if($this->reservation_expiration_date_seconds < 1)
                {
                    $this->reservation_expiration = true;

                    // PEDIDO EXPIRADO
                    $this->order->status = 'expired_order';
                    $this->order->save();

                    // LIMPA CACHE
                    sessionClear('pedido');
                }
                else
                {
                    $this->reservation_expiration = false;
                }

                //
                return $this->reservation_expiration;
            }
        }

    }

    public $payments;
    public function validarPagamento($orderId=false,$debug=false)
    {
        // DEFINE O APP_REF CORRETO DE ACORDO COM O TARGET TYPE
        $app_ref_filter = in_array($this->target_type, ['event', 'evento', 'app_event']) ? 'app_event' : $this->target_type;

        // PEGA PAGAMENTOS
        if($orderId ?? false)
        {
            $this->payment  = false;
            $this->payments = AppPayment::where('app_ref', $app_ref_filter)->where('app_ref_order_id',$orderId)->get();
        }
        else
        {
            $this->payment  = $this->order->payment;
            $this->payments = AppPayment::where('app_ref', $app_ref_filter)->where('app_ref_order_id',$this->order->id)->get();
        }

        //
        $validarLoop = [];

        // SE FOR CARNE PIX
        if(($this->pay_type == 'slip_pix'))
        {
            // session()->flash('error',"CRIAR O FLUXO DE VALIDAÇÃO");
            return false;
        }
        else
        {
            // SE PAGAMENTO EM QUESTAO
            if($this->payment->pay_nsu ?? false)
            {
                $validarLoop[$this->payment->pay_nsu] = $this->payment;
            }

            // CORRE PAGAMENTO // MONTA LOOP
            foreach ($this->payments as $payment_values)
            {
                if(!$payment_values->pay_nsu ?? false)
                {
                    continue;
                }

                $validarLoop[$payment_values->pay_nsu] = $payment_values;
            }
        }

        // SE NAO TEM NSU
        if(empty($validarLoop))
        {
            return false;
        }

        // ORDENA POR NSU CRESCENTE
        ksort($validarLoop);

        foreach ($validarLoop as $nsu => $payment)
        {
            $this->payment = $payment;

            // SE PIX
            if($this->payment->pay_type == 'pix')
            {
                // PIX SOMENTE PRODUÇÃO (LIVE CLIENTE)
                $token   = $this->gateway_pay->token_live;
                $sandbox = false;
            }
            else
            {
                $token   = $this->sandbox ? $this->gateway_pay->token_test : $this->gateway_pay->token_live;
                $sandbox = $this->sandbox ?? false;
            }

            // PARA FUTUROS TESTES
            $pay_nsu = $this->payment->pay_nsu;

            //
            $validaPagamento = safe2payValidarPagamento($this->order->id,$pay_nsu,$this->payment->id,$token,$this->target_type);

            //
            if($validaPagamento->pagamento_ok ?? false)
            {
                $this->order->payment_id = $this->payment->id;
                $this->order->save();

                $this->modal_pagamento_success = true;
                session()->flash('modal_pagamento_success',$validaPagamento->msg);
                session()->flash('modal_pagamento_success_sub',$validaPagamento->msg_sub);

                // ATUALIZA ORDER
                $this->getOrder($this->order->id);

                return true;
            }
        }

        // ATUALIZA ORDER
        $this->getOrder($this->order->id);

        return false;
    }

    // FORMA CREDITO
    public $card_credit_cpf;
    public $card_credit_num;
    public $card_credit_nome;
    public $card_credit_validade_mm;
    public $card_credit_validade_aaaa;
    public $card_credit_cvv;
    public $card_credit_parcelas;
    public $pay_installments_number;


    // FORMA PIX
    public $pix_cpf_comprador;
    public $pix_cpf;
    protected $return;

    public function processarPagamento()
    {
        // return; // RETURN

        // SE NSU - TRANSACAO
        if($this->order->payment->pay_nsu ?? false)
        {
            // VALIDA SE REALIZADO
            if($this->validarPagamento($this->order->id))
            {
                // LIMPA CACHE
                sessionClear('pedido');

                return redirect()->route('pagamento',
                    [
                        'targetType'  => $this->target_type,
                        'localizador' => $this->order->order_control,
                        'timestamp'   => now()->format('His')
                    ]);
            }
        }

        // INICIA RETORNO
        $this->return = new stdClass();
        $this->return->error            = false;
        $this->return->status           = 'payment_data_validate';
        $this->return->msg              = __('payment_data_validate');
        $this->return->msg_sub          = 'processarPagamento';
        $this->return->gateway_slug     = $this->gateway_pay->pay_gateway_slug;
        $this->return->gateway_sandbox  = $this->target->pay_sandbox;
        $this->return->localizador      = $this->order->order_control;
        $this->return->pay_postback_url = null;

        $validatedData = [];

        // PIX
        if($this->pay_type == 'pix')
        {
            $validatedData = $this->validate([
                'pix_cpf' => ['required','cpf_cnpj'],
            ]);

            // PIX SEMPRE SERA UM
            $this->pay_installments_number = 1;
        }

        // CARTAO DE CREDITO
        if($this->pay_type == 'card_credit')
        {
            $validatedData = $this->validate([
                'pay_installments_number'          => ['required'],
                'card_credit_cpf'                  => ['required','cpf_cnpj'],
                'card_credit_num'                  => ['required'],
                'card_credit_nome'                 => ['required'],
                'card_credit_validade_mm'          => ['required'],
                'card_credit_validade_aaaa'        => ['required'],
                'card_credit_cvv'                  => ['required','min:3'],
                // 'card_credit_endereco_cep'         => ['required'],
                // 'card_credit_endereco'             => ['required'],
                // 'card_credit_endereco_num'         => ['required'],
                // 'card_credit_endereco_complemento' => ['nullable'],
                // 'card_credit_endereco_bairro'      => ['required'],
                // 'card_credit_endereco_cidade'      => ['required'],
                // 'card_credit_endereco_estado'      => ['required'],
            ]);
        }

        // BOLETO
        if($this->pay_type == 'boleto')
        {
            // BOLETO SEMPRE SERA UM
            $this->pay_installments_number = 1;
        }

        // CHECK STATUS ATUAL
        $orderCheck = $this->getOrder($this->order->id);

        if(in_array($orderCheck->status, listOrderStatusPaid()))
        {
            $this->conclusao_success = TRUE;
            return session()->flash('conclusao_success', 'Opa! Esse pagamento já foi realizado');
        }

        try
        {
            // REFERENCIA
            switch ($this->target_type)
            {
                case 'event':
                case 'evento':
                    $app_ref = 'app_event';
                    break;
                default:
                    $app_ref = $this->target_type;
                    break;
            }

            // PEGA VALORES PARCELA
            $valores = $this->pagamento_parcelas[$this->pay_installments_number ?? 1];

            // DEFINE ATRIBUTOS
            $value_paid                     = $valores['order_amount'] ?? 0;
            $value_fees                     = $valores['encargos'] ?? 0;
            $value_liquid                   = $valores['order_amount'] - $valores['encargos'];
            $fee_percentage_used            = $valores['taxa'] ?? 0;
            $paid_label                     = toMoney($value_liquid,'R$ ');
            $paid_description               = strtoupper('PAGO COM ' . __($this->pay_type ?? null));
            $pay_type                       = strtolower($this->pay_type ?? null);
            $pay_code_promo_id              = $this->pay_code_promo_id ?? null;
            $pay_code_promo_discount_amount = $this->pay_code_promo_discount_amount ?? null;
            $pay_installments_number        = $valores['parcela_qtd'] ?? 1;
            $pay_installment_value          = $valores['parcela_valor'] ?? 0;
            $pay_integration_type           = $this->target->pay_sandbox ? 'sandbox' : 'live';

            // CRIA TRANSACAO
            $this->payment = AppPayment::create([
                'app_ref'                        => $app_ref,
                'app_ref_order_id'               => $this->order->id,
                'gateway_id'                     => $this->gateway_pay->id,
                'gateway_slug'                   => $this->gateway_pay->pay_gateway_slug,
                'gateway_sandbox'                => $this->target->pay_sandbox ?? false,
                'status'                         => 'sending_provider',
                'description'                    => strtoupper('ENVIANDO PARA PROCESSAMENTO'),
                'value_paid'                     => $value_paid,
                'value_fees'                     => $value_fees,
                'value_liquid'                   => $value_liquid,
                'fee_percentage_used'            => $fee_percentage_used,
                'paid_label'                     => $paid_label,
                'paid_description'               => $paid_description,
                'pay_type'                       => $pay_type,
                'pay_code_promo_id'              => $pay_code_promo_id,
                'pay_code_promo_discount_amount' => $pay_code_promo_discount_amount,
                'pay_installments_number'        => $pay_installments_number,
                'pay_installment_value'          => $pay_installment_value,
                'pay_integration_type'           => $pay_integration_type,
                'order_slip_id'                  => null,
            ]);

            // ASSOCIA PAGAMENTO
            $this->order->payment_id       = $this->payment->id;
            $this->order->slip_id          = null;
            $this->order->slip_description = null;
            $this->order->save();

            // SE EVENTO PATROCINADOR
            if (in_array($this->target_type,['evento_patrocinador']))
            {
                // INSTANCIA GATEWAY
                switch ($this->gateway_pay->appGateway->gateway_slug ?? false)
                {
                    case 'safe2pay':
                        $gatewayReturn = $this->processarSafe2pay($this->payment, $this->order, $this->gateway_pay, $validatedData, $this->target->pay_sandbox ?? false);
                        break;

                    case 'cupom_full':
                        $gatewayReturn = convertArrayToObject([
                            'error'              => false,
                            'pay_refused'        => false,
                            'status'             => 'paid_cupom_full',
                            'pay_type'           => 'CUPOM_FULL',
                            'pay_datetime'       => now(),
                            'pay_transaction_id' => $this->code_promo_id,
                            'msg'                => 'Pagamento realizado através de Cupom',
                        ]);
                        break;

                    default:
                        $this->conclusao_error = true;
                        session()->flash('conclusao_error', 'Ops!');
                        session()->flash('conclusao_error_sub','Nenhum provedor de pagamento disponível');
                        return;
                }
            }
            else
            {
                // INSTANCIA GATEWAY
                switch ($this->gateway_pay->appGateway->gateway_slug ?? false)
                {
                    case 'safe2pay':
                        $gatewayReturn = $this->processarSafe2pay($this->payment, $this->order, $this->gateway_pay, $validatedData, $this->target->pay_sandbox ?? false);
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
                $this->payment->description       = 'CÓDIGO: '. ($gatewayReturn->code ?? 0000) .' // '. ($gatewayReturn->msg ?? 'Erro ao processar pagamento');
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
                return;
            }

            // SE CUPOM = PROCESSSA CODES PROMO
            if($this->code_promo_id ?? false)
            {
                $codePromo = EventTicketCodePromo::find($this->code_promo_id);
                $codePromo->code_use_amount_used = $codePromo->code_use_amount_used + 1;

                // SE UTILIZAÇÃO UNICA
                if($codePromo->code_use_amount == 1)
                {
                    $codePromo->code_used_order_id = $this->order->id;
                }

                // SE ATINGIU A QTD DE USOS DO CUPOM
                if($codePromo->code_use_amount_used >= $codePromo->code_use_amount)
                {
                    $codePromo->code_used = true;
                }

                // SAVE PROMO
                $codePromo->save();

                // UPDATE ORDER
                $this->order->code_promo_id = $this->code_promo_id;
                $this->order->code_promo_discount_amount = $this->code_promo_discount_amount;
                $this->order->code_promo_label           = $this->code_promo_label ?? null;
                $this->order->code_promo_price_old       = $this->code_promo_price_old ?? null;
                $this->order->code_promo_price_less      = $this->code_promo_price_less ?? null;
                $this->order->code_promo_price_new       = $this->code_promo_price_new ?? null;
                $this->order->save();

                // UPDATE PAYMENT
                $this->payment->pay_code_promo_discount_amount = $this->code_promo_price_less ?? 0;
            }

            // SALVA RETORNO
            $this->payment->status_old          = $this->payment->status;
            $this->payment->status              = $gatewayReturn->status;
            $this->payment->pay_nsu             = $gatewayReturn->nsu;
            $this->payment->description         = $gatewayReturn->msg;
            $this->payment->value_paid          = $gatewayReturn->pagamento_valor;
            $this->payment->value_fees          = $gatewayReturn->pagamento_taxa;
            $this->payment->value_liquid        = $gatewayReturn->pagamento_liquido;
            $this->payment->pay_json_response   = json_encode($gatewayReturn);
            $this->payment->save();

            // SE PENDENTE
            if(($gatewayReturn->status ?? false) && in_array($gatewayReturn->status,["pendente"]))
            {
                //
                if($gatewayReturn->pagamento_forma_slug == 'pix')
                {
                    // ATUALIZA RETORNO // deu erro
                    // $this->payment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key'] ?? null; //?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['Key'] ?? null);
                    // $this->payment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null; //?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['QrCode'] ?? null);
                    // $this->payment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null; //?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['QrCode'] ?? null);

                    // ATUALIZA RETORNO
                    $this->payment->status              = 'pending_pix';
                    $this->payment->pay_pix_key         = $gatewayReturn->response["ResponseDetail"]["Key"] ?? null;
                    $this->payment->pay_pix_qr_code     = $gatewayReturn->response["ResponseDetail"]["QrCode"] ?? null;
                    $this->payment->pay_pix_qr_code_url = $gatewayReturn->response["ResponseDetail"]["QrCode"] ?? null;
                    $this->payment->save();

                    // SET VARIAVEIS
                    $ticket_status           = 'reserva_temp';
                    $conclusao_success       = 'PIX gerado com sucesso';
                    $conclusao_success_sub   = 'Use a chave para efetuar o pagamento';
                    $this->conclusao_success = TRUE;

                    // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                    $this->order->reservation_expiration_date = now()->addHours(48);
                    $this->order->status = 'pending_pix';
                    $this->order->save();
                }
                elseif($gatewayReturn->pagamento_forma_slug == 'boleto')
                {
                    DD('AINDA EM CONSTRUÇÃO');

                    $this->pay_boleto_barcode = $this->payment->pay_boleto_barcode ?? '---';
                    $this->conclusao_success  = true;

                    // SALVA RETORNO
                    $this->payment->status_old          = $this->payment->status;
                    $this->payment->status              = 'pending_boleto';
                    $this->payment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key'] ?? null;
                    $this->payment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                    $this->payment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                    $this->payment->save();

                    // SET VARIAVEIS
                    $ticket_status           = 'reserva_temp';
                    $conclusao_success       = 'Boleto gerado com sucesso';
                    $conclusao_success_sub   = 'Efetue o pagamento do Boleto';
                    $this->conclusao_success = TRUE;

                    // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                    $this->order->reservation_expiration_date = Carbon::create($this->payment->pay_boleto_expiration_date)->subHours(3)->addDays(5);
                    $this->order->status = $this->payment->status;
                    $this->order->save();
                }
                else
                {
                    // SET VARIAVEIS
                    $this->conclusao_success = TRUE;

                    // ORDER
                    $this->order->status = $this->payment->status;
                    $this->order->save();
                }

                // RODAR TICKETS
                trataTicketsEvento($this->order->id,$ticket_status ?? 'reserva_temp');

                // NOTIFICAÇÃO
                $this->dialog()->show([
                    'title'       => mb_strtoupper($conclusao_success ?? $gatewayReturn->msg),
                    'description' => $conclusao_success_sub ?? ($gatewayReturn->msg_sub ?? null),
                    'icon'        => 'info'
                ]);

                // NOTIFICAÇÃO
                session()->flash('conclusao_info', $conclusao_success ?? $gatewayReturn->msg);
                session()->flash('conclusao_info_sub', $conclusao_success_sub ?? $gatewayReturn->msg_sub ?? null);
                return;
            }

            // SE PAGAMENTO OK
            if($gatewayReturn->pagamento_ok ?? false)
            {
                // VALIDA PAGAMENTO
                $this->validarPagamento($this->order->id);

                // TODO: VERIFICAR SE O VALOR PAGO LIQUIDA TODA A COMRPA

                // PREPARA AJUSTE TICKETS
                if(in_array($this->target_type,['evento_patrocinador']))
                {
                    $ticket_status           = 'disponivel';
                    $conclusao_success       = 'Pagamento realizado';
                    $conclusao_success_sub   = '';
                    $this->conclusao_success = TRUE;
                }
                else
                {
                    $ticket_status           = 'disponivel';
                    $conclusao_success       = 'Pagamento realizado';
                    $conclusao_success_sub   = 'Vaga garantida';
                    $this->conclusao_success = TRUE;
                }

                // TRATA VOUCHERS
                $tickets = trataTicketsEvento($this->order->id,$ticket_status);

                // VALIDA SE REALIZADO DE FATO
                $this->validarPagamento($this->order->id);

                // EMAIL DE NOTIFICAÇÃO
                // JÁ ENVIADO NO VALID PAGAMENTO

                // NOTIFICAÇÃO
                $this->dialog()->show([
                    'title'       => mb_strtoupper($conclusao_success ?? $gatewayReturn->msg),
                    'description' => $conclusao_success_sub ?? ($gatewayReturn->msg_sub ?? null),
                    'icon'        => 'success'
                ]);

                // ALERT SUCESSO
                session()->flash('success',$conclusao_success ?? ($gatewayReturn->msg ?? null));
                session()->flash('conclusao_success',$conclusao_success ?? ($gatewayReturn->msg ?? null));
                session()->flash('conclusao_success_sub',$conclusao_success_sub ?? ($gatewayReturn->msg_sub ?? null));

                // LIMPA CACHE
                sessionClear('pedido');

                $this->conclusao_success = TRUE;
            }

            // SE CONCLUSAO SUCESSO
            if($this->conclusao_success ?? false)
            {
                return redirect()->route('pagamento',
                [
                    'targetType'  => $this->target_type,
                    'localizador' => $this->order->order_control,
                    'timestamp'   => now()->format('His')
                ]);
            }
        }
        catch (\Throwable $th)
        {
            if($this->order->buyer_email == "proeventpay@gmail.com")
            {
                dd('Throwable by Debug',__FUNCTION__,__LINE__,$th);
            }

            $this->error = TRUE;
            session()->flash('error',$th->getMessage());
            session()->flash('error_sub','('.$th->getCode().')');
            return;
        }
    }

    protected $orderSlip;
    public function processarPagamentoSlip()
    {
        // return; // RETURN

        $validatedData = [];

        // SLIP PIX
        if($this->pay_type == 'slip_pix')
        {
            $validatedData = $this->validate([
                'pix_cpf' => ['required','cpf_cnpj'],
            ]);

            //
            foreach ($this->aceite_termos['slip_pix'] as $termo_values)
            {
                if(!$termo_values['check'])
                {
                    return session()->flash('error','Um ou mais termos não foram aceitos');
                }
            }

            //
            $slipType = 'pix';
        }

        //
        $this->pay_installments_number = 1;

        // CHECK STATUS ATUAL
        $orderCheck = $this->getOrder($this->order->id);

        if(in_array($orderCheck->status, listOrderStatusPaid()))
        {
            $this->conclusao_success = TRUE;
            return session()->flash('conclusao_success', 'Opa! Essa compra já foi paga');
        }

        try
        {
            // REFERENCIA
            switch ($this->target_type)
            {
                case 'event':
                case 'evento':
                    $app_ref = 'app_event';
                    break;
                default:
                    $app_ref = $this->target_type;
                    break;
            }

            // DB::beginTransaction();

            // PEGA VALORES PARCELA
            $valoresInfo = $this->pagamento_parcelas[$this->pay_installments_number ?? 1];

            // SLIP
            $slipService = new OrderService();
            $slipService->gerarCarne($this->order->id,$app_ref,$this->pagamento_parcelas,$slipType);

            //
            $this->order     = $slipService->order;
            $this->orderSlip = $slipService->orderSlip->where('status',"aguardando_pagamento")->first();

            //
            if(!$this->orderSlip ?? false)
            {
                $this->error = TRUE;
                session()->flash('error','ERRO AO GERAR CARNÊ');
                session()->flash('error_sub','Tente novamente mais tarde');
                return;
            }

            // AJUSTA ATRIBUTOS
            $paymentCreate = [
                'app_ref'                        => $app_ref,
                'app_ref_order_id'               => $this->order->id,
                'gateway_id'                     => $this->gateway_pay->id,
                'gateway_slug'                   => $this->gateway_pay->pay_gateway_slug,
                'gateway_sandbox'                => $this->target->pay_sandbox ?? false,
                'status'                         => 'sending_provider',
                'description'                    => strtoupper('ENVIANDO PARA PROCESSAMENTO'),
                'value_paid'                     => $valoresInfo['parcela_valor'],
                'value_fees'                     => $valoresInfo['parcela_valor_encargos'],
                'value_liquid'                   => $valoresInfo['parcela_valor'] - $valoresInfo['parcela_valor_encargos'],
                'fee_percentage_used'            => $valoresInfo['taxa'],
                'paid_label'                     => toMoney($valoresInfo['parcela_valor'],'R$ '),
                'paid_description'               => strtoupper($valoresInfo['label'] . ' // PAGO COM ' . __($this->pay_type ?? null)),
                'pay_type'                       => strtolower($this->pay_type ?? null),
                'pay_integration_type'           => $this->target->pay_sandbox ? 'sandbox' : 'live',
                'pay_code_promo_id'              => $this->pay_code_promo_id ?? null,
                'pay_code_promo_discount_amount' => $this->pay_code_promo_discount_amount ?? null,
                'pay_installments_number'        => $valoresInfo['parcela'],
                'pay_installment_value'          => $valoresInfo['parcela_valor'],
                'order_slip_id'                  => $this->orderSlip->id,
            ];

            // CRIA TRANSACAO
            $this->payment = AppPayment::create($paymentCreate);

            // ORDER - ASSOCIA PAGAMENTO
            $this->order->payment_id = $this->payment->id;
            $this->order->save();

            // SLIP - ASSOCIA PAGAMENTO
            $this->orderSlip->update([
                'payment_id' => $this->payment->id,
            ]);

            // INSTANCIA GATEWAY
            switch ($this->gateway_pay->appGateway->gateway_slug ?? false)
            {
                case 'safe2pay':
                    $gatewayReturn = $this->processarSafe2pay($this->payment, $this->order, $this->gateway_pay, $validatedData, $this->target->pay_sandbox ?? false);
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
                $this->payment->description       = 'CÓDIGO: '. ($gatewayReturn->code ?? 0000) .' // '. ($gatewayReturn->msg ?? 'Erro ao processar pagamento');
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
                return;
            }

            // SE CUPOM = PROCESSSA CODES PROMO
            if($this->code_promo_id ?? false)
            {
                $codePromo = EventTicketCodePromo::find($this->code_promo_id);
                $codePromo->code_use_amount_used = $codePromo->code_use_amount_used + 1;

                // SE UTILIZAÇÃO UNICA
                if($codePromo->code_use_amount == 1)
                {
                    $codePromo->code_used_order_id = $this->order->id;
                }

                // SE ATINGIU A QTD DE USOS DO CUPOM
                if($codePromo->code_use_amount_used >= $codePromo->code_use_amount)
                {
                    $codePromo->code_used = true;
                }

                // SAVE PROMO
                $codePromo->save();

                // UPDATE ORDER
                $this->order->code_promo_id = $this->code_promo_id;
                $this->order->code_promo_discount_amount = $this->code_promo_discount_amount;
                $this->order->code_promo_label           = $this->code_promo_label ?? null;
                $this->order->code_promo_price_old       = $this->code_promo_price_old ?? null;
                $this->order->code_promo_price_less      = $this->code_promo_price_less ?? null;
                $this->order->code_promo_price_new       = $this->code_promo_price_new ?? null;
                $this->order->save();

                // UPDATE PAYMENT
                $this->payment->pay_code_promo_discount_amount = $this->code_promo_price_less ?? 0;
            }

            // SALVA RETORNO
            $this->payment->status_old   = $this->payment->status;
            $this->payment->status       = $gatewayReturn->status;
            $this->payment->pay_nsu      = $gatewayReturn->nsu;
            $this->payment->description  = $gatewayReturn->msg;
            $this->payment->value_paid   = $gatewayReturn->pagamento_valor;
            $this->payment->value_fees   = $gatewayReturn->pagamento_taxa;
            $this->payment->value_liquid = $gatewayReturn->pagamento_liquido;
            $this->payment->pay_json_response = json_encode($gatewayReturn);
            $this->payment->save();

            //
            if(in_array($this->pay_type,['pix','slip_pix']))
            {
                // ATUALIZA RETORNO
                $this->payment->status_old          = $this->payment->status;
                $this->payment->status              = $this->pay_type;
                $this->payment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key']    ?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['Key'] ?? null);
                $this->payment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['QrCode'] ?? null);
                $this->payment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['QrCode'] ?? null);
                $this->payment->save();

                // SET VARIAVEIS
                $ticket_status           = 'reserva_temp';
                $conclusao_success       = 'PIX gerado com sucesso';
                $conclusao_success_sub   = 'Use a chave para efetuar o pagamento';
                $this->conclusao_success = TRUE;

                // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                $this->order->reservation_expiration_date = now()->addHours(48);
                $this->order->status = 'pending_' . $this->payment->status;
                $this->order->save();
            }
            elseif($gatewayReturn->pagamento_forma_slug == 'boleto')
            {
                DD('AINDA EM CONSTRUÇÃO');

                $this->pay_boleto_barcode = $this->payment->pay_boleto_barcode ?? '---';
                $this->conclusao_success  = true;

                // SALVA RETORNO
                $this->payment->status_old          = $this->payment->status;
                $this->payment->status              = 'pending_boleto';
                $this->payment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key'] ?? null;
                $this->payment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                $this->payment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                $this->payment->save();

                // SET VARIAVEIS
                $ticket_status           = 'reserva_temp';
                $conclusao_success       = 'Boleto gerado com sucesso';
                $conclusao_success_sub   = 'Efetue o pagamento do Boleto';
                $this->conclusao_success = TRUE;

                // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                $this->order->reservation_expiration_date = Carbon::create($this->payment->pay_boleto_expiration_date)->subHours(3)->addDays(5);
                $this->order->status = $this->payment->status;
                $this->order->save();
            }
            else
            {
                // SET VARIAVEIS
                $this->conclusao_success = TRUE;

                // ORDER
                $this->order->status = $this->payment->status;
                $this->order->save();
            }

            // RODAR TICKETS
            trataTicketsEvento($this->order->id,$ticket_status ?? 'reserva_temp');

            // NOTIFICAÇÃO
            session()->flash('conclusao_info', $conclusao_success ?? $gatewayReturn->msg);
            session()->flash('conclusao_info_sub', $conclusao_success_sub ?? $gatewayReturn->msg_sub ?? null);

            // NOTIFICAÇÃO
            session()->flash('info', $conclusao_success ?? $gatewayReturn->msg);
            session()->flash('info_sub', $conclusao_success_sub ?? $gatewayReturn->msg_sub ?? null);

            // ATUALIZA ORDER
            $this->order = $this->getOrder($this->order->id);

            $this->pay_type = false;
            $this->forma_pagamento_disponivel = false;

            $this->dialog()->show([
                'title'       => mb_strtoupper($conclusao_success ?? $gatewayReturn->msg),
                'description' => $conclusao_success_sub ?? ($gatewayReturn->msg_sub ?? null),
                'icon'        => 'info'
            ]);

            return redirect()->route('pagamento',[
                    'targetType'  => $this->target_type,
                    'localizador' => $this->order->order_control,
                    'slipInstallmentControl'   => $this->orderSlip->slip_installment_control,
                ]);
        }
        catch (\Throwable $th)
        {
            if($this->order->buyer_email == "proeventpay@gmail.com")
            {
                dd('Throwable by Debug',__FUNCTION__,__LINE__,$th);
            }

            $this->error = TRUE;
            session()->flash('error',$th->getMessage());
            session()->flash('error_sub','('.$th->getCode().')');
        }
    }

    public $payload;
    public $sandbox;
    public $token;
    public $pagamento;
    public $transaction;
    public $service;

    public function processarSafe2pay($payment, $order, $gateway, $data, $sandBox=false)
    {
        try
        {
            if(in_array($payment->pay_type,['pix','slip_pix']))
            {
                $sandBox = false; // FORÇA SER LIVE
                $this->token = $gateway->token_live; // PIX SOMENTE PRODUÇÃO
                $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $gateway->appGateway->gateway_slug , 'callbackType' => $payment->app_ref, 'orderId' => $order->id, 'paymentId' => $payment->id]);
            }
            else
            {
                $this->token  = $sandBox ? $gateway->token_test : $gateway->token_live;
                $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $gateway->appGateway->gateway_slug , 'callbackType' => $payment->app_ref, 'orderId' => $order->id, 'paymentId' => $payment->id]);
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
            $service = new Safe2PayService($this->token,$sandBox);

            // SET APLICATION
            $service->Application = trim(mb_strtoupper(toSlug($gateway->pay_gateway_slug,'-') . '.' . $this->target_type . '.' . ($sandBox ? "SANDBOX" : "LIVE")));
            $service->Vendor      = trim($vendor ?? 'PROEVENTPAY');
            $service->CallbackUrl = $postback_url;
            $service->Reference   = mb_strtoupper($order->order_control);
            $service->setAplication();

            // SET META
            $service->order_id              = $order->id;
            $service->localizador           = $order->order_control;
            $service->payment_id            = $payment->id;
            $service->gateway_id            = $gateway->id;
            $service->app_ref               = $payment->app_ref;
            $service->order_amount          = $payment->pay_installment_value ?? 0;
            $service->order_amount_discount = in_array($this->target_type, ['evento_patrocinador']) ? 0 : ($payment->pay_code_promo_discount_amount ?? 0);
            $service->order_amount_pay      = $payment->pay_installment_value ?? 0;
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

            // PERCORE OS ITENS DO PEDIDO
            if($order->itens ?? false)
            {
                // SE ITENS
                foreach ($order->itens ?? [] as $itemKey => $orderItem)
                {
                    // APPEND PRODUCTS
                    $service->appendProducts(
                        Code:$orderItem->id ?? $itemKey,
                        UnitPrice:$orderItem->item_amount ?? 0,
                        Quantity:1,
                        Description:($orderItem->item_description ?? 'ITEM') . ' | ' . ($orderItem->user_name ?? '#' . $itemKey++) . ' | ' . ($orderItem->user_email ?? 'COMPRA')
                    );
                }
            }
            else
            {
                // APPEND PRODUCTS
                $service->appendProducts(
                    Code:$order->order_control,
                    UnitPrice:$order->order_amount_pay ?: ($order->order_amount ?? 0),
                    Quantity:1,
                    Description:'COMPRA SEM ITENS - LOCALIZADOR ' . $order->order_control
                );
            }

            // SE PAGAMENTO FOR CARNÊ
            if(($payment->order_slip_id ?? false) && in_array($this->pay_type,['slip_pix']))
            {
                // LIMPA PRODUTOS
                $service->setProducts(clearFull:true);

                // APPEND PRODUCTS
                $service->appendProducts(
                    Code:'SLIP.'.$this->orderSlip->slip_installment_control,
                    UnitPrice:$payment->value_liquid,
                    Quantity:1,
                    Description:'CARNÊ // ' . $this->orderSlip->installment_description
                );

                // ADICIONA META
                $service->pay_slip             = true;
                $service->pay_slip_id          = $this->orderSlip->id;
                $service->pay_slip_description = $this->orderSlip->installment_description;
                $service->setMeta();
            }
            else // SE NAO FOR SLIP
            {
                // SE EXISTIR DESCONTO
                if($this->code_promo_id ?? false)
                {
                    // APPEND DISCOUNT PRODUCTS
                    $service->appendProducts(
                        Code:$this->code_promo_id,
                        UnitPrice:$this->code_promo_price_less ?? 0,
                        Quantity:1,
                        Description:mb_strtoupper($this->code_promo_label ?? 'DESCONTO'),
                        Discount:true,
                    );
                }
            }

            // SE EXISTIR REPASSE DE TAXA
            if($payment->value_fees ?? false)
            {
                // ADICIONA ENCARGOS
                $service->appendProducts(
                    Code:999,
                    UnitPrice:$payment->value_fees,
                    Quantity:1,
                    Description:'ENCARGOS PARCELAMENTO ' . $payment->fee_percentage_used . '%',
                );

                // COBRAR JUROS
                $service->IsApplyInterest = true;
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
                    // SAFE2PAY - ID ACCOUNT
                    // PARA O CASO DE SLIP, REPASSE DE ENCARGOS PARA SAFE2PAY
                    if($gateway->appGateway->pay_slip_pix_split_receiver_id ?? false)
                    {
                        $service->ReceiverId   = $gateway->appGateway->pay_slip_pix_split_receiver_id ?? false;   // SAFE2PAY - ID ACCOUNT
                        $service->ReceiverName = $gateway->appGateway->pay_slip_pix_split_receiver_name ?? false; // SAFE2PAY - NAME SPLIT
                        $payload = $service->setPaymentPix($this->pix_cpf,split:true,splitValor:toMoneyDot($this->payment->value_fees));
                    }
                    else
                    {
                        $payload = $service->setPaymentPix($this->pix_cpf);
                    }

                    // SALVA PAYLOAD
                    $this->payment->pay_json_request   = json_encode($payload);
                    $this->payment->pay_pix_expires_at = $service->ExpirationDateTime;
                    $this->payment->save();
                    break;
                case 'card_credit':
                case 'credit_card':
                    $service->Holder = $this->card_credit_nome;
                    $service->CardNumber = $this->card_credit_num;
                    $service->ExpirationDateMM = $this->card_credit_validade_mm;
                    $service->ExpirationDateAAAA = $this->card_credit_validade_aaaa;
                    $service->SecurityCode = $this->card_credit_cvv;
                    $service->InstallmentQuantity = $this->pay_installments_number ?? 1;
                    $payload = $service->setPaymentCredit($this->card_credit_cpf);

                    // SALVA PAYLOAD
                    $this->payment->paid_description = $payload['PaymentObject']['CardNumber'] ? ($payload['PaymentObject']['CardNumber']) : $this->payment->paid_description;
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

            // PROCESSAR
            return $service->executeTransaction();
        }
        catch (\Throwable $th)
        {
            $return = new stdClass();
            $return->error             = true;
            $return->error_getMessage  = $th->getMessage();
            $return->msg               = $th->getMessage();
            $return->msg_sub           = null;
            $return->pay_json_request  = json_encode($post ?? []);
            $return->pay_json_response = json_encode($transaction ?? []);
            $return->response          = $transaction ?? false;

            return $return;
        }
    }

    public $calculoParcelas;
    public $installment_max;
    public function calculaParcelas($type=false)
    {
        //
        if(!$type)
        {
            $type = $this->pay_type;
        }

        //
        $this->forma_pagamento_selecionada = $this->forma_pagamento_disponivel[$type] ?? false;

        //
        if ($type == 'card_credit')
        {
            // GET TAXAS
            $taxas = json_decode($this->gateway_pay->pay_gateway_installment_fees_json, true);

            // APLICA TAXAS
            $apply_installment_fees = $this->gateway_pay->apply_installment_fees;

            //
            if ($this->target_type == 'evento_patrocinador')
            {
                // PARCELA MAX
                $this->installment_max = $this->order->plano->installments_max ?? 1;

                // APLICA TAXAS
                $apply_installment_fees = $this->order->plano->installments_fees_pay;
            }
            elseif($this->target->pay_card_credit_installment_max ?? false)
            {
                $this->installment_max = $this->target->pay_card_credit_installment_max ?? false;
            }
            else
            {
                $this->installment_max = $this->gateway_pay->pay_card_credit_installment_max ?? false;
            }

            $this->pagamento_parcelas = [];

            //
            foreach (range(1, $this->installment_max ?? 1) as $parcelaKey => $parcela)
            {
                $parcela_valor   = toMoneyDot($this->order_amount / $parcela);
                $taxa            = 0;
                $taxaAntecipacao = 0;
                $encargos        = 0;
                $amount          = toMoneyDot($this->order_amount);
                $order_amount    = toMoneyDot($this->order_amount);

                if($parcela != 1)
                {
                    // APLICAR TAXAS CLIENTE
                    if($apply_installment_fees ?? false)
                    {
                        // Taxa em porcentagem
                        $taxa = (float) $taxas[$parcela] ?? 0;

                        // Cálculo do valor inicial necessário
                        $order_amount = $amount / (1 - ($taxa / 100));
                    }

                    // CALCULA ENGARGOS
                    $encargos = $order_amount - $amount;

                    // PROCESSA OS VALORES
                    $parcela_valor  = $order_amount / $parcela;

                    // PROCESSA OS VALORES
                    $parcela_valor  = $order_amount / $parcela;

                    // PARCELA MINIMA
                    if(toMoneyInt($parcela_valor) < ($this->forma_pagamento_selecionada['valorMinimo'] ?? $this->gateway_pay->pay_card_credit_installment_amount_min))
                    {
                        continue;
                    }

                    // dd(
                    //     toMoneyInt($parcela_valor),
                    //     $this->forma_pagamento_selecionada['valorMinimo'],
                    //     $this->gateway_pay->pay_card_credit_installment_amount_min,
                    // );
                }

                // SET LABEL
                $label = ($parcela . 'x ' .toMoney(toMoneyInt($parcela_valor), 'R$ ')) . ' = ' . toMoney(toMoneyInt($order_amount), 'R$ ');
                $label = ($parcela . 'x ' .toMoney(toMoneyInt($parcela_valor), 'R$ '));

                // SE JUROS
                // if($encargos ?? false)
                // {
                //     $label .= ' - com juros';
                // }
                // else
                // {
                //     $label .= ' - sem acréscimo';
                // }

                // DFINE PARCELA
                $this->pagamento_parcelas[$parcela] = [
                    'parcela_qtd'            => $parcela,
                    'label'                  => $label,
                    'taxa'                   => $taxa,
                    'order_amount'           => toMoneyInt($order_amount),
                    'encargos'               => toMoneyInt($encargos),
                    'parcela_valor'          => toMoneyInt($parcela_valor),
                    'parcela_valor_encargos' => toMoneyInt($encargos / $parcela),
                ];
            }

            //
            $this->calculoParcelas = $this->pagamento_parcelas;
        }
        elseif ($type == 'slip_pix')
        {
            // APLICA TAXAS
            $apply_installment_fees = $this->gateway_pay->apply_installment_fees;

            // GET TAXAS
            if($apply_installment_fees ?? false)
            {
                $taxas = json_decode($this->gateway_pay->pay_slip_pix_fees_json, true);
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
                $this->installment_max = $this->gateway_pay->pay_slip_pix_installment_max ?? false;
            }

            // SE installment_max CABE NO EVENTO
            if($this->target->pay_slip_pix_installment_max_days_before ?? false)
            {
                // DATA EVENTO
                $dateNow        = now();
                $eventDateStart = Carbon::parse(\Carbon\Carbon::parse($this->target->event_datetime_start)->format('Y-m-d'));

                // CALCULA MESES
                $diffMonths = $dateNow->diffInMonths($eventDateStart->addMonths(10)) ?? 0;

                // SE MAX DEFINIDO MAIOR QUE QTD MESES // QTD MESES
                if($this->installment_max > $diffMonths)
                {
                    $this->installment_max = $diffMonths;
                }
            }

            $now = now();
            $this->pagamento_parcelas = [];

            //
            $amount          = toMoneyDot($this->order_amount);
            $order_amount    = toMoneyDot($this->order_amount);
            $parcela_valor   = toMoneyDot($this->order_amount) / $this->installment_max;
            $taxa            = 0;
            $encargos        = 0;

            // SE APLICAR TAXAS
            if($apply_installment_fees ?? false)
            {
                // Taxa em porcentagem
                $taxa = (float) $taxas[$this->installment_max] ?? 0;

                // Cálculo do valor inicial necessário
                $order_amount = $amount / (1 - ($taxa / 100));

                //
                $encargos = $order_amount - $amount;

                $parcela_valor = $order_amount / $this->installment_max;
            }

            //
            foreach (range(1, $this->installment_max ?? 1) as $parcelaKey => $parcela)
            {
                // SE NAO FOR ZERO
                if($parcelaKey ?? false)
                {
                    $now->addMonth();
                }

                $vencimento = $now->format('d/m/Y');
                $date_due   = $now->format('Y-m-d 23:59:59');

                // SET LABEL
                $label = ('Parcela ' . $parcela . ' de ' . $this->installment_max);

                // DFINE PARCELA
                $this->pagamento_parcelas[$parcela] = [
                    'parcela'                => $parcela,
                    'parcela_qtd'            => $this->installment_max,
                    'label'                  => $label,
                    'taxa'                   => $taxa,
                    'order_amount'           => toMoneyInt($order_amount),
                    'encargos'               => toMoneyInt($encargos),
                    'parcela_valor'          => toMoneyInt($parcela_valor),
                    'parcela_valor_encargos' => toMoneyInt($encargos / $this->installment_max),
                    'vencimento'             => $vencimento,
                    'date_due'               => $date_due,
                ];
            }

            //
            $this->calculoParcelas = $this->pagamento_parcelas;
        }
        else // OUTRAS FORMA DE PAGAMENTO
        {
            $taxa          = 1;
            $parcela       = 1;
            $encargos      = 0;
            $order_amount  = toMoneyDot($this->order_amount);
            $parcela_valor = toMoneyDot($this->order_amount);
            $label         = $parcela . 'x ' .toMoney(toMoneyInt($parcela_valor), 'R$ ');

            $this->pagamento_parcelas[$parcela] = [
                'parcela'                => $parcela,
                'parcela_qtd'            => $parcela,
                'label'                  => $label,
                'taxa'                   => $taxa,
                'order_amount'           => toMoneyInt($order_amount),
                'encargos'               => toMoneyInt($encargos),
                'parcela_valor'          => toMoneyInt($parcela_valor),
                'parcela_valor_encargos' => toMoneyInt($encargos) / $parcela,
            ];
        }

        $this->pay_installments_number = 1;

        return $this->pagamento_parcelas;
    }

    public $aceite_termos = [];
    public function updated($name, $value)
    {
        //
        if ($name == 'pay_type')
        {
            $this->aceite_termos = [];

            // TERMOS
            if($value == 'slip_pix')
            {
                $this->aceite_termos['slip_pix'] = [
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
                        'termo' => 'Estou ciente que passados 7 dias desta compra não tenho direito a rembolso do que já foi pago.',
                        'check' => false,
                    ],
                ];
            }

            // VALIDA SE REALIZADO DE FATO
            // $this->validarPagamento($this->order->id);
            $this->calculaParcelas($value);
        }
    }

    public $pix_valido;
    public $modal_error;
    public $modal_info;
    public $modal_pagamento_success;
    public function render()
    {
        // CHECK EXPIRADO
        $this->checkExpiration();

        // dd(__LINE__);

        // PARA OS CASOS QUE SERÃO CARNÊ
        if(in_array($this->order->status, ['pending_slip_pix']))
        {
            $this->pix = FALSE;

            // VALIDAR OS PAGAMENTOS DO CARNE
            //$this->validarPagamento($this->order->id); // FLUXO SEM CARNE
        }
        elseif(in_array($this->order->status, ['fase_pagamento','pending_boleto','pending_pix']))
        {
            $this->payment = $this->order->payment;

            if($transacoes = $this->order->payments ?? false)
            {
                if(!$this->validarPagamento($this->order->id))
                {
                    foreach ($transacoes as $transacao_key => $transacao_values)
                    {
                        //
                        if(!$transacao_values->pay_nsu ?? false)
                        {
                            continue;
                        }

                        // SE FOI NO PIX
                        if(in_array($transacao_values->status,['pix','pendente','pending_pix','pending_slip_pix']))
                        {
                            if(($this->payment ?? false) && ($transacao_values->id != $this->payment->id))
                            {
                                continue;
                            }

                            $this->pix = $this->payment;
                            $this->pix_valido = true;

                            if($this->payment->pay_nsu ?? false)
                            {
                                if($this->payment->pay_pix_expires_at ?? false)
                                {
                                    $this->pix_valido = (calculaSegundosDif($this->payment->pay_pix_expires_at) > 0);

                                    if(!$this->pix_valido ?? false)
                                    {
                                        $this->modal_error=true;
                                        session()->flash('modal_alert','O PIX anterior excedeu o prazo limite');
                                        session()->flash('modal_alert_sub','Gere uma nova chave para pagamento');
                                        //
                                        session()->flash('pix_alert','O PIX anterior excedeu o prazo limite');
                                        session()->flash('pix_alert_sub','Gere uma nova chave para pagamento');
                                    }
                                }
                            }
                        }
                        else
                        {
                            $this->validarPagamento($this->order->id);
                        }
                    }
                }
            }
        }

        //
        switch (strtolower($this->target_type))
        {
            case 'evento_patrocinador':
                return view('livewire.pagamento.patrocinador.realizar-pagamento-patrocinador-v1')->layout('layouts.app-pep-home');
            default:
                return view('livewire.pagamento.realizar-pagamento-safe2pay')->layout('layouts.app-pep-home');
        }
    }

    public function formasPagamento($target)
    {
        //
        $this->forma_pagamento_disponivel = [];

        // BOLETO
        if(($this->gateway_pay->pay_boleto ?? false) && ($target->pay_boleto ?? false) && (\Carbon\Carbon::parse($target->event_datetime_start)->format('Ymd') - now()->format('Ymd')) >= 4)
        {
            $this->forma_pagamento_disponivel['boleto'] = [
                'slug'        => 'boleto',
                'label'       => 'Boleto' ,
                'descricao'   => 'Dois ou mais dias úteis para liberação',
                'parcelado'   => 1,
                'valorMinimo' => 1,
            ];
        }

        // CARTAO DE CREDITO
        if(($this->gateway_pay->pay_card_credit ?? false) && ($target->pay_card_credit ?? false))
        {
            $this->forma_pagamento_disponivel['card_credit'] = [
                'slug'        => 'card_credit',
                'label'       => 'Cartão de Crédito' ,
                'descricao'   => 'Liberação imediata',
                'parcelado'   => $target->pay_card_credit_installment_max ?? 1,
                'valorMinimo' => $target->pay_card_credit_installment_amount_min ?? 1,
            ];
        }

        // PIX
        if(($this->gateway_pay->pay_pix ?? false) && ($target->pay_pix ?? false))
        {
            $this->forma_pagamento_disponivel['pix'] = [
                'slug'        => 'pix',
                'label'       => 'PIX' ,
                'descricao'   => 'Liberação imediata',
                'parcelado'   => 1,
                'valorMinimo' => 100,
            ];
        }

        // CARNE ONLINE
        if(($this->gateway_pay->pay_slip_pix ?? false) && ($target->pay_slip_pix ?? false))
        {
            $this->forma_pagamento_disponivel['slip_pix'] = [
                'slug'        => 'slip_pix',
                'label'       => 'CARNÊ' ,
                'descricao'   => 'Liberação após quitação',
                'parcelado'   => 1,
                'valorMinimo' => 100,
            ];
        }

        // INICIA PARCELADO
        if(!$this->pay_installments_number) $this->pay_installments_number = 1;

        // SE EXISTE PAY_TYPE
        if($this->pay_type && array_key_exists($this->pay_type, $this->forma_pagamento_disponivel))
        {
            $this->forma_pagamento_selecionada = $this->forma_pagamento_disponivel[$this->pay_type];
            $this->updated('pay_type', $this->pay_type);
        }
    }

    public function formasPagamentoPatrocinio($target)
    {
        //
        $this->forma_pagamento_disponivel = [];

        $plano = $this->order->plano;

        // BOLETO
        if(($this->gateway_pay->pay_boleto ?? false) && ($plano->pay_boleto ?? false) && (\Carbon\Carbon::parse($target->event_datetime_start)->format('Ymd') - now()->format('Ymd')) >= 4)
        {
            $this->forma_pagamento_disponivel['boleto'] = [
                'slug'        => 'boleto',
                'label'       => 'Boleto' ,
                'descricao'   => 'Dois ou mais dias úteis para liberação',
                'parcelado'   => 1,
                'valorMinimo' => 1,
            ];
        }

        // CARTAO DE CREDITO
        if(($this->gateway_pay->pay_card_credit ?? false) && ($plano->pay_credit ?? false))
        {
            $this->forma_pagamento_disponivel['card_credit'] = [
                'slug'        => 'card_credit',
                'label'       => 'Cartão de Crédito' ,
                'descricao'   => 'Liberação imediata',
                'parcelado'   => $target->pay_card_credit_installment_max ?? 1,
                'valorMinimo' => $target->pay_card_credit_installment_amount_min ?? 1,
            ];
        }

        // PIX
        if(($this->gateway_pay->pay_pix ?? false) && ($plano->pay_pix ?? false))
        {
            $this->forma_pagamento_disponivel['pix'] = [
                'slug'        => 'pix',
                'label'       => 'PIX' ,
                'descricao'   => 'Liberação imediata',
                'parcelado'   => 1,
                'valorMinimo' => 100,
            ];
        }

        // dd($target->pay_pix,$this->forma_pagamento_disponivel);

        // INICIA PARCELADO
        if(!$this->pay_installments_number) $this->pay_installments_number = 1;

        // SE EXISTE PAY_TYPE
        if($this->pay_type && array_key_exists($this->pay_type, $this->forma_pagamento_disponivel))
        {
            $this->forma_pagamento_selecionada = $this->forma_pagamento_disponivel[$this->pay_type];
            $this->updated('pay_type', $this->pay_type);
        }
    }

    public function getOrder($orderId)
    {
        switch (strtolower($this->target_type))
        {
            case 'event':
            case 'evento':
            case 'app_event':
                $this->order = AppEventOrder::find($orderId);
                break;

            case 'evento_patrocinador':
                $this->order = AppEventOrderSponsorship::find($orderId);
                break;

            default:
                $this->order = AppEventOrder::find($orderId);
        }

        // PEGA PAGAMENTO
        $this->payment = $this->order->payment;

        return $this->order;
    }

    public function cancelarPedido()
    {
        // SE STATUS
        if(!in_array($this->order->status, listOrderStatusNaoCancelar()))
        {
            $this->order->status                      = 'cancelado_no_pagamento';
            $this->order->order_cancel_datetime       = now()->format('Y-m-d H:i:s');
            $this->order->order_cancel_description    = 'Desistiu no momento do pagamento';
            $this->order->reservation_expiration_date = null;
            $this->order->save();
            //
            session()->flash('error','Pedido cancelado');

            // SE TICKETS
            if ($tickets = $this->order->tickets)
            {
                foreach ($tickets ?? [] as $ticketKey => $ticket)
                {
                    $tickets[$ticketKey]->delete();
                }
            }
        }

        sessionClear('pedido');

        return;
    }

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
        $this->code_promo_price_old  = (int) $this->order_amount;
        $this->code_promo_price_less = (int) $this->code_promo_discount_amount;
        $this->code_promo_price_new  = (int) $newTicketPrice;

        //
        $this->order_amount = (int) $newTicketPrice;

        //
        $this->calculaParcelas();

        //
        session()->flash('ticket_code_promo_sucesso', 'Cupom aplicado');
        session()->flash('ticket_code_promo_ok', $this->code_promo_label);

        return true;
    }

    public function resetCupom()
    {
        $this->order_amount = $this->order->order_amount;
        $this->code_promo_discount_amount = 0;
        //
        $this->code_promo_id         = '';
        $this->code_promo_label      = '';
        $this->code_promo_price_old  = '';
        $this->code_promo_price_new  = '';
        $this->code_promo_price_less = '';
        $this->code_promo_selected   = false;
    }

    public function removeCupom()
    {
        $this->ticket_code_promo = '';
        $this->resetCupom();
        $this->calculaParcelas();
    }

    public function buscarEndereco($cep)
    {
        $buscaCep = buscarCep($cep);
        //
        $this->card_credit_endereco = ucwords($buscaCep->endereco ?? '');
        $this->card_credit_endereco_bairro = ucwords($buscaCep->bairro ?? '');
        $this->card_credit_endereco_cidade = ucwords($buscaCep->cidade ?? '');
        $this->card_credit_endereco_estado = ucwords($buscaCep->estado ?? '');
    }
}



