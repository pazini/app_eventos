<?php

namespace App\Http\Livewire\Campanha;

use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrder;
use App\Models\ModCampaign\CampaignOrderAnswer;
use App\Models\ModCampaign\CampaignPayment;
use App\Models\ModCampaign\CampaignPaymentSlip;
use App\Models\ModCampaign\CampaignSubscription;
use App\Models\ModCampaign\CampaignSubscriptionCycle;
use App\Models\AppBuyers;
use App\Services\CampaignEmailService;
use App\Services\Payments\PaymentService;
use App\Services\safe2pay\Safe2PayService;
use App\Services\Campaign\CampaignPaymentService;
use Illuminate\Support\Str;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CampanhaPublica extends Component
{
    public $campaign;
    public $campaign_slug;
    public $customer_organization_slug;
    public $appUserUuid; // UUID do usuário do app quando vem de link específico
    public $appSource; // Origem do app (query appSource ou fallback via referer)

    // Dados do comprador
    public $buyer_name;
    public $buyer_email;
    public $buyer_doc_num;
    public $buyer_birth_date;
    public $buyer_contact_country = '55';
    public $buyer_contact_ddd;
    public $buyer_contact_num;
    public $amount_total;
    public $amount_total_input;

    // Endereço do comprador
    public $buyer_address_cep;
    public $buyer_address_logradouro;
    public $buyer_address_numero;
    public $buyer_address_complemento;
    public $buyer_address_bairro;
    public $buyer_address_cidade;
    public $buyer_address_estado;

    // Controle
    public $step = 1; // 1 = quiz+valor, 2 = dados, 3 = pagamento, 4 = confirmação

    // Propriedades de pagamento
    public $campaignPaymentSlip;
    public $campaignPayment;

    public function updatedStep($value)
    {
        // Quando muda para etapa 2, carrega dados do pedido se existir
        if ($value == 2) {
            // Se não tem pedido ainda, garante que is_anonymous seja false para exibir formulário
            if (!$this->order) {
                $this->is_anonymous = false;
            } else {
                // Recarrega o pedido do banco para garantir dados atualizados
                $this->order = CampaignOrder::find($this->order->id);
                $this->loadOrderData();
            }
        }
    }
    public $order;
    public $order_id;
    public $buyer_id;

    // Respostas do quiz
    public $quizAnswers = [];

    // Forma de pagamento
    public $payment_method;

    // Doação anônima
    public $is_anonymous = false;
    public $is_recurring = null;
    public $recurring_allowed = false;

    // Dados de pagamento
    public $payment; // CampaignPayment (para compatibilidade com views)
    public $payment_result = []; // Resultado do processamento (array para compatibilidade com Livewire)

    // Dados PIX
    public $pix_cpf;

    // Dados Boleto
    public $boleto_cpf;

    // Dados Cartão de Crédito
    public $card_credit_cpf;
    public $card_credit_num;
    public $card_credit_nome;
    public $card_credit_validade_mm;
    public $card_credit_validade_aaaa;
    public $card_credit_cvv;
    public $pay_installments_number = 1;
    public $available_installments = []; // Parcelas disponíveis calculadas

    protected $rules = [
        'buyer_name' => 'required|string|min:10|max:255',
        'buyer_email' => 'required|email|max:255',
        'buyer_doc_num' => 'required|cpf_cnpj',
        'buyer_birth_date' => 'required|date|before:today',
        'buyer_contact_country' => 'required|string|max:5',
        'buyer_contact_ddd' => 'nullable|string|max:3',
        'buyer_contact_num' => 'required|string|max:15',
        'amount_total' => 'required|numeric|min:0.01',
    ];

    protected $messages = [
        'buyer_name.required' => 'Nome completo é obrigatório',
        'buyer_name.min' => 'O nome completo deve ter pelo menos 10 caracteres',
        'buyer_email.required' => 'E-mail é obrigatório',
        'buyer_email.email' => 'E-mail inválido',
        'buyer_doc_num.required' => 'CPF/CNPJ é obrigatório',
        'buyer_birth_date.required' => 'Data de nascimento é obrigatória',
        'buyer_birth_date.before' => 'Data de nascimento inválida',
        'buyer_contact_num.required' => 'Telefone é obrigatório',
        'amount_total.required' => 'Informe o valor da contribuição',
        'amount_total.min' => 'Valor mínimo não atingido',
    ];

    public function mount($customer_organization_slug, $campaign_slug, $order_id = null)
    {
        $this->customer_organization_slug = $customer_organization_slug;
        $this->campaign_slug = $campaign_slug;

        // Captura appUserUuid da query string se fornecido
        $this->appUserUuid = request()->get('appUserUuid');
        $this->appSource = request()->get('appSource') ?: getAppSource();

        if ($this->appSource) {
            setAppSource($this->appSource);
        }

        // Salva na sessão se capturado
        if ($this->appUserUuid) {
            setAppUserUuid($this->appUserUuid);
            \Log::info('CampanhaPublica: appUserUuid capturado e salvo na sessão', [
                'uuid' => $this->appUserUuid,
                'campaign' => $campaign_slug,
                'customer' => $customer_organization_slug
            ]);

            $appSource = $this->resolveAppSource();
            $referer = request()->header('referer');

            // Verifica se já existe um comprador com esse UUID + SOURCE
            $existingBuyer = null;
            $foundBy = '';
            if ($appSource) {
                // Busca pela combinação source + uuid (mais específico)
                $existingBuyer = AppBuyers::where('app_source', $appSource)
                    ->where('app_user_uuid', $this->appUserUuid)
                    ->first();
                if ($existingBuyer) {
                    $foundBy = 'source+uuid';
                }
            }

            // Se não encontrou por source+uuid, busca só por uuid
            if (!$existingBuyer) {
                $existingBuyer = AppBuyers::where('app_user_uuid', $this->appUserUuid)->first();
                if ($existingBuyer) {
                    $foundBy = 'uuid_only';
                }
            }
            if ($existingBuyer) {
                // Preenche o formulário com dados existentes
                $this->buyer_name = $existingBuyer->name;
                $this->buyer_email = $existingBuyer->email;
                $this->buyer_doc_num = $existingBuyer->doc_num;
                $this->buyer_birth_date = $existingBuyer->birth_date;
                $this->buyer_contact_country = $existingBuyer->contact_country ?: '55';
                $this->buyer_contact_ddd = $existingBuyer->contact_ddd;
                $this->buyer_contact_num = $existingBuyer->contact_num;
                $this->buyer_address_cep = $existingBuyer->zip_code;
                $this->buyer_address_logradouro = $existingBuyer->address;
                $this->buyer_address_numero = $existingBuyer->address_number;
                $this->buyer_address_complemento = $existingBuyer->address_complement;
                $this->buyer_address_bairro = $existingBuyer->city_neighborhood;
                $this->buyer_address_cidade = $existingBuyer->city;
                $this->buyer_address_estado = $existingBuyer->state;
                $this->buyer_id = $existingBuyer->id;

                \Log::info('CampanhaPublica: dados do comprador preenchidos automaticamente', [
                    'uuid' => $this->appUserUuid,
                    'buyer_id' => $existingBuyer->id,
                    'name' => $existingBuyer->name,
                    'app_source' => $appSource,
                    'buyer_app_source' => $existingBuyer->app_source,
                    'found_by' => $foundBy,
                    'referer_original' => $referer
                ]);
            }
        }

        $redirect = $this->loadCampaign($customer_organization_slug, $campaign_slug, $order_id);
        if ($redirect) {
            return $redirect;
        }

        // Garante que buyer_contact_country seja sempre string
        $this->buyer_contact_country = (string) ($this->buyer_contact_country ?? '55');

        // Se há order_id, carrega o pedido existente
        if ($order_id ?? false) {
            if (!Str::isUuid((string) $order_id)) {
                session()->flash('error', 'A doação que está tentando acessar está incorreta ou é inexistente');
                return redirect()->route('campanha-publica', array_merge([
                    'customer_organization_slug' => $customer_organization_slug,
                    'campaign_slug' => $campaign_slug,
                ], request()->query()));
            }

            $this->order_id = $order_id;

            $this->loadOrder($order_id);

            if (!$this->order) {
                session()->flash('error', 'A doação que está tentando acessar está incorreta ou é inexistente');
                return redirect()->route('campanha-publica', array_merge([
                    'customer_organization_slug' => $customer_organization_slug,
                    'campaign_slug' => $campaign_slug,
                ], request()->query()));
            }

        } else {
            $this->amount_total = null;
            $this->amount_total_input = $this->formatCurrencyValue($this->amount_total);

            // Inicializa arrays vazios para perguntas do tipo checkbox
            foreach ($this->campaign->questions as $question) {
                if ($question->question_type === 'checkbox') {
                    $this->quizAnswers[$question->id] = [];
                }
            }
        }

        // Garante novamente após loadOrder (caso tenha sido alterado)
        $this->buyer_contact_country = (string) ($this->buyer_contact_country ?? '55');
    }

    protected function loadCampaign($customer_organization_slug, $campaign_slug, $order_id = null)
    {
        $allowedStatuses = ['active', 'active_direct', 'paused', 'draft', 'finished'];

        $campaign = Campaign::where('customer_organization_slug', $customer_organization_slug)
            ->where('slug', $campaign_slug)
            ->where('visibility_public', true)
            ->whereIn('status', $allowedStatuses)
            ->with(['customer', 'organization', 'organizer', 'gateway', 'questions'])
            ->first();

        // Compatibilidade com links legados (quando a URL antiga usava slug de filial/nome)
        if (!$campaign) {
            $legacyCandidates = Campaign::where('slug', $campaign_slug)
                ->where('visibility_public', true)
                ->whereIn('status', $allowedStatuses)
                ->with(['customer', 'organization', 'organizer', 'gateway', 'questions'])
                ->get();

            $campaign = $legacyCandidates->first(function ($item) use ($customer_organization_slug) {
                $legacySlugs = collect([
                    $item->customer_organization_slug,
                    $item->organizer?->organizer_slug,
                    $item->organizer?->organizer_name,
                    $item->organization?->organization_slug,
                    $item->organization?->organization_name,
                ])
                    ->filter()
                    ->map(fn($value) => Str::slug($value))
                    ->unique();

                return $legacySlugs->contains(Str::slug($customer_organization_slug));
            });

            if ($campaign && ($campaign->organizer?->organizer_slug ?? false)) {
                $canonicalSlug = $campaign->organizer?->organizer_slug;

                if (Str::slug($canonicalSlug) !== Str::slug($customer_organization_slug)) {
                    $routeParams = [
                        'customer_organization_slug' => $canonicalSlug,
                        'campaign_slug' => $campaign_slug,
                    ];

                    if ($order_id) {
                        $routeParams['order_id'] = $order_id;
                    }

                    return redirect()->route('campanha-publica', array_merge($routeParams, request()->query()));
                }
            }
        }

        if (!$campaign) {
            session()->flash('error', 'A campanha que está tentando acessar está incorreta ou é inexistente');
            return redirect()->route('campanhas-home');
        }

        $this->campaign = $campaign;
        $this->recurring_allowed = $this->canUseRecurring();

        return null;
    }

    protected function loadOrder($order_id)
    {
        $this->order = CampaignOrder::with(['answers.question', 'paymentSlips.payment', 'currentPaymentSlip.payment'])
            ->where('id', $order_id)
            ->where('campaign_id', $this->campaign->id)
            ->first();

        if (!$this->order) {
            // Não deveria chegar aqui devido à validação no mount
            return;
        }

        // Restaura dados do pedido (sempre, mesmo se anônimo, para permitir edição)
        $this->buyer_name = $this->order->buyer_name === 'Anônimo' ? '' : ($this->order->buyer_name ?? '');
        $this->buyer_email = $this->order->buyer_email ?? '';
        $this->buyer_doc_num = $this->order->buyer_doc_num ?? '';
        $orderContactCountry = trim((string) ($this->order->buyer_contact_country ?? ''));
        $this->buyer_contact_country = $orderContactCountry !== '' ? $orderContactCountry : '55';
        $this->buyer_contact_ddd = $this->order->buyer_contact_ddd ?? '';
        $this->buyer_contact_num = $this->order->buyer_contact_num ?? '';
        $this->amount_total = $this->order->amount_total;
        $this->amount_total_input = $this->formatCurrencyValue($this->amount_total);
        $this->buyer_id = $this->order->buyer_id;
        $this->is_anonymous = $this->order->is_anonymous ?? false;
        $this->is_recurring = $this->order->is_recurring === null
            ? null
            : (bool) $this->order->is_recurring;

        // Restaura buyer_contact_country e birth_date do buyer se existir
        if ($this->order->buyer_id) {
            $buyer = AppBuyers::find($this->order->buyer_id);
            if ($buyer ?? false) {
                // Se não houver país no pedido, usa do buyer como fallback
                if ($orderContactCountry === '' && !empty($buyer->contact_country)) {
                    $this->buyer_contact_country = (string) $buyer->contact_country;
                }
                $this->buyer_birth_date = $buyer->birth_date ?? '';
            } else {
                $this->buyer_contact_country = $orderContactCountry !== '' ? $orderContactCountry : '55';
                $this->buyer_birth_date = '';
            }
        } else {
            $this->buyer_contact_country = $orderContactCountry !== '' ? $orderContactCountry : '55';
            $this->buyer_birth_date = '';
        }

        // Restaura respostas do quiz
        foreach ($this->order->answers as $answer) {
            $answerValue = $answer->answer_value;
            // Se for JSON (checkbox), decodifica
            $decoded = json_decode($answerValue, true);
            $this->quizAnswers[$answer->campaign_question_id] = is_array($decoded) ? $decoded : $answerValue;
        }

        // Define etapa baseado no status do pedido
        // Verifica se há pagamento com erro para manter na tela de pagamento
        // Busca o último payment slip (pode ser que current_payment_slip_id esteja null)

        $lastPaymentSlip = $this->order->currentPaymentSlip ?? $this->order->paymentSlips()->first();
        $lastPayment = $lastPaymentSlip?->payment;
        $this->campaignPaymentSlip = $lastPaymentSlip;
        $this->campaignPayment = $lastPayment;

        $hasPaymentError = $lastPayment && $lastPayment->status === 'error';

        // Verifica se há PIX expirado independente do status
        if (
            $lastPayment &&
            in_array($lastPayment->pay_type, ['pix', 'slip_pix']) &&
            ($lastPayment->pay_pix_expires_at ?? false) &&
            \Carbon\Carbon::parse($lastPayment->pay_pix_expires_at)->isPast() &&
            ($lastPayment->status !== 'paid')
        ) {

            // PIX expirado - atualiza status e limpa dados no banco (PIX e BOLETO)
            $lastPayment->update([
                'status' => 'pix_expired',
                'pay_pix_qr_code_url' => null,
                'pay_pix_key' => null,
                'pay_pix_qr_code' => null,
                // Limpa também os dados do boleto quando PIX expirar
                'pay_boleto_barcode' => null,
                'pay_boleto_url' => null,
                'pay_boleto_expiration_date' => null,
            ]);

            // IMPORTANTE: Recarrega o payment após update para refletir mudanças
            $lastPayment->refresh();
            $this->campaignPayment = $lastPayment;

            // Limpa também a variável $payment_result para remover dados em memória
            $this->payment_result = [];

            // Mantém o payment carregado mas força payment_method como pix para permitir novo PIX
            $this->payment_method = 'pix';

            // Exibe alerta de PIX expirado
            session()->flash('forma_pagamento_info', 'PIX Expirado!');
            session()->flash('forma_pagamento_info_sub', 'O prazo para pagamento do PIX expirou. Gere um novo PIX para concluir sua doação');
        }


        // Define payment_method baseado no último pagamento com dados gerados (ANTES de definir step)
        $hasPendingPayment = false;
        if ($lastPayment && in_array($lastPayment->status, ['pending', 'processing', 'autorizado', 'sending_provider'])) {
            $payType = $lastPayment->pay_type;

            // Verifica se há dados de PIX gerados e válidos
            if (
                ($payType === 'pix' || $payType === 'slip_pix') &&
                ($lastPayment->pay_pix_qr_code_url || $lastPayment->pay_pix_key)
            ) {
                $this->payment_method = 'pix';
                $hasPendingPayment = true;
            }
            // Verifica se há dados de Boleto gerados
            elseif (
                ($payType === 'boleto' || $payType === 'slip') &&
                ($lastPayment->pay_boleto_barcode || $lastPayment->pay_boleto_url)
            ) {
                $this->payment_method = 'boleto';
                $hasPendingPayment = true;
            }
            // Cartão de crédito
            elseif ($payType === 'card_credit') {
                $this->payment_method = 'credit_card';
            }
        }

        if ($this->order->status === 'pending' || $this->order->status === 'pay-error' || $hasPaymentError || $hasPendingPayment) {
            // Se está pendente, teve erro ou tem pagamento aguardando, vai para tela de pagamento
            $this->step = 3;

            // Calcula parcelas disponíveis se for cartão de crédito
            if ($this->payment_method === 'credit_card') {
                $this->calculateAvailableInstallments();
            }

            // Carrega o último pagamento (pode ter erro) para exibir informações
            $this->payment = $lastPayment;
        } elseif ($this->order->status === 'paid') {
            // Pedido já foi pago - vai para confirmação
            $this->step = 4;

            // Carrega o pagamento associado ao pedido
            $this->campaignPaymentSlip = $this->order->currentPaymentSlip;
            $this->campaignPayment = $this->campaignPaymentSlip?->payment;
            $this->payment = $this->campaignPayment;
        } else {
            // Outros status - vai para confirmação
            $this->step = 4;

            // Carrega o pagamento associado ao pedido
            $this->payment = $lastPayment;
        }
    }

    public function setAmount($value)
    {
        // Converte reais para centavos (multiplica por 100)
        $this->amount_total = (int) ($value * 100);
        $this->amount_total_input = $this->formatCurrencyValue($this->amount_total);

        // Se já existe pedido, atualiza o valor
        if ($this->order) {
            $this->order->update(['amount_total' => $this->amount_total]);
        }

        // Recalcula parcelas se for cartão de crédito
        if ($this->payment_method === 'credit_card') {
            $this->calculateAvailableInstallments();
        }
    }

    public function updatedAmountTotalInput($value)
    {
        // Parse do valor formatado
        $parsedValue = $this->parseCurrencyInput($value);
        $this->amount_total = $parsedValue;
        $this->amount_total_input = $this->formatCurrencyValue($this->amount_total);

        // Se já existe pedido, atualiza o valor imediatamente
        if ($this->order && $this->amount_total) {
            $this->order->update(['amount_total' => $this->amount_total]);
        }

        // Recalcula parcelas se for cartão de crédito
        if ($this->payment_method === 'credit_card') {
            $this->calculateAvailableInstallments();
        }

        // O Livewire limpa os erros automaticamente quando a validação passa
        // Não é necessário limpar manualmente
    }

    public function updatedBuyerContactCountry($value)
    {
        $normalizedCountry = $this->sanitizeContactCountry($value);
        $this->buyer_contact_country = $normalizedCountry ?? '55';

        // Limpa DDD se não for Brasil (55)
        if ($this->buyer_contact_country !== '55') {
            $this->buyer_contact_ddd = null;
        }
    }

    public function updatedBuyerContactDdd($value)
    {
        // Durante digitação: apenas remove não numéricos, não valida range
        $this->buyer_contact_ddd = $this->numericOnly($value, 2);
    }

    public function updatedBuyerContactNum($value)
    {
        // Durante digitação: permite até 15 dígitos para internacionais
        $maxLength = ($this->buyer_contact_country === '55') ? 9 : 15;
        $this->buyer_contact_num = $this->numericOnly($value, $maxLength);
    }

    public function updatedBuyerEmail($value)
    {
        $this->buyer_email = strtolower(trim($value));
    }

    public function updatedBuyerDocNum($value)
    {
        $this->buyer_doc_num = $this->numericOnly($value, 20);
    }

    public function updatedIsAnonymous($value)
    {
        if ($value) {
            $this->is_recurring = false;
        }
    }

    public function updatedPaymentMethod($value)
    {
        if ($value === 'credit_card') {
            // Limpa dados do cartão
            // $this->card_credit_nome          = null;
            // $this->card_credit_num           = null;
            // $this->card_credit_cpf           = null;
            // $this->card_credit_validade_mm   = null;
            // $this->card_credit_validade_aaaa = null;
            // $this->card_credit_cvv           = null;
            // $this->pay_installments_number   = 1;

            // Calcula parcelas disponíveis imediatamente
            // Usa $value em vez de $this->payment_method porque pode ainda não estar atualizado
            $this->calculateAvailableInstallmentsForMethod('credit_card');

            // Garantir que seja pelo menos 1 para cartão também
            if (empty($this->pay_installments_number) || $this->pay_installments_number < 1) {
                $this->pay_installments_number = 1;
            }
        } else {
            $this->is_recurring = false;

            // PIX e Boleto são sempre à vista (1 parcela) - força o valor
            $this->available_installments = [];
            $this->pay_installments_number = 1;

            // Limpa dados do cartão se houver
            $this->card_credit_nome = null;
            $this->card_credit_num = null;
            $this->card_credit_cpf = null;
            $this->card_credit_validade_mm = null;
            $this->card_credit_validade_aaaa = null;
            $this->card_credit_cvv = null;
        }
    }

    public function updatedIsRecurring($value)
    {
        if (!$value) {
            $this->calculateAvailableInstallmentsForMethod('credit_card');
            return;
        }

        if (!$this->canUseRecurring()) {
            $this->is_recurring = false;
            return;
        }

        $this->payment_method = 'credit_card';
        $this->pay_installments_number = 1;
        $this->available_installments = [
            [
                'installments' => 1,
                'installment_value' => $this->amount_total ?? 0,
                'label' => '1x - À vista',
            ]
        ];
    }

    /**
     * Calcula parcelas para um método específico (permite forçar cálculo)
     */
    protected function calculateAvailableInstallmentsForMethod($method = null)
    {
        $method = $method ?? $this->payment_method;

        if ($method !== 'credit_card') {
            $this->available_installments = [];
            return;
        }

        $this->calculateAvailableInstallments(true);
    }

    /**
     * Calcula as parcelas disponíveis baseado no valor mínimo e juros
     */
    public function calculateAvailableInstallments($force = false)
    {
        $this->available_installments = [];

        if ($this->is_recurring) {
            $this->pay_installments_number = 1;
            $this->available_installments = [
                [
                    'installments' => 1,
                    'installment_value' => $this->amount_total ?? 0,
                    'label' => '1x - À vista',
                ]
            ];
            return;
        }

        // Só calcula se for cartão de crédito e tiver valor
        if (!$force && $this->payment_method !== 'credit_card') {
            return;
        }

        if (!$this->amount_total || $this->amount_total <= 0) {
            return;
        }

        $gatewayPay = $this->campaign->gateway;
        if (!$gatewayPay) {
            return;
        }

        // Configurações
        $installmentMax = $this->campaign->pay_card_credit_installment_max ?? $gatewayPay->pay_card_credit_installment_max ?? 1;
        $minInstallmentAmount = $this->campaign->pay_card_credit_installment_amount_min ?? $gatewayPay->pay_card_credit_installment_amount_min ?? 0;
        $feePayer = $this->campaign->pay_card_credit_installment_fee_payer ?? 'campaign';
        $taxas = json_decode($gatewayPay->pay_gateway_installment_fees_json ?? '{}', true);

        $amountInCents = $this->amount_total; // Já está em centavos

        // Sempre inclui 1x (sem juros)
        $this->available_installments[1] = [
            'installments' => 1,
            'total_amount' => $amountInCents,
            'installment_value' => $amountInCents,
            'fees' => 0,
            'fee_percentage' => 0,
            'label' => '1x de ' . toMoney($amountInCents, 'R$ '),
        ];

        // Calcula outras parcelas
        for ($i = 2; $i <= $installmentMax; $i++) {
            $calculatedAmount = $amountInCents;
            $installmentFeePercentage = 0;

            // Se cliente paga juros, calcula taxa
            if ($feePayer === 'customer' && isset($taxas[$i])) {
                $installmentFeePercentage = (float) $taxas[$i] ?? 0;

                if ($installmentFeePercentage > 0) {
                    // Calcula valor total com juros
                    $calculatedAmount = (int) round($amountInCents / (1 - ($installmentFeePercentage / 100)));
                }
            }

            // Calcula valor da parcela
            $installmentValue = (int) round($calculatedAmount / $i);

            // Verifica se atende valor mínimo
            if ($installmentValue >= $minInstallmentAmount) {
                $fees = $calculatedAmount - $amountInCents;

                // Monta label com informações detalhadas
                if ($feePayer === 'customer' && $installmentFeePercentage > 0) {
                    $label = $i . 'x de ' . toMoney($installmentValue, 'R$ ') . ' (com juros)';
                } else {
                    $label = $i . 'x de ' . toMoney($installmentValue, 'R$ ');
                }

                $this->available_installments[$i] = [
                    'installments' => $i,
                    'total_amount' => $calculatedAmount,
                    'installment_value' => $installmentValue,
                    'fees' => $fees,
                    'fee_percentage' => $installmentFeePercentage,
                    'label' => $label,
                ];
            }
        }

        // Se não há parcelas disponíveis além de 1x, reseta para 1x
        if (count($this->available_installments) === 1 && $this->pay_installments_number > 1) {
            $this->pay_installments_number = 1;
        }

        // Garante que o valor das parcelas nunca seja vazio ou zero
        if (empty($this->pay_installments_number) || $this->pay_installments_number < 1) {
            $this->pay_installments_number = 1;
        }

        // Reindexa o array mantendo a estrutura correta para o foreach
        $reindexed = [];
        foreach ($this->available_installments as $installment) {
            $reindexed[] = $installment;
        }
        $this->available_installments = $reindexed;
    }

    protected function canUseRecurring(): bool
    {
        $gateway = $this->campaign->gateway ?? null;

        if (!$this->campaign || !$this->campaign->allow_recurring) {
            return false;
        }

        if (!$this->campaign->pay_card_credit) {
            return false;
        }

        $gatewaySlug = $gateway->pay_gateway_slug ?? '';

        if (!$gateway || $gatewaySlug === '' || !Str::contains($gatewaySlug, 'safe2pay')) {
            return false;
        }

        return true;
    }

    private function numericOnly($value, $limit = null)
    {
        $clean = preg_replace('/\D/', '', $value ?? '');

        if ($limit) {
            return substr($clean, 0, $limit);
        }

        return $clean;
    }

    private function parseCurrencyInput($value)
    {
        $digits = preg_replace('/[^0-9]/', '', $value ?? '');

        if ($digits === '') {
            return null;
        }

        // Retorna em centavos (inteiro)
        // O valor formatado já está sem ponto/vírgula, só precisa retornar como inteiro
        return (int) $digits;
    }

    private function formatCurrencyValue($value)
    {
        if ($value === null || $value === 0) {
            return '';
        }

        // Converte de centavos para reais e formata
        return number_format((int) $value / 100, 2, ',', '');
    }

    private function detectDocType($docNum)
    {
        // Remove caracteres não numéricos
        $cleanDoc = preg_replace('/[^0-9]/', '', $docNum);

        if (strlen($cleanDoc) == 11) {
            return 'CPF';
        } elseif (strlen($cleanDoc) == 14) {
            return 'CNPJ';
        }

        return 'OTHER';
    }

    private function sanitizeContactDdd($ddd)
    {
        if (!$ddd)
            return null;

        // Remove caracteres não numéricos
        $cleanDdd = preg_replace('/[^0-9]/', '', $ddd);

        if (empty($cleanDdd))
            return null;

        // Converte para inteiro
        $dddInt = (int) $cleanDdd;

        // Para Brasil, valida DDD (11-99), para outros países aceita qualquer valor
        if ($this->buyer_contact_country === '55') {
            if ($dddInt < 11 || $dddInt > 99) {
                return null; // DDD inválido para Brasil
            }
        }

        return $dddInt;
    }

    private function sanitizeContactCountry($country): ?string
    {
        $cleanCountry = preg_replace('/[^0-9]/', '', (string) $country);

        if ($cleanCountry === '') {
            return null;
        }

        return substr($cleanCountry, 0, 5);
    }

    private function sanitizeContactNum($num, $country = null)
    {
        if (!$num)
            return null;

        // Remove caracteres não numéricos
        $cleanNum = preg_replace('/[^0-9]/', '', $num);
        $numLength = strlen($cleanNum);

        if (empty($cleanNum))
            return null;

        // Usa o país atual se não especificado
        $contactCountry = $country ?? $this->buyer_contact_country ?? '55';

        if ($contactCountry === '55') {
            // Brasil: aceita 7-9 dígitos (mais permissivo)
            if ($numLength < 7 || $numLength > 9) {
                return null; // Número inválido para Brasil
            }
        } else {
            // Internacional: aceita 4-15 dígitos
            if ($numLength < 4 || $numLength > 15) {
                return null; // Número inválido para internacional
            }
        }

        // Verifica se cabe em integer
        $numInt = (int) $cleanNum;
        if ($numInt > 2147483647) {
            return null; // Muito grande para integer - não pode salvar na estrutura atual
        }

        return $numInt;
    }

    private function resolveAppSource(): ?string
    {
        $appSourceFromQuery = request()->get('appSource');
        if (is_string($appSourceFromQuery) && trim($appSourceFromQuery) !== '') {
            $this->appSource = trim($appSourceFromQuery);
            setAppSource($this->appSource);

            return $this->appSource;
        }

        if (is_string($this->appSource) && trim($this->appSource) !== '') {
            return trim($this->appSource);
        }

        $appSourceFromSession = getAppSource();
        if (is_string($appSourceFromSession) && trim($appSourceFromSession) !== '') {
            $this->appSource = trim($appSourceFromSession);

            return $this->appSource;
        }

        return $this->getAppSourceFromReferer();
    }

    private function getAppSourceFromReferer(): ?string
    {
        $referer = request()->header('referer');
        if (!$referer) {
            return null;
        }

        $parsed = parse_url($referer);
        if (!$parsed || !isset($parsed['scheme']) || !isset($parsed['host'])) {
            return null;
        }

        return $parsed['scheme'] . '://' . $parsed['host'] . '/';
    }

    private function saveOrUpdateBuyer()
    {
        $docType = $this->detectDocType($this->buyer_doc_num);
        $cleanDocNum = preg_replace('/[^0-9]/', '', $this->buyer_doc_num);

        // Prioriza appSource de query/session; referer é fallback.
        $appSource = $this->resolveAppSource();

        // SIMPLES: Se tem buyer_id, busca por ID. Senão busca por UUID+source ou documento
        $buyer = null;
        $currentUuid = $this->appUserUuid ?: getAppUserUuid();

        if ($this->buyer_id) {
            $buyer = AppBuyers::find($this->buyer_id);
        } elseif ($currentUuid && $appSource) {
            $buyer = AppBuyers::where('app_source', $appSource)
                ->where('app_user_uuid', $currentUuid)
                ->first();
        } elseif ($currentUuid) {
            $buyer = AppBuyers::where('app_user_uuid', $currentUuid)->first();
        } else {
            $buyer = AppBuyers::where('doc_num', $cleanDocNum)->first();
        }

        // LIMPEZA SIMPLES - sem validação complicada
        $cleanDdd = empty($this->buyer_contact_ddd) ? null : (int) preg_replace('/[^0-9]/', '', $this->buyer_contact_ddd);
        $cleanNum = empty($this->buyer_contact_num) ? null : (int) preg_replace('/[^0-9]/', '', $this->buyer_contact_num);

        $buyerData = [
            'doc_type' => $docType,
            'doc_num' => $cleanDocNum,
            'name' => $this->buyer_name,
            'email' => strtolower(trim($this->buyer_email)),
            'birth_date' => $this->buyer_birth_date,
            'contact_country' => $this->sanitizeContactCountry($this->buyer_contact_country) ?? '55',
            'contact_ddd' => $cleanDdd,
            'contact_num' => $cleanNum,
            'address' => $this->buyer_address_logradouro,
            'address_number' => $this->buyer_address_numero,
            'address_complement' => $this->buyer_address_complemento,
            'address_reference' => null,
            'city_neighborhood' => $this->buyer_address_bairro,
            'city' => $this->buyer_address_cidade,
            'state' => $this->buyer_address_estado,
            'country' => 'BRA',
            'zip_code' => $this->buyer_address_cep ? preg_replace('/[^0-9]/', '', $this->buyer_address_cep) : null,
            'app_user_uuid' => $currentUuid,
            'app_source' => $appSource,
        ];

        if ($buyer) {
            // ATUALIZA
            $buyer->update($buyerData);
            $this->buyer_id = $buyer->id;
            \Log::info('CampanhaPublica: ATUALIZOU buyer', ['buyer_id' => $buyer->id]);
        } else {
            // CRIA
            $buyer = AppBuyers::create($buyerData);
            $this->buyer_id = $buyer->id;
            \Log::info('CampanhaPublica: CRIOU buyer', ['buyer_id' => $buyer->id]);
        }

        return $buyer;
    }

    protected function createOrderForPayment()
    {
        // Converte o valor formatado para centavos
        if ($this->amount_total_input) {
            $this->amount_total = $this->parseCurrencyInput($this->amount_total_input);
        }


        if ($this->amount_total === null) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount_total' => 'Informe o valor da contribuição'
            ]);
        }

        // Valida valor mínimo (amount_min já está em centavos)
        $minAmount = $this->campaign->amount_min ?? 1000;
        // Usa comparação com tolerância para evitar problemas de ponto flutuante
        if ($this->amount_total === null || ($this->amount_total < $minAmount)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount_total' => 'O valor mínimo para contribuição é de R$ ' . toMoney($minAmount)
            ]);
        }

        // Salva ou atualiza o comprador (apenas se não for anônimo)
        $buyer = null;
        if (!$this->is_anonymous) {
            $buyer = $this->saveOrUpdateBuyer();
        }

        // Se já existe um pedido, atualiza ao invés de criar
        if ($this->order) {
            $contactCountry = $this->is_anonymous
                ? null
                : ($this->sanitizeContactCountry($this->buyer_contact_country) ?? '55');

            // Atualiza dados do pedido
            $this->order->update([
                'buyer_id' => $buyer->id ?? $this->order->buyer_id,
                'buyer_name' => $this->is_anonymous ? 'Anônimo' : $this->buyer_name,
                'buyer_email' => $this->is_anonymous ? null : strtolower(trim($this->buyer_email)),
                'buyer_doc_num' => $this->is_anonymous ? null : ($buyer->doc_num ?? $this->order->buyer_doc_num),
                'buyer_contact_country' => $contactCountry,
                'buyer_contact_ddd' => $this->is_anonymous ? null : $this->sanitizeContactDdd($this->buyer_contact_ddd),
                'buyer_contact_num' => $this->is_anonymous ? null : $this->sanitizeContactNum($this->buyer_contact_num),
                'amount_total' => $this->amount_total,
                'is_anonymous' => $this->is_anonymous,
                'is_recurring' => $this->is_recurring,
            ]);

            // Atualiza respostas do quiz se necessário
            if ($this->campaign->enable_questions) {
                // Remove respostas antigas
                CampaignOrderAnswer::where('campaign_order_id', $this->order->id)->delete();

                // Salva novas respostas
                foreach ($this->campaign->questions as $question) {
                    $questionId = $question->id;
                    $answer = $this->quizAnswers[$questionId] ?? null;

                    $isEmpty = false;
                    if ($answer === null || $answer === '') {
                        $isEmpty = true;
                    } elseif (is_array($answer)) {
                        $filtered = array_filter($answer, function ($item) {
                            return !empty($item);
                        });
                        $isEmpty = empty($filtered);
                    }

                    if ($isEmpty) {
                        if (!$question->is_required) {
                            CampaignOrderAnswer::create([
                                'campaign_order_id' => $this->order->id,
                                'campaign_question_id' => $questionId,
                                'answer_value' => '--',
                            ]);
                        }
                        continue;
                    }

                    $answerValue = is_array($answer) ? json_encode($answer) : $answer;

                    CampaignOrderAnswer::create([
                        'campaign_order_id' => $this->order->id,
                        'campaign_question_id' => $questionId,
                        'answer_value' => $answerValue,
                    ]);
                }
            }

            return;
        }

        // Obtém informações de rastreamento
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();
        $referer = request()->header('referer');

        // Cria o pedido
        $contactCountry = $this->is_anonymous
            ? null
            : ($this->sanitizeContactCountry($this->buyer_contact_country) ?? '55');

        $this->order = CampaignOrder::create([
            'campaign_id' => $this->campaign->id,
            'buyer_id' => $buyer->id ?? null,
            'order_control' => 'CMP' . Str::upper(Str::random(8)),
            'buyer_name' => $this->is_anonymous ? 'Anônimo' : $this->buyer_name,
            'buyer_email' => $this->is_anonymous ? null : strtolower(trim($this->buyer_email)),
            'buyer_doc_num' => $this->is_anonymous ? null : ($buyer->doc_num ?? null),
            'buyer_contact_country' => $contactCountry,
            'buyer_contact_ddd' => $this->is_anonymous ? null : $this->sanitizeContactDdd($this->buyer_contact_ddd),
            'buyer_contact_num' => $this->is_anonymous ? null : $this->sanitizeContactNum($this->buyer_contact_num),
            'amount_total' => $this->amount_total,
            'amount_paid' => 0,
            'status' => 'pending',
            'is_anonymous' => $this->is_anonymous,
            'is_recurring' => $this->is_recurring,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'referer' => $referer,
        ]);

        // Salva respostas do quiz (apenas se enable_questions estiver ativo)
        if ($this->campaign->enable_questions) {
            // Itera sobre todas as perguntas da campanha para garantir que todas sejam salvas
            foreach ($this->campaign->questions as $question) {
                $questionId = $question->id;
                $answer = $this->quizAnswers[$questionId] ?? null;

                // Verifica se a resposta está vazia
                $isEmpty = false;
                if ($answer === null || $answer === '') {
                    $isEmpty = true;
                } elseif (is_array($answer)) {
                    // Para arrays (checkboxes), verifica se está vazio ou contém apenas valores vazios
                    $filtered = array_filter($answer, function ($item) {
                        return !empty($item);
                    });
                    $isEmpty = empty($filtered);
                }

                // Se não há resposta
                if ($isEmpty) {
                    // Se a pergunta é opcional, salva "--" como valor padrão
                    if (!$question->is_required) {
                        CampaignOrderAnswer::create([
                            'campaign_order_id' => $this->order->id,
                            'campaign_question_id' => $questionId,
                            'answer_value' => '--',
                        ]);
                    }
                    // Se for obrigatória e não respondida, não salva (validação deve ter capturado)
                    continue;
                }

                // Processa e salva a resposta
                $answerValue = is_array($answer) ? json_encode($answer) : $answer;

                CampaignOrderAnswer::create([
                    'campaign_order_id' => $this->order->id,
                    'campaign_question_id' => $questionId,
                    'answer_value' => $answerValue,
                ]);
            }
        }

        // Dispara evento para atualizar URL quando pedido for criado
        $this->dispatchBrowserEvent('order-created', ['orderId' => $this->order->id]);
    }

    protected function ensureRecurringSubscription(): void
    {
        if (!$this->order || $this->order->subscription_id) {
            return;
        }

        $buyer = $this->order->buyer_id ? AppBuyers::find($this->order->buyer_id) : null;
        if (!$buyer) {
            throw new \Exception('Comprador não encontrado para recorrência.');
        }

        $billingDate = now();

        $subscription = CampaignSubscription::create([
            'campaign_id' => $this->campaign->id,
            'customer_id' => $this->campaign->customer_id,
            'buyer_id' => $buyer->id,
            'amount_total' => $this->amount_total,
            'status' => 'active',
            'current_cycle' => 0,
            'next_charge_at' => $billingDate->copy()->addMonthNoOverflow(),
        ]);

        $cycle = CampaignSubscriptionCycle::create([
            'subscription_id' => $subscription->id,
            'cycle_number' => 1,
            'billing_date' => $billingDate->toDateString(),
            'status' => 'pending',
            'campaign_order_id' => $this->order->id,
            'next_attempt_at' => $billingDate,
            'attempts_count' => 0,
        ]);

        $this->order->update([
            'subscription_id' => $subscription->id,
            'subscription_cycle_id' => $cycle->id,
            'is_recurring' => true,
        ]);
    }

    public function processarPagamento()
    {
        // Verifica se a campanha está ativa
        if (!in_array($this->campaign->status, ['active', 'active_direct'])) {
            session()->flash('error', 'Esta campanha não está aceitando pedidos no momento');
            return;
        }

        // Valida forma de pagamento
        $availableMethods = [];
        if ($this->campaign->pay_pix)
            $availableMethods[] = 'pix';
        if ($this->campaign->pay_boleto)
            $availableMethods[] = 'boleto';
        if ($this->campaign->pay_card_credit)
            $availableMethods[] = 'credit_card';


        if (empty($availableMethods)) {
            session()->flash('error', 'Nenhuma forma de pagamento disponível para esta campanha');
            return;
        }

        if (empty($this->payment_method) || !in_array($this->payment_method, $availableMethods)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'payment_method' => 'Selecione uma forma de pagamento'
            ]);
        }

        // Se há payment expirado, reseta para permitir gerar novo
        if ($this->campaignPayment && $this->campaignPayment->status === 'pix_expired') {
            $this->campaignPayment = null;
            $this->campaignPaymentSlip = null;
            session()->forget(['forma_pagamento_info', 'forma_pagamento_info_sub']);
        }

        // Garante que pay_installments_number seja sempre pelo menos 1
        if (empty($this->pay_installments_number) || $this->pay_installments_number < 1) {
            $this->pay_installments_number = 1;
        }

        if ($this->is_recurring) {
            if (!$this->canUseRecurring()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_method' => 'Recorrência não disponível para esta campanha',
                ]);
            }

            if ($this->is_anonymous) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_method' => 'Doações recorrentes não estão disponíveis para contribuições anônimas',
                ]);
            }

            if ($this->payment_method !== 'credit_card') {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'payment_method' => 'Recorrência mensal está disponível apenas no cartão de crédito',
                ]);
            }

            $this->pay_installments_number = 1;
        }

        // Valida campos específicos conforme forma de pagamento
        if ($this->payment_method === 'pix') {
            $this->validate([
                'pix_cpf' => ['required', 'cpf_cnpj'],
            ], [
                'pix_cpf.required' => 'É obrigatório para pagamento PIX',
                'pix_cpf.cpf_cnpj' => 'Número inválido',
            ]);
        }

        // Valida campos específicos conforme forma de pagamento
        if ($this->payment_method === 'boleto') {
            $this->validate([
                'boleto_cpf' => ['required', 'cpf_cnpj'],
            ], [
                'boleto_cpf.required' => 'É obrigatório para pagamento em boleto',
                'boleto_cpf.cpf_cnpj' => 'Número inválido',
            ]);
        }

        if ($this->payment_method === 'credit_card') {

            $this->validate([
                'card_credit_nome' => ['required', 'string', 'min:3'],
                'card_credit_num' => ['required', 'string', 'min:13', 'max:19'],
                'card_credit_cpf' => ['required', 'cpf_cnpj'],
                'card_credit_validade_mm' => ['required', 'string', 'size:2'],
                'card_credit_validade_aaaa' => ['required', 'string', 'size:4'],
                'card_credit_cvv' => ['required', 'string', 'min:3', 'max:4'],
                'pay_installments_number' => ['required', 'integer', 'min:1', 'max:' . ($this->is_recurring ? 1 : ($this->campaign->pay_card_credit_installment_max ?? 1))],
            ], [
                'card_credit_num.required' => 'Número do cartão é obrigatório',
                'card_credit_num.min' => 'Número do cartão deve ter mais de 13 dígitos',
                'card_credit_num.max' => 'Número do cartão deve ter menos de 19 dígitos',
                'card_credit_cpf.required' => 'CPF/CNPJ do titular é obrigatório',
                'card_credit_cpf.cpf_cnpj' => 'CPF/CNPJ inválido',
                'card_credit_nome.required' => 'Nome no cartão obrigatório',
                'card_credit_validade_mm.required' => 'Mês obrigatório',
                'card_credit_validade_aaaa.required' => 'Ano obrigatório',
                'card_credit_cvv.required' => 'CVV obrigatório',
                'pay_installments_number.required' => 'Selecione o número de parcelas',
            ]);

            // Remove formatação do número do cartão para validação
            $cardNumClean = preg_replace('/[^0-9]/', '', $this->card_credit_num ?? '');

            // Validação customizada para número do cartão (sem formatação)
            // Usa strlen() em vez de empty() para evitar problemas com '0' e garantir consistência
            if (strlen($cardNumClean) === 0) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'card_credit_num' => 'Número do cartão é obrigatório',
                ]);
            }

            // Valida comprimento do número do cartão (13-19 dígitos)
            if (strlen($cardNumClean) < 13 || strlen($cardNumClean) > 19) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'card_credit_num' => 'Número do cartão deve ter entre 13 e 19 dígitos',
                ]);
            }

            // Valida se a data de validade não está no passado
            if ($this->card_credit_validade_mm && $this->card_credit_validade_aaaa) {
                $mes = (int) $this->card_credit_validade_mm;
                $ano = (int) $this->card_credit_validade_aaaa;

                // Cria data do último dia do mês de validade
                $dataValidade = Carbon::create($ano, $mes, 1)->endOfMonth();
                $dataAtual = Carbon::now()->startOfMonth();

                // Verifica se a data de validade já passou
                if ($dataValidade->lt($dataAtual)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'card_credit_validade_mm' => 'Cartão vencido',
                        'card_credit_validade_aaaa' => 'Reveja informações',
                    ]);
                }
            }
        }

        // Garante que o pedido existe
        if (!$this->order) {
            $this->createOrderForPayment();
        }

        if ($this->is_recurring) {
            $this->ensureRecurringSubscription();
        }

        // Atualiza metadata com forma de pagamento
        $metadata = $this->order->metadata ?? [];
        $metadata['payment_method'] = $this->payment_method;
        $metadata['is_recurring'] = $this->is_recurring;
        $this->order->update(['metadata' => $metadata]);

        // Processa o pagamento
        $p = $this->processPayment();
    }

    protected function getRecurringAttemptOffsets(): array
    {
        return [0, 1, 5, 7, 15, 30];
    }

    protected function getNextRecurringAttemptAt(CampaignSubscriptionCycle $cycle, int $attemptsCount): ?Carbon
    {
        $offsets = $this->getRecurringAttemptOffsets();
        if ($attemptsCount >= count($offsets)) {
            return null;
        }

        return Carbon::parse($cycle->billing_date)->startOfDay()->addDays($offsets[$attemptsCount]);
    }

    protected function registerRecurringAttemptFailure(CampaignSubscriptionCycle $cycle, string $errorMessage): void
    {
        $attemptsCount = $cycle->attempts_count + 1;
        $nextAttemptAt = $this->getNextRecurringAttemptAt($cycle, $attemptsCount);

        $cycle->update([
            'attempts_count' => $attemptsCount,
            'last_attempt_at' => now(),
            'next_attempt_at' => $nextAttemptAt,
            'error_message' => $errorMessage,
        ]);

        if ($nextAttemptAt) {
            return;
        }

        $cycle->update([
            'status' => 'failed',
        ]);

        $subscription = $cycle->subscription;
        if ($subscription) {
            $subscription->update([
                'status' => 'error_disabled',
                'error_at' => now(),
                'error_message' => $errorMessage,
            ]);
        }
    }

    protected function registerRecurringAttemptPending(CampaignSubscriptionCycle $cycle): void
    {
        $attemptsCount = $cycle->attempts_count + 1;

        $cycle->update([
            'attempts_count' => $attemptsCount,
            'last_attempt_at' => now(),
            'next_attempt_at' => null,
        ]);
    }

    protected function registerRecurringAttemptSuccess(CampaignSubscriptionCycle $cycle): void
    {
        $attemptsCount = $cycle->attempts_count + 1;
        $billingDate = Carbon::parse($cycle->billing_date);

        $cycle->update([
            'status' => 'paid',
            'paid_at' => now(),
            'attempts_count' => $attemptsCount,
            'last_attempt_at' => now(),
            'next_attempt_at' => null,
        ]);

        $subscription = $cycle->subscription;
        if ($subscription) {
            $subscription->update([
                'status' => 'active',
                'current_cycle' => max($subscription->current_cycle ?? 0, $cycle->cycle_number),
                'last_charge_at' => now(),
                'next_charge_at' => $billingDate->copy()->addMonthNoOverflow(),
                'error_at' => null,
                'error_message' => null,
            ]);
        }
    }

    protected function tokenizeCardForRecurring(Safe2PayService $service): void
    {
        $buyer = $this->buyer_id ? AppBuyers::find($this->buyer_id) : null;
        $subscription = $this->order && $this->order->subscription_id
            ? CampaignSubscription::find($this->order->subscription_id)
            : null;

        $existingToken = $subscription->card_token ?? $buyer->card_token ?? null;
        if ($existingToken) {
            $this->syncRecurringCardInfo($existingToken, $buyer->card_description ?? $subscription->card_description);
            return;
        }

        $payload = [
            'Holder' => $this->card_credit_nome,
            'CardNumber' => preg_replace('/[^0-9]/', '', $this->card_credit_num ?? ''),
            'ExpirationDate' => $this->card_credit_validade_mm . '/' . $this->card_credit_validade_aaaa,
            'SecurityCode' => $this->card_credit_cvv,
        ];

        $response = $service->tokenizaCartao($payload);
        if ($response instanceof \Throwable) {
            throw new \Exception('Erro ao tokenizar cartão: ' . ($response->getMessage() ?? 'Falha na tokenização'));
        }

        $token = $this->extractSafe2PayCardToken($response);
        if (!$token) {
            throw new \Exception('Não foi possível tokenizar o cartão.');
        }

        $cardNumber = preg_replace('/[^0-9]/', '', $this->card_credit_num ?? '');
        $description = $this->formatCardDescription(
            null,
            substr($cardNumber, 0, 4),
            substr($cardNumber, -4)
        );

        $this->syncRecurringCardInfo($token, $description);
    }

    protected function extractSafe2PayCardToken($response): ?string
    {
        if (!is_array($response)) {
            return null;
        }

        return $response['Token']
            ?? $response['token']
            ?? $response['ResponseDetail']['Token']
            ?? $response['ResponseDetail']['PaymentObject']['Token']
            ?? $response['ResponseDetail']['PaymentToken']
            ?? null;
    }

    protected function formatCardDescription(?string $brand, ?string $first, ?string $last): ?string
    {
        if (!$first || !$last) {
            return null;
        }

        $prefix = $brand ? strtoupper($brand) . ' ' : '';

        return trim($prefix . $first . ' **** ' . $last);
    }

    protected function syncRecurringCardInfo(string $token, ?string $description): void
    {
        $buyer = $this->buyer_id ? AppBuyers::find($this->buyer_id) : null;
        if ($buyer) {
            $buyerUpdate = [
                'card_token' => $token,
                'card_validate_mm' => $this->card_credit_validade_mm,
                'card_validate_aaaa' => $this->card_credit_validade_aaaa,
            ];

            if ($description) {
                $buyerUpdate['card_description'] = $description;
            }

            $buyer->update($buyerUpdate);
        }

        $subscription = $this->order && $this->order->subscription_id
            ? CampaignSubscription::find($this->order->subscription_id)
            : null;

        if ($subscription) {
            $subscriptionUpdate = [
                'card_token' => $token,
                'card_validate_mm' => $this->card_credit_validade_mm,
                'card_validate_aaaa' => $this->card_credit_validade_aaaa,
            ];

            if ($description) {
                $subscriptionUpdate['card_description'] = $description;
            }

            $subscription->update($subscriptionUpdate);
        }
    }

    protected function syncRecurringCardDescriptionFromPayment(CampaignPayment $payment): void
    {
        if (!$this->is_recurring) {
            return;
        }

        $description = $this->formatCardDescription(
            $payment->pay_card_brand ?? null,
            $payment->pay_card_first ?? null,
            $payment->pay_card_last ?? null
        );

        if (!$description) {
            return;
        }

        $buyer = $this->buyer_id ? AppBuyers::find($this->buyer_id) : null;
        if ($buyer && $buyer->card_description !== $description) {
            $buyer->update(['card_description' => $description]);
        }

        $subscription = $this->order && $this->order->subscription_id
            ? CampaignSubscription::find($this->order->subscription_id)
            : null;

        if ($subscription && $subscription->card_description !== $description) {
            $subscription->update(['card_description' => $description]);
        }
    }

    public function voltarFormulario()
    {
        $this->step = 1;
    }

    public function proximaEtapa()
    {
        // Limpa erros da etapa anterior antes de validar
        $this->resetErrorBag();

        if ($this->step == 1) {
            // Valida quiz e valor
            $this->validateQuizAndAmount();

            // Se já existe pedido, atualiza com novo valor e quiz
            if ($this->order) {
                $this->updateOrderFromStep1();
                // Recarrega os dados para exibir na próxima etapa
                $this->loadOrderData();
            }

            // Se passou na validação, avança
            $this->step = 2;
        } elseif ($this->step == 2) {
            // Valida dados do comprador
            $this->validateBuyerData();

            // Cria ou atualiza o pedido antes de avançar para pagamento
            $this->createOrderForPayment();

            //
            if ($this->order ?? false) {
                // Email de pagamento pendente será enviado APÓS gerar PIX/Boleto
                // (removido daqui para evitar envio antes de ter os dados de pagamento)

                // Dispara evento para atualizar URL após criar o order
                $this->dispatchBrowserEvent('order-created', ['orderId' => $this->order->id]);
            }

            // Se passou na validação, avança
            $this->step = 3;


            // Calcula parcelas disponíveis se for cartão de crédito
            if ($this->payment_method === 'credit_card') {
                $this->calculateAvailableInstallments();
            }
        }
    }

    /**
     * Atualiza pedido com dados da etapa 1 (quiz e valor)
     */
    protected function updateOrderFromStep1()
    {
        if (!$this->order) {
            return;
        }

        // Converte o valor formatado para centavos
        if ($this->amount_total_input) {
            $this->amount_total = $this->parseCurrencyInput($this->amount_total_input);
        }

        // Atualiza valor do pedido
        $this->order->update([
            'amount_total' => $this->amount_total,
        ]);

        // Atualiza respostas do quiz
        if ($this->campaign->enable_questions) {
            // Remove respostas antigas
            CampaignOrderAnswer::where('campaign_order_id', $this->order->id)->delete();

            // Salva novas respostas
            foreach ($this->campaign->questions as $question) {
                $questionId = $question->id;
                $answer = $this->quizAnswers[$questionId] ?? null;

                $isEmpty = false;
                if ($answer === null || $answer === '') {
                    $isEmpty = true;
                } elseif (is_array($answer)) {
                    $filtered = array_filter($answer, function ($item) {
                        return !empty($item);
                    });
                    $isEmpty = empty($filtered);
                }

                if ($isEmpty) {
                    if (!$question->is_required) {
                        CampaignOrderAnswer::create([
                            'campaign_order_id' => $this->order->id,
                            'campaign_question_id' => $questionId,
                            'answer_value' => '--',
                        ]);
                    }
                    continue;
                }

                $answerValue = is_array($answer) ? json_encode($answer) : $answer;

                CampaignOrderAnswer::create([
                    'campaign_order_id' => $this->order->id,
                    'campaign_question_id' => $questionId,
                    'answer_value' => $answerValue,
                ]);
            }
        }
    }

    public function etapaAnterior()
    {
        if ($this->step > 1) {
            // Limpa erros ao voltar
            $this->resetErrorBag();

            // Se existe pedido, recarrega os dados ANTES de mudar o step
            if ($this->order) {
                $this->loadOrderData();
            }

            $this->step--;
        }
    }

    /**
     * Recarrega dados do pedido para exibir nas etapas
     */
    protected function loadOrderData()
    {
        if (!$this->order) {
            return;
        }

        // Recarrega o pedido com relacionamentos
        $this->order->refresh();
        $this->order->load(['answers.question']);

        // Restaura dados do pedido (sempre, mesmo se anônimo, para permitir edição)
        // Prioriza dados do pedido, depois do buyer se existir
        // IMPORTANTE: Se o pedido não tem is_anonymous definido, assume false para exibir formulário
        $this->is_anonymous = (bool) ($this->order->is_anonymous ?? false);

        // Se for anônimo, limpa os campos para permitir edição
        if ($this->is_anonymous) {
            $this->buyer_name = '';
            $this->buyer_email = '';
            $this->buyer_doc_num = '';
        } else {
            // Carrega dados do pedido primeiro (podem estar salvos diretamente no pedido)
            $this->buyer_name = $this->order->buyer_name ?? '';
            $this->buyer_email = $this->order->buyer_email ?? '';
            $this->buyer_doc_num = $this->order->buyer_doc_num ?? '';

            // Se não tem dados no pedido mas tem buyer_id, busca do buyer
            if (empty($this->buyer_name) && $this->order->buyer_id) {
                $buyer = AppBuyers::find($this->order->buyer_id);
                if ($buyer) {
                    $this->buyer_name = $buyer->name ?? '';
                    $this->buyer_email = $buyer->email ?? '';
                    $this->buyer_doc_num = $buyer->doc_num ?? '';
                }
            }
        }

        $this->buyer_contact_ddd = $this->order->buyer_contact_ddd ?? '';
        $this->buyer_contact_num = $this->order->buyer_contact_num ?? '';
        $this->amount_total = $this->order->amount_total;
        $this->amount_total_input = $this->formatCurrencyValue($this->amount_total);
        $this->buyer_id = $this->order->buyer_id;
        $orderContactCountry = trim((string) ($this->order->buyer_contact_country ?? ''));

        // Restaura buyer_contact_country e birth_date do buyer se existir
        if ($this->order->buyer_id) {
            $buyer = AppBuyers::find($this->order->buyer_id);
            if ($buyer) {
                $this->buyer_contact_country = $orderContactCountry !== ''
                    ? $orderContactCountry
                    : (string) ($buyer->contact_country ?? '55');
                $this->buyer_birth_date = $buyer->birth_date ?? '';

                // Se não tem dados de contato no pedido, busca do buyer
                if (empty($this->buyer_contact_ddd) && !empty($buyer->contact_ddd)) {
                    $this->buyer_contact_ddd = $buyer->contact_ddd;
                }
                if (empty($this->buyer_contact_num) && !empty($buyer->contact_num)) {
                    $this->buyer_contact_num = $buyer->contact_num;
                }
            } else {
                // Se não tem buyer mas tem dados no pedido, usa do pedido
                $this->buyer_contact_country = $orderContactCountry !== '' ? $orderContactCountry : '55';
                $this->buyer_birth_date = '';
            }
        } else {
            // Se não tem buyer_id, tenta buscar do pedido ou usa padrão
            $this->buyer_contact_country = $orderContactCountry !== '' ? $orderContactCountry : '55';
            $this->buyer_birth_date = '';
        }

        // Restaura respostas do quiz
        $this->quizAnswers = [];
        foreach ($this->order->answers as $answer) {
            $answerValue = $answer->answer_value;
            // Ignora valores padrão '--'
            if ($answerValue === '--') {
                continue;
            }
            // Se for JSON (checkbox), decodifica
            $decoded = json_decode($answerValue, true);
            $this->quizAnswers[$answer->campaign_question_id] = is_array($decoded) ? $decoded : $answerValue;
        }

        // Recalcula parcelas se for cartão de crédito
        if ($this->payment_method === 'credit_card') {
            $this->calculateAvailableInstallments();
        }
    }

    protected function validateBuyerData()
    {
        // Validação customizada para nome completo - EXECUTA PRIMEIRO (apenas se não for anônimo)
        if (!$this->is_anonymous) {
            $this->validateFullName();
        }

        // Regras base
        $rules = $this->rules;

        // Se for doação anônima, alguns campos não são obrigatórios
        if ($this->is_anonymous) {
            $rules['buyer_name'] = 'nullable|string|max:255';
            $rules['buyer_email'] = 'nullable|email|max:255';
            $rules['buyer_birth_date'] = 'nullable|date|before:today';
            $rules['buyer_contact_num'] = 'nullable|string|max:15';
        }

        // Se require_doc for false, CPF não é obrigatório
        if (!$this->campaign->require_doc) {
            $rules['buyer_doc_num'] = 'nullable|cpf_cnpj';
        }

        // Validações específicas por país (apenas se não for anônimo)
        if (!$this->is_anonymous) {
            if ($this->buyer_contact_country === '55') {
                // Brasil: DDD obrigatório e número 8-9 dígitos
                $rules['buyer_contact_ddd'] = 'required|integer|min:11|max:99';
                $rules['buyer_contact_num'] = 'required|integer|min:10000000|max:999999999';
                $this->messages = array_merge($this->messages, [
                    'buyer_contact_ddd.required' => 'DDD é obrigatório',
                    'buyer_contact_ddd.integer' => 'DDD deve ser numérico',
                    'buyer_contact_ddd.min' => 'DDD deve ser válido (11-99)',
                    'buyer_contact_ddd.max' => 'DDD deve ser válido (11-99)',
                    'buyer_contact_num.required' => 'Número de telefone é obrigatório',
                    'buyer_contact_num.integer' => 'Número deve ser numérico',
                    'buyer_contact_num.min' => 'Número deve ter pelo menos 8 dígitos',
                    'buyer_contact_num.max' => 'Número deve ter no máximo 9 dígitos',
                ]);
            } else {
                // Internacional: DDD opcional, número 4-15 dígitos
                $rules['buyer_contact_ddd'] = 'nullable|integer|min:1|max:9999';
                $rules['buyer_contact_num'] = 'required|string|min:4|max:15';
                $this->messages = array_merge($this->messages, [
                    'buyer_contact_ddd.integer' => 'Código de área deve ser numérico',
                    'buyer_contact_ddd.min' => 'Código de área inválido',
                    'buyer_contact_ddd.max' => 'Código de área inválido',
                    'buyer_contact_num.required' => 'Número de telefone é obrigatório',
                    'buyer_contact_num.min' => 'Número deve ter pelo menos 4 dígitos',
                    'buyer_contact_num.max' => 'Número deve ter no máximo 15 dígitos',
                ]);
            }
        }

        // Valida com as regras dinâmicas
        $this->validate($rules, $this->messages);
    }

    protected function validateFullName()
    {
        $name = trim($this->buyer_name);

        // Remove múltiplos espaços
        $name = preg_replace('/\s+/', ' ', $name);

        // Verifica tamanho total primeiro (menos de 10 caracteres)
        if (mb_strlen($name) < 10) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'buyer_name' => 'O nome completo deve ter pelo menos 10 caracteres'
            ]);
        }

        // Divide em palavras
        $words = explode(' ', $name);

        // Remove palavras vazias e reindexa o array
        $words = array_values(array_filter(array_map('trim', $words), function ($word) {
            return $word !== '';
        }));

        // Verifica se tem pelo menos 2 palavras (nome + sobrenome)
        if (count($words) < 2) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'buyer_name' => 'Informe o nome completo com pelo menos nome e sobrenome'
            ]);
        }

        // Partículas conhecidas
        $particles = ['de', 'da', 'do', 'das', 'dos', 'e'];

        // Verifica cada palavra
        foreach ($words as $word) {
            $trimmedWord = trim($word);
            $wordLength = mb_strlen($trimmedWord);
            $cleanWord = mb_strtolower($trimmedWord);

            // 1. PRIMEIRO: Bloqueia palavras que contêm ponto (abreviações como "p.", "J.")
            if (strpos($trimmedWord, '.') !== false) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'buyer_name' => 'Não são permitidas abreviações, apenas o nome completo'
                ]);
            }

            // 2. Ignora partículas conhecidas para validações de tamanho
            if (in_array($cleanWord, $particles)) {
                continue;
            }

            // 3. Bloqueia palavras de 1 caractere (como "p", "s", "j")
            if ($wordLength == 1) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'buyer_name' => 'Apenas nomes completos'
                ]);
            }

            // 4. Bloqueia palavras de 2 caracteres
            if ($wordLength == 2) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'buyer_name' => 'Cada parte do nome deve ter pelo menos 3 caracteres completos'
                ]);
            }
        }

        // Verifica sequências repetitivas de caracteres (mais de 3 repetições)
        foreach ($words as $word) {
            if (preg_match('/(.)\1{3,}/i', $word)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'buyer_name' => 'O nome contém caracteres repetidos inválidos'
                ]);
            }
        }

        // Verifica tamanho máximo de cada palavra (máximo 30 caracteres por palavra)
        foreach ($words as $word) {
            $cleanWord = strtolower(trim($word));
            $particles = ['de', 'da', 'do', 'das', 'dos', 'e'];

            if (!in_array($cleanWord, $particles) && mb_strlen($word) > 30) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'buyer_name' => 'Cada parte do nome deve ter no máximo 30 caracteres'
                ]);
            }
        }

        // Conta palavras válidas (sem contar partículas e já validadas acima)
        $particles = ['de', 'da', 'do', 'das', 'dos', 'e'];
        $validWords = 0;
        foreach ($words as $word) {
            $cleanWord = mb_strtolower(trim($word));
            if (!in_array($cleanWord, $particles) && mb_strlen(trim($word)) >= 3) {
                $validWords++;
            }
        }

        // Deve ter pelo menos 2 palavras válidas (sem contar partículas)
        if ($validWords < 2) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'buyer_name' => 'Informe pelo menos nome e sobrenome completos (mínimo 3 caracteres cada)'
            ]);
        }

        // Verifica se não tem números
        if (preg_match('/[0-9]/', $name)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'buyer_name' => 'O nome não pode conter números'
            ]);
        }

        // Verifica padrões suspeitos (muitas consoantes ou vogais repetidas)
        foreach ($words as $word) {
            $cleanWord = strtolower(trim($word));
            $particles = ['de', 'da', 'do', 'das', 'dos', 'e'];

            if (!in_array($cleanWord, $particles) && mb_strlen($word) >= 3) {
                // Conta vogais e consoantes
                $vowels = preg_match_all('/[aeiouáàâãéêíóôõúü]/i', $word);
                $consonants = preg_match_all('/[bcdfghjklmnpqrstvwxyzç]/i', $word);
                $total = $vowels + $consonants;

                // Se a palavra tem mais de 5 caracteres e mais de 80% são vogais ou consoantes, é suspeito
                if ($total > 5) {
                    $vowelRatio = $vowels / $total;
                    $consonantRatio = $consonants / $total;

                    if ($vowelRatio > 0.8 || $consonantRatio > 0.8) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'buyer_name' => 'O nome contém padrões inválidos'
                        ]);
                    }
                }
            }
        }
    }

    protected function validateQuizAndAmount()
    {
        // Parse do valor formatado antes de validar
        if ($this->amount_total_input) {
            $this->amount_total = $this->parseCurrencyInput($this->amount_total_input);
        }

        // Limpa valores vazios do quizAnswers antes de validar
        foreach ($this->quizAnswers as $key => $value) {
            if ($value === '' || (is_string($value) && trim($value) === '')) {
                unset($this->quizAnswers[$key]);
            }
        }

        // Valida valor (amount_min já está em centavos)
        $minAmount = $this->campaign->amount_min ?? 1000;
        // Usa comparação com tolerância para evitar problemas de ponto flutuante
        // Aceita valores iguais ou maiores que o mínimo
        if ($this->amount_total === null || ($this->amount_total < $minAmount)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'amount_total' => 'O valor mínimo para contribuição é R$ ' . toMoney($minAmount)
            ]);
        }

        // Valida perguntas obrigatórias (apenas se enable_questions estiver ativo)
        if ($this->campaign->enable_questions) {
            foreach ($this->campaign->questions as $question) {
                if ($question->is_required) {
                    $answer = $this->quizAnswers[$question->id] ?? null;

                    // Para checkbox (array)
                    if ($question->question_type === 'checkbox') {
                        // Normaliza: converte null, string vazia ou não-array em array vazio
                        if (!is_array($answer)) {
                            $answer = [];
                        }

                        // Remove valores vazios do array
                        $answer = array_filter($answer, function ($item) {
                            return !empty($item) && trim($item) !== '';
                        });

                        if (count($answer) == 0) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'quiz_' . $question->id => 'Esta pergunta é obrigatória'
                            ]);
                        }
                    }
                    // Para radio, select e outros tipos que retornam string
                    else {
                        // Normaliza o valor
                        if (is_array($answer)) {
                            $answer = !empty($answer) && isset($answer[0]) ? $answer[0] : null;
                        }

                        // Valida se está vazio, null, ou string vazia
                        $isEmpty = false;
                        if ($answer === null || $answer === '') {
                            $isEmpty = true;
                        } elseif (is_string($answer) && trim($answer) === '') {
                            $isEmpty = true;
                        }

                        if ($isEmpty) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'quiz_' . $question->id => 'Esta pergunta é obrigatória'
                            ]);
                        }
                    }
                }
            }
        }

        if ($this->campaign->allow_recurring && $this->is_recurring !== true && $this->is_recurring !== false) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'is_recurring' => 'Selecione se a doação será recorrente ou única.'
            ]);
        }
    }

    public function validarPagamento($showMessages = true)
    {
        try {
            DB::beginTransaction();

            // Usa CampaignPayment
            $paymentToValidate = $this->campaignPayment;

            // Verifica se há payment pendente
            if (!$paymentToValidate) {
                if ($showMessages) {
                    session()->flash('forma_pagamento_error', 'Nenhum pagamento encontrado para validação');
                }
                DB::rollBack();
                return;
            }

            // Verifica se já está pago
            if (in_array($paymentToValidate->status, ['paid', 'approved', 'autorizado', 'captured'])) {
                if ($showMessages) {
                    session()->flash('forma_pagamento_success', 'Pagamento já foi confirmado anteriormente');
                }
                $this->step = 4;
                DB::commit();
                return;
            }

            // Obtém gateway
            $gatewayPay = $this->campaign->gateway;
            if (!$gatewayPay) {
                throw new \Exception('Gateway de pagamento não configurado para esta campanha');
            }

            // Define token e sandbox baseado no tipo de pagamento
            $sandbox = false;
            $token = null;

            // Define sandbox e token baseado no método de pagamento
            if (in_array($paymentToValidate->pay_type, ['pix', 'slip_pix', 'pix_direto'], true)) {
                // PIX sempre usa produção
                $sandbox = false;
                $token = $gatewayPay->token_live;
            } else {
                // Cartão de crédito pode usar sandbox ou produção
                $sandbox = $this->campaign->pay_sandbox ?? false;
                $token = $sandbox ? $gatewayPay->token_test : $gatewayPay->token_live;
            }

            if ($this->payment_method == 'pix') {
                // Verifica se PIX expirou
                if (
                    ($paymentToValidate->pay_pix_expires_at ?? false) &&
                    \Carbon\Carbon::parse($paymentToValidate->pay_pix_expires_at)->isPast()
                ) {

                    // Atualiza status para expirado e limpa dados (PIX e BOLETO)
                    $paymentToValidate->update([
                        'status' => 'pix_expired',
                        'pay_pix_qr_code_url' => null,
                        'pay_pix_key' => null,
                        'pay_pix_qr_code' => null,
                        // Limpa também os dados do boleto quando PIX expirar
                        'pay_boleto_barcode' => null,
                        'pay_boleto_url' => null,
                        'pay_boleto_expiration_date' => null,
                    ]);

                    DB::commit();

                    // Limpa também a variável $payment_result para remover dados em memória
                    $this->payment_result = [];

                    // Reseta payment_method para permitir gerar novo PIX
                    $this->payment_method = 'pix';
                    // $this->campaignPayment     = null;
                    // $this->campaignPaymentSlip = null;

                    session()->flash('forma_pagamento_info', 'O prazo para pagamento expirou');
                    session()->flash('forma_pagamento_info_sub', 'Você ainda pode gerar um novo PIX e concluir o pagamento');

                    return;
                }

                // Verifica se tem NSU
                if (!$paymentToValidate->pay_nsu) {
                    if ($showMessages) {
                        session()->flash('forma_pagamento_error', 'NSU do pagamento não encontrado. Aguarde alguns instantes e tente novamente');
                    }
                    DB::rollBack();
                    return;
                }
            }

            // Consulta transação no gateway
            $service = new Safe2PayService($token, (bool) $sandbox);
            $consultaResponse = $service->consultaTransacao($paymentToValidate->pay_nsu, true);

            // Processa resposta
            if ($consultaResponse->error ?? false) {
                // Erro na consulta - só mostra mensagem se for chamado manualmente
                if ($showMessages) {
                    session()->flash('forma_pagamento_error', $consultaResponse->msg ?? 'Erro ao consultar pagamento');
                    session()->flash('forma_pagamento_error_sub', $consultaResponse->msg_sub ?? 'Código: ' . ($consultaResponse->code ?? 'N/A'));
                }
                DB::rollBack();
                return;
            }

            // Verifica se foi pago
            $isPaid = $consultaResponse->pagamento_ok ?? false;
            $paymentStatus = $consultaResponse->status ?? 'pending';

            if ($isPaid || in_array($paymentStatus, ['paid', 'approved', 'autorizado', 'captured', 'sucesso'])) {
                // Pagamento confirmado - usa CampaignPaymentService
                $paymentService = new CampaignPaymentService();
                $paymentService->markAsPaid($paymentToValidate, [
                    'datahora' => $consultaResponse->datahora ?? now(),
                    'pagamento_valor' => $consultaResponse->pagamento_valor ?? null,
                    'pagamento_taxa' => $consultaResponse->pagamento_taxa ?? null,
                    'pagamento_liquido' => $consultaResponse->pagamento_liquido ?? null,
                ]);

                // Atualiza payment com resposta completa
                $paymentToValidate->update([
                    'pay_json_response' => $consultaResponse,
                ]);

                // Recarrega dados
                $this->order = CampaignOrder::find($this->order->id);
                $this->campaignPaymentSlip = $this->order->currentPaymentSlip;
                $this->campaignPayment = $this->campaignPaymentSlip?->payment;
                $this->payment = $this->campaignPayment;

                DB::commit();

                // Mensagem de sucesso sempre é exibida
                session()->flash('forma_pagamento_success', 'Pagamento confirmado com sucesso!');

                // Avança para confirmação
                $this->step = 4;

            } else {
                // Ainda não foi pago - só mostra mensagem se for chamado manualmente
                if ($showMessages) {
                    session()->flash('forma_pagamento_info', 'Pagamento ainda não foi reconhecido');
                    session()->flash('forma_pagamento_info_sub', 'Se o pagamento já foi realizado, ele pode levar alguns minutos para ser processado. Tente novamente em alguns instantes');
                }
                DB::commit();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            if ($showMessages) {
                session()->flash('forma_pagamento_error', 'Erro ao validar pagamento: ' . $e->getMessage());
            }
        }
    }

    protected function processPayment()
    {
        try {
            DB::beginTransaction();

            // VALIDAÇÃO: Doações anônimas só podem usar PIX
            if ($this->is_anonymous && $this->payment_method !== 'pix') {
                DB::rollBack();
                session()->flash('forma_pagamento_error', 'Doações anônimas só podem ser realizadas via PIX');
                session()->flash('forma_pagamento_error_sub', 'Por favor, selecione o PIX como forma de pagamento');
                $this->step = 3;
                return false;
            }

            // VALIDAÇÃO: Verifica se já existe uma transação paga para este pedido
            if ($this->order->status === 'paid') {
                DB::rollBack();
                session()->flash('forma_pagamento_error', 'Este pedido já foi pago anteriormente');
                session()->flash('forma_pagamento_error_sub', 'Não é possível processar um novo pagamento para um pedido já finalizado');
                $this->step = 4;
                return true;
            }

            // Verifica se existe algum pagamento aprovado nas novas tabelas
            $paidCampaignPayment = CampaignPayment::where('campaign_order_id', $this->order->id)
                ->where('status', 'paid')
                ->first();

            if ($paidCampaignPayment) {
                DB::rollBack();
                session()->flash('forma_pagamento_error', 'Este pedido já possui um pagamento aprovado');
                session()->flash('forma_pagamento_error_sub', 'Pagamento já processado. NSU: ' . ($paidCampaignPayment->pay_nsu ?? 'N/A'));
                $this->step = 4;
                $this->payment = $paidCampaignPayment;
                return true;
            }

            // Obtém gateway de pagamento
            $gatewayPay = $this->campaign->gateway;
            if (!$gatewayPay) {
                throw new \Exception('Gateway de pagamento não configurado para esta campanha');
            }

            // Calcula valores
            $value_paid = $this->amount_total;
            $value_fees = 0; //TODO: CANCULAR AS TAXAS QUANDO HOUVER
            $value_liquid = $value_paid - $value_fees;

            // Se for cartão de crédito e tiver parcelas, calcula taxas e valida valor mínimo
            $installmentFeePercentage = 0;
            $calculatedAmount = $this->amount_total;
            $installmentValue = $calculatedAmount;

            if ($this->payment_method === 'credit_card' && $this->pay_installments_number > 1) {
                if ($this->campaign->pay_card_credit_installment_fee_payer === 'customer') {
                    $taxas = json_decode($gatewayPay->pay_gateway_installment_fees_json ?? '{}', true);
                    $applyInstallmentFees = $gatewayPay->apply_installment_fees ?? false;

                    if ($applyInstallmentFees && isset($taxas[$this->pay_installments_number])) {
                        $installmentFeePercentage = (float) $taxas[$this->pay_installments_number] ?? 0;

                        if ($installmentFeePercentage > 0) {
                            $amountInCents = $this->amount_total;
                            $calculatedAmount = (int) round($amountInCents / (1 - ($installmentFeePercentage / 100)));
                            $installmentValue = (int) round($calculatedAmount / $this->pay_installments_number);
                        } else {
                            $installmentValue = (int) round($calculatedAmount / $this->pay_installments_number);
                        }
                    } else {
                        $installmentValue = (int) round($calculatedAmount / $this->pay_installments_number);
                    }

                    $minInstallmentAmount = $this->campaign->pay_card_credit_installment_amount_min ?? $gatewayPay->pay_card_credit_installment_amount_min ?? 0;

                    if ($installmentValue < $minInstallmentAmount) {
                        throw new \Exception(
                            'O valor da parcela (R$ ' . number_format($installmentValue / 100, 2, ',', '') .
                            ') é menor que o valor mínimo permitido (R$ ' . number_format($minInstallmentAmount / 100, 2, ',', '') .
                            '). Por favor, escolha menos parcelas ou aumente o valor da contribuição'
                        );
                    }
                } else {
                    $installmentValue = (int) round($calculatedAmount / $this->pay_installments_number);

                    $minInstallmentAmount = $this->campaign->pay_card_credit_installment_amount_min ?? $gatewayPay->pay_card_credit_installment_amount_min ?? 0;

                    if ($installmentValue < $minInstallmentAmount) {
                        throw new \Exception(
                            'O valor da parcela (R$ ' . number_format($installmentValue / 100, 2, ',', '') .
                            ') é menor que o valor mínimo permitido (R$ ' . number_format($minInstallmentAmount / 100, 2, ',', '') .
                            '). Por favor, escolha menos parcelas ou aumente o valor da contribuição'
                        );
                    }
                }
            }

            // Calcula valores finais
            $value_paid = $calculatedAmount;
            $value_fees = $calculatedAmount - $this->amount_total;
            $value_liquid = $this->amount_total;

            // PIX e Boleto são sempre à vista (1 parcela)
            $installments = ($this->payment_method === 'credit_card')
                ? ($this->pay_installments_number ?? 1)
                : 1;

            // Usa CampaignPaymentService para criar payment (e slip se não existir)
            $paymentService = new CampaignPaymentService();

            $this->campaignPayment = $paymentService->createPayment($this->order, [
                'value_paid' => $value_paid,
                'value_fees' => $value_fees,
                'value_liquid' => $value_liquid,
                'fee_percentage_used' => $installmentFeePercentage,
                'payment_method' => $this->payment_method,
                'installments' => $installments,
                'installment_value' => $installmentValue,
                'subscription_id' => $this->order->subscription_id,
                'subscription_cycle_id' => $this->order->subscription_cycle_id,
            ]);

            // FORCE CRIAR VARIAVEL PAYMENT
            $this->payment = $this->campaignPayment;

            // Carrega o slip do payment
            $this->campaignPaymentSlip = $this->campaignPayment->slip;

            // Processa conforme o tipo
            $paymentResult = $this->executePaymentProcessingSafe2pay();

            // Converte objeto para array
            $this->payment_result = $paymentResult ? json_decode(json_encode($paymentResult), true) : [];

            // Mapeia campo datahoraExpiracao do Safe2Pay para pay_pix_expires_at (padrão)
            if (isset($this->payment_result['datahoraExpiracao']) && !isset($this->payment_result['pay_pix_expires_at'])) {
                $this->payment_result['pay_pix_expires_at'] = $this->payment_result['datahoraExpiracao'];
            }

            $cycle = $this->order->subscription_cycle_id
                ? CampaignSubscriptionCycle::find($this->order->subscription_cycle_id)
                : null;
            $attemptNumber = $cycle ? ($cycle->attempts_count + 1) : null;
            $scheduledAt = $cycle ? ($cycle->next_attempt_at ?? $cycle->billing_date) : null;

            // Cria tentativa no histórico
            $attempt = \App\Models\ModCampaign\CampaignPaymentAttempt::create([
                'campaign_id' => $this->campaign->id,
                'campaign_order_id' => $this->order->id,
                'campaign_payment_id' => $this->campaignPayment->id,
                'pay_type' => $this->payment_method === 'credit_card' ? 'card_credit' : $this->payment_method,
                'gateway_slug' => $this->campaign->gateway->pay_gateway_slug ?? 'safe2pay',
                'status' => 'success',
                'subscription_id' => $this->order->subscription_id,
                'subscription_cycle_id' => $this->order->subscription_cycle_id,
                'attempt_number' => $attemptNumber,
                'scheduled_at' => $scheduledAt,
                'request_data' => [
                    'payment_method' => $this->payment_method,
                    'installments' => $installments, // Usa variável calculada acima
                    'amount' => $value_paid,
                ],
                'response_data' => $this->payment_result ?? [],
                'attempted_at' => now(),
            ]);

            // Atualiza payment com resultado
            if (empty($this->payment_result) || ($this->payment_result['error'] ?? false)) {

                $errorMsg = $this->payment_result['msg'] ?? 'Erro ao processar pagamento';

                session()->flash('forma_pagamento_error', $errorMsg);

                if ($this->payment_result['msg_sub'] ?? false) {
                    session()->flash('forma_pagamento_error_sub', $this->payment_result['msg_sub']);
                } else {
                    session()->flash('forma_pagamento_error_sub', 'Cod: ' . ($this->payment_result['code'] ?? 400));
                }

                $this->campaignPayment->update([
                    'status' => 'error',
                    'pay_json_response' => $this->sanitizePaymentData($this->payment_result ?? []),
                ]);

                // FORCE ATUALIZAR VARIAVEL PAYMENT
                $this->payment = $this->campaignPayment;

                if ($this->order->status !== 'paid') {
                    $this->order->update(['status' => 'pending']);
                }

                $attempt->update([
                    'status' => 'error',
                    'error_message' => $errorMsg,
                    'error_code' => $this->payment_result['code'] ?? 400,
                    'response_data' => $this->payment_result ?? [],
                ]);

                if ($cycle) {
                    $this->registerRecurringAttemptFailure($cycle, $errorMsg);
                }

                $this->payment_result = [];
                $this->step = 3;
                DB::commit();
                return;
            }

            // Atualiza payment com dados do gateway
            $paymentService->updatePaymentFromGateway($this->campaignPayment, $this->payment_result);
            $this->syncRecurringCardDescriptionFromPayment($this->campaignPayment);

            // Atualiza transação
            $paymentStatus = $this->payment_result['status'] ?? 'processing';

            // Atualiza attempt como sucesso
            $attempt->update([
                'status' => 'success',
                'response_data' => $this->payment_result ?? [],
            ]);

            // Atualiza status do pedido
            if (in_array($paymentStatus, ['paid', 'pago', 'approved', 'autorizado', 'captured'])) {
                $paymentService->markAsPaid($this->campaignPayment, $this->payment_result);
                if ($cycle) {
                    $this->registerRecurringAttemptSuccess($cycle);
                }
            } elseif (in_array($paymentStatus, ['pendente', 'pending', 'processing'])) {
                $this->order->update(['status' => 'pending']);
                $emailSent = CampaignEmailService::enviarNotificacaoPagamentoPendente($this->order);
                // Limpa Mensagem
                session()->forget(['forma_pagamento_success', 'forma_pagamento_info', 'forma_pagamento_info_sub']);
                if ($cycle) {
                    $this->registerRecurringAttemptPending($cycle);
                }
            }

            // FORCE ATUALIZAR VARIAVEL PAYMENT
            DB::commit();

            $this->payment = $this->campaignPayment;

            // Mensagem de sucesso
            session()->flash('forma_pagamento_success', $this->getSuccessMessage());

            // Atualiza URL se necessário
            if (!$this->order_id) {
                $this->dispatchBrowserEvent('order-created', ['orderId' => $this->order->id]);
            }

            // Para PIX/BOLETO, mantém na etapa de pagamento
            $paidStatuses = ['paid', 'approved', 'autorizado', 'captured'];
            $shouldStayOnPayment = in_array($this->payment_method, ['pix', 'boleto'])
                && !in_array(($this->payment_result['status'] ?? 'processing'), $paidStatuses);

            $this->step = $shouldStayOnPayment ? 3 : 4;

            return $this->campaignPayment;

        } catch (\Exception $e) {
            DB::rollBack(); // ✅ ROLLBACK ao invés de commit!

            if ($this->campaignPayment ?? false) {
                $this->campaignPayment->update([
                    'status' => 'error',
                    'pay_json_response' => $this->sanitizePaymentData([
                        'error' => true,
                        'message' => $e->getMessage(),
                        'code' => $e->getCode(),
                        'timestamp' => now()->toIso8601String(),
                    ]),
                ]);
            }

            if ($this->order ?? false && $this->order->status !== 'paid') {
                $this->order->update(['status' => 'pending']);
            }

            $this->campaignPayment = null;
            $this->campaignPaymentSlip = null;
            $this->payment = null;
            $this->payment_result = [];

            session()->flash('forma_pagamento_error', $e->getMessage());
            $this->step = 3;

            return false;
        }
    }

    protected function executePaymentProcessingSafe2pay()
    {
        $gatewayPay = $this->campaign->gateway;

        // Determina token e sandbox
        if (in_array($this->payment_method, ['pix', 'slip_pix', 'pix_direto'], true)) {
            $sandbox = false; // PIX sempre produção
            $token = $gatewayPay->token_live;
        } else {
            $sandbox = $this->campaign->pay_sandbox ?? false;
            $token = $sandbox ? $gatewayPay->token_test : $gatewayPay->token_live;
        }

        $sandbox = (bool) $sandbox;

        if (empty($token)) {
            throw new \Exception('Credencial de pagamento não configurada para este ambiente.');
        }

        \Log::info('CampanhaPublica: ambiente de pagamento resolvido', [
            'campaign_id' => $this->campaign->id ?? null,
            'order_id' => $this->order->id ?? null,
            'payment_method' => $this->payment_method,
            'sandbox' => $sandbox,
            'token_type' => $sandbox ? 'test' : 'live',
            'token_fingerprint' => substr(sha1((string) $token), 0, 12),
            'gateway_id' => $gatewayPay->id ?? null,
            'gateway_slug' => $gatewayPay->pay_gateway_slug ?? null,
        ]);

        $service = new Safe2PayService($token, $sandbox);

        // SET APPLICATION
        $service->Application = trim(mb_strtoupper(toSlug($gatewayPay->pay_gateway_slug . '-app_campaign-' . ($sandbox ? "SANDBOX" : "LIVE"), '-')));
        $vendor = $this->campaign->organizer->organizer_name_full
            ?? ($this->campaign->organizer->organizer_name ?? null);
        $service->Vendor = trim(mb_strtoupper(str_replace('//', '|', $vendor ?? 'PROEVENTPAY')));
        $service->CallbackUrl = route('api.campaigns.webhook.safe2pay', [
            'orderId' => $this->order->id,
            'paymentId' => $this->campaignPayment->id,
        ]);
        $service->Reference = $this->order->order_control;
        $service->setAplication();

        // SET META
        $service->app_ref = 'app_campaign';
        $service->order_id = $this->order->id;
        $service->payment_id = $this->campaignPayment->id;
        $service->gateway_id = $gatewayPay->id;
        $service->localizador = $this->order->order_control;
        $service->order_amount = $this->campaignPayment->value_paid; // Já está em centavos
        $service->order_amount_discount = 0;
        $service->order_amount_pay = $this->campaignPayment->value_paid; // Já está em centavos
        $service->is_anonymous = $this->is_anonymous ?? false;
        $service->setMeta();

        // SET CUSTOMER
        $service->Name = $this->is_anonymous ? 'Anônimo' : $this->buyer_name;
        $service->Identity = $this->is_anonymous ? null : preg_replace('/[^0-9]/', '', $this->buyer_doc_num ?? '');

        // Formata telefone: remove código do país (55) e limita a 10 dígitos
        $phone = null;
        if (!$this->is_anonymous) {
            $phone = $this->buyer_contact_country . $this->buyer_contact_ddd . $this->buyer_contact_num;
            $phone = preg_replace('/[^0-9]/', '', $phone); // Remove não numéricos

            // Remove código do país se for Brasil (55)
            if (substr($phone, 0, 2) === '55') {
                $phone = substr($phone, 2);
            }

            // Limita a 10 dígitos
            $phone = substr($phone, 0, 10);
        }
        $service->Phone = $phone;

        $service->Email = $this->is_anonymous ? null : strtolower(trim($this->buyer_email));
        $service->setCustomer();

        // SET CUSTOMER ADDRESS
        // Prioridade: 1) Endereço do comprador, 2) Endereço do customer (fallback)
        $hasAddressData = !$this->is_anonymous && $this->buyer_address_cep;

        if ($hasAddressData) {
            // Usa endereço do comprador
            $service->ZipCode = preg_replace('/[^0-9]/', '', $this->buyer_address_cep);
            $service->Street = $this->buyer_address_logradouro;
            $service->Number = $this->buyer_address_numero;
            $service->Complement = $this->buyer_address_complemento;
            $service->District = $this->buyer_address_bairro;
            $service->CityName = $this->buyer_address_cidade;
            $service->StateInitials = $this->buyer_address_estado;
            $service->setCustomerAddress();
        } elseif (!$this->is_anonymous && $this->campaign->customer) {
            // Fallback: usa endereço do customer da campanha
            $customer = $this->campaign->customer;
            if ($customer->zip_code) {
                $service->ZipCode = preg_replace('/[^0-9]/', '', $customer->zip_code);
                $service->Street = $customer->address ?? 'Não informado';
                $service->Number = $customer->address_number ?? 'S/N';
                $service->Complement = $customer->address_complement;
                $service->District = $customer->city_neighborhood ?? 'Centro';
                $service->CityName = $customer->city ?? 'São Paulo';
                $service->StateInitials = $customer->state ?? 'SP';
                $service->setCustomerAddress();
            } else {
                $service->setCustomerAddress(clear: true);
            }
        } else {
            $service->setCustomerAddress(clear: true);
        }

        // ADD PRODUCT
        $service->appendProducts(
            Code: $this->order->order_control,
            UnitPrice: $sandbox ? 100 : $this->campaignPayment->value_paid, // TESTE = R$ 1,00 // Já está em centavos
            Quantity: 1,
            Description: mb_strtoupper($sandbox ? $this->campaign->name . ' - TESTE' : $this->campaign->name, 'UTF-8')
        );

        $service->setProducts();

        // ========================================
        // PIX DIRETO (Static PIX) - Fluxo simplificado
        // ========================================
        if ($this->payment_method === 'pix_direto') {

            // Gera PIX estático via Safe2Pay
            $pixResult = $service->createStaticPix(
                amount: $this->campaignPayment->value_paid, // Já está em centavos
                description: mb_strimwidth($this->campaign->name, 0, 50), // Limita a 50 chars
                reference: $this->order->order_control,
                callbackUrl: route('api.campaigns.webhook.safe2pay')
            );

            // Verifica erro
            if ($pixResult['HasError'] ?? false) {
                throw new \Exception(
                    $pixResult['Error'] ?? 'Erro ao gerar PIX estático',
                    $pixResult['ErrorCode'] ?? 500
                );
            }

            // Processa response
            $processedResult = $service->processStaticPixResponse($pixResult);

            if ($processedResult->error) {
                throw new \Exception($processedResult->msg ?? 'Erro ao processar PIX estático');
            }

            // Prepara request payload para salvar (sem dados sensíveis)
            $requestPayload = [
                'PaymentMethod' => 'static_pix',
                'Amount' => convertInt2Float(toMoneyInt(toMoneyDot($this->campaignPayment->value_paid))),
                'Description' => mb_strimwidth($this->campaign->name, 0, 50),
                'Reference' => $this->order->order_control,
                'CallbackUrl' => route('api.campaigns.webhook.safe2pay'),
            ];

            // Sanitiza e salva request + response
            $sanitizedRequest = $this->sanitizePaymentData($requestPayload);
            $sanitizedResponse = $this->sanitizePaymentData($pixResult);

            // Atualiza payment com dados do PIX estático
            $this->campaignPayment->update([
                'pay_transaction_id' => $processedResult->nsu,
                'pay_pix_qr_code' => $processedResult->pay_pix_qr_code,
                'pay_pix_qr_code_url' => $processedResult->pay_pix_qr_code_url,
                'pay_pix_key' => $processedResult->pay_pix_key,
                'pay_json_request' => $sanitizedRequest,
                'pay_json_response' => $sanitizedResponse,
                'status' => 'pending',
            ]);

            // Retorna resultado processado
            return (object) [
                'error' => false,
                'status' => 'pending',
                'msg' => 'PIX gerado com sucesso',
                'msg_sub' => 'Escaneie o QR Code ou copie o código PIX',
                'pay_pix_qr_code' => $processedResult->pay_pix_qr_code,
                'pay_pix_qr_code_url' => $processedResult->pay_pix_qr_code_url,
                'pay_pix_key' => $processedResult->pay_pix_key,
                'identifier' => $processedResult->identifier,
                'reference' => $processedResult->reference,
            ];
        }

        // Prepara payload conforme tipo (mas NÃO salva ainda)
        $requestPayload = null;

        switch ($this->payment_method) {
            case 'boleto':
                // Prioridade: 1) CPF do pagador Boleto, 2) CPF/CNPJ do comprador
                $cpf = preg_replace('/[^0-9]/', '', $this->boleto_cpf ?? $this->buyer_doc_num ?? '');

                // Valida se tem CPF/CNPJ (boleto sempre precisa)
                if (empty($cpf)) {
                    throw new \Exception('CPF ou CNPJ é obrigatório para pagamento com boleto');
                }

                // Define dias para vencimento baseado na configuração
                // Sandbox: 2 dias | Produção: 3 dias
                $daysToExpire = $sandbox ? 2 : 3;

                $requestPayload = $service->setPaymentBoleto(
                    $cpf,
                    $daysToExpire,
                    $this->campaign->name,
                    $this->campaign->description ?? $this->campaign->about
                );
                break;

            case 'pix':
                // Prioridade: 1) CPF do pagador PIX, 2) CPF/CNPJ do comprador
                $cpf = preg_replace('/[^0-9]/', '', $this->pix_cpf ?? $this->buyer_doc_num ?? '');

                // Valida se tem CPF e se não é anônimo
                if (empty($cpf) && !$this->is_anonymous) {
                    throw new \Exception('CPF é obrigatório para pagamento PIX');
                }

                // Para anônimo, tenta usar o CPF do comprador como fallback
                if (empty($cpf) && $this->is_anonymous) {
                    $cpf = preg_replace('/[^0-9]/', '', $this->buyer_doc_num ?? '');
                }

                // Configura expiração PIX baseado na configuração sandbox da campanha
                // Sandbox: 2 minutos (120 segundos) | Produção: 5 horas (18000 segundos)
                $service->CustomPixExpiration = $sandbox ? 120 : 18000;

                $requestPayload = $service->setPaymentPix($cpf);

                // Salva apenas o expires_at (sem dados sensíveis)
                $this->campaignPayment->update([
                    'pay_pix_expires_at' => $service->ExpirationDateTime ?? null
                ]);
                break;

            case 'credit_card':
                if (
                    !$this->card_credit_num || !$this->card_credit_nome || !$this->card_credit_validade_mm ||
                    !$this->card_credit_validade_aaaa || !$this->card_credit_cvv
                ) {
                    throw new \Exception('Dados do cartão incompletos');
                }

                // Prioridade: 1) CPF do titular do cartão, 2) CPF/CNPJ do comprador
                $cpf = preg_replace('/[^0-9]/', '', $this->card_credit_cpf ?? $this->buyer_doc_num ?? '');

                // Valida se tem CPF/CNPJ e se não é anônimo
                if (empty($cpf) && !$this->is_anonymous) {
                    throw new \Exception('CPF ou CNPJ é obrigatório para pagamento com cartão');
                }

                // Para anônimo, tenta usar o CPF do comprador como fallback
                if (empty($cpf) && $this->is_anonymous) {
                    $cpf = preg_replace('/[^0-9]/', '', $this->buyer_doc_num ?? '');
                }

                if ($this->is_recurring && !$sandbox) {
                    $this->tokenizeCardForRecurring($service);
                }

                $service->CardNumber = preg_replace('/[^0-9]/', '', $this->card_credit_num ?? '');
                $service->Holder = $this->card_credit_nome;
                $service->ExpirationDateMM = $this->card_credit_validade_mm;
                $service->ExpirationDateAAAA = $this->card_credit_validade_aaaa;
                $service->SecurityCode = $this->card_credit_cvv;
                $service->InstallmentQuantity = $this->pay_installments_number ?? 1;

                if (($this->pay_installments_number > 1) && ($this->campaign->pay_card_credit_installment_fee_payer === 'customer')) {
                    $service->IsApplyInterest = true;
                }

                // Prepara payload completo (retorna COM dados sensíveis para envio)
                $requestPayload = $service->setPaymentCredit($cpf);
                break;

            default:
                throw new \Exception('Forma de pagamento não suportada');
        }

        // Executa transação (envia COM dados completos)
        $transactionResult = $service->executeTransaction();

        // APÓS receber resposta, sanitiza e salva request + response
        $sanitizedRequest = $this->sanitizePaymentData($requestPayload);
        $sanitizedResponse = $this->sanitizePaymentData(
            is_object($transactionResult) ? json_decode(json_encode($transactionResult), true) : $transactionResult
        );

        // Atualiza com dados sanitizados
        $this->campaignPayment->update([
            'pay_json_request' => $sanitizedRequest,
            'pay_json_response' => $sanitizedResponse,
        ]);

        return $transactionResult;
    }

    /**
     * Sanitiza dados sensíveis de pagamento (cartão)
     * Remove/mascara: CardNumber, SecurityCode em TODAS as estruturas possíveis
     */
    protected function sanitizePaymentData($data)
    {
        if (!is_array($data)) {
            return $data;
        }

        // Função recursiva para sanitizar em qualquer nível
        $sanitizeCardData = function (&$arr) use (&$sanitizeCardData) {
            foreach ($arr as $key => &$value) {
                if ($key === 'CardNumber' && is_string($value)) {
                    // Remove espaços e caracteres especiais
                    $cardNumber = preg_replace('/[^0-9]/', '', $value);
                    // Mascara: 4444 **** **** 4447
                    if (strlen($cardNumber) >= 8) {
                        $value = substr($cardNumber, 0, 4) . ' **** **** ' . substr($cardNumber, -4);
                    }
                } elseif ($key === 'SecurityCode' && is_string($value)) {
                    $value = '***';
                } elseif (is_array($value)) {
                    // Recursivamente sanitiza arrays aninhados
                    $sanitizeCardData($value);
                }
            }
        };

        $sanitizeCardData($data);
        return $data;
    }

    protected function getSuccessMessage()
    {
        $paymentStatus = $this->payment_result['status'] ?? $this->campaignPayment->status ?? 'processing';

        switch ($this->payment_method) {
            case 'pix':
            case 'slip_pix':
                if (isset($this->payment_result['pay_pix_qr_code']) || isset($this->campaignPayment->pay_pix_qr_code)) {
                    return 'PIX gerado com sucesso! Realize o pagamento para finalizar';
                }
                return 'Pedido criado com sucesso! Aguarde a geração do QR Code PIX para realizar o pagamento';

            case 'boleto':
            case 'slip':
                if (isset($this->payment_result['pay_boleto_barcode']) || isset($this->campaignPayment->pay_boleto_barcode)) {
                    return 'Pedido criado com sucesso! Utilize o código de barras do boleto para realizar o pagamento';
                }
                return 'Pedido criado com sucesso! Aguarde a geração do boleto';

            case 'credit_card':
                $installments = $this->pay_installments_number ?? 1;
                if ($installments > 1) {
                    return 'Pedido criado com sucesso! Seu pagamento em ' . $installments . 'x está sendo processado';
                }
                return 'Pedido criado com sucesso! Seu pagamento está sendo processado';

            default:
                return 'Pedido criado com sucesso!';
        }
    }

    public function render()
    {
        return view('livewire.campanha.campanha-publica')
            ->layout('layouts.app-public-campanha');
    }
}
