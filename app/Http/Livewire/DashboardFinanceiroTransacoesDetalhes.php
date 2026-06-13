<?php

namespace App\Http\Livewire;

use App\Jobs\AppPayment\NotificationAppPaymentPagamento;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppPayment\AppPayment;
use App\Models\ModEvent\Event;
use App\Models\ModEvent\EventBudget;
use App\Models\ModEvent\EventBudgetItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use stdClass;

class DashboardFinanceiroTransacoesDetalhes extends Component
{
    //
    public $target_ref;
    public $target_id;
    public $view_type;
    //
    public $target_list=[
        ['id' => '26b7af5f-29d2-430f-9cc5-b87ad532afda', 'name' => 'NEXT SUMMER CAMP 2022', 'description' => 'Do dia 9 ao dia 11 de Dezembro'],
    ];
    public $target_list_ref=['app_event' => 'Evento'];
    //
    public $orderControl;
    public $action;
    public $action_list=[
        'gestao-orcamentaria' => 'Gestão Orçamentária',
        'transacoes'          => 'Transações',
    ];
    //
    public $target;
    public $pedidos;
    public $order;

    //
    public $divBtnActions = true;
    public $divAdicionarPagamento;

    //
    public $forma_pagamento;
    public $pagamento_valor = 600;

    //
    public $line_aditional_top_titulo    = 'ATENÇÃO';
    public $line_aditional_top_texto     = 'Tivemos um problema com o boleto gerado antes. Pedimos desculpas e que o desconsidere. Use esse aqui para efetuar o PAGAMENTO, e assim confirmar sua inscrição.';
    public $line_aditional_botton_titulo = 'Dúvidas, entre em contato nos telefones';
    public $line_aditional_botton_texto  = '(21) 97046-0648 ou (21) 97908-1000';

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

    public $alterar_add_item_div;
    public $alterar_item_id;

    // protected $messages = [
    //     '*.required' => 'Obrigatório',
    // ];

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

    public function verDetalhes($orderControl)
    {
        $this->action = 'transacoes-detalhes';
        $this->orderControl = $orderControl;
    }

    public function getTarget($target_ref, $target_id)
    {
        switch ($target_ref) {
            case 'evento':
            case 'app_event':
                $target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.tickets','ticketsTypes','tickets'])->find($target_id);
                break;

            default:
                $target = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.tickets','ticketsTypes','tickets'])->find($target_id);
                break;
        }

        //
        if(!$target)
        {
            $target = false;
            session()->flash('error','Parametros da URL estão incorretos');
        }

        return $target;
    }

    public function gatewayPagarMeV4($data, $gateway, $sandBox=false)
    {
        try {

            // SE AMBIENTE TESTE
            if($sandBox ?? false)
            {
                if($gateway['pay_gateway_direct_client'] ?? false)
                    $token = $gateway['token_test']; // V4 USA PK DO CLIENTE
                else
                    $token = $gateway['app_gateway']['token_test']; // V4 USA PK

                $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $gateway['pay_gateway_slug'] . '-test' , 'callbackType' => 'app_event', 'orderId' => $data['order']['id'], 'paymentId' => $data['order']['payment']['id']]);
            }
            else
            {
                if($gateway['pay_gateway_direct_client'] ?? false)
                    $token = $gateway['token_live']; // V4 USA PK DO CLIENTE
                else
                    $token = $gateway['app_gateway']['token_live']; // V4 USA PK

                $postback_url = route('payment-callback-gateway-slug', ['gatewaySlug' => $gateway['pay_gateway_slug'] , 'callbackType' => 'app_event', 'orderId' => $data['order']['id'], 'paymentId' => $data['order']['payment']['id']]);
            }

