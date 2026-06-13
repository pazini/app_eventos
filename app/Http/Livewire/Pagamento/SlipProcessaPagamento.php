<?php

namespace App\Http\Livewire\Pagamento;

use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentSlip;
use App\Services\Payments\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use stdClass;
use WireUi\Traits\Actions;


class SlipProcessaPagamento extends Component
{
    use Actions;

    public $slipId;
    protected $paymentService;
    public function mount($slipId)
    {
        $this->slipId = $slipId ?? false;
        $this->getSlip($this->slipId);
    }

    public function render()
    {
        return view('livewire.pagamento.slip-processa-pagamento');
    }

    public $order;
    public $event;
    public $gatewayPay;
    public $slip;
    public $currentPayment;
    public $gateway_pay;
    public $pay_sandbox;
    public function getSlip($slipId=false)
    {
        $paymentService = new PaymentService();
        $paymentService->slipId = $this->slipId;

        $this->slip = $paymentService->getSlip();

        //
        if($this->slip ?? FALSE)
        {
            //
            $this->order          = $paymentService->getOrder();
            $this->event          = $paymentService->getEvent();
            $this->gatewayPay     = $paymentService->getGatewayPay();
            $this->currentPayment = $paymentService->getPayment();
        }

        //
        if($this->currentPayment ?? false)
        {
            if(in_array($this->currentPayment->pay_type,['pix','slip_pix']))
            {
                if(!$paymentService->checkValidatePix())
                {
                    $this->currentPayment = FALSE;

                    // NOTIFICAÇÃO
                    session()->flash('info','PIX EXPIRADO');
                    session()->flash('info_sub','A chave PIX gerada expirou. Gere uma nova chave para pagamento.');
                }
            }
        }
    }


