<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PagarmeService
{
    private $pagarme;

    public function __construct($var='PAGAR.ME')
    {
        // $this->pagarme = new \PagarMe\Client(env('PAGARME_API_KEY_TEST'));
        // $this->pagarme = new \PagarMe\Client(env('PAGARME_API_KEY_LIVE'));
    }


    // public function gatewayPagarMeV5($validatedData,$gateway,$sandBox=false)
    // {
    //     try {
    //         // SE AMBIENTE TESTE
    //         if($sandBox ?? false)
    //         {
    //             // SE GATEWAY DIRETO DO CLIENTE
    //             if($gateway['pay_gateway_direct_client'] ?? false)
    //                 $token = $gateway['token_test_secret']; // V4 USA PK DO CLIENTE
    //             else
    //                 $token = $gateway['app_gateway']['token_test_secret']; // V4 USA PK

    //             $pass = null;
    //             //
    //             $recipient_id        = $gateway['app_gateway']['split_test_recipient_id']; // NOS
    //             $recipient_id_client = $gateway['split_test_recipient_id_client']; // CLIENTE
    //             $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $this->pagamentoGatewaySlug , 'callbackType' => 'app_event']);
    //         }
    //         else
    //         {
    //             // SE GATEWAY DIRETO DO CLIENTE
    //             if($gateway['pay_gateway_direct_client'] ?? false)
    //                 $token = $gateway['token_live_secret']; // V4 USA PK DO CLIENTE
    //             else
    //                 $token = $gateway['app_gateway']['token_live_secret']; // V4 USA PK

    //             $pass = null;
    //             //
    //             $recipient_id        = $gateway['app_gateway']['split_live_recipient_id']; // NOS
    //             $recipient_id_client = $gateway['split_live_recipient_id_client']; // CLIENTE
    //             $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $this->pagamentoGatewaySlug , 'callbackType' => 'app_event']);
    //         }

    //         // SET ITEMS
    //         $items = [];
    //         //
    //         foreach ($validatedData['order']['tickets'] ?? [] as $ticket) {
    //             $items[] = [
    //                 "code"        => $ticket['event_ticket_price'],
    //                 "amount"      => (int) $ticket['event_ticket_price'],
    //                 "description" => $ticket['event_ticket_price'],
    //                 "quantity"    => 1,
    //             ];
    //         }

    //         // SET PAYMENTS
    //         switch ($validatedData['comprador_formapagamento'] ?? []) {
    //             case 'credit_card':
    //             case 'card_credit':

    //                 $cardcreditEndereco = buscarCep($validatedData['card_credit_cep'] ?? null);
    //                 //
    //                 if($cardcreditEndereco->error)
    //                     throw new Exception($cardcreditEndereco->msg);

    //                 $gatewayId  = $validatedData['event']['pay_gateway_id'];
    //                 $payment    = [
    //                     "payment_method" => "credit_card",
    //                     "credit_card" => [
    //                         "card" => [
    //                             "holder_document" => $validatedData['comprador_cpf'],
    //                             "number"          => $validatedData['card_credit_num'],
    //                             "holder_name"     => strtolower($validatedData['card_credit_nome']),
    //                             "exp_month"       => $validatedData['card_credit_validade_mm'],
    //                             "exp_year"        => $validatedData['card_credit_validade_aaaa'],
    //                             "cvv"             => $validatedData['card_credit_cvv'],
    //                             "billing_address" => [
    //                                 "zip_code"    => $cardcreditEndereco->cep,
    //                                 "line_1"      => $cardcreditEndereco->endereco . ' ' . $validatedData['card_credit_cep_num'],
    //                                 "city"        => $cardcreditEndereco->cidade,
    //                                 "state"       => $cardcreditEndereco->estado,
    //                                 "country"     => "BR"
    //                             ],
    //                         ],
    //                         "operation_type"       => "auth_and_capture",
    //                         "installments"         => (int) $validatedData['card_credit_parcelado'],
    //                         "statement_descriptor" => substr(str_replace(" ","",($validatedData['event']['event_name_short'] ?? $validatedData['event']['event_name'])), 0, 13),
    //                     ],
    //                 ];
    //                 break;

    //             case 'boleto':
    //                 $gatewayId  = $validatedData['event']['pay_boleto_gateway_id'];
    //                 $payment    = [
    //                     "payment_method" => "boleto",
    //                     "boleto" => [
    //                         "instructions"    => ($validatedData['event']['event_name'] ?? 'ATENÇÃO') . " - A compra será efetivada após a confirmação do pagamento",
    //                         "nosso_numero"    => now()->timestamp,
    //                         "document_number" => substr(str_replace(" ","",($validatedData['event']['event_name_short'] ?? $validatedData['event']['event_name'])), 0, 16),
    //                     ],
    //                 ];
    //                 break;

    //             default:

    //                 // RETURN ERROR SEM MODULO DE PAGAMENTO DEFINIDO
    //                 throw new Exception('modulo_pagamento_invalido');
    //                 return;
    //         }

    //         // SE SPLIT DE PAGAMENTO
    //         if ($gateway['split_pay'] ?? false) {

    //             // dd(
    //             //     $gateway,
    //             // );

    //             // SET SPLIT
    //             $split               = $gateway['split_pay'];
    //             $split_mode          = $gateway['split_mode'] ?? "percentage";
    //             $split_amount        = (int) $gateway['split_customer_amount '] ?? 0; // NOSSA COMISSAO
    //             $split_amount_client = (int) 100 - $split_amount; // COMISSAO CLIENTE
    //             //
    //             $charge_processing_fee = false;
    //             $charge_remainder_fee = false;
    //             $liable = false;
    //             //
    //             $charge_processing_fee_client = true;
    //             $charge_remainder_fee_client = true;
    //             $liable_client = true;
    //             //
    //             $payment['split'] = [
    //                 [
    //                     "recipient_id" => $recipient_id, // REDSERVICE
    //                     "amount"       => $split_amount,
    //                     "type"         => $split_mode, // "percentage",
    //                     "options" => [
    //                         "charge_processing_fee" => $charge_processing_fee,
    //                         "charge_remainder_fee"  => $charge_remainder_fee,
    //                         "liable"                => $liable,
    //                     ],
    //                 ],
    //                 [
    //                     "recipient_id" => $recipient_id_client, // CLIENTE FINAL
    //                     "amount"       => $split_amount_client,
    //                     "type"         => $split_mode, // "percentage",
    //                     "options" => [
    //                         "charge_processing_fee" => $charge_processing_fee_client,
    //                         "charge_remainder_fee"  => $charge_remainder_fee_client,
    //                         "liable"                => $liable_client,
    //                     ],
    //                 ],
    //             ];
    //         }

    //         // APPEND PAGAMENTO
    //         $payments[] = $payment;

    //         $postBody = [
    //             "customer" => [
    //                 "type"      => "individual",
    //                 "code"      => $validatedData['order']['order_control'],
    //                 "name"      => strtolower($validatedData['comprador_nome'] . ' ' . $validatedData['comprador_nome']),
    //                 "email"     => strtolower($validatedData['comprador_email']),
    //                 "birthdate" => $validatedData['comprador_nascimento'],
    //                 "document"  => $validatedData['comprador_cpf'],
    //                 "phones" => [
    //                 "mobile_phone" => [
    //                         "country_code" => "55",
    //                         "area_code"    => $validatedData['comprador_celular_ddd'],
    //                         "number"       => $validatedData['comprador_celular_num'],
    //                     ],
    //                 ],
    //             ],
    //             "code"         => $validatedData['order']['order_control'],
    //             "items"        => $items,
    //             "payments"     => $payments,
    //             "postback_url" => $postback_url,
    //             "metadata"     => [
    //                 "gateway_id" => $gatewayId,
    //                 "order_id"   => $validatedData['order']['id'],
    //                 "payment_id" => $validatedData['order']['payment']['id'],
    //             ],
    //         ];

    //         // SUBMETE
    //         $response = Http::withHeaders([
    //             'Authorization' => 'Basic ' . base64_encode($token.':'.$pass),
    //             'Accept'        => 'application/json',
    //             'Content-type'  => 'application/json',
    //         ])->post('https://api.pagar.me/core/v5/orders', $postBody);

    //         // ERROS POR STATUS
    //         if(in_array($response->status(), [401]))
    //         {
    //             throw new Exception($response->json('message'));
    //         }

    //         // ERROS EM LISTA
    //         if($response->json('errors'))
    //         {
    //             throw new Exception('errors_list');
    //         }

    //         // SANITIZA POST BODY - CARD CREDIT
    //         if($validatedData['comprador_formapagamento'] == 'card_credit' && isset($postBody['payments'][0]['credit_card']['card']))
    //         {
    //             $postBody['payments'][0]['credit_card']['card'] = $response->json('charges.0.last_transaction.card');
    //         }

    //         // SET STATUS
    //         $responseStatus  = $response->json('status');
    //         $responsePayType = $response->json('charges.0.last_transaction.transaction_type');
    //         //
    //         if(in_array(strtoupper($responseStatus), ['PENDING']) && in_array(strtoupper($responsePayType), ['BOLETO']))
    //             $responseStatus = 'pending_boleto';

    //         // SET REFUSED
    //         $payRefused = (in_array(strtoupper($responseStatus), ['PAID','PENDING','PENDING_BOLETO'])) ? false : true;
    //         //
    //         if(is_array($response->json('charges.0.last_transaction.antifraud_response')))
    //         {
    //             if(empty($response->json('charges.0.last_transaction.antifraud_response')))
    //             {
    //                 $antifraud_response = null;
    //             }
    //             else
    //             {
    //                 $antifraud_response = json_encode($response->json('charges.0.last_transaction.antifraud_response'));
    //             }
    //         }
    //         else
    //         {
    //             $antifraud_response = $response->json('charges.0.last_transaction.antifraud_response');
    //         }

    //         // MONTA OBJ RETORNO
    //         $return = new stdClass();
    //         $return->error                      = false;
    //         $return->msg                        = __(strtoupper('PAY_' . $responseStatus));
    //         $return->msg_sub                    = $payRefused ? ($antifraud_response ?? null) : $response->json('charges.0.last_transaction.acquirer_message');
    //         $return->gateway_slug               = $this->pagamentoGatewaySlug;
    //         $return->status                     = $responseStatus;
    //         $return->status_old                 = null;
    //         $return->value_paid                 = (int) $response->json('amount');
    //         //
    //         $return->pay_refused                = $payRefused;
    //         $return->pay_refused_reason         = $antifraud_response ?? null;
    //         //
    //         $return->pay_nsu                    = $response->json('charges.0.last_transaction.acquirer_nsu') ?? $response->json('charges.0.id');
    //         $return->pay_type                   = strtoupper($responsePayType);
    //         $return->pay_datetime               = Carbon::create($response->json('charges.0.last_transaction.updated_at'))->subHours(3)->format('Y-m-d H:i:s');
    //         $return->pay_installments_number    = $response->json('charges.0.last_transaction.installments');
    //         $return->pay_installment_value      = null;
    //         $return->pay_card_first             = $response->json('charges.0.last_transaction.card.first_six_digits');
    //         $return->pay_card_last              = $response->json('charges.0.last_transaction.card.last_four_digits');
    //         $return->pay_card_name              = $response->json('charges.0.last_transaction.card.holder_name');
    //         $return->pay_card_brand             = $response->json('charges.0.last_transaction.card.brand');
    //         $return->pay_boleto_barcode         = $response->json('charges.0.last_transaction.line');
    //         $return->pay_boleto_url             = $response->json('charges.0.last_transaction.url');
    //         $return->pay_boleto_expiration_date = Carbon::create($response->json('charges.0.last_transaction.due_at'))->format('Y-m-d H:i:s');
    //         $return->pay_postback_url           = $postback_url;
    //         $return->pay_json_request           = json_encode($postBody);
    //         $return->pay_json_response          = json_encode($response->json());
    //         //
    //         $return->pay_gateway_direct_client   = $gateway['pay_gateway_direct_client'] ?? false;
    //         $return->split                       = $response->json('charges.0.last_transaction.split') ? true : false;
    //         $return->split_type                  = $response->json('charges.0.last_transaction.split.0.type');
    //         $return->split_amount                = $response->json('charges.0.last_transaction.split.0.amount');
    //         $return->split_amount_client         = $response->json('charges.0.last_transaction.split.1.amount');
    //         $return->split_recipient_id          = $response->json('charges.0.last_transaction.split.0.recipient.id');
    //         $return->split_recipient_id_client   = $response->json('charges.0.last_transaction.split.1.recipient.id');
    //         $return->split_charge_processing_fee = $response->json('charges.0.last_transaction.split.1.options.charge_processing_fee');
    //         $return->split_charge_remainder_fee  = $response->json('charges.0.last_transaction.split.1.options.charge_remainder_fee');
    //         $return->split_liable                = $response->json('charges.0.last_transaction.split.1.options.liable');

    //         // dd(
    //         //     $response->json('charges.0.last_transaction.split'),
    //         //     $return,
    //         // );

    //         return $return;

    //     } catch (\Throwable $th) {

    //         $return = new stdClass();
    //         $return->error   = true;
    //         $return->msg     = $th->getMessage();
    //         $return->msg_sub = null;

    //         // dd(
    //         //     $gateway,
    //         //     $postBody,
    //         //     $th,
    //         //     $response->status(),
    //         //     $response->json(),
    //         // );

    //         //
    //         if($th->getMessage() == 'modulo_pagamento_invalido')
    //         {
    //             $return->msg     = "ERRO AO PROCESSAR PAGAMENTO";
    //             $return->msg_sub = "MODULO DE PAGAMENTO '{$validatedData['comprador_formapagamento']}' NÃO É VALIDO";
    //         }

    //         //
    //         if($th->getMessage() == 'errors_list')
    //         {
    //             // dd(
    //             //     $th,
    //             //     $response->json()
    //             // );

    //             $return->msg     = 'ERRO AO PROCESSAR PAGAMENTO';
    //             $return->msg_sub = 'REVISE OS DADOS INFORMADOS';

    //             foreach ($response->json('errors') as $error) {
    //                 foreach ($error as $mensage) {
    //                     $return->error_messages[] = __($mensage);
    //                 }
    //             }
    //         }

    //         //
    //         if($th->getMessage() == 'response_failed')
    //         {
    //             $return->msg = 'ERRO AO INICIAR PAGAMENTO';

    //             // SE ERROR MENSAGES
    //             if(isset($response['message']) ?? false)
    //             {
    //                 $return->error_messages[] = __($response['message']);
    //             }

    //             // SE ERROR MENSAGES
    //             if(isset($response['error_messages']) && count($response['error_messages']) ?? false)
    //             {
    //                 $return->error_messages = [];

    //                 foreach($response['error_messages'] as $error_messages)
    //                 {
    //                     $implode = strtoupper(implode(" ",$error_messages));

    //                     $return->error_messages[] = __($implode);

    //                     // dd(
    //                     //     ">{$implode}<",
    //                     //     __($implode),
    //                     //     $response['error_messages'],
    //                     // );

    //                 }
    //             }
    //         }

    //         return $return;
    //     }
    // }
}
