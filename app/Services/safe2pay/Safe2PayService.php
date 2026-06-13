<?php

namespace App\Services\safe2pay;

use Exception;
use GuzzleHttp\Client;

class Safe2PayService
{
    protected $sandbox;
    protected $client;
    protected $headers;
    protected $payload;

    public function __construct($token,$sandbox=false)
    {
        $sandboxParsed = filter_var($sandbox, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $this->sandbox = $sandboxParsed ?? false;

        //
        $this->client    = new Client(["base_uri" => "https://payment.safe2pay.com.br/v2/"]);
        $this->headers   = [
            'Content-Type' => 'application/json',
            'x-api-key'    => $token,
        ];
    }

    // SET APLICATION
    public $Application;
    public $Vendor;
    public $CallbackUrl;
    public $Reference;
    public $Valor;
    function setAplication($Application=false,$Vendor=false,$CallbackUrl=false,$Reference=false,$ShouldUseAntiFraud=false)
    {
        $this->payload['Application']        = $Application ? $Application : $this->Application;
        $this->payload['Vendor']             = $Vendor ? $Vendor : $this->Vendor;
        $this->payload['CallbackUrl']        = $this->sanitizeCallbackUrl($CallbackUrl ? $CallbackUrl : $this->CallbackUrl);
        $this->payload['Reference']          = $Reference ? $Reference : $this->Reference;
        $this->payload["ShouldUseAntiFraud"] = $ShouldUseAntiFraud ? true : false; // AntiFraude
    }

    private function sanitizeCallbackUrl($url)
    {
        if (!is_string($url)) {
            return $url;
        }

        $url = trim($url);
        $url = preg_replace('/[\x00-\x1F\x7F]/u', '', $url);

        return $url;
    }

    // SET META
    public $order_id;
    public $payment_id;
    public $gateway_id;
    public $app_ref;
    public $localizador;
    public $order_amount;
    public $order_amount_discount;
    public $order_amount_pay;
    public $pay_slip;
    public $pay_slip_id;
    public $pay_slip_description;
    public $is_anonymous=false;
    function setMeta($order_id=false,$payment_id=false,$gateway_id=false,$app_ref=false,$localizador=false,$order_amount=false,$order_amount_discount=false,$order_amount_pay=false,$is_anonymous=false)
    {
        $this->payload['Meta']['order_id'] = $order_id ? $order_id : $this->order_id;
        $this->payload['Meta']['payment_id'] = $payment_id ? $payment_id : $this->payment_id;
        $this->payload['Meta']['gateway_id'] = $gateway_id ? $gateway_id : $this->gateway_id;
        $this->payload['Meta']['app_ref'] = $app_ref ? $app_ref : $this->app_ref;
        $this->payload['Meta']['localizador'] = $localizador ? $localizador : $this->localizador;
        $this->payload['Meta']['order_amount'] = $order_amount ? $order_amount : $this->order_amount;
        $this->payload['Meta']['order_amount_discount'] = $order_amount_discount ? $order_amount_discount : $this->order_amount_discount;
        $this->payload['Meta']['order_amount_pay'] = $order_amount_pay ? $order_amount_pay : $this->order_amount_pay;
        $this->payload['Meta']['is_anonymous'] = $is_anonymous ? $is_anonymous : $this->is_anonymous;
        //
        if($this->payload['Meta']['pay_slip'] = $this->pay_slip)
        {
            $this->payload['Meta']['pay_slip_id'] = $this->pay_slip_id ?? null;
            $this->payload['Meta']['pay_slip_description'] = $this->pay_slip_description ?? null;
        }
    }

    // SET CUSTOMER
    public $Name;
    public $Identity;
    public $Phone;
    public $Email;
    function setCustomer($Name=false,$Identity=false,$Phone=false,$Email=false)
    {
        $this->payload['Customer']['Name'] = $Name ? $Name : $this->Name;
        $this->payload['Customer']['Identity'] = $Identity ? $Identity : $this->Identity;
        $this->payload['Customer']['Phone'] = $Phone ? $Phone : $this->Phone;
        $this->payload['Customer']['Email'] = $Email ? $Email : $this->Email;
    }

    // SET CUSTOMER ADDRESS
    public $IsSandbox;
    public $ZipCode;
    public $Street;
    public $Number;
    public $Complement;
    public $District;
    public $CityName;
    public $StateInitials;
    public $CountryName;
    function setCustomerAddress($ZipCode=false,$Street=false,$Number=false,$Complement=false,$District=false,$CityName=false,$StateInitials=false,$CountryName=false,$clear=false)
    {
        $this->payload['Customer']["Address"]['ZipCode'] = $ZipCode ? $ZipCode : $this->ZipCode;
        $this->payload['Customer']["Address"]['Street'] = $Street ? $Street : $this->Street;
        $this->payload['Customer']["Address"]['Number'] = $Number ? $Number : $this->Number;
        $this->payload['Customer']["Address"]['Complement'] = $Complement ? $Complement : $this->Complement;
        $this->payload['Customer']["Address"]['District'] = $District ? $District : $this->District;
        $this->payload['Customer']["Address"]['CityName'] = $CityName ? $CityName : $this->CityName;
        $this->payload['Customer']["Address"]['StateInitials'] = $StateInitials ? $StateInitials : $this->StateInitials;
        $this->payload['Customer']["Address"]['CountryName'] = $CountryName ? $CountryName : $this->CountryName;

        //
        if(($clear ?? false) && isset($this->payload['Customer']["Address"]))
        {
            unset($this->payload['Customer']["Address"]);
        }
    }

    // SET APPEND PRODUCTS
    public $products=[];
    function appendProducts($Code,$UnitPrice,$Quantity,$Description=null,$Discount=false)
    {
        $UnitPrice = convertInt2Float(toMoneyInt(toMoneyDot($UnitPrice)) ?? 0);

        //
        if($Discount ?? false)
        {
            $UnitPrice = $UnitPrice * (-1);
        }

        $this->products[] =  [
            "Code"        => $Code,
            "UnitPrice"   => $UnitPrice,
            "Quantity"    => $Quantity,
            "Description" => strtoupper(trim(($Description))),
        ];

    }

    // SET PRODUCTS
    function setProducts($clear=false,$clearFull=false)
    {
        if($clearFull ?? false)
        {
            $this->products = [];
            unset($this->payload['Products']);
        }
        elseif($clear ?? false)
        {
            unset($this->payload['Products']);
        }
        else
        {
            $this->payload['Products'] = $this->products;
        }
    }

    // SET PRODUCTS >> META
    function setProductsToMeta()
    {
        $this->payload['Meta']['Products'] = $this->payload['Products'];

        // dd(
        //     $this->payload['Meta'],
        //     $this->payload['Products'],
        // );
    }

    // APLICA SPLIT PAGAMENTO
    public $ReceiverId;
    public $ReceiverName;
    function applySplitPayment($splitValor=0,$splitType='valor',$clear=false)
    {
        unset($this->payload["Splits"]);

        if($clear ?? false)
        {
            return true;
        }

        switch ($splitType)
        {
            case 'percentual':
                $splitType=1;
                break;
            case 'valor':
            default:
                $splitType=2;
                break;
        }

        //
        if(($this->ReceiverId ?? false) && ($this->ReceiverName ?? false))
        {
            //
            $this->payload["Splits"][] = [
                "IsPayTax"    => false,             // Boolean - Informar se a taxa de custo será paga pelo recebedor.
                "CodeTaxType" => $splitType,        // Int - Tipo de repasse, informar 1 para Percentual ou 2 para Valor
                "Amount"      => $splitValor ?? 0,  // Decimal - Informar o valor do repasse, em percentual ou valor. Ex: 2.30
                "IdReceiver"  => $this->ReceiverId, // Int - Código da empresa no Safe2Pay. Se enviar o IdReceiver. Ex: 3512213
                "Name"        => $this->ReceiverName ?? 'REPASSA TAXA SAFE2PAY', // String - Nome da empresa recebedora. Ex: Empresa de teste
            ];

            return true;
        }

        return false;
    }

    // TRANSACAO PIX
    public $Expiration;
    public $ExpirationDateTime;
    public $CustomPixExpiration = null; // Permite sobrescrever expiração PIX externamente

    function setPaymentPix($docNum)
    {
        try
        {
            // Se CustomPixExpiration foi definido, usa ele; senão usa lógica padrão
            if ($this->CustomPixExpiration !== null) {
                $this->Expiration = $this->CustomPixExpiration;
            } else {
                $this->Expiration = ($this->sandbox ?? false) ? 120 : 18000; // 2 MINUTOS ou 60 MINUTOS
            }

            $this->ExpirationDateTime = now()->addSeconds($this->Expiration)->format('Y-m-d H:i:s');
            //
            $this->payload["IsSandbox"] = false; // CPF DO PAGADOR
            $this->payload["Customer"]["Identity"] = $docNum; // CPF DO PAGADOR
            $this->payload["PaymentMethod"]        = "6"; // 6:PIX
            $this->payload["PaymentObject"]        = [
                "Expiration"      => $this->Expiration,
                "IsApplyInterest" => $this->IsApplyInterest ?? false, // Bool Se deve cobrar juros ao valor da compra.Ex: true	0-1	-
            ];

            return $this->payload;
        }
        catch (\Throwable $th)
        {
            throw new Exception($th->getMessage() ?? "Error Processing", $th->getCode() ?? 500);
        }
    }

    // TRANSACAO CREDITO
    public $Holder;
    public $CardNumber;
    public $ExpirationDateMM;
    public $ExpirationDateAAAA;
    public $SecurityCode;
    public $InstallmentQuantity;
    public $IsApplyInterest=false;
    public $InterestRate=0;
    function setPaymentCredit($docNum)
    {
        try
        {
            //
            $this->payload["IsSandbox"] = (bool) $this->sandbox ?? false;

            // DEFINE CPF/CNPJ DO TITULAR DO CARTÃO
            $this->payload["Customer"]["Identity"] = $docNum; // CPF/CNPJ DO TITULAR

            // AJUSTA PAYLOAD COM DADOS COMPLETOS (para envio ao gateway)
            $this->payload["PaymentMethod"] = "2"; // 2:Credito
            $this->payload["PaymentObject"] = [
                "Holder"              => $this->Holder,
                "CardNumber"          => $this->CardNumber,
                "ExpirationDate"      => $this->ExpirationDateMM.'/'.$this->ExpirationDateAAAA,
                "SecurityCode"        => $this->SecurityCode,
                "InstallmentQuantity" => $this->InstallmentQuantity ?? 1,
                "IsPreAuthorization"  => false, // Realizar a pré-autorização de uma transação com cartão de crédito, sem realizar a captura. Se usado, a transação obrigatoriamente deverá ser capturada em até 5 dias corridos.Ex: true	0-1	-
                "IsApplyInterest"     => $this->IsApplyInterest ?? false, // Bool Se deve cobrar juros ao valor da compra.Ex: true	0-1	-
                "InterestRate"        => $this->InterestRate ?? 0, // Valor, em percentual (%), de juros que deve ser cobrado.Ex: 2.50
            ];

            // REMOVE ADDRESS
            $this->setCustomerAddress(clear:true);

            // RETORNA PAYLOAD COMPLETO (a sanitização será feita no controller APÓS receber resposta)
            return $this->payload;
        }
        catch (\Throwable $th)
        {
            throw new Exception($th->getMessage() ?? "Error Processing", $th->getCode() ?? 500);
        }
    }

    function setPaymentCreditToken($docNum, $cardToken)
    {
        try
        {
            $this->payload["Customer"]["Identity"] = $docNum;

            $this->payload["PaymentMethod"] = "2"; // 2:Credito
            $this->payload["PaymentObject"] = [
                "Token"               => $cardToken,
                "InstallmentQuantity" => $this->InstallmentQuantity ?? 1,
                "IsPreAuthorization"  => false,
                "IsApplyInterest"     => $this->IsApplyInterest ?? false,
                "InterestRate"        => $this->InterestRate ?? 0,
            ];

            $this->setCustomerAddress(clear:true);

            return $this->payload;
        }
        catch (\Throwable $th)
        {
            throw new Exception($th->getMessage() ?? "Error Processing", $th->getCode() ?? 500);
        }
    }

    // TRANSACAO BOLETO
    public $BoletoExpirationDate;
    public $BoletoInstruction;
    public $BoletoMessage;

    function setPaymentBoleto($docNum, $daysToExpire = 3, $campaignName = null, $campaignDescription = null)
    {
        try
        {
            //
            $this->payload["IsSandbox"] = (bool) $this->sandbox ?? false;

            // Calcula data de vencimento (padrão 3 dias)
            $this->BoletoExpirationDate = now()->addDays($daysToExpire)->format('Y-m-d');

            // CPF/CNPJ do pagador (obrigatório para boleto)
            $this->payload["Customer"]["Identity"] = $docNum;

            // PaymentMethod 1 = Boleto
            $this->payload["PaymentMethod"] = "1";

            // Prepara mensagens do boleto com informações da campanha
            $messages = [];

            if ($campaignName) {
                $messages[] = "Campanha: " . mb_strimwidth($campaignName, 0, 80);
            }

            if ($campaignDescription) {
                // Limpa HTML e formata descrição
                $cleanDescription = strip_tags($campaignDescription);
                $cleanDescription = preg_replace('/\s+/', ' ', $cleanDescription);
                $cleanDescription = trim($cleanDescription);
                $messages[] = mb_strimwidth($cleanDescription, 0, 80);
            }

            // Dados do boleto
            $this->payload["PaymentObject"] = [
                "DueDate" => $this->BoletoExpirationDate,
                "Instruction" => $this->BoletoInstruction ?? "Não receber após o vencimento",
                "Message" => !empty($messages) ? $messages : ($this->BoletoMessage ?? []),
            ];

            return $this->payload;
        }
        catch (\Throwable $th)
        {
            throw new Exception($th->getMessage() ?? "Error Processing Boleto", $th->getCode() ?? 500);
        }
    }

    /**
     * PIX DIRETO / STATIC PIX
     * Gera PIX estático com QR Code permanente
     * Endpoint: /v2/staticPix
     *
     * @param float|int $amount Valor em centavos ou decimal
     * @param string $description Descrição do pagamento
     * @param string|null $reference Referência única
     * @param string|null $callbackUrl URL de callback
     * @return array Response da API
     */
    public function createStaticPix($amount, $description, $reference = null, $callbackUrl = null)
    {
        try
        {
            // Converte centavos para decimal se necessário
            $amountDecimal = convertInt2Float(toMoneyInt(toMoneyDot($amount)));

            // Monta payload
            $payload = [
                'Amount' => $amountDecimal,
                'Description' => $description,
                'Reference' => $reference ?? $this->Reference ?? 'PIX.' . strtoupper(uniqid()),
                'CallbackUrl' => $callbackUrl ?? $this->CallbackUrl ?? null,
            ];

            // Remove CallbackUrl se for null
            if (empty($payload['CallbackUrl'])) {
                unset($payload['CallbackUrl']);
            }

            // Faz requisição para endpoint staticPix
            $response = $this->client->post('staticPix', [
                'headers' => $this->headers,
                'json' => $payload,
            ]);

            $result = json_decode($response->getBody(), true);

            // Adiciona o payload enviado no resultado (para log)
            $result['RequestPayload'] = $payload;

            return $result;
        }
        catch (\GuzzleHttp\Exception\ClientException $e)
        {
            $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;
            return [
                'HasError' => true,
                'ErrorCode' => $e->getCode(),
                'Error' => $e->getMessage(),
                'ErrorDetail' => $errorBody ?? null,
            ];
        }
        catch (\Throwable $th)
        {
            return [
                'HasError' => true,
                'ErrorCode' => $th->getCode() ?? 500,
                'Error' => $th->getMessage(),
                'ErrorDetail' => null,
            ];
        }
    }

    /**
     * Processa response do Static PIX
     * Converte para formato padrão do sistema
     */
    public function processStaticPixResponse($response)
    {
        $return = new \stdClass();
        $return->error = false;
        $return->code = null;
        $return->msg = null;
        $return->msg_sub = null;
        $return->status = null;
        $return->datahora = now()->format('Y-m-d H:i:s');
        $return->response = $response;

        // Verifica erro
        if ($response['HasError'] ?? false) {
            $return->error = true;
            $return->code = $response['ErrorCode'] ?? 500;
            $return->msg = $response['Error'] ?? 'Erro ao gerar PIX estático';
            $return->status = 'error';
            return $return;
        }

        // Sucesso - extrai dados
        $detail = $response['ResponseDetail'] ?? [];

        $return->error = false;
        $return->code = 200;
        $return->msg = 'PIX estático gerado com sucesso';
        $return->status = 'pending';
        $return->nsu = $detail['Id'] ?? null;
        $return->identifier = $detail['Identifier'] ?? null;
        $return->reference = $detail['Reference'] ?? null;

        // Dados do PIX
        $return->pay_pix_qr_code = $detail['Key'] ?? ($detail['Pix']['Key'] ?? null);
        $return->pay_pix_qr_code_url = $detail['QrCode'] ?? ($detail['Pix']['QrCode'] ?? null);
        $return->pay_pix_key = $detail['Key'] ?? ($detail['Pix']['Key'] ?? null);

        // Valores
        $return->valor = convertDecimalInt($detail['Amount'] ?? 0);
        $return->pagamento_forma = 'PIX Direto';
        $return->pagamento_forma_slug = 'pix_direto';

        return $return;
    }


    // EXECUTE
    function executeTransaction($forceConsulta=false)
    {
        try
        {
            $createTransaction = $this->createTransaction($this->payload);
            $processaResponse  = $this->processaResponse($createTransaction);

            // SE FORCA CONSULTA
            if(($forceConsulta ?? false) && ($processaResponse ?? false) && $processaResponse->nsu ?? false)
            {
                return $this->consultaTransacao($processaResponse->nsu,true);
            }

            return $processaResponse;
        }
        catch (\Throwable $th)
        {
            throw new Exception($th->getMessage() ?? "Error Processing", $th->getCode() ?? 500);
        }
    }

    public function createTransaction($data)
    {
        try
        {
            $data['IsSandbox'] = (bool) $this->sandbox;

            if (isset($data['CallbackUrl'])) {
                $data['CallbackUrl'] = $this->sanitizeCallbackUrl($data['CallbackUrl']);
            }

            $response = $this->client->post('payment', [
                'headers' => $this->headers,
                'json'    => $data,
            ]);

            return json_decode($response->getBody(), true);
        }
        catch (\GuzzleHttp\Exception\ClientException $e)
        {
            // Captura erro da API e retorna como array para processamento
            $errorBody = $e->getResponse() ? json_decode($e->getResponse()->getBody()->getContents(), true) : null;
            return [
                'HasError' => true,
                'ErrorCode' => $e->getCode(),
                'Error' => $e->getMessage(),
                'ErrorDetail' => $errorBody ?? null,
            ];
        }
        catch (\Throwable $th)
        {
            // Outros erros também retornam como array
            return [
                'HasError' => true,
                'ErrorCode' => $th->getCode() ?? 500,
                'Error' => $th->getMessage(),
                'ErrorDetail' => null,
            ];
        }
    }

    public $response;
    public function creditoConsultaParcelamento(int $amount)
    {
        $amount = toMoneyDot($amount);

        $client = new Client(["base_uri" => "https://api.safe2pay.com.br/v2/"]);

        $this->response = $client->get("CreditCard/InstallmentValue/?amount={$amount}", [
            'headers' => $this->headers,
        ]);

        return json_decode($this->response->getBody(), true);
    }

    public function consultaTransacao($transactionId,$processaResponse=false)
    {
        $client = new Client(["base_uri" => "https://api.safe2pay.com.br/v2/"]);

        $this->response = $client->get("transaction/Get?Id={$transactionId}", [
            'headers' => $this->headers,
        ]);

        if($processaResponse ?? false)
        {
            return $this->processaResponse(json_decode($this->response->getBody(), true));
        }

        return json_decode($this->response->getBody(), true);
    }

    public function mktListarSubcontas()
    {
        $client = new Client(["base_uri" => "https://api.safe2pay.com.br/v2/"]);

        $this->response = $client->get("Marketplace/List", [
            'headers' => $this->headers,
        ]);

        return json_decode($this->response->getBody(), true);
    }

    public function tokenizaCartao($data)
    {
        try
        {
            $response = $this->client->post('token', [
                'headers' => $this->headers,
                'json'    => $data,
            ]);

            return json_decode($response->getBody(), true);
        }
        catch (\Throwable $th)
        {
            return $th;
        }
    }

    public function transacoesListar()
    {
        $client = new Client(["base_uri" => "https://api.safe2pay.com.br/v2/"]);

        $response = $client->get("Transaction/List?RowsPerPage=100", [
            'headers' => $this->headers,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function cobrancasListar()
    {
        $client = new Client(["base_uri" => "https://api.safe2pay.com.br/v2/"]);

        $response = $client->get("SingleSale/List?PageNumber=1&RowsPerPage=100", [
            'headers' => $this->headers,
        ]);

        return json_decode($response->getBody(), true);
    }

    public function cobrancaReenviar($hash)
    {
        $client = new Client(["base_uri" => "https://api.safe2pay.com.br/v2/"]);

        $response = $client->get("SingleSale/Resend?singleSaleHash={$hash}", [
            'headers' => $this->headers,
        ]);

        return json_decode($response->getBody(), true);
    }

    public $transacao;
    public $return;
    function processaResponse($response)
    {
        // Verifica se a resposta é uma exceção ou objeto inválido
        if (!is_array($response)) {
            if ($response instanceof \Throwable) {
                // Se for uma exceção, converte para formato de erro
                $response = [
                    'HasError' => true,
                    'ErrorCode' => $response->getCode() ?? 500,
                    'Error' => $response->getMessage() ?? 'Erro ao processar pagamento',
                ];
            } else {
                // Se for outro tipo inválido, retorna erro genérico
                $response = [
                    'HasError' => true,
                    'ErrorCode' => 500,
                    'Error' => 'Resposta inválida do gateway de pagamento',
                ];
            }
        }

        //
        $this->return = new \stdClass();
        $this->return->error                = false;
        $this->return->code                 = null;
        $this->return->msg                  = null;
        $this->return->msg_sub              = null;
        $this->return->msg_adicional        = null;
        $this->return->nsu                  = null;
        $this->return->datahora             = now()->format('Y-m-d H:i:s');
        $this->return->datahoraExpiracao    = $this->ExpirationDateTime ?? null;
        $this->return->status               = null;
        $this->return->valor                = $this->Valor ?? null;
        $this->return->callback_url         = $this->CallbackUrl ?? null;
        $this->return->pagamento_forma      = null;
        $this->return->pagamento_forma_slug = null;
        $this->return->pagamento_ok         = false;
        $this->return->pagamento_datahora   = null;
        $this->return->pagamento_valor      = 0;
        $this->return->pagamento_taxa       = 0;
        $this->return->pagamento_liquido    = 0;
        $this->return->repasse_data         = null;
        $this->return->repasse_realizado    = null;
        $this->return->repasse_valor        = 0;
        $this->return->repasse_valor_taxa   = 0;
        $this->return->repasse_parcela      = 0;
        $this->return->response             = $response;
        $this->return->payload              = $this->payload ?? null;

        // SE EXISTE = PEGA FORMA PAGAMENTO
        if(isset($response['ResponseDetail']['PaymentMethod']))
        {
            $pagamento_forma = $this->formasPagamento($response['ResponseDetail']['PaymentMethod']) ?? ['nome' => 'ND','slug' => 'nd'];
            $this->return->pagamento_forma = mb_strtoupper($pagamento_forma['nome']);
            $this->return->pagamento_forma_slug = mb_strtolower($pagamento_forma['slug']);
        }
        elseif($this->return->payload ?? false)
        {
            $pagamento_forma = $this->formasPagamento($this->return->payload['PaymentMethod']) ?? ['nome' => 'ND','slug' => 'nd'];
            $this->return->pagamento_forma = mb_strtoupper($pagamento_forma['nome']);
            $this->return->pagamento_forma_slug = mb_strtolower($pagamento_forma['slug']);
        }

        // SE EXISTE = PEGA FORMA PAGAMENTO
        if(isset($response['ResponseDetail']['CallbackUrl']))
        {
            $this->return->callback_url = $response['ResponseDetail']['CallbackUrl'];
        }

        // ERROR
        if ($response['HasError'] ?? false)
        {
            $this->return->error      = true;
            $this->return->code       = $response['ErrorCode'] ?? 500;
            $this->return->msg        = __($response['Error'] ?? 'Erro não especificado');
            //
            return $this->return;
        }

        //
        $this->return->nsu                = $response['ResponseDetail']['IdTransaction'];
        $this->return->valor              = (isset($response['ResponseDetail']['Amount']) ?? false) ? convertDecimalInt($response['ResponseDetail']['Amount']) : $this->return->valor;
        $this->return->pagamento_datahora = dataCarbon($response['ResponseDetail']['PaymentDateTime'] ?? false,'Y-m-d H:i:s',returnNull:true);

        // SE CARTAO
        if(isset($response['ResponseDetail']['PaymentObject']['CardNumber']))
        {
            $this->return->msg_adicional = 'Cartão ' . $response['ResponseDetail']['PaymentObject']['CardNumber'];
            $CardNumber = $response['ResponseDetail']['PaymentObject']['CardNumber'];

            //
            $this->return->pay_card_first = substr($CardNumber, 0, 4);
            $this->return->pay_card_last  = substr($CardNumber, -4);
            $this->return->pay_card_brand = $response['ResponseDetail']['PaymentObject']['Brand'] ?? null;

            //
            if(isset($this->payload['PaymentObject']['CardNumber']))
            {
                $CardNumber = $this->payload['PaymentObject']['CardNumber'];
                $Holder     = $this->payload['PaymentObject']['Holder'];
                //
                $this->return->pay_card_first = substr($CardNumber, 0, 4);
                $this->return->pay_card_last  = substr($CardNumber, -4);
                $this->return->pay_card_name  = mb_strtolower($Holder);
                $this->return->pay_card_brand = $response['ResponseDetail']['PaymentObject']['Brand'] ?? null;
            }

            //
            $this->payload['PaymentObject']['CardNumber']   = $response['ResponseDetail']['PaymentObject']['CardNumber'];
            $this->payload['PaymentObject']['SecurityCode'] = '***';
            $this->return->payload = $this->payload ?? null;
        }

        // SE PIX - V1
        if(isset($response['ResponseDetail']['QrCode']))
        {
            //
            $this->return->pay_pix_key         = $response['ResponseDetail']['Key'] ?? null;
            $this->return->pay_pix_qr_code     = $response['ResponseDetail']['Key'] ?? null;
            $this->return->pay_pix_qr_code_url = $response['ResponseDetail']['QrCode'] ?? null;
        }

        // SE PIX - V2
        if(isset($response['ResponseDetail']['PaymentObject']['QrCode']))
        {
            //
            $this->return->pay_pix_key         = $response['ResponseDetail']['PaymentObject']['Key'] ?? null;
            $this->return->pay_pix_qr_code     = $response['ResponseDetail']['PaymentObject']['Key'] ?? null;
            $this->return->pay_pix_qr_code_url = $response['ResponseDetail']['PaymentObject']['QrCode'] ?? null;
        }

        // SE BOLETO - Verifica em ResponseDetail diretamente (Safe2Pay retorna assim)
        if(isset($response['ResponseDetail']['BankSlipNumber']) || isset($response['ResponseDetail']['DigitableLine']))
        {
            $dueDate = $response['ResponseDetail']['DueDate'] ?? $this->BoletoExpirationDate ?? null;

            // Formata data de vencimento se vier no formato dd/mm/yyyy ou yyyy-mm-dd
            if ($dueDate && !str_contains($dueDate, '/')) {
                try {
                    $dueDate = \Carbon\Carbon::parse($dueDate)->format('d/m/Y');
                } catch (\Exception $e) {
                    // Mantém formato original se falhar parse
                }
            }

            $this->return->pay_boleto_number          = $response['ResponseDetail']['BankSlipNumber'] ?? null;
            $this->return->pay_boleto_barcode         = $response['ResponseDetail']['DigitableLine'] ?? null;
            $this->return->pay_boleto_url             = $response['ResponseDetail']['BankSlipUrl'] ?? null;
            $this->return->pay_boleto_expiration_date = $dueDate;

            // PIX do Boleto (alguns boletos Safe2Pay permitem pagamento via PIX também)
            if(isset($response['ResponseDetail']['KeyPix'])) {
                $this->return->pay_boleto_pix_key         = $response['ResponseDetail']['KeyPix'] ?? null;
                $this->return->pay_boleto_pix_qrcode_url  = $response['ResponseDetail']['QrCodePix'] ?? null;
            }
        }

        // SE BOLETO em PaymentObject (fallback para outros formatos)
        if(isset($response['ResponseDetail']['PaymentObject']['BankSlipNumber']))
        {
            $dueDate = $response['ResponseDetail']['PaymentObject']['DueDate'] ?? $this->BoletoExpirationDate ?? null;

            if ($dueDate && !str_contains($dueDate, '/')) {
                try {
                    $dueDate = \Carbon\Carbon::parse($dueDate)->format('d/m/Y');
                } catch (\Exception $e) {
                    // Mantém formato original
                }
            }

            $this->return->pay_boleto_number          = $this->return->pay_boleto_number ?? ($response['ResponseDetail']['PaymentObject']['BankSlipNumber'] ?? null);
            $this->return->pay_boleto_barcode         = $this->return->pay_boleto_barcode ?? ($response['ResponseDetail']['PaymentObject']['DigitableLine'] ?? null);
            $this->return->pay_boleto_url             = $this->return->pay_boleto_url ?? ($response['ResponseDetail']['PaymentObject']['BankSlipUrl'] ?? null);
            $this->return->pay_boleto_expiration_date = $this->return->pay_boleto_expiration_date ?? $dueDate;
        }

        // ERROR CODES RETORNO
        $ReturnCreditCardCodesError = ['14','05','57','78','99','70','77','79'];

        // SE ERROR - CODE RETORNO
        if(($response['ResponseDetail']['CreditCard']['ReturnCode'] ?? false) && in_array($response['ResponseDetail']['CreditCard']['ReturnCode'],$ReturnCreditCardCodesError))
        {
            //
            $this->return->error        = true;
            $this->return->code         = $response['ResponseDetail']['Status'];
            $this->return->status       = 'pagamento_erro';
            $this->return->msg          = __(mb_strtolower($response['ResponseDetail']['Message']));
            $this->return->msg_sub      = __(mb_strtolower(($response['ResponseDetail']['CreditCard']['MessageProvider'] ?? false) ?? ($response['ResponseDetail']['Description'])));
            //
            return $this->return;
        }

        // FILTRA SE ERRO 1
        if(strpos(($response['ResponseDetail']['Description'] ?? null), 'SecurityCode length exceeded') !== false)
        {
            //
            $this->return->error        = true;
            $this->return->code         = $response['ResponseDetail']['Status'];
            $this->return->status       = 'pagamento_erro';
            $this->return->msg          = "Código CVV inválido";
            $this->return->msg_sub      = __(mb_strtolower($response['ResponseDetail']['Message'] ?? $response['ResponseDetail']['Description']));
            //
            return $this->return;
        }

        // ERROR
        if(in_array($response['ResponseDetail']['Status'] ?? false,['6']) && ($response['ResponseDetail']['Message'] == 'Estornado'))
        {
            //
            $this->return->error        = true;
            $this->return->status       = 'estornado';
            $this->return->code         = $response['ErrorCode'] ?? $response['ResponseDetail']['Status'];
            $this->return->msg          = __(mb_strtolower($response['ResponseDetail']['Message']));
            $this->return->msg_sub      = ($response['ResponseDetail']['PaymentObject']['Message'] ?? false) ? mb_strtolower($response['ResponseDetail']['PaymentObject']['Message']) : null;
            //
            return $this->return;
        }

        // ERROR
        if(in_array($response['ResponseDetail']['Status'] ?? false,['6','8']))
        {
            //
            $this->return->error        = true;
            $this->return->code         = $response['ErrorCode'] ?? $response['ResponseDetail']['Status'];
            $this->return->status       = 'pagamento_erro';
            $this->return->msg          = __(mb_strtolower($response['ResponseDetail']['Message']));
            $this->return->msg_sub      = ($response['ResponseDetail']['PaymentObject']['Message'] ?? false) ? mb_strtolower($response['ResponseDetail']['PaymentObject']['Message']) : null;
            //
            return $this->return;
        }

        // PENDENTE
        if(in_array($response['ResponseDetail']['Status'] ?? false,['1']))
        {
            //
            $pagamento_data  = ($response['ResponseDetail']['PaymentDateTime'] ?? false) ? dataCarbon($response['ResponseDetail']['PaymentDateTime']) : now()->format('Y-m-d H:i:s');

            //
            $this->return->error        = false;
            $this->return->code         = $response['ResponseDetail']['Status'] ?? 200;
            $this->return->datahora     = $pagamento_data;
            $this->return->status       = 'pendente';
            $this->return->msg          = $response['ResponseDetail']['Message'] ?? 'Pagamento Pendente';
            $this->return->msg_sub      = $response['ResponseDetail']['Description'] ?? null;
            $this->return->pagamento_ok = false;
        }

        // SUCESSO CODE RETORNO
        $ReturnStatusSuccess = ['3'];

        // SE SUCESSO
        if(in_array($response['ResponseDetail']['Status'],$ReturnStatusSuccess))
        {
            // PEGA MAIS DETALHES RESPONSE
            $response = $this->consultaTransacao($this->return->nsu);

            //
            $pagamento_data  = ($response['ResponseDetail']['PaymentDateTime'] ?? false) ? dataCarbon($response['ResponseDetail']['PaymentDateTime'],'Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');
            $pagamento_forma = $this->formasPagamento($response['ResponseDetail']['PaymentMethod']) ?? ['nome' => 'ND'];

            //
            $this->return->error             = false;
            $this->return->code              = $response['ResponseDetail']['Status'] ?? 200;
            $this->return->datahora          = $pagamento_data;
            $this->return->status            = mb_strtolower($response['ResponseDetail']['Message'] ?? 'sucesso');
            $this->return->pagamento_ok      = true;
            $this->return->pagamento_forma   = mb_strtoupper($pagamento_forma['nome']);
            $this->return->pagamento_valor   = convertDecimalInt($response['ResponseDetail']['Amount'] ?? 0);
            $this->return->pagamento_taxa    = convertDecimalInt($response['ResponseDetail']['TaxValue'] ?? 0);
            $this->return->pagamento_liquido = $this->return->pagamento_valor - $this->return->pagamento_taxa;
            $this->return->msg               = 'Pagamento Realizado via ' . $pagamento_forma['nome'];
            $this->return->msg_sub           = mb_strtolower($response['ResponseDetail']['Message'] ?? null);

            // REPASSES
            if(isset($response['ResponseDetail']['CheckingAccounts'][0]))
            {
                $repasse = $response['ResponseDetail']['CheckingAccounts'][0];

                $this->return->repasse_data       = $repasse['ReleaseDate'] ? $repasse['ReleaseDate'] . ' 00:00:00' : null;
                $this->return->repasse_valor      = convertDecimalInt($repasse['Amount'] ?? 0);
                $this->return->repasse_valor_taxa = convertDecimalInt($repasse['Tax'] ?? 0);
                $this->return->repasse_parcela    = $repasse['InstallmentNumber'] ?? 0;
                $this->return->repasse_realizado  = $repasse['IsTransferred'] ?? false;
            }
        }

        return $this->return;
    }

    function formasPagamento($id=false,$slug=false)
    {
        $list = collect([
            ['id' => 6, 'slug' => 'pix', 'slug_label' => 'pix' ,'nome' => 'PIX'],
            ['id' => 6, 'slug' => 'slip_pix', 'slug_label' => 'pix' ,'nome' => 'PIX'],
            ['id' => 6, 'slug' => 'pix-recebimento', 'slug_label' => 'pix' ,'nome' => 'PIX'],
            ['id' => 2, 'slug' => 'cartao-credito', 'slug_label' => 'cartao-credito' ,'nome' => 'Cartão de Crédito'],
            ['id' => 1, 'slug' => 'boleto', 'slug_label' => 'boleto' ,'nome' => 'Boleto'],
        ]);

        if($id ?? false)
        {
            return $list->where('id',$id)->first();
        }

        if($slug ?? false)
        {
            return $list->where('slug',$slug)->first();
        }

        return $list->sortBy('nome');
    }
}
