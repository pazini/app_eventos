<?php

namespace App\Services\Payments;

use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use App\Models\AppPayment\AppPaymentSlip;
use App\Services\safe2pay\Safe2PayService;
use Exception;
use GuzzleHttp\Client;
use stdClass;

class PaymentService
{
    public $slipId;

    public function __construct()
    {

    }

    public $slip;
    public $order;
    public $orderItens;
    public $event;
    public $gatewayPay;
    public $payment;
    public function getSlip()
    {
        $this->slip = false;
        $this->payment = false;

        if($this->slip = AppPaymentSlip::with(['payment','order','order.event','order.itens','order.event.gatewayPay','order.event.gatewayPay.appGateway','payments'])->find($this->slipId))
        {
            return $this->slip;
        }

        return false;
    }

    function checkValidatePix()
    {
        // SE NAO ESTA PAGO
        if(!in_array($this->payment->status,listOrderStatusPaid()))
        {
            // SE VENCEU
            // if(($this->payment->pay_pix_expires_at ?? false) && (dataCarbon($this->payment->pay_pix_expires_at)->format('YmdHis') < now()->format('YmdHis')))
            if(($this->payment->pay_pix_expires_at ?? false) && (dataCarbon($this->payment->pay_pix_expires_at)->format('YmdHis') < now()->format('YmdHis')))
            {
                // UPDATE SLIP PAYMENT
                $this->payment->update([
                    'status' => 'pix_expired',
                ]);

                // UPDATE SLIP
                $this->slip->update([
                    'payment_id' => null,
                    'status' => 'aguardando_pagamento',
                ]);

                return false;
            }

            return $this->payment;
        }
    }