            // SET PAYMENTS
            switch ($data['order']['payment']['pay_type'] ?? []) {
                case 'credit_card':
                case 'card_credit':

                    // PEGA ENDERECO
                    $endereco = buscarCep($data['card_credit_cep'] ?? null);
                    //
                    if($endereco->error)
                        throw new Exception($endereco->msg);

                    $payment = [
                        'payment_method'       => 'credit_card',
                        "installments"         => (int) trim($data['card_credit_parcelado'] ?? 1),
                        'card_holder_name'     => $data['card_credit_nome'],
                        'card_cvv'             => $data['card_credit_cvv'],
                        'card_number'          => $data['card_credit_num'],
                        'card_expiration_date' => trim($data['card_credit_validade_mm']).trim(substr($data['card_credit_validade_aaaa'],-2)),
                        "soft_descriptor"      => substr(str_replace(" ","",($data['event']['event_name_short'] ?? $data['event']['event_name'])), 0, 13),
                        'billing'     => [
                            'name'    => strtolower($data['order']['buyer_name']),
                            'address' => [
                                'country'       => 'br',
                                'street'        => $endereco->endereco,
                                'street_number' => $data['card_credit_cep_num'],
                                'state'         => $endereco->estado,
                                'city'          => $endereco->cidade,
                                'neighborhood'  => $endereco->bairro,
                                'zipcode'       => $endereco->cep
                            ]
                        ],
                    ];
                    break;

                case 'boleto':
                    $payment = [
                        'payment_method'         => 'boleto',
                        "installments"           => (int) 1,
                        'boleto_expiration_date' => now()->addDays(3)->format('Y-m-d'),
                        'boleto_instructions'    => ($data['event']['event_name'] ?? 'ATENÇÃO') . " - A compra será efetivada após a confirmação do pagamento",

                    ];
                    break;

                default:
                    // RETURN ERROR SEM MODULO DE PAGAMENTO DEFINIDO
                    throw new Exception('modulo_pagamento_invalido');
                    return;
            }

            // MONTA POST
            $postBody = [
                'reference_key'        => $data['order']['payment']['id'],
                'amount'               => (int) $data['order']['payment']['value_paid'],
                "async"                => false,
                "capture"              => true,
                "postback_url"         => $postback_url,
                'customer' => [
                    'external_id' => '1',
                    'name'        => strtolower($data['order']['buyer_name']),
                    'type'        => 'individual',
                    'country'     => 'br',
                    'documents'   => [
                        [
                            'type'   => $data['order']['buyer_doc_type'] ?? 'cpf',
                            'number' => $data['order']['buyer_doc_num'],
                        ]
                    ],
                    'phone_numbers' => ['+'.trim($data['order']['buyer_contact_country']).trim($data['order']['buyer_contact_ddd']).trim($data['order']['buyer_contact_num'])],
                    'email'         => $data['order']['buyer_email']
                ],
                "metadata" => [
                    "event_id"      => $data['event']['id'] ?? null,
                    "event_slug"    => $data['event']['event_slug'] ?? null,
                    "order_id"      => $data['order']['id'] ?? null,
                    "order_control" => $data['order']['order_control'] ?? null,
                    "payment_id"    => $data['order']['payment']['id'] ?? null,
                    "gateway_slug"  => $gateway['pay_gateway_slug'] ?? null,
                ],
            ];

            // APPEND
            foreach ($payment as $paymentKey => $paymentValues) {
                $postBody[$paymentKey] = $paymentValues;
            }

            // AGRUPA ITEMS DA TRANSAÇÃO
            if($data['order']['tickets'] && count($data['order']['tickets']))
            {
                foreach ($data['order']['tickets'] as $ticketKey => $ticketValues) {
                    $postBody['items'][] = [
                        "id"         => $ticketValues['id'],
                        "title"      => $ticketValues['ticket_control'],
                        "unit_price" => (int) $ticketValues['event_ticket_price'],
                        "quantity"   => 1,
                        "tangible"   => false,
                        "date"       => $data['event']['event_datetime_start'] ? date('Y-m-d',strtotime($data['event']['event_datetime_start'])) : null,
                        "category"   => $ticketValues['event_name'],
                    ];

                    //
                    $postBody['metadata']['event_ticket_id']   = $ticketValues['event_ticket_id'] ?? null;
                    $postBody['metadata']['event_ticket_slug'] = $ticketValues['event_ticket_slug'] ?? null;

                }
            }

            // INSTANCIA PAGARME
            $pagarme = new \PagarMe\Client($token);

            // REALIZA A TRANSAÇÃO
            $transaction = $pagarme->transactions()->create($postBody);

            // VALIDA RETORNO
            if(!$transaction)
                throw new Exception('response_failed');

            // SANITIZA POST BODY - CARD CREDIT
            if(isset($postBody['payment_method']['card']))
                $postBody['payment_method']['card'] = '...';

