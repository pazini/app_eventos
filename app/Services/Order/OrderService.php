<?php

namespace App\Services\Order;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPaymentSlip;
use Exception;
use GuzzleHttp\Client;

class OrderService
{
    protected $customer;
    public $order;

    public function __construct()
    {

    }

    public $orderSlip;
    public $orderSlipAction;
    function gerarCarne($orderId,$orderType,$parcelas=[],$slipType='pix')
    {
        switch ($orderType) {
            case 'event':
            case 'app_event':
                $this->order = AppEventOrder::find($orderId);
                break;

            default:
                return false;
        }

        $this->orderSlipAction = false;

        if($this->order ?? false)
        {
            // SE JA EXISTE CARNE
            if(($this->order->slip_id ?? false) && ($this->orderSlip = AppPaymentSlip::where('slip_id',$this->order->slip_id)->orderBy('slip_installment')->get()))
            {
                if($this->orderSlip->count() ?? false)
                {
                    $this->orderSlipAction = 'get';
                }
            }

            // SE NAO PEGOU CARNE EXISTENTE
            if(!$this->orderSlipAction ?? false)
            {
                $this->orderSlipAction = 'create';

                $slip_installment_control = now()->format('YmdHis');

                // CRIA CARNE >> PERCORRE PARCELAS
                foreach ($parcelas as $parcelaKey => $pagamento_parcela_values)
                {
                    $slipCreate = [
                        'order_id'                        => $this->order->id,
                        'slip_id'                         => $slip->slip_id ?? null,
                        'slip_installment_id_previous'    => $slip->id ?? null,
                        'slip_installment'                => $pagamento_parcela_values['parcela'] ?? ($parcelaKey + 1),
                        'slip_installment_control'        => $slip_installment_control + ($parcelaKey + 1),
                        'slip_installment_available'      => $pagamento_parcela_values['parcela_qtd'] ?? count($parcelas),
                        'installment_description'         => $pagamento_parcela_values['label'] ?? null,
                        'installment_date_due'            => $pagamento_parcela_values['date_due'] ?? null,
                        'installment_pay_type'            => $slipType,
                        'installment_value'               => $pagamento_parcela_values['parcela_valor'] ?? 0,
                        'installment_value_fees'          => $pagamento_parcela_values['parcela_valor_encargos'] ?? 0,
                        'installment_value_liquid'        => ($pagamento_parcela_values['parcela_valor'] ?? 0) - ($pagamento_parcela_values['parcela_valor_encargos'] ?? 0),
                        'installment_value_amortization'  => ($pagamento_parcela_values['parcela_valor'] ?? 0) - ($pagamento_parcela_values['parcela_valor_encargos'] ?? 0),
                        'installment_fee_percentage_used' => ($pagamento_parcela_values['taxa'] ?? 0),
                        'status'                          => 'agendado',
                        'paid_datetime'                   => null,
                        'paid_value'                      => null,
                        'paid_label'                      => null,
                        'payment_id'                      => null,
                    ];

                    //
                    $slip = AppPaymentSlip::create($slipCreate);

                    // SE PRIMEIRO
                    if($pagamento_parcela_values['parcela'] == 1)
                    {
                        // SALVA CARNE ID + STATUS
                        $slip->update([
                            'slip_id' => $slip->id,
                            'status'  => 'aguardando_pagamento',
                        ]);

                        // ATUALIZA ORDER
                        $this->order->update([
                            'slip_id' => $slip->id,
                            'slip_description' => ("Carnê " . $pagamento_parcela_values["parcela_qtd"] . "x de " . toMoney($pagamento_parcela_values["parcela_valor"],'R$ ') . ' iniciando em ' . $pagamento_parcela_values["vencimento"] . ' Controle: ' . $slip_installment_control),
                        ]);
                    }
                }

                //
                $this->orderSlip = AppPaymentSlip::where('slip_id',$this->order->slip_id)->orderBy('slip_installment')->get();
            }

            return $this->orderSlip;
        }

        return false;
    }
}