    public $return;
    function processaSafe2pay($currentPayment,$validatedData,$sandbox=false)
    {
        $order      = $this->getOrder();
        $gatewayPay = $this->getGatewayPay();
        $event      = $this->getEvent();

        if(in_array($currentPayment->pay_type,['pix','slip_pix']))
        {
            $sandbox      = false; // FORÇA SER LIVE
            $token        = $gatewayPay->token_live; // PIX SOMENTE PRODUÇÃO
            $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $gatewayPay->appGateway->gateway_slug , 'callbackType' => $currentPayment->app_ref, 'orderId' => $this->order->id, 'paymentId' => $currentPayment->id]);
        }
        else
        {
            $token        = ($sandbox ?? false) ? $gatewayPay->token_test : $gatewayPay->token_live;
            $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $gatewayPay->appGateway->gateway_slug , 'callbackType' => $currentPayment->app_ref, 'orderId' => $this->order->id, 'paymentId' => $currentPayment->id]);
        }

        try
        {
            //
            $this->return = new stdClass;
            $this->return->error            = false;
            $this->return->msg              = __('payment_started');
            $this->return->msg_sub          = __FUNCTION__;
            $this->return->gateway_slug     = $gatewayPay->pay_gateway_slug;
            $this->return->gateway_sandbox  = $sandbox;
            $this->return->status           = 'payment_started';
            $this->return->pay_postback_url = $postback_url;

            // MONTA VENDOR
            if($event->organizer->organizer_name_full ?? false)
            {
                $vendor = mb_strtoupper(toSlug($event->organizer->organizer_name_full,'-'));
            }
            else
            {
                $vendor = mb_strtoupper(toSlug($event->organizer->customer->name_corporate . ' ' . $event->organizer->organizer_name,'-'));
            }

            // INICIA SERVICE
            $service = new Safe2PayService($token,$sandbox);

            // SET APLICATION
            $service->Application = trim(mb_strtoupper(toSlug($gatewayPay->pay_gateway_slug,'-') . '.' . $currentPayment->app_ref . '.' . ($sandbox ? "SANDBOX" : "LIVE")));
            $service->Vendor      = trim($vendor ?? 'PROEVENTPAY');
            $service->CallbackUrl = $postback_url;
            $service->Reference   = mb_strtoupper($order->order_control);
            $service->setAplication();

            // SET META
            $service->order_id              = $order->id;
            $service->localizador           = $order->order_control;
            $service->payment_id            = $currentPayment->id;
            $service->gateway_id            = $gatewayPay->id;
            $service->app_ref               = $currentPayment->app_ref;
            $service->order_amount          = $currentPayment->pay_installment_value ?? 0;
            $service->order_amount_discount = $currentPayment->code_promo_discount_amount ?? 0;
            $service->order_amount_pay      = $currentPayment->pay_installment_value ?? 0;
            $service->setMeta();

            // SET CUSTOMER
            $service->Name     = mb_strtoupper($order->buyer_name);
            $service->Identity = $order->buyer_doc_num;
            $service->Phone    = $order->buyer_contact_ddd.$order->buyer_contact_num;
            $service->Email    = mb_strtolower($order->buyer_email);
            $service->setCustomer();

            // SE FORMA PAGAMENTO
            if(in_array($currentPayment->pay_type,['pix','slip_pix','boleto']))
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
                    UnitPrice:$currentPayment->value_liquid,
                    Quantity:1,
                    Description:'COMPRA SEM ITENS - LOCALIZADOR ' . $order->order_control
                );
            }

            // SE PAGAMENTO FOR CARNÊ
            if($currentPayment->order_slip_id ?? false)
            {
                // LIMPA PRODUTOS
                $service->setProducts(clearFull:true);

                // APPEND PRODUCTS
                $service->appendProducts(
                    Code:'SLIP.'.$this->slip->slip_installment_control,
                    UnitPrice:$currentPayment->value_liquid,
                    Quantity:1,
                    Description:'CARNÊ // ' . $this->slip->installment_description
                );

                // ADICIONA META
                $service->pay_slip             = true;
                $service->pay_slip_id          = $this->slip->id;
                $service->pay_slip_description = $this->slip->installment_description;
                $service->setMeta();
            }

            // TODO: SE EXISTIR DESCONTO
            if(false)
            {
                // $order->code_promo_discount_amount
                // $order->codePromo->???

                // APPEND DISCOUNT PRODUCTS
                // $service->appendProducts(
                //     Code:'cupom-123',
                //     Discount:true,
                //     UnitPrice:500,
                //     Quantity:1,
                //     Description:'DESCONTO CUPOM',
                // );
            }

            // SE EXISTIR REPASSE DE TAXA
            if($currentPayment->value_fees ?? false)
            {
                // ADICIONA ENCARGOS
                $service->appendProducts(
                    Code:999,
                    UnitPrice:$currentPayment->value_fees,
                    Quantity:1,
                    Description:'ENCARGOS',
                );

                // COBRAR JUROS
                $service->IsApplyInterest = true;
            }

            // SET PRODUCTS PARA PAGAMENTO
            $service->setProducts();

            // APPEND PRODUCTS EM META
            $service->setProductsToMeta();

            // SET PAYMENTS
            switch ($currentPayment->pay_type ?? false)
            {
                case 'pix':
                    $payload = $service->setPaymentPix($validatedData['pix_cpf']);

                    // SALVA PAYLOAD
                    $currentPayment->pay_json_request   = json_encode($payload);
                    $currentPayment->pay_pix_expires_at = $service->ExpirationDateTime;
                    $currentPayment->save();
                    break;
                case 'slip_pix':
                    // SAFE2PAY - ID ACCOUNT - PARA O CASO DE SLIP, REPASSE DE ENCARGOS PARA SAFE2PAY
                    if($gatewayPay->appGateway->pay_slip_pix_split_receiver_id ?? false)
                    {
                        $service->ReceiverId   = $gatewayPay->appGateway->pay_slip_pix_split_receiver_id ?? false;   // SAFE2PAY - ID ACCOUNT
                        $service->ReceiverName = $gatewayPay->appGateway->pay_slip_pix_split_receiver_name ?? false; // SAFE2PAY - NAME SPLIT
                        $payload = $service->setPaymentPix($validatedData['pix_cpf'],split:true,splitValor:toMoneyDot($currentPayment->value_fees));
                    }
                    else
                    {
                        $payload = $service->setPaymentPix($validatedData['pix_cpf']);
                    }

                    // SALVA PAYLOAD
                    $currentPayment->pay_json_request   = json_encode($payload);
                    $currentPayment->pay_pix_expires_at = $service->ExpirationDateTime;
                    $currentPayment->save();
                    break;
                case 'card_credit':
                case 'credit_card':
                    $service->Holder = $validatedData['card_credit_nome'];
                    $service->CardNumber = $validatedData['card_credit_num'];
                    $service->ExpirationDateMM = $validatedData['card_credit_validade_mm'];
                    $service->ExpirationDateAAAA = $validatedData['card_credit_validade_aaaa'];
                    $service->SecurityCode = $validatedData['card_credit_cvv'];
                    $service->InstallmentQuantity = $validatedData['pay_installments_number'] ?? 1;
                    $payload = $service->setPaymentCredit($validatedData['card_credit_cpf']);

                    // SALVA PAYLOAD
                    $currentPayment->paid_description = $payload['PaymentObject']['CardNumber'] ? ($payload['PaymentObject']['CardNumber']) : $currentPayment->paid_description;
                    $currentPayment->pay_json_request = json_encode($payload);
                    $currentPayment->save();
                    break;

                case 'boleto':
                    dd($validatedData);
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

    function checkAllPayments($orderId,$orderSlipId=false)
    {
        return;
    }

    function checkPayment($paymentId)
    {
        $payment = AppPayment::with(['gateway'])->find($paymentId);

        //
        if(in_array($payment->pay_type,['pix','slip_pix']))
        {
            $sandbox = false; // FORÇA SER LIVE
            $token   = $payment->gateway->token_live; // PIX SOMENTE PRODUÇÃO
        }
        else
        {
            $sandbox = ($payment->pay_integration_type == "sandbox");
            $token   = ($sandbox ?? false) ? $payment->gateway->token_test : $payment->gateway->token_live;
        }

        // INICIA SERVICE
        $service = new Safe2PayService($token,$sandbox);

        // FORÇA RETORNO OK TESTE
        $payment->pay_nsu = 104889285;

        //
        return $service->consultaTransacao($payment->pay_nsu,true);
    }

    function getPayment()
    {
        $this->payment = $this->slip->payment;
        return $this->payment;
    }

    function getOrder()
    {
        $this->order = $this->slip->order;
        return $this->order;
    }

    function getOrderItens()
    {
        $this->orderItens = $this->slip->order->itens;
        return $this->orderItens;
    }

    function getEvent()
    {
        $this->event = $this->slip->order->event ?? false;
        return $this->event;
    }

    function getGatewayPay()
    {
        $this->gatewayPay = $this->slip->order->event->gatewayPay ?? false;
        return $this->gatewayPay;
    }

    function checkOrderPaid()
    {
        $this->getSlip();

        $order = $this->getOrder();

        if(in_array($order->status,listOrderStatusPaid()))
        {
            return true;
        }

        return false;
    }

    function installmentsCalulcate($payType=false,$installmentNum=false)
    {
        return [];

        dd($payType);
    }
}