            // SET STATUS
            $responseStatus  = $transaction->status;
            //
            if(in_array(strtoupper($responseStatus), ['PENDING','WAITING_PAYMENT']) && in_array(strtoupper($data['order']['payment']['pay_type']), ['BOLETO']))
                $responseStatus = 'pending_boleto';

            // SET REFUSED
            $payRefused = (!in_array(strtoupper($responseStatus), ['PAID','WAITING_PAYMENT','PENDING_BOLETO'])) ? true : false;;

            // MONTA OBJ RETORNO
            $return = new stdClass();
            $return->error                      = false;
            $return->msg                        = __(strtoupper('PAY_' . $responseStatus));
            $return->msg_sub                    = $payRefused ? $transaction->refuse_reason : $transaction->acquirer_response_message;;
            $return->gateway_slug               = $gateway['pay_gateway_slug'];
            $return->status                     = $responseStatus;
            $return->status_old                 = null;
            $return->value_paid                 = $transaction->amount;
            $return->pay_refused                = $payRefused;
            $return->pay_refused_reason         = $transaction->acquirer_response_message;
            $return->pay_nsu                    = $transaction->nsu ? $transaction->nsu : $transaction->id;
            $return->pay_type                   = strtoupper($transaction->payment_method);
            $return->pay_datetime               = Carbon::create($transaction->date_updated)->format('Y-m-d H:i:s');
            $return->pay_installments_number    = $transaction->installments;
            $return->pay_installment_value      = ((int) $transaction->installments > 1) ? (int) $transaction->amount / (int) $transaction->installments : null;
            $return->pay_card_first             = $transaction->card_last_digits;
            $return->pay_card_last              = $transaction->card_last_digits;
            $return->pay_card_name              = $transaction->card_holder_name;
            $return->pay_card_brand             = $transaction->card_brand;
            $return->pay_boleto_barcode         = $transaction->boleto_barcode;
            $return->pay_boleto_url             = $transaction->boleto_url;
            $return->pay_boleto_expiration_date = $transaction->boleto_expiration_date;
            $return->pay_postback_url           = $transaction->postback_url;
            $return->pay_json_request           = json_encode($postBody);
            $return->pay_json_response          = json_encode($transaction);