    public $pay_installments_number=1;
    public $error;
    public $conclusao_error;
    public $conclusao_success;
    public function processarPagamento($debug=false)
    {
        $this->pay_sandbox = $debug ? true : false;

        //
        if (in_array($this->slip->installment_pay_type,['pix','slip_pix']))
        {
            $validatedData = $this->processarPix();
        }

        $paymentService = new PaymentService();
        $paymentService->slipId = $this->slipId;

        // VALIDA ORDER STATUS
        if($paymentService->checkOrderPaid())
        {
            // SE JA FOI PAGO
            dd('ORDER JA ESTÁ QUITADA, CRIAR FLUXO E REDIRECT');
            return;
        }

        // VALIDA PAGAMENTO
        if($this->currentPayment ?? false)
        {
            // TODO: VALIDAR SE PAGAMENTO JA FOI FEITO
        }

        try
        {
            // DB::beginTransaction();

            // PEGA VALORES PARCELA
            $valores = $paymentService->installmentsCalulcate($this->slip->installment_pay_type);

            // DEFINE
            $value_paid          = $valores['order_amount'] ?? $this->slip->installment_value;
            $value_fees          = $valores['encargos'] ?? ($this->slip->installment_value_fees ?? 0);
            $value_liquid        = $value_paid - $value_fees;
            $fee_percentage_used = $valores['taxa'] ?? 0;
            $pay_type            = trim((($this->slip->id ?? false) ? 'slip_' : null) . $this->slip->installment_pay_type);

            // DEFINE
            $createPayment = [
                'app_ref'                        => 'app_event',
                'app_ref_order_id'               => $this->order->id,
                'gateway_id'                     => $this->gatewayPay->id,
                'gateway_slug'                   => $this->gatewayPay->pay_gateway_slug,
                'gateway_sandbox'                => $this->target->pay_sandbox ?? false,
                'status'                         => 'sending_provider',
                'description'                    => strtoupper('ENVIANDO PARA PROCESSAMENTO'),
                'value_paid'                     => $value_paid,
                'value_fees'                     => $value_fees,
                'value_liquid'                   => $value_liquid,
                'fee_percentage_used'            => $fee_percentage_used,
                'paid_label'                     => toMoney($value_paid,'R$ '),
                'paid_description'               => strtoupper('PAGO COM ' . __($pay_type ?? 'ND')),
                'pay_type'                       => strtolower($pay_type ?? 'ND'),
                'pay_code_promo_id'              => $this->pay_code_promo_id ?? null,
                'pay_code_promo_discount_amount' => $this->pay_code_promo_discount_amount ?? null,
                'pay_installments_number'        => $valores['parcela_qtd'] ?? 1,
                'pay_installment_value'          => $valores['parcela_valor'] ?? $this->slip->installment_value,
                'pay_integration_type'           => $this->event->pay_sandbox ? 'sandbox' : 'live',
                'order_slip_id'                  => $this->slip->id ?? null,
            ];

            // CRIA TRANSACAO
            $this->currentPayment = AppPayment::create($createPayment);

            // ASSOCIA PAGAMENTO - ORDER
            $this->order->payment_id = $this->currentPayment->id;
            $this->order->save();

            // ASSOCIA PAGAMENTO - SLIP
            $this->slip->payment_id = $this->currentPayment->id;
            $this->slip->save();

            // INSTANCIA GATEWAY
            switch ($this->gatewayPay->appGateway->gateway_slug ?? false)
            {
                case 'safe2pay':
                    $gatewayReturn = $paymentService->processaSafe2pay($this->currentPayment,$validatedData,$this->event->pay_sandbox);
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
                return false;
            }

            // SE ERROR
            if($gatewayReturn->error ?? false)
            {
                // TRANSAÇÃO
                $this->currentPayment->status_old        = $this->currentPayment->status;
                $this->currentPayment->status            = 'return-error';
                $this->currentPayment->description       = 'CÓDIGO: '. ($gatewayReturn->code ?? 0000) .' // '. ($gatewayReturn->msg ?? 'Erro ao processar pagamento');
                $this->currentPayment->pay_json_response = json_encode($gatewayReturn);
                $this->currentPayment->save();

                // ORDER
                $this->order->status = $this->currentPayment->status;
                $this->order->save();

                //
                $this->conclusao_error = true;
                session()->flash('conclusao_error', $gatewayReturn->msg);
                session()->flash('conclusao_error_sub', $gatewayReturn->msg_sub ?? 'Tente novamente mais tarde');
                return false;
            }

            // SALVA RETORNO
            $this->currentPayment->status_old        = $this->currentPayment->status;
            $this->currentPayment->status            = $gatewayReturn->status;
            $this->currentPayment->pay_nsu           = $gatewayReturn->nsu;
            $this->currentPayment->description       = $gatewayReturn->msg;
            $this->currentPayment->pay_json_response = json_encode($gatewayReturn);
            $this->currentPayment->save();

            // SE PENDENTE
            if(($gatewayReturn->status ?? false) && $gatewayReturn->status == "pendente")
            {
                //
                if(in_array($gatewayReturn->pagamento_forma_slug,['pix','slip_pix']))
                {
                    // ATUALIZA RETORNO
                    $this->currentPayment->status              = 'pending_' . $gatewayReturn->pagamento_forma_slug;
                    $this->currentPayment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key']    ?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['Key'] ?? null);
                    $this->currentPayment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['QrCode'] ?? null);
                    $this->currentPayment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? ($gatewayReturn->response['ResponseDetail']['PaymentObject']['QrCode'] ?? null);
                    $this->currentPayment->save();

                    // SET VARIAVEIS
                    $ticket_status           = 'reserva_temp';
                    $conclusao_success       = 'PIX gerado com sucesso';
                    $conclusao_success_sub   = 'Use a chave para efetuar o pagamento';
                    $this->conclusao_success = true;

                    //
                    if($this->currentPayment->order_slip_id ?? false)
                    {
                        $statusOrder = 'pending_slip_' . $gatewayReturn->pagamento_forma_slug;
                        $reservation_expiration_date = null;
                    }
                    else
                    {
                        $statusOrder = $this->currentPayment->status;
                        $reservation_expiration_date = now()->addHours(48);
                    }

                    // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                    $this->order->status = $statusOrder;
                    $this->order->reservation_expiration_date = $reservation_expiration_date;
                    $this->order->save();
                }
                elseif($gatewayReturn->pagamento_forma_slug == 'boleto')
                {
                    DD('AINDA EM CONSTRUÇÃO');

                    $this->pay_boleto_barcode = $this->currentPayment->pay_boleto_barcode ?? '---';
                    $this->conclusao_success  = true;

                    // SALVA RETORNO
                    $this->currentPayment->status              = 'pending_boleto';
                    $this->currentPayment->pay_pix_key         = $gatewayReturn->response['ResponseDetail']['Key'] ?? null;
                    $this->currentPayment->pay_pix_qr_code     = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                    $this->currentPayment->pay_pix_qr_code_url = $gatewayReturn->response['ResponseDetail']['QrCode'] ?? null;
                    $this->currentPayment->save();

                    // SET VARIAVEIS
                    $ticket_status           = 'reserva_temp';
                    $conclusao_success       = 'Boleto gerado com sucesso';
                    $conclusao_success_sub   = 'Efetue o pagamento do Boleto';
                    $this->conclusao_success = TRUE;

                    // ORDER + SET NOVA DATA DE EXPIRAÇÃO
                    $this->order->reservation_expiration_date = Carbon::create($this->currentPayment->pay_boleto_expiration_date)->subHours(3)->addDays(5);
                    $this->order->status = $this->currentPayment->status;
                    $this->order->save();
                }
                else
                {
                    // SET VARIAVEIS
                    $this->conclusao_success = true;

                    // ORDER
                    $this->order->status = $this->currentPayment->status;
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

                return true;

                // return redirect()->route('pagamento',[
                //     'targetType'             => $this->currentPayment->app_ref,
                //     'localizador'            => $this->order->order_control,
                //     'slipInstallmentControl' => $this->slip->slip_installment_control
                // ]);
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

    public function validarPagamento()
    {
        $paymentService = new PaymentService();
        $paymentService->slipId = $this->slipId;

        $gatewayReturn = $paymentService->checkPayment($this->currentPayment->id);

        // ERROR
        if($gatewayReturn->error ?? false)
        {
            $this->error = TRUE;
            session()->flash('error',$gatewayReturn->msg);
            session()->flash('error_sub',$gatewayReturn->msg_sub);
            return;
        }

        // DB::beginTransaction();

        // SUCESSO
        if($gatewayReturn->pagamento_ok ?? false)
        {
            //
            if($gatewayReturn->pagamento_valor < $this->currentPayment->value_paid)
            {
                $status = 'pago_parcial';
            }
            else
            {
                $status = 'pago';
            }

            // ATUALIZA RETORNO
            $this->currentPayment->status                = $status;
            $this->currentPayment->pay_type              = $gatewayReturn->pagamento_forma_slug;
            $this->currentPayment->pay_datetime          = $gatewayReturn->datahora;
            $this->currentPayment->pay_value_paid        = $gatewayReturn->pagamento_valor;
            $this->currentPayment->pay_value_fees        = $gatewayReturn->pagamento_taxa;
            $this->currentPayment->pay_value_liquid      = $gatewayReturn->pagamento_liquido;
            $this->currentPayment->paid_label            = $gatewayReturn->msg;
            $this->currentPayment->save();

            // SE SLIP - AVANCA SILP
            if($this->currentPayment->order_slip_id ?? false)
            {
                $this->slip->update([
                    "status"               => $status,
                    "installment_pay_type" => $gatewayReturn->pagamento_forma_slug,
                    "paid_datetime"        => $gatewayReturn->datahora,
                    "paid_value"           => $gatewayReturn->pagamento_valor,
                    "paid_label"           => $gatewayReturn->msg,
                ]);

                // SE PROXIMO - ABRE
                if($slipPaymentNext = $this->slip->paymentNext ?? false)
                {
                    $this->slip->paymentNext->update([
                        "status" => 'aguardando_pagamento',
                    ]);
                }
            }

            //
            if($this->consolidaPagamentos())
            {
                // ALTERA STATUS ORDER

                // ALTERA STATUS TICKETS

                // ENVIA NOTIFICAÇÃO WAPP

                // ENVIA NOTIFICAÇÃO EMAIL


                DD('TUDO PAGO');

            }


            // dd(
            //     $this->currentPayment->toArray(),
            //     $this->slip->toArray(),
            //     $this->order->toArray(),
            //     $this->slip->paymentNext->toArray(),
            //     $gatewayReturn,
            // );


            // NOTIFICAÇÃO
            $this->dialog()->show([
                'title'       => __($status),
                'description' => $gatewayReturn->msg ?? null,
                'icon'        => 'success'
            ]);

            // ALERT SUCESSO
            $this->conclusao_success = true;
            session()->flash('success',$gatewayReturn->msg ?? null);
            session()->flash('conclusao_success',$gatewayReturn->msg ?? null);
            session()->flash('conclusao_success_sub',$gatewayReturn->msg_sub ?? null);

            if($slipPaymentNext ?? false)
            {
                return redirect()->route('pagamento',[
                    'targetType'             => $this->currentPayment->app_ref,
                    'localizador'            => $this->order->order_control,
                    'slipInstallmentControl' => $slipPaymentNext->slip_installment_control
                ]);
            }
            else
            {
                return redirect()->route('pagamento',[
                    'targetType'             => $this->currentPayment->app_ref,
                    'localizador'            => $this->order->order_control,
                ]);
            }

        }

        $this->getSlip($this->slip->id);
    }

    private function consolidaPagamentos()
    {
        $orderPaymentValue = $this->order->order_amount_pay ?? 0;
        $orderPaymentFull  = 0;

        // VERIFICA SE SLIP
        if($slip = $this->order->paymentsSlip ?? false)
        {
            // PRECORRE PARCELAS

            // PERCORRE OS PAGAMENTOS DAS PARECELAS

        }
        else
        {
            // PERCORRE OS PAGAMENTOS DA ORDER

        }

        return ($orderPaymentFull >= $orderPaymentValue);
    }

    public $pix_cpf='11122233344';
    private function processarPix()
    {
        $data = $this->validate([
            'pix_cpf' => ['required','cpf'],
        ]);

        return $data;
    }
}

