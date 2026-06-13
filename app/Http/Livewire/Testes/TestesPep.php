<?php

namespace App\Http\Livewire\Testes;

use App\Models\ModEvent\Event;
use App\Services\PagarmeServiceApiV5;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use stdClass;

class TestesPep extends Component
{
    protected $gateway;
    public    $slug;
    public    $target;

    public function executeTesteSplit()
    {
        try {

            $token        = 'sk_powL92mdH1fdOWx1';
            $pass         = null;
            $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => 'pagarme-v5' , 'callbackType' => 'evento']);

            //
            $event_name       = "Evento de Teste";
            $event_name_short = "EVENTOTESTE";
            $order_control    = "abc-123";
            $order_id         = "order-id";

            //
            $gateway_id = "gateway-id";
            $payment_id = "payment-id";

            $document_num     = '01256522082';
            $card_number      = '5256310016494125';
            $card_holder_name = strtolower('eduarte santos');
            $card_exp_month   = '02';
            $card_exp_year    = '26';
            $card_cvv         = '081';
            //
            $card_billing_address_zip_code = '24120196';
            $card_billing_address_line_1   = 'ALAMEDA SÃO BOAVENTURA 300';
            $card_billing_address_city     = 'NITEROI';
            $card_billing_address_state    = 'RJ'; // UF
            $card_billing_address_country  = 'BR';
            //
            $card_installments         = 1;
            $card_statement_descriptor = $event_name_short;

            // SET ITEMS
            $items = [];
            //
            foreach (range(1,1) as $i) {
                $items[] = [
                    "code"        => 'item-code-' . $i,
                    "amount"      => (int) $i . '00',
                    "description" => 'item-description-' . $i,
                    "quantity"    => 1,
                ];
            }

            $sandBox        = true;
            $split_pay      = true;
            $formapagamento = 'credit_card';
            $formapagamento = 'pix';
            $formapagamento = 'boleto';

            // SET PAYMENTS
            switch ($formapagamento ?? []) {
                case 'credit_card':
                case 'card_credit':
                    $payment = [
                        "payment_method" => "credit_card",
                        "credit_card" => [
                            "card" => [
                                "holder_document" => $document_num,
                                "number"          => $card_number,
                                "holder_name"     => $card_holder_name,
                                "exp_month"       => $card_exp_month,
                                "exp_year"        => $card_exp_year,
                                "cvv"             => $card_cvv,
                                "billing_address" => [
                                    "zip_code" => $card_billing_address_zip_code,
                                    "line_1"   => $card_billing_address_line_1,
                                    "city"     => $card_billing_address_city,
                                    "state"    => $card_billing_address_state,
                                    "country"  => $card_billing_address_country,
                                ],
                            ],
                            "operation_type"       => "auth_and_capture",
                            "installments"         => (int) $card_installments,
                            "statement_descriptor" => substr(str_replace(" ","",($card_statement_descriptor ?? 'PROEVENTPAY')), 0, 13),
                        ],
                    ];
                    break;

                case 'boleto':
                    $payment = [
                        "payment_method" => "boleto",
                        "boleto" => [
                            "instructions"    => ($event_name ?? 'ATENÇÃO') . " - A compra será efetivada após a confirmação do pagamento",
                            "nosso_numero"    => now()->timestamp,
                            "document_number" => substr(str_replace(" ","",($event_name_short ?? $event_name)), 0, 16),
                        ],
                    ];
                    break;

                case 'pix':
                    $payment = [
                        "payment_method" => "pix",
                        "pix" => [
                            //"expires_in" => "259200", // SEGUNDOS = 72H
                            "expires_at" => "2022-10-30T23:59:00", // SEGUNDOS = 72H
                            "additional_information" => [
                                [
                                    "name" => "Compra",
                                    "value" => "Teste de PIX!"
                                ],
                                [
                                    "name" => "Compra 2",
                                    "value" => "Teste 2!"
                                ]
                            ],
                        ]
                    ];
                    break;

                default:
                    // RETURN ERROR SEM MODULO DE PAGAMENTO DEFINIDO
                    throw new Exception('modulo_pagamento_invalido');
                    return;
            }