            return $return;

        } catch (\Throwable $th) {

            $return = new stdClass();
            $return->error   = true;
            $return->msg     = $th->getMessage();
            $return->msg_sub = null;

            //
            if($th->getMessage() == 'modulo_pagamento_invalido')
            {
                $return->msg     = "ERRO AO PROCESSAR PAGAMENTO";
                $return->msg_sub = "MODULO DE PAGAMENTO " . $data['order']['payment']['pay_type'] . " NÃO É VALIDO";
            }

            //
            if($th->getMessage() == 'response_failed')
            {
                $return->msg = 'ERRO AO INICIAR PAGAMENTO';

                // SE ERROR MENSAGES
                if(isset($response['message']) ?? false)
                {
                    $return->error_messages[] = __($response['message'] ?? 'message');
                }

                // SE ERROR MENSAGES
                if(isset($response['error_messages']) && count($response['error_messages']) ?? false)
                {
                    $return->error_messages = [];

                    foreach($response['error_messages'] ?? [] as $error_messages)
                    {
                        $implode = strtoupper(implode(" ",$error_messages));

                        $return->error_messages[] = __($implode);
                    }
                }
            }

            return $return;
        }
    }

    public function callGatewayPayment($order, $gateway, $sandBox=false, $gatewaySlug=false)
    {
        //
        if(!$gatewaySlug)
        {
            $gatewaySlug = $gateway['pay_gateway_slug'] ?? false;
        }

        // INSTANCIA GATEWAY
        switch ($gatewaySlug ?? false) {
           case 'pagarme-v4':
           case 'pagarme-v4-test':
               $gatewayReturn = $this->gatewayPagarMeV4($order, $gateway, $sandBox);
               break;

           case 'pagarme-v5':
           case 'pagarme-v5-test':
               $gatewayReturn = $this->gatewayPagarMeV5($order, $gateway, $sandBox);
               break;

           case 'pagseguro':
           case 'pagseguro-test':

               // SUBMETE AO PAGSEGURO
               $gatewayReturn = $this->gatewayPagSeguro($order);
               break;

           default:
               $errorMsg = 'Nenhum gateway de pagamento disponível';
                //
               session()->flash('error', 'ERRO INTERNO');
               session()->flash('error_sub', $errorMsg);
               //
               throw new Exception($errorMsg);
        }

        //
        if(!$gatewayReturn)
        {
            $errorMsg = 'Sem retorno do Gateway de Pagamento';

            session()->flash('error', $errorMsg);
            session()->flash('error_sub', 'Tente novamente');

            throw new Exception($errorMsg);
       }

       // TRANSLATE MSG
       $gatewayReturn->msg = __($gatewayReturn->msg);

       // VALIDA RETORNO - ERROR
       if($gatewayReturn->error)
       {
           session()->flash('error', $gatewayReturn->msg);
           session()->flash('error_sub', 'Tente novamente mais tarde');
           //
           if(count($gatewayReturn->error_messages ?? []) ?? false)
               foreach($gatewayReturn->error_messages as $error_message)
                   session()->flash('error_sub', $error_message);

            throw new Exception($gatewayReturn->msg . (($error_message ?? false) ? (' - ' . $error_message) : null));
       }

       // VALIDA RETORNO - NEGADA
       if($gatewayReturn->pay_refused ?? false)
       {
           // SE BANDEIRA
           if($gatewayReturn->pay_card_brand ?? false)
               $gatewayReturn->msg .= " cartão {$gatewayReturn->pay_card_brand}";

           // SE CARD_LAST
           if($gatewayReturn->pay_card_last ?? false)
           $gatewayReturn->msg .= " - final {$gatewayReturn->pay_card_last}";

           session()->flash('error', __($gatewayReturn->msg));
           session()->flash('error_sub', __($gatewayReturn->pay_refused_reason));

           // FINALIZA
           return $gatewayReturn;
        }

        return $gatewayReturn;
    }

    public function defineAction($action=false)
    {
        $this->divBtnActions = false;

        switch ($action) {
            case 'adicionarPagamento':
                $this->divAdicionarPagamento = true;
                break;
            default:
                $this->divBtnActions = true;
                $this->divAdicionarPagamento = false;
                break;
        }
    }

    public function adicionarPagamento()
    {
        $data = $this->validate([
            'forma_pagamento' => ['required'],
            'pagamento_valor' => ['required'],
        ]);

        try {

            //
            $data['forma_pagamento']  = $this->forma_pagamento;
            $data['pagamento_valor'] = $this->pagamento_valor  . '00';

            // BOLETO
            $compraValores['valorTotal']          = (int) $data['pagamento_valor'];
            $compraValores['valorTotalAcrescimo'] = 0;
            $compraValores['valorTotalLiquido']   = $compraValores['valorTotal'] + $compraValores['valorTotalAcrescimo'];
            $compraValores['taxa']                = 0;
            $compraValores['label']               = '1x de R$ ' . number_format((int) $compraValores['valorTotalLiquido'] / 100 , 2, ',', '.');


            // INSTRUÇÕES - TOP
            $data['line_aditional'] = false;
            //
            if ($this->line_aditional_top_titulo ?? false)
                $data['line_aditional']['line_aditional_top_titulo'] = $this->line_aditional_top_titulo;

            if ($this->line_aditional_top_texto ?? false)
                $data['line_aditional']['line_aditional_top_texto']  = $this->line_aditional_top_texto;

            // INSTRUÇÕES - BOTTON
            if ($this->line_aditional_botton_titulo ?? false)
                $data['line_aditional']['line_aditional_botton_titulo'] = $this->line_aditional_botton_titulo;

            if ($this->line_aditional_botton_texto ?? false)
                $data['line_aditional']['line_aditional_botton_texto']  = $this->line_aditional_botton_texto;

            if ($data['line_aditional'] ?? false)
            {
                $paid_description = substr(implode(' | ',$data['line_aditional']), 0, 250);
            }
            else
            {
                $paid_description = strtoupper('PAGO COM ' . $this->comprador_formapagamento ?? null);
            }

            DB::beginTransaction();

            // CRIA PAGAMENTO
            $payment = AppPayment::create([
                'app_ref'             => $this->target_ref,
                'app_ref_order_id'    => $this->order->id,
                'gateway_id'          => $this->target->gatewayPay->id ?? null,
                'gateway_slug'        => $this->target->gatewayPay->pay_gateway_slug ?? null,
                'status'              => 'iniciado',
                'description'         => strtoupper($this->target->event_name),
                'value_paid'          => $compraValores['valorTotal'] ?? null,
                'value_fees'          => $compraValores['valorTotalAcrescimo'] ?? null,
                'value_liquid'        => $compraValores['valorTotalLiquido'] ?? null,
                'fee_percentage_used' => $compraValores['taxa'] ?? null,
                'paid_label'          => $compraValores['label'] ?? null,
                'paid_description'    => $paid_description,
                'pay_type'            => strtolower($data['forma_pagamento'] ?? null),
            ]);

            // MONTA VALIDATE DATA
            $data['event'] = $this->target->toArray();
            $data['order'] = $this->order->toArray();
            $data['order']['payment'] = $payment->toArray();
            //
            foreach ($this->order->tickets as $ticketKey => $ticket)
            {
                $data['order']['tickets'][$ticketKey] = $ticket->toArray();
                $data['order']['tickets'][$ticketKey]['event_datetime'] = $ticket->event_datetime ? $ticket->event_datetime->format('d/m/Y H:i') : null;
            }

            // PROCESSA PGTO
            $gatewayReturn = $this->callGatewayPayment($data, $this->target->gatewayPay->toArray(), $this->target->pay_sandbox);

            // PAYMENT - UPDATE
            $payment->update((array) $gatewayReturn);
            $payment->save();

            // ORDER - AJUSTES FINAIS
            $data['order']['payment'] = $payment->toArray();
            $data['order']['payment']['pay_boleto_expiration_date'] = $data['order']['payment']['pay_boleto_expiration_date'] ? Carbon::create($data['order']['payment']['pay_boleto_expiration_date'])->format('d/m/Y') : null;

            // NOTIFICAÇÃO - EMAIL
            $job = NotificationAppPaymentPagamento::dispatch($data, $data['order']['buyer_email'], config('mail.bcc_pagamentos'));

            DB::commit();
            session()->flash('success','PAGAMENTO ADICIONADO');
            $this->defineAction();
        }
        catch (\Throwable $th)
        {

            DB::rollBack();

            session()->flash('error', $th->getMessage());

            // dd(
            //     'Throwable: ' . __FILE__,
            //     $th
            // );
        }
    }

    public function updatedTargetRef()
    {
        Session::put('target_ref', $this->target_ref);
    }

    public function updatedTargetId()
    {
        Session::put('target_id', $this->target_id);
    }

    public function alterarTarget()
    {
        $this->target_id  = false;
        $this->target_ref = false;
        $this->target     = false;
        //
        Session::put('target_ref', $this->target_ref);
        Session::put('target_id', $this->target_id);
        return redirect()->route('dashboard');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($orderControl=false)
    {
        // dd(
        //     \Route::currentRouteName(),
        // );

        $this->action       = $action;
        $this->orderControl = $orderControl;
        //
        if($this->action == 'transacoes-detalhes' && !$this->orderControl)
            $this->action = 'transacoes';
    }

    public function render()
    {
        // RESET
        $this->target = false;

        // RECUPERA DA SESSAO - target_ref
        if(Session::has('target_ref'))
            $this->target_ref = Session::get("target_ref");

        // RECUPERA DA SESSAO - target_id
        if(Session::has('target_id'))
            $this->target_id = Session::get("target_id");

        // DESVIO POR AÇÃO
        switch ($this->action ?? false) {

            case 'gestao-orcamentaria':

                //
                if(($this->target_ref ?? false) && ($this->target_id ?? false))
                {
                    // GET OS DADOS
                    $this->target  = Event::with(['ticketsTypes','ticketsTypes.orders','budgetsReceita','budgetsReceita.budgetsItems','budgetsDespesa','budgetsDespesa.budgetsItems'])->find($this->target_id);

                    // dd(Session::get("target_id"));
                }

                return view('livewire.dashboard.dashboard-financeiro-gestao-orcamentaria')->layout('layouts.app-pep-auth');
                break;

            case 'gestao-orcamentaria-adm-receita':

                // SE NAO TARGET_ID - RENDER
                if(!$this->target_id)
                {
                    $this->action = 'gestao-orcamentaria';
                    return $this->render();
                }

                $this->budget_operation = 'receita';

                // GET OS DADOS
                $this->target = Event::with(['ticketsTypes','ticketsTypes.orders','budgetsReceita','budgetsReceita.budgetsItems'])->find($this->target_id);

                //
                if($this->budget_id ?? false)
                    $this->budget = $this->target->budgetsReceita->find($this->budget_id);

                return view('livewire.dashboard.dashboard-financeiro-gestao-orcamentaria-adm-receita')->layout('layouts.app-pep-auth');
                break;

            case 'gestao-orcamentaria-adm-despesa':

                // SE NAO TARGET_ID - RENDER
                if(!$this->target_ref || !$this->target_id)
                {
                    $this->action = 'gestao-orcamentaria';
                    return $this->render();
                }

                $this->budget_operation = 'despesa';

                // GET OS DADOS
                $this->target = Event::with(['ticketsTypes','ticketsTypes.orders','budgetsDespesa','budgetsDespesa.budgetsItems'])->find($this->target_id);

                //
                if($this->budget_id ?? false)
                    $this->budget = $this->target->budgetsDespesa->find($this->budget_id);

                return view('livewire.dashboard.dashboard-financeiro-gestao-orcamentaria-adm-despesa')->layout('layouts.app-pep-auth');
                break;

            case 'transacoes':

                //
                if(($this->target_ref ?? false) && ($this->target_id ?? false))
                {
                    // GET OS DADOS
                    $this->target  = Event::with(['gatewayPay','gatewayPay.appGateway','orders','orders.payments','orders.tickets','ticketsTypes','tickets'])->find($this->target_id);
                    $this->pedidos = $this->target->orders;
                }

                return view('livewire.dashboard.dashboard-financeiro-transacoes')->layout('layouts.app-pep-auth');
                break;

            case 'transacoes-detalhes':

                // GET OS DADOS
                $this->target = $this->getTarget($this->target_ref, $this->target_id);
                $this->order  = $this->target->orders->where('order_control',$this->orderControl)->first();

                return view('livewire.dashboard.dashboard-financeiro-transacoes-detalhes')->layout('layouts.app-pep-auth');
                break;

            default:
                // if(($this->target_ref ?? false) && ($this->target_id ?? false))
                // {
                //     // GET OS DADOS
                //     $this->target = $this->getTarget($this->target_ref, $this->target_id);
                // }

                return view('livewire.dashboard.dashboard-financeiro')->layout('layouts.app-pep-auth');
                break;
        }
    }

    public function testeGateway()
    {
        $token = $this->target->gatewayPay->token_live;

        $pagarme = new \PagarMe\Client($token);

        // $transactions = $pagarme->transactions()->getList([
        //     'count' => 1000,
        //     'status' => 'paid',
        //     'metadata' => [
        //         'event_slug' => 'next-summer-camp-2022',
        //     ]
        // ]);

        $balances = $pagarme->balances()->get();

        $transactionBoleto = $pagarme->transactions()->get([
            'id' => '835927921', // BOLETO
        ]);

        $transaction1x = $pagarme->transactions()->get([
            'id' => '839007224',
        ]);
        $transaction5x = $pagarme->transactions()->get([
            'id' => '838263866',
        ]);

        // $transactionPayables = $pagarme->transactions()->listPayables([
        //     'id' => '838263866'
        // ]);

        // $transactionPayables3 = $pagarme->transactions()->listPayables([
        //     'id' => '839007224'
        // ]);

        dd(
            $balances,
            $transactionBoleto,
            $transaction1x,
            $transaction5x,

            // $transactionPayables,
            // $this->order->toArray(),
            // $transaction->paid_amount,
            // $transaction->payment->payment_date,
            // $transaction->payment->net_amount,
            // $transactions[0]->paid_amount,
            // $transactions[0]->payment->payment_date,
            // $transactions[0]->payment->net_amount,
            // $transactions,
            // $this->target->gatewayPay->appGateway->toArray(),
            // $this->target->gatewayPay->toArray(),
            // $this->target->toArray(),
        );
    }
}

