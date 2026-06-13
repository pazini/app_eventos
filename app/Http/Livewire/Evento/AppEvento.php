<?php

namespace App\Http\Livewire\Evento;

use App\Jobs\AppEvent\NotificationAppEventCompra;
use App\Models\AppEvent\AppEventOrder;
use App\Models\AppEvent\AppEventOrderItem;
use App\Models\AppEvent\AppEventOrderTicket;
use App\Models\ModEvent\Event;
use App\Rules\NomeCompleto;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use WireUi\Traits\Actions;

class AppEvento extends Component
{
    use Actions;

    protected $listeners = ['filterChanged', 'searchChanged'];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'ativas'],
        'filterCustomer' => ['except' => ''],
        'filterCustomerSlug' => ['except' => ''],
        'filterOrganizer' => ['except' => ''],
        'sortBy' => ['except' => 'event_datetime_start_asc'],
        'appUserId' => ['except' => ''],
    ];

    public $slug;
    public $listaDd;
    public $listaMm;
    public $listaAa;
    public $listaAaaa;
    public $participantes = [];
    public $participantesRange;
    public $listaParticipantes;
    public $participante_prefix;
    public $validatedData;
    public $comprar_novamente;
    public $participantes_questions = [];

    //
    public $event;
    public $eventList;
    public $eventName;

    // Filtros (sincronizados com navigation)
    public $search = '';
    public $filterStatus = 'ativas'; // ativas, todas, finalizadas
    public $filterCustomer = '';
    public $filterCustomerSlug = '';
    public $filterOrganizer = '';
    public $sortBy = 'event_datetime_start_asc'; // padrão: mais próximos primeiro
    public $customers = [];

    // App-Version (modo empresa - sempre mobile)
    public $isAppVersion = false;
    public $showCustomerSelection = false;
    public $appCustomerId = null;
    public $appCustomerName = null;
    public $appUserId = '';
    public $appSource = null;
    public $selectedCustomerId = '';

    // Paginação
    public $perPage = 30;
    public $currentPage = 1;
    public $totalEvents = 0;
    public $order;
    public $orderNum = false;
    public $orderPrice=0;
    public $order_json;
    public $event_questions_buyer;
    public $event_questions_item;

    //
    public $loteAtivo;
    public $maxLotes;
    public $maxLotesIngressos;
    public $lotesValores;

    //
    public $pagamentoGatewaySlug;
    public $pagamentoTipo;
    public $pagamentoFormas;
    public $pagamentoParcelamentoMax;
    public $pagamentoParcelamento;
    public $pagamentoFormaSelecionada;

    //
    public $comprador_nome;
    public $comprador_sobrenome;
    public $comprador_email;
    public $comprador_cpf;
    public $comprador_celular_ddd;
    public $comprador_celular_num;
    public $comprador_nascimento;
    public $comprador_nascimento_dd;
    public $comprador_nascimento_mm;
    public $comprador_nascimento_aaaa;
    public $comprador_ingressos_qtd;
    public $comprador_ingressos_valor;
    public $comprador_ingressos_valor_unitario;
    public $comprador_formapagamento;

    //
    public $comprador_endereco_cep;
    public $comprador_endereco_endereco;
    public $comprador_endereco_endereco_num;
    public $comprador_endereco_bairro;
    public $comprador_endereco_cidade;
    public $comprador_endereco_estado;

    //
    public $card_credit_num;
    public $card_credit_nome;
    public $card_credit_validade_mm;
    public $card_credit_validade_aaaa;
    public $card_credit_cvv;
    public $card_credit_parcelado;

    //
    public $card_credit_endereco_cep;
    public $card_credit_endereco;
    public $card_credit_endereco_complemento;
    public $card_credit_endereco_num;
    public $card_credit_endereco_bairro;
    public $card_credit_endereco_cidade;
    public $card_credit_endereco_estado;
    public $card_credit_endereco_enable=false;

    //
    public $ticketTypes;
    public $ticketTypesFinalFila;

    //
    public $ticketPrice;
    public $ticketTypeSelected;
    public $ticketTypeSelectedId;

    //
    public $ticket_code_promo;
    public $code_promo_selected;
    public $code_promo_label;
    public $code_promo_price_old;
    public $code_promo_price_new;
    public $code_promo_price_less;

    //
    public $debug=false;

    protected function messages()
    {
        $messages = [
            '*.required' => 'Obrigatório',
            '*.email'    => 'E-mail inválido',
            '*.cpf'      => 'CPF inválido',
            '*.ddd'      => 'DDD inválido',
            '*.integer'  => 'Deve ser um número',
            '*.string'   => 'Deve ser um texto',
        ];

        // Mensagens personalizadas para participantes
        $labelItem = $this->event->sales_label_item ?? 'participante';

        foreach (range(1, 20) as $range) {
            $messages["participantes.{$range}.required"] = "Nome do {$labelItem} " . ($range > 1 ? $range : '') . " é obrigatório";

            // Mensagens para questions dinâmicas
            if($this->event_questions_item ?? false) {
                foreach ($this->event_questions_item as $questions_key => $questions_item) {
                    $questionLabel = $questions_item['input_label'] ?? $questions_key;
                    $messages["participantes_questions.{$range}.{$questions_key}.required"] = "{$questionLabel} do {$labelItem} " . ($range > 1 ? $range : '') . " é obrigatório";
                    $messages["participantes_questions.{$range}.{$questions_key}.string"] = "{$questionLabel} do {$labelItem} " . ($range > 1 ? $range : '') . " deve ser um texto";
                }
            }
        }

        return $messages;
    }

    protected function validationAttributes()
    {
        $attributes = [
            'comprador_nome'            => 'Nome',
            'comprador_sobrenome'       => 'Sobrenome',
            'comprador_email'           => 'E-mail',
            'comprador_cpf'             => 'CPF',
            'comprador_celular_ddd'     => 'DDD',
            'comprador_celular_num'     => 'Celular',
            'comprador_nascimento_dd'   => 'Dia de nascimento',
            'comprador_nascimento_mm'   => 'Mês de nascimento',
            'comprador_nascimento_aaaa' => 'Ano de nascimento',
            'comprador_ingressos_qtd'   => 'Quantidade de ingressos',
        ];

        // Adiciona labels dinâmicas para participantes
        $labelItem = $this->event->sales_label_item ?? 'participante';

        foreach (range(1, 20) as $range) {
            $attributes["participantes.{$range}"] = "Nome do {$labelItem}" . ($range > 1 ? " {$range}" : '');

            // Adiciona labels para questions dinâmicas
            if($this->event_questions_item ?? false) {
                foreach ($this->event_questions_item as $questions_key => $questions_item) {
                    $questionLabel = $questions_item['input_label'] ?? $questions_key;
                    $attributes["participantes_questions.{$range}.{$questions_key}"] = "{$questionLabel}" . ($range > 1 ? " ({$labelItem} {$range})" : '');
                }
            }
        }

        return $attributes;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($slug=false)
    {
        // Detecta se está no modo app-version
        $this->isAppVersion = request()->is('app-version*');

        // Se está no modo app-version, carrega dados da sessão
        if ($this->isAppVersion) {
            session()->put('app_mode', true);
            $this->appCustomerId = session('app_customer_id');
            $this->appCustomerName = session('app_customer_name');

            // Se appUserId veio na URL, valida UUID e salva na sessão
            if ($this->appUserId && Str::isUuid($this->appUserId)) {
                session(['app_user_id' => $this->appUserId]);
            } else {
                $this->appUserId = '';
            }

            // Se há customer na sessão, aplica automaticamente no filtro
            if ($this->appCustomerId) {
                $this->filterCustomer = $this->appCustomerId;
            }

            // Se foi passado filterCustomer ou filterCustomerSlug via URL, salva na sessão
            if ($this->filterCustomer && $this->filterCustomer != $this->appCustomerId) {
                $this->saveCustomerToSession($this->filterCustomer);
            } elseif ($this->filterCustomerSlug && !$this->appCustomerId) {
                // Resolve o slug para ID e salva
                $customer = \App\Models\Customer::all(['id', 'name_corporate'])
                    ->first(function($c) {
                        return Str::slug($c->name_corporate) === $this->filterCustomerSlug;
                    });
                if ($customer) {
                    $this->saveCustomerToSession($customer->id);
                }
            }

            // Se não há customer selecionado, mostra tela de seleção
            if (!$this->appCustomerId && !$slug) {
                $this->showCustomerSelection = true;
            }
        }

        // SLUG
        $this->slug = strtolower($slug);

        // Carrega lista de empresas para filtro (apenas quando não há slug)
        if (!$this->slug) {
            $customerIds = DB::table('tev_events')
                ->where('active', true)
                ->distinct()
                ->pluck('customer_id')
                ->toArray();

            $this->customers = \App\Models\Customer::whereIn('id', $customerIds)
                ->orderBy('name_corporate')
                ->get(['id', 'name_corporate']);
        }

        // Se filterCustomerSlug foi passado, resolve o ID mas mantém o slug na URL
        if ($this->filterCustomerSlug && !$this->filterCustomer) {
            // Busca o ID do customer pelo slug, mas NÃO seta filterCustomer para manter o slug na URL
            // A conversão será feita apenas no loadEvents()
        }

        // BUSCA -DEBUG
        if(strpos($this->slug,'-debug') || strpos($this->slug,'debug'))
        {
            //
            if (sessionUserRole() && sessionUserRole() == 'admin')
            {
                $this->debug = true;
                $this->slug  = str_replace(['-debug','debug'],'',$this->slug);
            }
        }

        if($this->slug ?? false)
        {
            if ($this->event = Event::with(['customer','organizer','ticketsTypes','gatewayPay','gatewayPay.appGateway'])->where('event_slug', $this->slug)->first())
            {
                // FORMAS DE PAGAMENTO
                // $this->setFormasPagamento($this->event);

                // SE LABEL DO ITEM >> PREFIX
                if($this->event->sales_label_item ?? false)
                {
                    $this->participante_prefix = Str::slug($this->event->sales_label_item,'_');
                }
                else
                {
                    $this->participante_prefix = 'participante';
                }

                // QUESTIOAMENTOS COMPRADOR >> MONTA CAMPOS ADICIONAIS
                if($this->event->questions_user_json ?? false)
                {
                    $this->event_questions_buyer = json_decode($this->event->questions_buyer_json ?? '{}',true);
                }

                // QUESTIOAMENTOS PARTICIPANTES >> MONTA CAMPOS ADICIONAIS
                if($this->event->questions_user_json ?? false)
                {
                    $this->event_questions_item = json_decode($this->event->questions_user_json ?? '{}',true);
                    //
                    if($this->event_questions_item['campos'] ?? false)
                    {
                        $this->event_questions_item = $this->event_questions_item['campos'];
                    }
                    else
                    {
                        $this->event_questions_item = [];
                    }

                    // SE QUESTIONS ITEM - Inicializa array de questions para participantes
                    foreach($this->event_questions_item ?? [] as $questions_key => $questions_item)
                    {
                        // Inicializa array para MAX 20 participantes
                        foreach (range(1 , 20) as $range)
                        {
                            if (!isset($this->participantes_questions[$range])) {
                                $this->participantes_questions[$range] = [];
                            }
                            $this->participantes_questions[$range][$questions_key] = null;
                        }
                    }
                }
            }
            else
            {
                session()->flash('error','Evento não encontrado');
                return redirect()->route($this->isAppVersion ? 'app-version-home' : 'eventos-home');
            }
        }
    }

    public function updatedSearch()
    {
        if (!$this->slug) {
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    public function updatedFilterStatus()
    {
        if (!$this->slug) {
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    public function updatedFilterCustomer()
    {
        if (!$this->slug) {
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    public function updatedFilterOrganizer()
    {
        if (!$this->slug) {
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    public function updatedSortBy()
    {
        if (!$this->slug) {
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    public function filterChanged($filters)
    {
        if (!$this->slug) {
            $this->filterStatus = $filters['status'] ?? 'ativas';

            // No modo app-version, não altera o customer (é fixo pela sessão)
            if (!$this->isAppVersion) {
                $this->filterCustomer = $filters['customer'] ?? '';
            }

            $this->filterOrganizer = $filters['organizer'] ?? '';
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    public function searchChanged($search)
    {
        if (!$this->slug) {
            $this->search = $search;
            $this->currentPage = 1;
            $this->loadEvents();
        }
    }

    /**
     * Salva customer selecionado na sessão (modo app-version)
     */
    protected function saveCustomerToSession($customerId)
    {
        $customer = \App\Models\Customer::find($customerId);
        if ($customer) {
            $customerSlug = Str::slug($customer->name_corporate);
            session([
                'app_customer_id' => $customer->id,
                'app_customer_name' => $customer->name_corporate,
                'app_mode' => true,
                'app_source' => $customerSlug,
            ]);
            $this->appCustomerId = $customer->id;
            $this->appCustomerName = $customer->name_corporate;
            $this->appSource = $customerSlug;
            $this->filterCustomer = $customer->id;
            $this->showCustomerSelection = false;
        }
    }

    /**
     * Submit do formulário de seleção de empresa (modo app-version)
     */
    public function submitCustomerSelection()
    {
        if (!$this->isAppVersion) {
            return;
        }

        // Valida empresa e appUserId juntos
        $rules = [
            'selectedCustomerId' => 'required|uuid',
        ];
        $messages = [
            'selectedCustomerId.required' => 'Selecione uma empresa.',
            'selectedCustomerId.uuid' => 'Empresa inválida.',
        ];

        // Se appUserId foi preenchido, exige formato UUID
        if (!empty($this->appUserId)) {
            $rules['appUserId'] = ['regex:/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i'];
            $messages['appUserId.regex'] = 'O ID do usuário deve ser um UUID válido.';
        }

        $this->validate($rules, $messages);

        // Salva appUserId na sessão (se válido)
        if ($this->appUserId && Str::isUuid($this->appUserId)) {
            session(['app_user_id' => $this->appUserId]);
        }

        $this->saveCustomerToSession($this->selectedCustomerId);
        return redirect()->route('app-version-home');
    }

    /**
     * Permite usuário selecionar customer manualmente (modo app-version)
     * @deprecated Use submitCustomerSelection() com formulário
     */
    public function selectCustomer($customerId)
    {
        if ($this->isAppVersion) {
            if ($this->appUserId && Str::isUuid($this->appUserId)) {
                session(['app_user_id' => $this->appUserId]);
            }
            $this->saveCustomerToSession($customerId);
            return redirect()->route('app-version-home');
        }
    }

    protected function loadEvents()
    {
        $query = Event::with([
                'customer',
                'organizer',
                'organizer.customer:id,name_corporate',
                'organizer.organization:id,organization_name',
            ])
            ->where('active', true)
            ->whereHas('customer');

        // Filtro de status
        if ($this->filterStatus === 'ativas') {
            $query->where(function($q) {
                $q->whereNull('event_datetime_finish')
                    ->orWhere('event_datetime_finish', '>=', now()->format('Y-m-d H:i:s'));
            });
        } elseif ($this->filterStatus === 'finalizadas') {
            $query->where('event_datetime_finish', '<', now()->format('Y-m-d H:i:s'));
        }
        // Se for 'todas', não aplica filtro de data

        // Filtro por empresa (modo app-version prioriza sessão)
        if ($this->isAppVersion && $this->appCustomerId) {
            // No modo app-version, sempre usa o customer da sessão
            $query->where('customer_id', $this->appCustomerId);
        } elseif ($this->filterCustomer) {
            // Modo normal: usa filtro de URL
            $query->where('customer_id', $this->filterCustomer);
        } elseif ($this->filterCustomerSlug) {
            // Busca customer pelo slug dinamicamente
            $customers = \App\Models\Customer::all(['id', 'name_corporate']);
            foreach ($customers as $customer) {
                if (Str::slug($customer->name_corporate) === $this->filterCustomerSlug) {
                    $query->where('customer_id', $customer->id);
                    break;
                }
            }
        }

        // Filtro por organizador
        if ($this->filterOrganizer) {
            $query->where('organizer_id', $this->filterOrganizer);
        }

        // Busca por nome
        if ($this->search) {
            $query->where(function($q) {
                $q->where('event_name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('event_description', 'ilike', '%' . $this->search . '%');
            });
        }

        // Conta total de eventos
        $this->totalEvents = $query->count();

        // Ordenação
        // Para colunas de data usamos orderByRaw com NULLS LAST/FIRST para evitar
        // que registros sem data flutuem para o topo (comportamento padrão do PostgreSQL).
        // event_datetime_start_asc  = mais próximos primeiro (padrão)
        // event_datetime_start       = mais distantes primeiro
        $rawSortMap = [
            'event_datetime_start_asc' => 'event_datetime_start ASC NULLS LAST',
            'event_datetime_start'     => 'event_datetime_start DESC NULLS LAST',
            'event_name_asc'           => null,
            'event_name_desc'          => null,
            'created_at'               => 'created_at DESC NULLS LAST',
        ];
        $plainSortMap = [
            'event_name_asc'  => ['event_name', 'asc'],
            'event_name_desc' => ['event_name', 'desc'],
        ];

        if (isset($rawSortMap[$this->sortBy]) && $rawSortMap[$this->sortBy] !== null) {
            $query->orderByRaw($rawSortMap[$this->sortBy]);
        } elseif (isset($plainSortMap[$this->sortBy])) {
            [$sortField, $sortDir] = $plainSortMap[$this->sortBy];
            $query->orderBy($sortField, $sortDir);
        } else {
            $query->orderByRaw('event_datetime_start ASC NULLS LAST');
        }

        // Carrega eventos paginados
        $this->eventList = $query
            ->take($this->perPage * $this->currentPage)
            ->get();
    }

    public function loadMore()
    {
        $this->currentPage++;
        $this->loadEvents();
    }

    public function getHasMoreProperty()
    {
        return $this->eventList->count() < $this->totalEvents;
    }

    public function render()
    {
        // SE SESSION PEDIDO
        if(sessionPedido())
        {
            $this->order_json = sessionPedido();
        }

        // SE PEDIDO E PENDENTE PAGAMENTO
        if(($this->order_json ?? false) && ($this->order_json['status']) && in_array($this->order_json['status'], listOrderStatusIrProPagamento()))
        {
            $layout = $this->isAppVersion ? 'layouts.app-version-detail' : 'layouts.app-pep-home';
            return view('livewire.evento.evento-pagamento')->layout($layout);
        }

        // SE EVENTO
        if($this->event)
        {
            // SE TIPO SELECIONADO
            if($this->ticketTypeSelected ?? false)
            {
                $this->calculaValor();
            }
            else
            {
                $this->ticketTypes          = [];
                $this->ticketTypesFinalFila = [];

                // PERCORRE LOTES
                foreach ($this->event->ticketsTypes->sortBy('sale_start_datetime')->sortBy('view_order') ?? [] as $ticketType)
                {
                    // SE VISIVEL
                    if(!$ticketType->visible ?? false)
                        continue;

                    // SE VISIVEL
                    if(!$ticketType->lote_publico ?? false)
                        continue;

                    // VALIDA QUANDO ESGOTADO
                    $ticketType->esgotado = false;

                    // SE QTD MAX
                    if(((int) $this->event->sales_amount_max ?? 0) > 0)
                    {
                        // SE TICKETS DISPONIVEIS/UTILIZADOS >= QTD MAX
                        if($this->event->tickets->whereIn('ticket_status',ticketStatusCapacidade())->count() >= (int) $this->event->sales_amount_max)
                        {
                            $ticketType->esgotado = true;
                            $this->ticketTypesFinalFila[$ticketType->id] = $ticketType;
                            continue;
                        }
                    }

                    // SE QTD MAX LOTE
                    if(((int) $ticketType->amount ?? 0) > 0)
                    {
                        // SE TICKETS DISPONIVEIS/UTILIZADOS >= QTD MAX LOTE
                        if($this->event->tickets->where('event_ticket_id',$ticketType->id)->whereIn('ticket_status',ticketStatusCapacidade())->count() >= (int) $ticketType->amount)
                        {
                            $ticketType->esgotado = true;
                            $this->ticketTypesFinalFila[$ticketType->id] = $ticketType;
                            continue;
                        }
                    }

                    // LOTE FECHADO
                    $ticketType->loteFechado = false;

                    // SE PERIODO = DATA
                    if(strtolower($ticketType->sale_period_type) == 'data')
                    {
                        //  SE HOJE < DATA INICIO
                        if(!$ticketType->sale_view_grid_pre && now()->format('YmdHi') < $ticketType->sale_start_datetime->format('YmdHi'))
                            continue;

                        //  SE HOJE > DATA FIM
                        if(!$ticketType->sale_view_grid_pos && now()->format('YmdHi') > $ticketType->sale_finish_datetime->format('YmdHi'))
                        {
                            $ticketType->loteFechado = true;
                            $this->ticketTypesFinalFila[$ticketType->id] = $ticketType;
                            continue;
                        }
                    }

                    //
                    $this->ticketTypes[$ticketType->id] = $ticketType;
                }

                //
                if(count($this->ticketTypesFinalFila ?? []))
                {
                    foreach ($this->ticketTypesFinalFila as $ticketTypeFinal)
                    {
                        $this->ticketTypes[$ticketTypeFinal->id] = $ticketTypeFinal;
                    }
                }
            }

            // Usa layout do app-version-detail (sem filtros) ou web normal
            $layout = $this->isAppVersion ? 'layouts.app-version-detail' : 'layouts.app-pep-home';
            return view('livewire.app-evento')->layout($layout);
        }

        // Se não há slug, carrega lista de eventos com filtros
        if (!$this->slug) {
            // Se modo app-version E precisa mostrar seleção de customer
            if ($this->isAppVersion && $this->showCustomerSelection) {
                // Carrega todos os customers para seleção
                $this->customers = \App\Models\Customer::orderBy('name_corporate')->get(['id', 'name_corporate']);
                return view('livewire.app-version.customer-selection')->layout('layouts.app-version');
            }

            $this->loadEvents();
            $layout = $this->isAppVersion ? 'layouts.app-version' : 'layouts.app-pep-guest';
            return view('livewire.app-evento-home')->layout($layout);
        }

        // Default (com layout app-version-detail se aplicável)
        $layout = $this->isAppVersion ? 'layouts.app-version-detail' : 'layouts.app-pep-home';
        return view('livewire.app-evento')->layout($layout);
    }

    public function concluirCompra()
    {
        // RESET
        $this->order = false;

        // RULE
        $rules = [
            "comprador_nome"            => ['required'],
            "comprador_sobrenome"       => ['required'],
            "comprador_email"           => ['required', 'email'],
            "comprador_cpf"             => ['required', 'cpf'],
            "comprador_celular_ddd"     => ['required', 'ddd'],
            "comprador_celular_num"     => ['required', 'integer'],
            "comprador_nascimento_dd"   => ['required'],
            "comprador_nascimento_mm"   => ['required'],
            "comprador_nascimento_aaaa" => ['required'],
            "comprador_ingressos_qtd"   => ['required', 'integer'],
        ];

        // VALIDATE - PERGUNTAS COMPRADOR
        $questions_buyer = [];
        if($this->event_questions_buyer ?? false)
        {
            foreach ($this->event_questions_buyer ?? [] as $questions_key => $questions_item)
            {
                $arrayRule = [];

                // SE OBRIGATORIO
                $arrayRule[] = ($questions_item['input_required'] ?? false) ? 'required' : 'nullable';

                // TIPOS
                switch ($questions_item['input_type'])
                {
                    case 'text':
                        $arrayRule[] = 'string';
                        break;
                }

                //
                $rules[$questions_key]           = $arrayRule;
                $questions_buyer[$questions_key] = $questions_item;
            }
        }

        // RULE - PARTICIPANTE (usando arrays)
        $questions_user = [];
        foreach (range(1, $this->comprador_ingressos_qtd ?? 1) as $range)
        {
            // Validação do nome do participante usando array
            $rules["participantes.{$range}"] = ['required', new NomeCompleto];

            // VALIDATE - PERGUNTAS PARTICIPANTE
            if($this->event_questions_item ?? false)
            {
                //
                foreach ($this->event_questions_item ?? [] as $questions_key => $questions_item)
                {
                    if(in_array($this->ticketTypeSelected->id, $questions_item['input_hidden_lotes'] ?? []))
                    {
                        continue;
                    }

                    $arrayRule = [];

                    // SE OBRIGATORIO
                    $arrayRule[] = ($questions_item['input_required'] ?? false) ? 'required' : 'nullable';

                    // TIPOS
                    switch ($questions_item['input_type'])
                    {
                        case 'text':
                            $arrayRule[] = 'string';
                            break;
                    }

                    //
                    $rules["participantes_questions.{$range}.{$questions_key}"] = $arrayRule;
                    $questions_user[$range][$questions_key] = $questions_item;
                }
            }
        }

        // VALIDATE
        try {
            $validatedData = $this->validate($rules);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $errorCount = count($errors);

            if ($errorCount === 1) {
                $description = $errors[0];
            } else {
                $description = "Existem {$errorCount} erros que precisam ser corrigidos. Verifique os campos destacados.";
            }

            $this->dialog()->show([
                'title'       => 'ATENÇÃO',
                'description' => $description,
                'icon'        => 'error',
            ]);

            throw $e;
        }

        // CHECK CAPACIDADE
        // TODO: CRIAR

        // GET TICKETS
        $tickets = AppEventOrderTicket::where('event_id',$this->event->id)->whereIn('ticket_status',ticketStatusCapacidade())->get();

        // CHECK CAPACIDADE TOTAL
        $capacidadeTotalDisp = ($this->event->sales_amount_max ?? 0) - ($tickets->count() ?? 0);
        if($capacidadeTotalDisp < 1)
        {
            $this->cancelTicketType();
            session()->flash("conclusao_error", __('event_capacidade_max'));
            session()->flash("conclusao_error_sub", __('event_capacidade_max_sub'));
            return;
        }

        // CHECK CAPACIDADE LOTE
        $capacidadeLoteDisp = ($this->ticketTypeSelected->amount ?? 0) - ($tickets->where('event_ticket_id',$this->ticketTypeSelected->id)->count());
        //
        if($capacidadeLoteDisp < 1)
        {
            $this->cancelTicketType();
            session()->flash("conclusao_error", __('event_capacidade_max_lote'));
            session()->flash("conclusao_error_sub", __('event_capacidade_max_lote_sub'));
            return;
        }

        // CHECK CAPACIDADE x INGRESSOS QTD
        if($capacidadeTotalDisp < (int) $validatedData['comprador_ingressos_qtd'] || $capacidadeLoteDisp < (int) $validatedData['comprador_ingressos_qtd'])
        {
            // $this->cancelTicketType();
            session()->flash("conclusao_error", __('event_capacidade_compra_max'));
            session()->flash("conclusao_error_sub", __('event_capacidade_compra_max_sub'));
            return;
        }

        try
        {
            // APPEND
            $this->comprador_nascimento               = Carbon::create($this->comprador_nascimento_aaaa.'-'.$this->comprador_nascimento_mm.'-'.$this->comprador_nascimento_dd);
            $validatedData['comprador_nascimento']    = $this->comprador_nascimento->format('Y-m-d');
            $validatedData['comprador_nome_completo'] = strtolower(trim($this->comprador_nome) . ' ' . trim($this->comprador_sobrenome));
            $validatedData['url_comprar_novamente']   = url('/' . $this->event->event_slug);

            DB::beginTransaction();

            // GERAR NUMERO DO PEDIDO
            $this->orderNum = "EV." . now()->format('ymds') . "." . strtoupper(hash('adler32', $this->event->event_slug . $this->comprador_cpf . now()->timestamp));

            // SET AGORA
            $agora = now();

            // REGISTRA PEDIDO
            $orderCreate = [
                'event_id'                    => $this->event->id,
                'status'                      => $this->orderPrice ? 'fase_pagamento' : 'concluido',
                'order_control'               => $this->orderNum,
                'order_generation_datetime'   => now()->format('Y-m-d H:i:s'),
                'payment_id'                  => null,
                'order_amount'                => $this->orderPrice ?? 0,
                'order_amount_pay'            => 0,
                'buyer_name'                  => $validatedData['comprador_nome_completo'],
                'buyer_email'                 => strtolower($validatedData['comprador_email']),
                'buyer_doc_type'              => "cpf",
                'buyer_doc_num'               => $validatedData['comprador_cpf'],
                'buyer_contact_country'       => 55,
                'buyer_contact_ddd'           => (int) preg_replace('/\D/', '', $validatedData['comprador_celular_ddd']),
                'buyer_contact_num'           => (int) preg_replace('/\D/', '', $validatedData['comprador_celular_num']),
                'buyer_birth_date'            => $validatedData['comprador_nascimento'],
                'buyer_json_answers'          => null,
                'channel_order'               => config('domains.eventos') . '/' . $this->event->event_slug,
                'order_items_ticket_type_id'  => $this->ticketTypeSelected->id ?? null,
                'order_items_qtd'             => (int) $validatedData['comprador_ingressos_qtd'],
                'order_items_amount'          => $this->orderPrice ?? null,
                'order_items_amount_total'    => null,
                'order_description'           => null,
                'code_promo_id'               => null,
                'code_promo_discount_amount'  => 0,
                'reservation_expiration_date' => $agora->addMinutes(30),
            ];

            // CRIA ORDEM
            $order = AppEventOrder::create($orderCreate);

            // SET ORDER JSON
            $this->order_json                    = [];
            $this->order_json['localizador']     = $this->orderNum;
            $this->order_json['status']          = $order->status;
            $this->order_json['order_type']      = 'evento';
            $this->order_json['order_data']      = $order->toArray();

            // PERCORRE PARTICIPANTES
            $loopCount = 0;
            $this->order_json['order_data']['itens'] = [];
            //
            foreach (range(1,$this->comprador_ingressos_qtd ?? 1) as $compradorRange)
            {
                // INCREMENTO
                $loopCount++;

                // DESCRIPTION
                $description = strtoupper(($this->event->event_name ?? 'COMPRA') . ' // ' . ($this->ticketTypeSelected->ticket_name ?? 'TICKET'));

                // SE EXISTE QUESTIONS USER ITEM
                if($questions_user[$loopCount] ?? false)
                {
                    // PERCORRE QUESTIONS e pega do array validado
                    foreach ($questions_user[$loopCount] as $questionKey => $questionValues)
                    {
                        // Pega do array participantes_questions validado
                        if(isset($validatedData['participantes_questions'][$loopCount][$questionKey]))
                        {
                            $questions_user[$loopCount][$questionKey] = $validatedData['participantes_questions'][$loopCount][$questionKey];
                        }
                    }

                    // CONVERT JSON
                    $user_json_answers = json_encode($questions_user[$loopCount]);
                }

                // ITENS
                $orderItemCreate = AppEventOrderItem::create([
                    'order_id'             => $order->id,
                    'item_ticket_type_id'  => $this->ticketTypeSelected->id,
                    'item_description'     => $description,
                    'item_status'          => 'adicionado',
                    'item_amount'          => (int) $this->ticketTypeSelected->price ?? 0,
                    'user_name'            => strtolower(trim(($validatedData['participantes'][$compradorRange]) ?? $this->comprador_nome)),
                    'user_email'           => strtolower(trim($this->comprador_email)),
                    'user_doc_type'        => $this->comprador_cpf ? 'CPF' : null,
                    'user_doc_num'         => $this->comprador_cpf ?? null,
                    'user_contact_country' => 55,
                    'user_contact_ddd'     => (int) preg_replace('/\D/', '', $this->comprador_celular_ddd),
                    'user_contact_num'     => (int) preg_replace('/\D/', '', $this->comprador_celular_num),
                    'user_json_answers'    => $user_json_answers ?? null,
                ]);

                $this->order_json['order_data']['itens'][$loopCount] = $orderItemCreate->toArray();

                // MONTA CREATE TICKET
                $orderTicketCreate = [
                    'order_id'                   => $order->id,
                    'order_item_id'              => $orderItemCreate->id ?? null,
                    'organizer_id'               => $this->event->organizer_id,
                    'organizer_name'             => $this->event->organizer->organizer_name,
                    'event_id'                   => $this->event->id,
                    'event_name'                 => strtoupper($this->event->event_name ?? null),
                    'event_description'          => strtoupper($description),
                    'event_datetime'             => $this->event->event_datetime_start ?? null,
                    'event_ticket_id'            => $this->ticketTypeSelected->id ?? null,
                    'event_ticket_slug'          => $this->ticketTypeSelected->ticket_slug ?? null,
                    'event_ticket_name'          => strtoupper($this->ticketTypeSelected->ticket_name ?? null),
                    'event_ticket_price'         => $this->ticketTypeSelected->price ?? null,
                    'event_ticket_price_paid'    => $this->ticketTypeSelected->price ?? null,
                    'ticket_control'             => $this->orderNum . '-' . $loopCount,
                    'ticket_status'              => ($this->orderPrice ?? 0) ? "reserva_temp" : 'disponivel',
                    'ticket_generation_datetime' => now()->format('Y-m-d H:i:s'),
                    'user_name'                  => strtolower(trim(($validatedData['participantes'][$compradorRange]) ?? $this->comprador_nome)),
                    'user_email'                 => strtolower(trim($this->comprador_email)),
                    'user_doc_type'              => $this->comprador_cpf ? 'CPF' : null,
                    'user_doc_num'               => $this->comprador_cpf ?? null,
                    'user_contact_country'       => 55,
                    'user_contact_ddd'           => (int) preg_replace('/\D/', '', $this->comprador_celular_ddd),
                    'user_contact_num'           => (int) preg_replace('/\D/', '', $this->comprador_celular_num),
                    'user_json_answers'          => $user_json_answers ?? null,
                ];

                $orderTicket = AppEventOrderTicket::create($orderTicketCreate);
                // SANTOS BD CLEAR // $this->order_json['order_data']['tickets'][$loopCount] = $orderTicket->toArray();
            }

            // SE TEM VALOR A PAGAR
            if($this->orderPrice ?? false)
            {
                // SAVE JSON
                $order->order_json = json_encode($this->order_json);
                $order->save();

                // CONCLUI
                DB::commit();

                // SALVA DADOS SESSION PARA PAGAMENTO
                sessionPedido($this->order_json);

                // REDIRECT PARA PAGAMENTO via RENDER
                return;
            }
            else
            {
                // ENVIA NOTIFICAÇÃO - EMAIL
                $job = NotificationAppEventCompra::dispatch($this->order_json['order_data'], $validatedData['comprador_email'], 'noreply@proeventpay.com.br');

                // CONCLUI
                DB::commit();

                // ALERT SUCESSO
                session()->flash('conclusao_success', 'Compra realizada com sucesso');
                session()->flash('conclusao_success_sub','LOCALIZADOR: ' . $order->order_control);
            }
        }
        catch (\Throwable $th)
        {
            DB::rollBack();

            $this->order = false;

            session()->flash('conclusao_error',$th->getMessage());
            session()->flash('conclusao_error_sub','CÓDIGO: ' . $th->getCode());
        }
    }

    public function setTicketType($ticketTypeSelectedId)
    {
        $this->comprador_ingressos_qtd            = 0;
        $this->ticketTypeSelectedId               = $ticketTypeSelectedId;
        $this->ticketTypeSelected                 = $this->event->ticketsTypes->where('id', $ticketTypeSelectedId)->first();
        $this->ticketPrice                        = $this->ticketTypeSelected->price;
        $this->comprador_ingressos_valor_unitario = $this->ticketTypeSelected->price;
        //
        $this->participantesRange = range($this->ticketTypeSelected->sale_amount_min ?? 1, $this->ticketTypeSelected->sale_amount_max ?? 10);
        $this->listaParticipantes = [];

        // INICIALIZA ARRAYS DINAMICAMENTE
        foreach (range(1,(int) $this->ticketTypeSelected->sale_amount_max ?? 10) as $rangeValue)
        {
            $this->participantes[$rangeValue] = null;

            // Inicializa questions se existirem
            if($this->event_questions_item ?? false)
            {
                foreach($this->event_questions_item as $questions_key => $questions_item)
                {
                    if (!isset($this->participantes_questions[$rangeValue])) {
                        $this->participantes_questions[$rangeValue] = [];
                    }
                    $this->participantes_questions[$rangeValue][$questions_key] = null;
                }
            }
        }

        // LISTA POSSIBILIDADE PARTICIPANTES
        foreach ($this->participantesRange as $rangeValue)
        {
            // $qtdParticipante = str_pad($rangeValue , 2 , '0' , STR_PAD_LEFT);
            $qtdParticipante = $rangeValue;
            $valor           = $this->ticketPrice * $rangeValue;
            $valorLabel      = toMoney($valor,'R$ ');

            $itemLabel = ($rangeValue > 1) ? ($this->event->sales_label_item_multiple ?? 'participantes') : ($this->event->sales_label_item ?? 'participante');

            $this->listaParticipantes[$rangeValue] = [
                'label'      => (count($this->listaParticipantes)) ? "{$qtdParticipante} {$itemLabel} - {$valorLabel}" : "{$qtdParticipante} {$itemLabel} - {$valorLabel}",
                'valor'      => $valor,
                'valorLabel' => $valorLabel
            ];
        }
        // dd($this->listaParticipantes);
    }

    public function cancelTicketType()
    {
        $this->ticketTypeSelectedId      = false;
        $this->ticketTypeSelected        = false;
        //
        $this->card_credit_endereco_cep  = null;
        $this->card_credit_num           = null;
        $this->card_credit_nome          = null;
        $this->card_credit_validade_mm   = null;
        $this->card_credit_validade_aaaa = null;
        $this->card_credit_cvv           = null;
        $this->card_credit_parcelado     = 1;
    }

    public function updated($name, $value)
    {
        if($name == 'comprador_ingressos_qtd')
        {
            $this->calculaValor();
        }
    }

    public function calculaValor()
    {
        $this->orderPrice = ($this->ticketTypeSelected->price ?? 0) * ($this->comprador_ingressos_qtd ?? 0);
        return $this->orderPrice;
    }

    public function buscaEndereco($comprador_endereco_cep=false)
    {
        if($comprador_endereco_cep)
            $this->comprador_endereco_cep = $comprador_endereco_cep;

        if(!$this->comprador_endereco_cep)
        {
            session()->flash('error','CEP precisa ser preenchido');
            return;
        }

        $this->card_credit_endereco_enable = false;

        $buscaCep = buscarCep($this->comprador_endereco_cep);


        // if($buscaCep->error)
        // {
        //     session()->flash('error', $buscaCep->msg ?? 'Erro ao cunsutar CEP. Tente novamente');
        //     return;
        // }

        if($buscaCep->error && empty($buscaCep->cidade))
        {
            session()->flash('error', $buscaCep->msg ?? 'Erro ao cunsutar CEP. Tente novamente');
            return;
        }
        else
        {
            $this->card_credit_endereco_enable = true;
            session()->flash('info', 'Endereço não lozalizado! Informe manualmente no campo acima.');
            return;
        }

        // $this->card_credit_endereco        = $buscaCep->endereco ?? null;
        // $this->card_credit_endereco_estado = ($buscaCep->cidade ?? '--') . '/' . ($buscaCep->estado ?? '--');

        $this->card_credit_endereco = ($buscaCep->endereco ?? null) . ' - ' . ($buscaCep->cidade ?? '--') . '/' . ($buscaCep->estado ?? '--');

        return $buscaCep;
    }
}