            // SE SPLIT DE PAGAMENTO
            if ($split_pay ?? false)
            {
                //
                $split_recipient_id_pep    = 'rp_GYqyp1bh71sGDmk8'; // PEP
                $split_recipient_id_client = 'rp_Nd0MQ0AU8UROn92J'; // CLIENTE

                // SET SPLIT
                $split_mode          = "percentage";
                $split_amount_pep    = (int) 2; // PARTE PEP
                $split_amount_client = (int) 100 - $split_amount_pep; // PARTE CLIENTE
                //
                $charge_processing_fee = false;
                $charge_remainder_fee = false;
                $liable = false;
                //
                $charge_processing_fee_client = true;
                $charge_remainder_fee_client = true;
                $liable_client = true;
                //
                $payment['split'] = [
                    [
                        "recipient_id" => $split_recipient_id_pep, // PEP
                        "amount"       => $split_amount_pep,
                        "type"         => $split_mode,
                        "options" => [
                            "charge_processing_fee" => $charge_processing_fee,
                            "charge_remainder_fee"  => $charge_remainder_fee,
                            "liable"                => $liable,
                        ],
                    ],
                    [
                        "recipient_id" => $split_recipient_id_client, // CLIENTE
                        "amount"       => $split_amount_client,
                        "type"         => $split_mode,
                        "options" => [
                            "charge_processing_fee" => $charge_processing_fee_client,
                            "charge_remainder_fee"  => $charge_remainder_fee_client,
                            "liable"                => $liable_client,
                        ],
                    ],
                ];
            }

            $customer_code          = $order_control;
            $customer_name          = "Maria Teste";
            $customer_email         = "maria@gmail.com";
            $customer_birthdate     = "2000-02-06";
            $customer_document      = $document_num;

            $customer_mobile_phone_country_code = "55";
            $customer_mobile_phone_area_code    = "21";
            $customer_mobile_phone_number       = "988887777";

            // POST
            $postBody = [
                "customer" => [
                    "type"      => "individual",
                    "code"      => $customer_code,
                    "name"      => $customer_name,
                    "email"     => $customer_email,
                    "birthdate" => $customer_birthdate,
                    "document"  => $customer_document,
                    "phones"    => [
                        "mobile_phone" => [
                                "country_code" => $customer_mobile_phone_country_code,
                                "area_code"    => $customer_mobile_phone_area_code,
                                "number"       => $customer_mobile_phone_number,
                            ],
                    ],
                ],
                "code"         => $order_control,
                "items"        => $items,
                "payments"     => [
                    $payment
                ],
                "postback_url" => $postback_url,
                "metadata"     => [
                    "gateway_id" => $gateway_id,
                    "order_id"   => $order_id,
                    "payment_id" => $payment_id,
                ],
            ];

            // dd($postBody);

            // SUBMETE
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($token.':'.$pass),
                'Accept'        => 'application/json',
                'Content-type'  => 'application/json',
            ])->post('https://api.pagar.me/core/v5/orders', $postBody);

            // ERROS POR STATUS
            if(in_array($response->status(), [401]))
            {
                throw new Exception($response->json('message'));
            }

            // ERROS EM LISTA
            if($response->json('errors'))
            {
                throw new Exception('errors_list');
            }

            dd(
                $response->json('status'),
                $response->json(),
            );

        }
        catch (\Throwable $th)
        {
            $return = new stdClass();
            $return->error   = true;
            $return->msg     = $th->getMessage();
            $return->msg_sub = null;

            //
            if($th->getMessage() == 'modulo_pagamento_invalido')
            {
                $return->msg     = "ERRO AO PROCESSAR PAGAMENTO";
                $return->msg_sub = "MODULO DE PAGAMENTO '{$validatedData['comprador_formapagamento']}' NÃO É VALIDO";
            }

            //
            if($th->getMessage() == 'errors_list')
            {
                $return->msg     = 'ERRO AO PROCESSAR PAGAMENTO';
                $return->msg_sub = 'REVISE OS DADOS INFORMADOS';

                foreach ($response->json('errors') as $error) {
                    foreach ($error as $mensage) {
                        $return->error_messages[] = __($mensage);
                    }
                }
            }

            //
            if($th->getMessage() == 'response_failed')
            {
                $return->msg = 'ERRO AO INICIAR PAGAMENTO';

                // SE ERROR MENSAGES
                if(isset($response['message']) ?? false)
                {
                    $return->error_messages[] = __($response['message']);
                }

                // SE ERROR MENSAGES
                if(isset($response['error_messages']) && count($response['error_messages']) ?? false)
                {
                    $return->error_messages = [];

                    foreach($response['error_messages'] as $error_messages)
                    {
                        $implode = strtoupper(implode(" ",$error_messages));

                        $return->error_messages[] = __($implode);
                    }
                }
            }

            dd(
                $th,
                $response->status(),
                $response->json(),
                $return,
                $postBody,
            );

            return $return;
        }
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($slug=false)
    {
        $this->slug = $slug;

        // $this->target = Event::with(['ticketsTypes','gatewayPay'])->where('event_slug',$slug)->first();
        $this->target = Event::with(['gatewayPay'])->where('event_slug',$slug)->first();
    }

    public function render()
    {

        // session()->flash('error','aaa');

        return view('livewire.testes.testes-pep')->layout('layouts.app-pep-flat');
    }
}

