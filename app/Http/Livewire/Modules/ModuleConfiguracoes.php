<?php

namespace App\Http\Livewire\Modules;

use App\Models\AppModule;
use App\Models\AppPayGateway;
use App\Models\Customer;
use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\CustomerAppModule;
use App\Models\CustomerPayGateway;
use App\Models\CustomerPayGatewayFee;
use App\Models\RefBanco;
use App\Models\CustomerOrganizerUser;
use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Models\ModEvent\Event;
use App\Models\UserCustomer;
use App\Models\UserCampaignOrganizer;
use App\Scopes\ActiveCustomerScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

class ModuleConfiguracoes extends Component
{
    public $app;
    public $appUserRole;
    public $appModules;
    //
    public $customers;
    public $customer;
    public $customerId;
    public $updateKey = 0; // Para forçar re-render
    //
    public $organizations;
    public $organization;
    public $organizationId;
    //
    public $organizationSubs;
    public $organizationSub;
    public $organizationSubId;
    //
    public $organizers;
    public $organizer;
    public $organizerId;
    //
    public $referer;
    //
    public $activeTab = 'cliente';
    public $customerUsers = [];
    public $customerUsersSnapshot = [];
    public $customerModules = [];
    public $allModules = [];
    public $customerModuleIds = [];

    // Modal de cliente
    public $showCustomerModal = false;
    public $isEditingCustomer = false;

    // Dados do cliente para criar/editar
    public $customerNameCorporate = '';
    public $customerNameFantasy = '';
    public $customerNameShort = '';
    public $customerDocType = 'cnpj';
    public $customerDocNum = '';
    public $customerSlug = '';
    public $customerComercialContactName = '';
    public $customerComercialContactEmail = '';
    public $customerComercialContactDdd = '';
    public $customerComercialContactNum = '';
    public $customerFinancialContactName = '';
    public $customerFinancialContactEmail = '';
    public $customerFinancialContactDdd = '';
    public $customerFinancialContactNum = '';
    public $customerAddress = '';
    public $customerAddressNumber = '';
    public $customerAddressComplement = '';
    public $customerCityNeighborhood = '';
    public $customerCity = '';
    public $customerState = '';
    public $customerZipCode = '';
    public $customerUrlSite = '';
    public $customerUrlInstagram = '';
    public $customerUrlFacebook = '';
    public $customerGenerateInvoice = false;
    public $standaloneCreate = false;
    public $standaloneEdit = false;
    public $confirmingCustomerDeletion = false;

    // Gateways
    public $customerGateways = [];
    public $customerGatewaysInactive = [];
    public $availableGateways = [];
    public $availableBanks = [];
    public $hasCodSubcontaIdColumn = false;

    // Filtros de Gateway
    public $filterGatewaySearch = '';
    public $filterPayBoleto = false;
    public $filterPayPix = false;
    public $filterPaySlipPix = false;
    public $filterPayCardDebit = false;
    public $filterPayCardCredit = false;
    public $filterUseEvents = false;
    public $filterUseCampaigns = false;
    public $filterHideFees = false;

    // Modal de edição de usuário
    public $showEditModal = false;
    public $selectedUserId = null;
    public $selectedUser = null;

    // Dados do usuário para edição
    public $editName = '';
    public $editEmail = '';
    public $editUserRole = '';
    public $editCanEvents = false;
    public $editCanCampaigns = false;
    public $editCanSubscriptions = false;

    // Alteração de senha
    public $showPasswordSection = false;
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    // Confirmação de remoção
    public $showDeleteConfirmation = false;

    // Modal de criação de novo usuário
    public $showNewUserModal = false;

    // Dados do novo usuário
    public $newUserName = '';
    public $newUserEmail = '';
    public $newUserPassword = '';
    public $newUserPasswordConfirmation = '';
    public $newUserRole = 'user';
    public $newUserCanEvents = false;
    public $newUserCanCampaigns = false;
    public $newUserCanSubscriptions = false;

    // Modal de gateway
    public $selectedGatewayId = null;
    public $selectedGateway = null;
    public $isEditingGateway = false;

    // Dados do gateway
    public $gatewayPayGatewayId = '';
    public $gatewayPayGatewaySlug = '';
    public $gatewayPayGatewayLabel = '';
    public $gatewayPayGatewayDescription = '';
    public $gatewayCodSubcontaId = '';
    public $gatewayContaCod = '';
    public $gatewayContaBanco = '';
    public $gatewayContaBancoDescricao = '';
    public $gatewayContaTipo = '';
    public $gatewayContaAgencia = '';
    public $gatewayContaAgenciaDv = '';
    public $gatewayContaNumero = '';
    public $gatewayContaNumeroDv = '';
    public $gatewayTokenLive = '';
    public $gatewayTokenLivePass = '';
    public $gatewayTokenTest = '';
    public $gatewayTokenTestPass = '';
    public $gatewayPayBoleto = false;
    public $gatewayPayPix = false;
    public $gatewayPaySlipPix = false;
    public $gatewayPayCardDebit = false;
    public $gatewayPayCardCredit = false;
    public $gatewayPayCardCreditInstallmentMax = 1;
    public $gatewayPayCardCreditInstallmentAmountMin = 500;
    public $gatewayPaySlipPixInstallmentMax = 1;
    public $gatewayPaySlipPixInstallmentAmountMin = 1000;
    public $gatewayPayActive = true;
    public $gatewayUseEvents = true;
    public $gatewayUseCampaigns = true;
    public $gatewaySplitPay = false;
    public $gatewaySplitMode = '';
    public $gatewaySplitLiveRecipientId = '';
    public $gatewaySplitTestRecipientId = '';

    // Confirmação de remoção de gateway
    public $showDeleteGatewayConfirmation = false;

    // Taxas de parcelamento (JSON)
    public $gatewayInstallmentFeesJson = '';

    // Taxas do Slip PIX (JSON)
    public $gatewaySlipPixFeesJson = '';

    // Taxas do PIX (JSON)
    public $gatewayPixFeesJson = '';

    // Taxas do Boleto (JSON)
    public $gatewayBoletoFeesJson = '';

    // Valores fixos por transação (centavos)
    public $gatewayBoletoFixedAmount = null;
    public $gatewayPixFixedAmount = null;
    public $gatewaySlipPixFixedAmount = null;
    public $gatewayCreditFixedAmount = null;

    // Modal de taxas de parcelamento
    public $showInstallmentFeesModal = false;
    public $selectedGatewayForInstallmentFees = null;

    // Modal de taxas do Slip PIX
    public $showSlipPixFeesModal = false;
    public $selectedGatewayForSlipPixFees = null;

    // Modal de taxas do PIX
    public $showPixFeesModal = false;
    public $selectedGatewayForPixFees = null;

    // Modal de taxas do Boleto
    public $showBoletoFeesModal = false;
    public $selectedGatewayForBoletoFees = null;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function changeTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function mount($standaloneCreate = false, $standaloneEdit = false)
    {
        // APP
        $this->app = sessionApp();
        $this->standaloneCreate = (bool) ($standaloneCreate ?: request()->routeIs('configuracoes-novo-cliente'));
        $this->standaloneEdit = (bool) ($standaloneEdit ?: request()->routeIs('configuracoes-editar-cliente'));
        $requestedTab = (string) request('tab', '');
        if (in_array($requestedTab, ['cliente', 'modulos', 'usuarios', 'gateways'], true)) {
            $this->activeTab = $requestedTab;
        }

        // SE NAO ADMIN
        if (!isAdmin()) {
            session()->flash('error', 'Acesso Negado');
            return redirect()->route('dashboard');
        }

        // GET REFERER
        $this->referer = sessionReferer();
        //
        // Verifica customer_id na URL (path param novo + query param legado)
        $customerIdFromUrl = request()->route('customer_id') ?: request('customer_id');
        if ($customerIdFromUrl) {
            $this->customerId = $customerIdFromUrl;
            sessionClear('customer');
            sessionCustomer($this->customerId);
            \Log::info("Mount - Customer ID da URL: " . $this->customerId);
        } else {
            // Não carrega automaticamente da sessão - força usuário a selecionar
            $this->customer = null;
            $this->customerId = null;
            \Log::info("Mount - CustomerId inicial: VAZIO (como solicitado)");
        }

        \Log::info("Mount - Customer: " . ($this->customerId ? 'ID: ' . $this->customerId : 'Nenhum (usuário deve selecionar)'));

        // Página dedicada de edição de cliente
        if ($this->standaloneEdit) {
            if (!$this->loadCustomerToEditForm()) {
                return redirect()->route('configuracoes');
            }
        }
        //
        $this->organization = sessionOrganization();
        $this->organizationId = $this->organization->id ?? false;
        //
        $this->organizationSub = sessionOrganizationSub();
        $this->organizationSubId = $this->organizationSub->id ?? false;

        // Verifica se a coluna cod_subconta_id existe no banco
        try {
            $this->hasCodSubcontaIdColumn = Schema::hasColumn('tb_customers_pay_gateways', 'cod_subconta_id');
        } catch (\Exception $e) {
            $this->hasCodSubcontaIdColumn = false;
        }
    }

    public function render()
    {
        // CUSTOMER
        $this->customers = sessionCustomers();

        // Carrega usuários, módulos e gateways do cliente selecionado (escopo principal da tela)
        if ($this->customerId) {
            $customer = Customer::withoutGlobalScope(ActiveCustomerScope::class)
                ->with(['users', 'appModules', 'paymentGateways'])->find($this->customerId);

            // Carrega usuários com os campos do pivot
            $this->customerUsers = $customer
                ? $customer->users()
                    ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns', 'can_subscriptions'])
                    ->reorder('users.name')
                    ->orderBy('users.email')
                    ->get()
                : collect();
            $this->syncCustomerUsersSnapshot($this->customerUsers);
            $this->customerModules = $customer?->appModules ?? collect();
            $this->customerModuleIds = $this->customerModules->pluck('id')->all();

            // Carrega gateways do cliente separados por status
            $gateways = $customer ? CustomerPayGateway::where('customer_id', $customer->id)->get() : collect();

            // Separa gateways ativos e inativos, ordenando cada grupo alfabeticamente
            $gatewaysActive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 1;
            });

            // Aplica filtros aos gateways ativos
            $this->customerGateways = $this->applyGatewayFilters($gatewaysActive)
                ->sortBy('pay_gateway_label')
                ->values();

            $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 0;
            })->sortBy('pay_gateway_label')->values();

            // Módulos disponíveis - SEMPRE mostra todos os módulos do sistema
            // independente se estão ativos ou associados ao app específico
            $this->allModules = AppModule::orderBy('module_name')->get();

            // Gateways disponíveis (lista de gateways configurados para este App)
            $this->availableGateways = AppPayGateway::where('app_id', $this->app->id)
                ->orderBy('gateway_name')
                ->get();
        } else {
            $this->customerUsers = collect();
            $this->customerUsersSnapshot = [];
            $this->customerModules = collect();
            $this->customerModuleIds = [];
            $this->allModules = collect();
            $this->customerGateways = collect();
            $this->customerGatewaysInactive = collect();
        }

        // Gateways disponíveis (lista de gateways configurados para este App)
        $this->availableGateways = AppPayGateway::where('app_id', $this->app->id)
            ->orderBy('gateway_name')
            ->get();

        // Bancos de referência para seleção no modal de gateway
        if (Schema::hasTable('ref_bancos')) {
            $this->availableBanks = RefBanco::where('to_view', true)
                ->orderBy('ref_banco')
                ->get();
        } else {
            $this->availableBanks = collect();
        }

        return view('livewire.modules.module-configuracoes')->layout('layouts.app-pep-auth');
    }

    /**
     * Busca um customer ignorando o ActiveCustomerScope.
     * Usado no painel de administração para que o admin possa acessar
     * e editar os dados do seu próprio customer mesmo que esteja inativo.
     */
    private function findCustomerIgnoringActiveScope(?string $id = null): ?Customer
    {
        $id = $id ?? $this->customerId;
        if (!$id) {
            return null;
        }
        return Customer::withoutGlobalScope(ActiveCustomerScope::class)->find($id);
    }

    /**
     * Aplica filtros aos gateways
     */
    private function applyGatewayFilters($gateways)
    {
        return $gateways->filter(function ($gateway) {
            // Filtro de busca por texto (nome, slug ou descrição)
            if (!empty($this->filterGatewaySearch)) {
                $search = strtolower($this->filterGatewaySearch);
                $label = strtolower($gateway->pay_gateway_label ?? '');
                $slug = strtolower($gateway->pay_gateway_slug ?? '');
                $description = strtolower($gateway->pay_gateway_description ?? '');

                if (
                    !str_contains($label, $search) &&
                    !str_contains($slug, $search) &&
                    !str_contains($description, $search)
                ) {
                    return false;
                }
            }

            // Filtros de métodos de pagamento (OR lógico - pelo menos um deve corresponder se algum filtro estiver ativo)
            $paymentMethodFiltersActive = $this->filterPayBoleto || $this->filterPayPix ||
                $this->filterPaySlipPix || $this->filterPayCardDebit ||
                $this->filterPayCardCredit;

            if ($paymentMethodFiltersActive) {
                $matchesPaymentMethod = false;

                if ($this->filterPayBoleto && $gateway->pay_boleto)
                    $matchesPaymentMethod = true;
                if ($this->filterPayPix && $gateway->pay_pix)
                    $matchesPaymentMethod = true;
                if ($this->filterPaySlipPix && $gateway->pay_slip_pix)
                    $matchesPaymentMethod = true;
                if ($this->filterPayCardDebit && $gateway->pay_card_debit)
                    $matchesPaymentMethod = true;
                if ($this->filterPayCardCredit && $gateway->pay_card_credit)
                    $matchesPaymentMethod = true;

                if (!$matchesPaymentMethod)
                    return false;
            }

            // Filtros de uso (AND lógico - deve ter ambos se ambos estiverem marcados)
            if ($this->filterUseEvents && !($gateway->use_events ?? 1)) {
                return false;
            }

            if ($this->filterUseCampaigns && !($gateway->use_campaigns ?? 1)) {
                return false;
            }

            return true;
        });
    }

    /**
     * Limpa todos os filtros de gateway
     */
    public function clearGatewayFilters(): void
    {
        $this->filterGatewaySearch = '';
        $this->filterPayBoleto = false;
        $this->filterPayPix = false;
        $this->filterPaySlipPix = false;
        $this->filterPayCardDebit = false;
        $this->filterPayCardCredit = false;
        $this->filterUseEvents = false;
        $this->filterUseCampaigns = false;
        $this->filterHideFees = false;
    }

    public function updatedCustomerNameFantasy()
    {
        $this->customerSlug = Str::slug($this->customerNameFantasy);
    }

    public function updatedOrganizationId()
    {
        $this->organizationSubId = false;
        $this->organizerId = false;
        sessionClear('organization');
        sessionOrganization($this->organizationId);
    }

    public function updatedOrganizationSubId()
    {
        $this->organizerId = false;
        $this->organizationSubs = false;
        sessionClear('organizationSub');
        sessionOrganizationSub($this->organizationSubId);
    }

    public function updatedOrganizerId()
    {
        sessionClear('organizer');
        sessionOrganizers();
        sessionOrganizer($this->organizerId);
    }

    public function updatedCustomerId()
    {
        $this->updateKey++; // Força atualização visual

        // Limpa cache da sessão
        sessionClear('customer');

        // Define novo customer na sessão se houver valor
        if ($this->customerId) {
            sessionCustomer($this->customerId);
            $customer = $this->findCustomerIgnoringActiveScope();
            $customerName = $customer ? $customer->name_corporate : 'Cliente não encontrado';

            $this->emit('showNotification', 'success', 'Cliente selecionado: ' . $customerName);
        } else {
            $this->emit('showNotification', 'info', 'Nenhum cliente selecionado');
        }
    }

    private function reloadCustomerUsers(): void
    {
        if (!$this->customerId) {
            $this->customerUsers = collect();
            $this->customerUsersSnapshot = [];
            return;
        }

        $customer = $this->findCustomerIgnoringActiveScope();

        if (!$customer) {
            $this->customerUsers = collect();
            $this->customerUsersSnapshot = [];
            return;
        }

        $this->customerUsers = $customer->users()
            ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns', 'can_subscriptions'])
            ->reorder('users.name')
            ->orderBy('users.email')
            ->get();
        $this->syncCustomerUsersSnapshot($this->customerUsers);
    }

    private function syncCustomerUsersSnapshot($users): void
    {
        $this->customerUsersSnapshot = collect($users)
            ->mapWithKeys(function ($user) {
                return [
                    (string) $user->id => [
                        'id' => (string) $user->id,
                        'name' => (string) ($user->name ?? ''),
                        'email' => (string) ($user->email ?? ''),
                        'user_role' => (string) ($user->pivot->user_role ?? 'user'),
                        'can_events' => (int) ($user->pivot->can_events ?? 0),
                        'can_campaigns' => (int) ($user->pivot->can_campaigns ?? 0),
                        'can_subscriptions' => (int) ($user->pivot->can_subscriptions ?? 0),
                    ],
                ];
            })
            ->all();
    }

    public function resetPerfil()
    {
        sessionClear();
        $this->customerId = false;
    }

    /**
     * Ativa ou desativa a associação de um módulo ao cliente atual.
     */
    public function toggleModule(string $moduleId): void
    {
        if (!$this->customerId) {
            return;
        }

        // Verifica se o módulo está ativo globalmente antes de permitir ativação
        $module = AppModule::find($moduleId);
        if (!$module) {
            session()->flash('error', 'Módulo não encontrado.');
            return;
        }

        $existing = CustomerAppModule::where('customer_id', $this->customerId)
            ->where('module_id', $moduleId)
            ->first();

        if ($existing) {
            $existing->delete();
            session()->flash('success', 'Módulo removido do cliente com sucesso.');
        } else {
            // Só permite ativar se o módulo estiver globalmente ativo
            if (!$module->module_active) {
                session()->flash('error', 'Este módulo está desativado globalmente e não pode ser ativado para o cliente.');
                return;
            }

            CustomerAppModule::create([
                'customer_id' => $this->customerId,
                'module_id' => $moduleId,
            ]);
            session()->flash('success', 'Módulo associado ao cliente com sucesso.');
        }

        // Força recálculo na próxima renderização
        $this->customerId = $this->customerId;
    }

    /**
     * Abre o modal de edição para um usuário específico.
     */
    public function openEditModal(string $userId): void
    {
        try {
            \Log::info('openEditModal chamado', [
                'userId' => $userId,
                'customerId' => $this->customerId,
            ]);

            if (!$this->customerId) {
                $this->emit('showNotification', 'error', 'Selecione um cliente antes de editar um usuário.');
                return;
            }

            $user = \App\Models\User::find($userId);
            if (!$user) {
                $this->emit('showNotification', 'error', 'Usuário não encontrado: ' . $userId);
                return;
            }

            // Busca direta na tabela pivot — sem depender de escopos do Customer model
            $userCustomer = UserCustomer::where('user_id', $userId)
                ->where('customer_id', $this->customerId)
                ->first();

            if (!$userCustomer) {
                $this->emit('showNotification', 'error', 'Vínculo do usuário com este cliente não encontrado.');
                \Log::warning('openEditModal: pivot não encontrado', [
                    'user_id' => $userId,
                    'customer_id' => $this->customerId,
                ]);
                return;
            }

            $this->resetErrorBag();
            $this->selectedUserId = $userId;
            $this->selectedUser = null;
            $this->editName = $user->name ?? '';
            $this->editEmail = $user->email ?? '';
            $this->editUserRole = $userCustomer->user_role ?? 'user';
            $this->editCanEvents = (bool) $userCustomer->can_events;
            $this->editCanCampaigns = (bool) $userCustomer->can_campaigns;
            $this->editCanSubscriptions = (bool) $userCustomer->can_subscriptions;
            $this->showPasswordSection = false;
            $this->newPassword = '';
            $this->newPasswordConfirmation = '';
            $this->showDeleteConfirmation = false;
            $this->showEditModal = true;

            \Log::info('openEditModal sucesso', [
                'editName' => $this->editName,
                'editEmail' => $this->editEmail,
            ]);

        } catch (\Throwable $e) {
            $this->emit('showNotification', 'error', 'Erro ao abrir modal: ' . $e->getMessage());
            \Log::error('Erro ao abrir modal de edição de usuário', [
                'user_id' => $userId,
                'customer_id' => $this->customerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Fecha o modal de edição.
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editUserRole = '';
        $this->editCanEvents = false;
        $this->editCanCampaigns = false;
        $this->editCanSubscriptions = false;
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->showDeleteConfirmation = false;
        $this->resetErrorBag();

        $this->reloadCustomerUsers();
    }

    public function toggleUserPasswordSection(): void
    {
        $this->showPasswordSection = !$this->showPasswordSection;
    }

    public function startUserDeleteConfirmation(): void
    {
        $this->showDeleteConfirmation = true;
    }

    public function cancelUserDeleteConfirmation(): void
    {
        $this->showDeleteConfirmation = false;
    }

    /**
     * Abre o modal de criação de novo usuário.
     */
    public function openNewUserModal(): void
    {
        $this->showNewUserModal = true;
        $this->newUserName = '';
        $this->newUserEmail = '';
        $this->newUserPassword = '';
        $this->newUserPasswordConfirmation = '';
        $this->newUserRole = 'user';
        $this->newUserCanEvents = false;
        $this->newUserCanCampaigns = false;
        $this->newUserCanSubscriptions = false;
        $this->resetErrorBag();
    }

    /**
     * Fecha o modal de criação de novo usuário.
     */
    public function closeNewUserModal(): void
    {
        $this->showNewUserModal = false;
        $this->newUserName = '';
        $this->newUserEmail = '';
        $this->newUserPassword = '';
        $this->newUserPasswordConfirmation = '';
        $this->newUserRole = 'user';
        $this->newUserCanEvents = false;
        $this->newUserCanCampaigns = false;
        $this->newUserCanSubscriptions = false;
        $this->resetErrorBag();
    }

    /**
     * Cria um novo usuário e associa ao cliente atual.
     */
    public function createUser(): void
    {
        if (!$this->customerId) {
            $this->emit('showNotification', 'error', 'Selecione um cliente antes de criar um usuário.');
            return;
        }

        $this->validate([
            'newUserName' => ['required', 'string', 'max:255'],
            'newUserEmail' => ['required', 'email', 'max:255'],
            'newUserPassword' => ['required', 'string', 'min:8'],
            'newUserPasswordConfirmation' => ['required', 'same:newUserPassword'],
            'newUserRole' => ['required', 'string', 'in:admin,owner,user'],
        ], [
            'newUserName.required' => 'O nome é obrigatório.',
            'newUserEmail.required' => 'O e-mail é obrigatório.',
            'newUserEmail.email' => 'O e-mail deve ser válido.',
            'newUserPassword.required' => 'A senha é obrigatória.',
            'newUserPassword.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'newUserPasswordConfirmation.required' => 'A confirmação de senha é obrigatória.',
            'newUserPasswordConfirmation.same' => 'A confirmação de senha não confere.',
            'newUserRole.required' => 'O papel do usuário é obrigatório.',
        ]);

        try {
            $emailLower = strtolower(trim($this->newUserEmail));

            // Verifica se o usuário já existe
            $existingUser = \App\Models\User::where('email', $emailLower)->first();

            //
            if ($existingUser) {
                // Verifica se já está associado ao cliente atual
                $isAlreadyAssociated = UserCustomer::where('user_id', $existingUser->id)
                    ->where('customer_id', $this->customerId)
                    ->exists();

                if ($isAlreadyAssociated) {
                    $this->addError('newUserEmail', 'Este e-mail já está associado a este cliente.');
                    return;
                }

                // Se o usuário existe mas não está associado, apenas associa ao cliente
                UserCustomer::create([
                    'user_id' => $existingUser->id,
                    'customer_id' => $this->customerId,
                    'user_active' => true,
                    'user_role' => $this->newUserRole,
                    'can_events' => $this->newUserCanEvents ? 1 : 0,
                    'can_campaigns' => $this->newUserCanCampaigns ? 1 : 0,
                    'can_subscriptions' => $this->newUserCanSubscriptions ? 1 : 0,
                ]);

                $user = $existingUser;
                $message = "Usuário {$user->name} associado ao cliente com sucesso!";

            } else {
                // Cria um novo usuário
                $user = \App\Models\User::create([
                    'name' => $this->newUserName,
                    'email' => $emailLower,
                    'password' => \Illuminate\Support\Facades\Hash::make($this->newUserPassword),
                ]);

                // Associa o usuário ao cliente com as permissões
                UserCustomer::create([
                    'user_id' => $user->id,
                    'customer_id' => $this->customerId,
                    'user_active' => true,
                    'user_role' => $this->newUserRole,
                    'can_events' => $this->newUserCanEvents ? 1 : 0,
                    'can_campaigns' => $this->newUserCanCampaigns ? 1 : 0,
                    'can_subscriptions' => $this->newUserCanSubscriptions ? 1 : 0,
                ]);

                $message = "Usuário {$user->name} criado e associado ao cliente com sucesso!";
            }

            session()->flash('success', $message);

            // Recarrega os usuários do cliente
            $customer = $this->findCustomerIgnoringActiveScope();
            if ($customer) {
                $this->reloadCustomerUsers();
            }

            // Fecha o modal após salvar
            $this->closeNewUserModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-lança a exceção de validação para que o Livewire trate corretamente
            throw $e;
        } catch (\Exception $e) {
            $this->addError('newUserEmail', 'Erro ao criar usuário: ' . $e->getMessage());
            \Log::error('Erro ao criar usuário', [
                'customer_id' => $this->customerId,
                'email' => $this->newUserEmail ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Atualiza os dados do usuário.
     */
    public function updateUser(): void
    {
        if (!$this->selectedUserId) {
            $this->emit('showNotification', 'error', 'Nenhum usuário foi selecionado para edição.');
            return;
        }

        $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255'],
            'editUserRole' => ['required', 'string', 'in:admin,owner,user,super-admin'],
        ], [
            'editName.required' => 'O nome é obrigatório.',
            'editEmail.required' => 'O e-mail é obrigatório.',
            'editEmail.email' => 'O e-mail deve ser válido.',
            'editUserRole.required' => 'O papel do usuário é obrigatório.',
            'editUserRole.in' => 'O papel selecionado é inválido.',
        ]);

        if (!$this->customerId) {
            $this->emit('showNotification', 'error', 'Selecione um cliente antes de salvar.');
            return;
        }

        $user = \App\Models\User::find($this->selectedUserId);

        if (!$user) {
            $this->emit('showNotification', 'error', 'Usuário não encontrado.');
            $this->closeEditModal();
            return;
        }

        $emailLower = strtolower(trim($this->editEmail));

        $existingUser = \App\Models\User::where('email', $emailLower)
            ->where('id', '!=', $this->selectedUserId)
            ->first();

        if ($existingUser) {
            $this->addError('editEmail', 'Este e-mail já está em uso por outro usuário.');
            return;
        }

        $userCustomer = UserCustomer::where('user_id', $user->id)
            ->where('customer_id', $this->customerId)
            ->first();

        if (!$userCustomer) {
            $this->emit('showNotification', 'error', 'O vínculo do usuário com este cliente não foi encontrado.');
            return;
        }

        try {
            DB::transaction(function () use ($user, $userCustomer, $emailLower) {
                $user->update([
                    'name' => $this->editName,
                    'email' => $emailLower,
                ]);

                $userCustomer->update([
                    'user_role' => $this->editUserRole,
                    'can_events' => $this->editCanEvents ? 1 : 0,
                    'can_campaigns' => $this->editCanCampaigns ? 1 : 0,
                    'can_subscriptions' => $this->editCanSubscriptions ? 1 : 0,
                ]);
            });

            $this->reloadCustomerUsers();
            $this->emit('showNotification', 'success', "Dados do usuário {$user->fresh()->name} atualizados com sucesso!");
            $this->closeEditModal();
        } catch (\Throwable $e) {
            $this->emit('showNotification', 'error', 'Erro ao atualizar usuário: ' . $e->getMessage());
            \Log::error('Erro ao atualizar usuário do cliente', [
                'user_id' => $this->selectedUserId,
                'customer_id' => $this->customerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Atualiza a senha do usuário selecionado.
     */
    public function updateUserPassword(): void
    {
        if (!$this->selectedUserId) {
            $this->emit('showNotification', 'error', 'Nenhum usuário foi selecionado para alterar a senha.');
            return;
        }

        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
            'newPasswordConfirmation' => ['required', 'same:newPassword'],
        ], [
            'newPassword.required' => 'A nova senha é obrigatória.',
            'newPassword.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'newPasswordConfirmation.required' => 'A confirmação de senha é obrigatória.',
            'newPasswordConfirmation.same' => 'A confirmação de senha não confere.',
        ]);

        $user = \App\Models\User::find($this->selectedUserId);

        if (!$user) {
            $this->emit('showNotification', 'error', 'Usuário não encontrado.');
            return;
        }

        $user->forceFill([
            'password' => \Illuminate\Support\Facades\Hash::make($this->newPassword),
        ])->save();

        $this->emit('showNotification', 'success', "Senha do usuário {$user->name} ({$user->email}) atualizada com sucesso!");
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->resetErrorBag(['newPassword', 'newPasswordConfirmation']);

        // Fecha o modal após alterar a senha
        $this->closeEditModal();
    }

    /**
     * Remove o usuário do customer.
     */
    public function removeUser(): void
    {
        if (!$this->selectedUserId) {
            $this->emit('showNotification', 'error', 'Nenhum usuário foi selecionado para remoção.');
            return;
        }

        if (!$this->showDeleteConfirmation) {
            $this->showDeleteConfirmation = true;
            return;
        }

        $user = \App\Models\User::find($this->selectedUserId);

        if (!$user) {
            $this->emit('showNotification', 'error', 'Usuário não encontrado.');
            $this->closeEditModal();
            return;
        }

        // Remove o relacionamento com o customer
        UserCustomer::where('user_id', $user->id)
            ->where('customer_id', $this->customerId)
            ->delete();

        // Recarrega os usuários do cliente
        $this->reloadCustomerUsers();

        $this->emit('showNotification', 'success', "Usuário {$user->name} removido do cliente com sucesso!");
        $this->closeEditModal();
    }

    /**
     * Abre o modal de criação de novo gateway.
     */
    public function openNewGatewayModal(): void
    {
        if (!$this->customerId) {
            session()->flash('error', 'Selecione um cliente antes de criar um gateway.');
            return;
        }

        // Recarrega os gateways disponíveis (lista de gateways configurados para este App)
        $this->availableGateways = AppPayGateway::where('app_id', $this->app->id)
            ->orderBy('gateway_name')
            ->get();

        $this->isEditingGateway = false;
        $this->selectedGatewayId = null;
        $this->selectedGateway = null;
        $this->resetGatewayFields();
        $this->resetErrorBag();

        $this->dispatchBrowserEvent('open-new-gateway');
    }

    /**
     * Abre o modal de edição de gateway.
     */
    public function openEditGatewayModal(string $gatewayId): void
    {
        try {
            \Log::info('openEditGatewayModal - Iniciando com ID: ' . $gatewayId);

            $gateway = CustomerPayGateway::with('appGateway')->find($gatewayId);

            if (!$gateway) {
                \Log::error('openEditGatewayModal - Gateway não encontrado: ' . $gatewayId);
                session()->flash('error', 'Gateway não encontrado.');
                return;
            }

            \Log::info('openEditGatewayModal - Gateway encontrado: ' . $gateway->pay_gateway_label);

            $this->isEditingGateway = true;
            $this->selectedGatewayId = $gatewayId;
            $this->selectedGateway = $gateway;

            // Preenche os campos com os dados do gateway
            $this->gatewayPayGatewayId = $gateway->pay_gateway_id ?? '';
            $this->gatewayPayGatewaySlug = $gateway->pay_gateway_slug ?? '';
            $this->gatewayPayGatewayLabel = $gateway->pay_gateway_label ?? '';
            $this->gatewayPayGatewayDescription = $gateway->pay_gateway_description ?? '';

            // Carrega cod_subconta_id apenas se a coluna existir no banco
            $this->gatewayCodSubcontaId = '';
            if ($this->hasCodSubcontaIdColumn) {
                $this->gatewayCodSubcontaId = $gateway->cod_subconta_id ?? '';
            }

            $this->gatewayContaCod = $gateway->conta_cod ?? '';
            $this->gatewayContaBanco = $gateway->conta_banco ?? '';
            $this->gatewayContaBancoDescricao = $gateway->conta_banco_descricao ?? '';
            $this->gatewayContaTipo = $gateway->conta_tipo ?? '';
            $this->gatewayContaAgencia = $gateway->conta_agencia ?? '';
            $this->gatewayContaAgenciaDv = $gateway->conta_agencia_dv ?? '';
            $this->gatewayContaNumero = $gateway->conta_numero ?? '';
            $this->gatewayContaNumeroDv = $gateway->conta_numero_dv ?? '';

            $this->gatewayTokenLive = $gateway->token_live ?? '';
            $this->gatewayTokenLivePass = $gateway->token_live_pass ?? '';
            $this->gatewayTokenTest = $gateway->token_test ?? '';
            $this->gatewayTokenTestPass = $gateway->token_test_pass ?? '';
            $this->gatewayPayBoleto = (bool) ($gateway->pay_boleto ?? 0);
            $this->gatewayPayPix = (bool) ($gateway->pay_pix ?? 0);
            $this->gatewayPaySlipPix = (bool) ($gateway->pay_slip_pix ?? 0);
            $this->gatewayPayCardDebit = (bool) ($gateway->pay_card_debit ?? 0);
            $this->gatewayPayCardCredit = (bool) ($gateway->pay_card_credit ?? 0);
            $this->gatewayPayCardCreditInstallmentMax = $gateway->pay_card_credit_installment_max ?? 1;
            $this->gatewayPayCardCreditInstallmentAmountMin = max(500, $gateway->pay_card_credit_installment_amount_min ?? 500);
            $this->gatewayPaySlipPixInstallmentMax = $gateway->pay_slip_pix_installment_max ?? 1;
            $this->gatewayPaySlipPixInstallmentAmountMin = max(1000, $gateway->pay_slip_pix_installment_amount_min ?? 1000);
            $this->gatewayBoletoFeesJson = $gateway->pay_boleto_fees_json ?? '';
            $this->gatewayPixFeesJson = $gateway->pay_pix_fees_json ?? '';
            $this->gatewayInstallmentFeesJson = $gateway->pay_gateway_installment_fees_json ?? '';
            $this->gatewaySlipPixFeesJson = $gateway->pay_slip_pix_fees_json ?? '';
            $this->gatewayBoletoFixedAmount = $gateway->fee_boleto_fixed_amount;
            $this->gatewayPixFixedAmount = $gateway->fee_pix_fixed_amount;
            $this->gatewaySlipPixFixedAmount = $gateway->fee_slip_pix_fixed_amount;
            $this->gatewayCreditFixedAmount = $gateway->fee_credit_fixed_amount;
            $this->gatewayPayActive = (bool) ($gateway->pay_active ?? 1);
            $this->gatewayUseEvents = (bool) ($gateway->use_events ?? 1);
            $this->gatewayUseCampaigns = (bool) ($gateway->use_campaigns ?? 1);
            // Campos de split removidos - não existem na tabela
            // $this->gatewaySplitPay = (bool)($gateway->split_pay ?? 0);
            // $this->gatewaySplitMode = $gateway->split_mode ?? '';
            // $this->gatewaySplitLiveRecipientId = $gateway->split_live_recipient_id ?? '';
            // $this->gatewaySplitTestRecipientId = $gateway->split_test_recipient_id ?? '';

            $this->showDeleteGatewayConfirmation = false;
            $this->resetErrorBag();

            // Envia TODOS os dados diretamente para o Alpine.js via browser event
            $this->dispatchBrowserEvent('open-edit-gateway', [
                'gatewayId' => $gateway->pay_gateway_id ?? '',
                'label' => $gateway->pay_gateway_label ?? '',
                'description' => $gateway->pay_gateway_description ?? '',
                'codSubcontaId' => $this->hasCodSubcontaIdColumn ? ($gateway->cod_subconta_id ?? '') : '',
                'contaCod' => $gateway->conta_cod ?? '',
                'contaBanco' => $gateway->conta_banco ?? '',
                'contaBancoDescricao' => $gateway->conta_banco_descricao ?? '',
                'contaTipo' => $gateway->conta_tipo ?? '',
                'contaAgencia' => $gateway->conta_agencia ?? '',
                'contaAgenciaDv' => $gateway->conta_agencia_dv ?? '',
                'contaNumero' => $gateway->conta_numero ?? '',
                'contaNumeroDv' => $gateway->conta_numero_dv ?? '',
                'tokenLive' => $gateway->token_live ?? '',
                'tokenLivePass' => $gateway->token_live_pass ?? '',
                'tokenTest' => $gateway->token_test ?? '',
                'tokenTestPass' => $gateway->token_test_pass ?? '',
                'payBoleto' => (bool) ($gateway->pay_boleto ?? 0),
                'payPix' => (bool) ($gateway->pay_pix ?? 0),
                'paySlipPix' => (bool) ($gateway->pay_slip_pix ?? 0),
                'payCardDebit' => (bool) ($gateway->pay_card_debit ?? 0),
                'payCardCredit' => (bool) ($gateway->pay_card_credit ?? 0),
                'installmentMax' => $gateway->pay_card_credit_installment_max ?? 1,
                'installmentAmountMin' => max(500, $gateway->pay_card_credit_installment_amount_min ?? 500),
                'slipPixInstallmentMax' => $gateway->pay_slip_pix_installment_max ?? 1,
                'slipPixInstallmentAmountMin' => max(1000, $gateway->pay_slip_pix_installment_amount_min ?? 1000),
                'boletoFixedAmount' => $gateway->fee_boleto_fixed_amount,
                'pixFixedAmount' => $gateway->fee_pix_fixed_amount,
                'slipPixFixedAmount' => $gateway->fee_slip_pix_fixed_amount,
                'creditFixedAmount' => $gateway->fee_credit_fixed_amount,
                'payActive' => (bool) ($gateway->pay_active ?? 1),
                'useEvents' => (bool) ($gateway->use_events ?? 1),
                'useCampaigns' => (bool) ($gateway->use_campaigns ?? 1),
                'recordId' => $gateway->id,
            ]);

            \Log::info('openEditGatewayModal - Browser event dispatched com dados do gateway');
        } catch (\Exception $e) {
            \Log::error('openEditGatewayModal - Erro: ' . $e->getMessage());
            \Log::error('openEditGatewayModal - Stack: ' . $e->getTraceAsString());
            session()->flash('error', 'Erro ao abrir modal de edição: ' . $e->getMessage());
        }
    }

    /**
     * Fecha o modal de gateway.
     */
    public function closeGatewayModal(): void
    {
        $this->isEditingGateway = false;
        $this->selectedGatewayId = null;
        $this->selectedGateway = null;
        $this->showDeleteGatewayConfirmation = false;
        $this->resetGatewayFields();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('close-gateway-modal');
    }

    /**
     * Reseta os campos do formulário de gateway.
     */
    private function resetGatewayFields(): void
    {
        $this->gatewayPayGatewayId = '';
        $this->gatewayPayGatewaySlug = '';
        $this->gatewayPayGatewayLabel = '';
        $this->gatewayPayGatewayDescription = '';
        $this->gatewayCodSubcontaId = '';
        $this->gatewayContaCod = '';
        $this->gatewayContaBanco = '';
        $this->gatewayContaBancoDescricao = '';
        $this->gatewayContaTipo = '';
        $this->gatewayContaAgencia = '';
        $this->gatewayContaAgenciaDv = '';
        $this->gatewayContaNumero = '';
        $this->gatewayContaNumeroDv = '';
        $this->gatewayTokenLive = '';
        $this->gatewayTokenLivePass = '';
        $this->gatewayTokenTest = '';
        $this->gatewayTokenTestPass = '';
        $this->gatewayPayBoleto = false;
        $this->gatewayPayPix = false;
        $this->gatewayPaySlipPix = false;
        $this->gatewayPayCardDebit = false;
        $this->gatewayPayCardCredit = false;
        $this->gatewayPayCardCreditInstallmentMax = 1;
        $this->gatewayPayCardCreditInstallmentAmountMin = 500;
        $this->gatewayPaySlipPixInstallmentMax = 1;
        $this->gatewayPaySlipPixInstallmentAmountMin = 1000;
        $this->gatewayBoletoFeesJson = '';
        $this->gatewayPixFeesJson = '';
        $this->gatewayInstallmentFeesJson = '';
        $this->gatewaySlipPixFeesJson = '';
        $this->gatewayBoletoFixedAmount = null;
        $this->gatewayPixFixedAmount = null;
        $this->gatewaySlipPixFixedAmount = null;
        $this->gatewayCreditFixedAmount = null;
        $this->gatewayPayActive = true;
        $this->gatewayUseEvents = true;
        $this->gatewayUseCampaigns = true;
        $this->gatewaySplitPay = false;
        $this->gatewaySplitMode = '';
        $this->gatewaySplitLiveRecipientId = '';
        $this->gatewaySplitTestRecipientId = '';
    }

    /**
     * Atualiza o slug e dados do gateway baseado no gateway selecionado.
     */
    public function updatedGatewayPayGatewayId(): void
    {
        $this->onGatewaySelected();
    }

    /**
     * Chamado quando o usuário seleciona um gateway no dropdown (via Alpine).
     */
    public function onGatewaySelected(): void
    {
        if ($this->gatewayPayGatewayId) {
            $appGateway = AppPayGateway::find($this->gatewayPayGatewayId);
            if ($appGateway) {
                $this->gatewayPayGatewaySlug = $appGateway->gateway_slug ?? '';
                if (!$this->isEditingGateway) {
                    $this->gatewayPayGatewayLabel = $appGateway->gateway_name ?? '';
                    $this->gatewayPayGatewayDescription = $appGateway->gateway_description ?? '';
                }
            }
        }
    }

    /**
     * Salva ou atualiza um gateway.
     */
    public function saveGateway(): void
    {
        if (!$this->customerId) {
            $this->dispatchBrowserEvent('gateway-errors', ['errors' => ['Selecione um cliente antes de salvar um gateway.']]);
            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'title' => 'Erro!', 'message' => 'Selecione um cliente antes de salvar um gateway.']);
            return;
        }

        // Validação
        $tokenRule = $this->gatewayPayActive ? ['required', 'string'] : ['nullable', 'string'];
        $validationRules = [
            'gatewayPayGatewayId' => ['required', 'string'],
            'gatewayPayGatewayLabel' => ['required', 'string', 'max:255'],
            'gatewayPayGatewayDescription' => ['required', 'string'],
            'gatewayTokenLive' => $tokenRule,
            'gatewayTokenTest' => $tokenRule,
            'gatewayContaCod' => ['nullable', 'string', 'max:50'],
            'gatewayContaBanco' => ['nullable', 'string', 'max:255'],
            'gatewayContaBancoDescricao' => ['nullable', 'string', 'max:255'],
            'gatewayContaTipo' => ['nullable', 'in:corrente,poupanca'],
            'gatewayContaAgencia' => ['nullable', 'string', 'max:20'],
            'gatewayContaAgenciaDv' => ['nullable', 'string', 'max:5'],
            'gatewayContaNumero' => ['nullable', 'string', 'max:30'],
            'gatewayContaNumeroDv' => ['nullable', 'string', 'max:5'],
            'gatewayPayCardCreditInstallmentMax' => ['nullable', 'integer', 'min:1', 'max:12'],
            'gatewayPayCardCreditInstallmentAmountMin' => ['nullable', 'numeric', 'min:500'],
            'gatewayPaySlipPixInstallmentMax' => ['nullable', 'integer', 'min:1', 'max:12'],
            'gatewayPaySlipPixInstallmentAmountMin' => ['nullable', 'numeric', 'min:1000'],
            'gatewayBoletoFixedAmount' => ['nullable', 'integer', 'min:0'],
            'gatewayPixFixedAmount' => ['nullable', 'integer', 'min:0'],
            'gatewaySlipPixFixedAmount' => ['nullable', 'integer', 'min:0'],
            'gatewayCreditFixedAmount' => ['nullable', 'integer', 'min:0'],
        ];

        // Adiciona validação de unique para cod_subconta_id (apenas se preenchido e coluna existir)
        // Regra: único por customer — o mesmo token pode existir no mesmo customer, mas não em outro
        if (!empty($this->gatewayCodSubcontaId) && $this->hasCodSubcontaIdColumn) {
            $uniqueRule = \Illuminate\Validation\Rule::unique('tb_customers_pay_gateways', 'cod_subconta_id')
                ->where('customer_id', $this->customerId);
            if ($this->isEditingGateway && $this->selectedGatewayId) {
                $uniqueRule = $uniqueRule->ignore($this->selectedGatewayId);
            }
            $validationRules['gatewayCodSubcontaId'] = ['nullable', 'string', 'max:255', $uniqueRule];
        }

        if (!empty($this->gatewayContaCod)) {
            $uniqueContaCodRule = \Illuminate\Validation\Rule::unique('tb_customers_pay_gateways', 'conta_cod');
            if ($this->isEditingGateway && $this->selectedGatewayId) {
                $uniqueContaCodRule = $uniqueContaCodRule->ignore($this->selectedGatewayId);
            }
            $validationRules['gatewayContaCod'][] = $uniqueContaCodRule;
        }

        if (Schema::hasTable('ref_bancos')) {
            $validationRules['gatewayContaBanco'] = ['nullable', 'exists:ref_bancos,ref_banco'];
        }

        try {
            $this->validate($validationRules, [
                'gatewayPayGatewayId.required' => 'Selecione um gateway.',
                'gatewayPayGatewayLabel.required' => 'O rótulo do gateway é obrigatório.',
                'gatewayPayGatewayDescription.required' => 'A descrição do gateway é obrigatória.',
                'gatewayTokenLive.required' => 'O token de produção é obrigatório para gateways ativos.',
                'gatewayTokenTest.required' => 'O token de teste é obrigatório para gateways ativos.',
                'gatewayPayCardCreditInstallmentMax.max' => 'O máximo de parcelas é 12.',
                'gatewayPayCardCreditInstallmentMax.min' => 'O mínimo de parcelas é 1.',
                'gatewayCodSubcontaId.unique' => 'Este código de subconta já está em uso por outro gateway.',
                'gatewayContaCod.unique' => 'Este Código da Conta já está em uso por outro gateway.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errorMessages = collect($e->validator->errors()->all())->values()->toArray();
            $this->dispatchBrowserEvent('gateway-errors', ['errors' => $errorMessages]);
            $this->dispatchBrowserEvent('notification', [
                'type' => 'error',
                'title' => 'Erro de validação',
                'message' => implode(' | ', $errorMessages),
            ]);
            throw $e;
        }

        try {
            $customer = $this->findCustomerIgnoringActiveScope();
            if (!$customer) {
                $this->dispatchBrowserEvent('gateway-errors', ['errors' => ['Cliente não encontrado.']]);
                $this->dispatchBrowserEvent('notification', ['type' => 'error', 'title' => 'Erro!', 'message' => 'Cliente não encontrado.']);
                return;
            }

            $appGateway = AppPayGateway::find($this->gatewayPayGatewayId);
            if (!$appGateway) {
                $this->dispatchBrowserEvent('gateway-errors', ['errors' => ['Gateway não encontrado.']]);
                $this->dispatchBrowserEvent('notification', ['type' => 'error', 'title' => 'Erro!', 'message' => 'Gateway não encontrado.']);
                return;
            }

            // Gera o slug: slug do cliente + slug do gateway + slug do label
            $customerSlug = $customer->customer_slug ?? '';
            $gatewaySlug = $appGateway->gateway_slug ?? '';
            $labelSlug = Str::slug($this->gatewayPayGatewayLabel);

            // Monta o slug: cliente-gateway-label
            $slugParts = array_filter([$customerSlug, $gatewaySlug, $labelSlug]);
            $slug = !empty($slugParts) ? implode('-', $slugParts) : $labelSlug;

            // Verifica se o slug já existe (ignorando o próprio registro se estiver editando)
            $existingGateway = CustomerPayGateway::where('customer_id', $this->customerId)
                ->where('pay_gateway_slug', $slug);

            if ($this->isEditingGateway && $this->selectedGatewayId) {
                $existingGateway->where('id', '!=', $this->selectedGatewayId);
            }

            $existingGateway = $existingGateway->first();

            if ($existingGateway) {
                $errorMsg = 'Já existe um gateway com este rótulo para este cliente. Por favor, modifique o rótulo para gerar um slug único.';
                $this->addError('gatewayPayGatewayLabel', $errorMsg);
                $this->dispatchBrowserEvent('gateway-errors', ['errors' => [$errorMsg]]);
                $this->dispatchBrowserEvent('notification', [
                    'type' => 'error',
                    'title' => 'Erro!',
                    'message' => $errorMsg,
                ]);
                return;
            }

            $data = [
                'customer_id' => $this->customerId,
                'pay_gateway_id' => $this->gatewayPayGatewayId,
                'pay_gateway_slug' => $slug,
                'pay_gateway_label' => $this->gatewayPayGatewayLabel,
                'pay_gateway_description' => $this->gatewayPayGatewayDescription,
                'conta_cod' => $this->gatewayContaCod ?: null,
                'conta_banco' => $this->gatewayContaBanco ?: null,
                'conta_banco_descricao' => $this->gatewayContaBancoDescricao ?: null,
                'conta_tipo' => $this->gatewayContaTipo ?: null,
                'conta_agencia' => $this->gatewayContaAgencia ?: null,
                'conta_agencia_dv' => $this->gatewayContaAgenciaDv ?: null,
                'conta_numero' => $this->gatewayContaNumero ?: null,
                'conta_numero_dv' => $this->gatewayContaNumeroDv ?: null,
                'token_live' => $this->gatewayTokenLive,
                'token_live_pass' => $this->gatewayTokenLivePass ?? '',
                'token_test' => $this->gatewayTokenTest,
                'token_test_pass' => $this->gatewayTokenTestPass ?? '',
                'pay_boleto' => $this->gatewayPayBoleto ? 1 : 0,
                'pay_pix' => $this->gatewayPayPix ? 1 : 0,
                'pay_slip_pix' => $this->gatewayPaySlipPix ? 1 : 0,
                'pay_card_debit' => $this->gatewayPayCardDebit ? 1 : 0,
                'pay_card_credit' => $this->gatewayPayCardCredit ? 1 : 0,
                'pay_card_credit_installment_max' => $this->gatewayPayCardCreditInstallmentMax ?? 1,
                'pay_card_credit_installment_amount_min' => max(500, $this->gatewayPayCardCreditInstallmentAmountMin ?? 500),
                'pay_slip_pix_installment_max' => $this->gatewayPaySlipPixInstallmentMax ?? 1,
                'pay_slip_pix_installment_amount_min' => max(1000, $this->gatewayPaySlipPixInstallmentAmountMin ?? 1000),
                'fee_boleto_fixed_amount' => $this->gatewayBoletoFixedAmount,
                'fee_pix_fixed_amount' => $this->gatewayPixFixedAmount,
                'fee_slip_pix_fixed_amount' => $this->gatewaySlipPixFixedAmount,
                'fee_credit_fixed_amount' => $this->gatewayCreditFixedAmount,
                'pay_active' => $this->gatewayPayActive ? 1 : 0,
                'use_events' => $this->gatewayUseEvents ? 1 : 0,
                'use_campaigns' => $this->gatewayUseCampaigns ? 1 : 0,
            ];

            // Adiciona cod_subconta_id apenas se a coluna existir no banco
            if ($this->hasCodSubcontaIdColumn) {
                $data['cod_subconta_id'] = $this->gatewayCodSubcontaId ?? '';
            }

            // dd($data);

            if ($this->isEditingGateway && $this->selectedGatewayId) {
                // Atualiza gateway existente
                $gateway = CustomerPayGateway::find($this->selectedGatewayId);
                if (!$gateway) {
                    $this->dispatchBrowserEvent('gateway-errors', ['errors' => ['Gateway não encontrado para atualização.']]);
                    $this->dispatchBrowserEvent('notification', ['type' => 'error', 'title' => 'Erro!', 'message' => 'Gateway não encontrado para atualização.']);
                    return;
                }
                $gateway->update($data);
                $message = "Gateway {$this->gatewayPayGatewayLabel} atualizado com sucesso!";
            } else {
                // Cria novo gateway (permite múltiplas contas do mesmo gateway para o cliente)
                CustomerPayGateway::create($data);
                $message = "Gateway {$this->gatewayPayGatewayLabel} criado com sucesso!";
            }

            // Recarrega os gateways do cliente separados por status
            $gateways = CustomerPayGateway::where('customer_id', $this->customerId)->get();
            $this->customerGateways = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 1;
            })->sortBy('pay_gateway_label')->values();

            $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 0;
            })->sortBy('pay_gateway_label')->values();

            $this->closeGatewayModal();

            // Exibe notificação de sucesso
            $this->dispatchBrowserEvent('notification', [
                'type' => 'success',
                'title' => 'Sucesso!',
                'message' => $message
            ]);

            session()->flash('success', $message);
            $this->emit('showNotification', 'success', $message);
        } catch (\Exception $e) {
            \Log::error('saveGateway error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Unique violation — cod_subconta_id ou conta_cod já em uso
            if (str_contains($e->getMessage(), '23505') || str_contains($e->getMessage(), 'Unique violation')) {
                $errorMessage = 'Já existe um registro com Código Subconta ID ou Conta Cod informado. Utilize um valor diferente.';
            } else {
                $errorMessage = 'Erro ao salvar gateway: ' . $e->getMessage();
            }

            $this->dispatchBrowserEvent('notification', [
                'type' => 'error',
                'title' => 'Não foi possível salvar',
                'message' => $errorMessage
            ]);

            session()->flash('error', $errorMessage);
            $this->emit('showNotification', 'error', $errorMessage);
        }
    }

    /**
     * Ativa ou desativa um gateway.
     */
    public function toggleGatewayActive(string $gatewayId): void
    {
        try {
            $gateway = CustomerPayGateway::find($gatewayId);

            if (!$gateway) {
                session()->flash('error', 'Gateway não encontrado.');
                return;
            }

            $gateway->update([
                'pay_active' => $gateway->pay_active ? 0 : 1
            ]);

            $status = $gateway->pay_active ? 'ativado' : 'desativado';
            session()->flash('success', "Gateway {$status} com sucesso!");

            // Recarrega os gateways separados por status
            $customer = $this->findCustomerIgnoringActiveScope();
            if ($customer) {
                $gateways = $customer->paymentGateways;
                $this->customerGateways = $gateways->filter(function ($gateway) {
                    return $gateway->pay_active == 1;
                })->sortBy('pay_gateway_label')->values();

                $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                    return $gateway->pay_active == 0;
                })->sortBy('pay_gateway_label')->values();
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao alterar status do gateway: ' . $e->getMessage());
        }
    }

    /**
     * Remove um gateway.
     */
    public function removeGateway(bool $confirmed = false): void
    {
        if (!$confirmed && !$this->showDeleteGatewayConfirmation) {
            $this->showDeleteGatewayConfirmation = true;
            return;
        }

        try {
            // Verificar ownership: gateway deve pertencer ao cliente atual (evita IDOR)
            $gateway = CustomerPayGateway::where('id', $this->selectedGatewayId)
                ->where('customer_id', $this->customerId)
                ->first();

            if (!$gateway) {
                session()->flash('error', 'Gateway não encontrado.');
                $this->closeGatewayModal();
                return;
            }

            $gatewayLabel = $gateway->pay_gateway_label ?? 'N/A';

            $gateway->delete();

            $this->dispatchBrowserEvent('notification', ['type' => 'success', 'title' => 'Sucesso!', 'message' => "Gateway {$gatewayLabel} removido com sucesso!"]);
            session()->flash('success', "Gateway {$gatewayLabel} removido com sucesso!");

            // Recarrega os gateways separados por status
            $customer = $this->findCustomerIgnoringActiveScope();
            if ($customer) {
                $gateways = $customer->paymentGateways;
                $this->customerGateways = $gateways->filter(function ($gateway) {
                    return $gateway->pay_active == 1;
                })->sortBy('pay_gateway_label')->values();

                $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                    return $gateway->pay_active == 0;
                })->sortBy('pay_gateway_label')->values();
            }

            $this->closeGatewayModal();
        } catch (\Exception $e) {
            \Log::error('removeGateway error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Foreign key violation — gateway em uso por eventos/campanhas
            if (str_contains($e->getMessage(), 'Foreign key violation') || str_contains($e->getMessage(), '23503')) {
                $msg = 'Este gateway não pode ser removido porque está vinculado a um ou mais eventos. Remova o vínculo nos eventos antes de excluí-lo.';
            } else {
                $msg = 'Erro ao remover gateway: ' . $e->getMessage();
            }

            $this->dispatchBrowserEvent('notification', ['type' => 'error', 'title' => 'Não foi possível remover', 'message' => $msg]);
            session()->flash('error', $msg);
        }
    }

    /**
     * Abre o modal de gerenciamento de taxas de parcelamento para um gateway específico.
     */
    public function openInstallmentFeesModalForGateway(string $gatewayId): void
    {
        try {
            $gateway = CustomerPayGateway::find($gatewayId);
            if (!$gateway) {
                session()->flash('error', 'Gateway não encontrado.');
                return;
            }

            $this->selectedGatewayForInstallmentFees = $gateway;
            $this->gatewayInstallmentFeesJson = $gateway->pay_gateway_installment_fees_json ?? '';
            $this->showInstallmentFeesModal = true;

            // Envia o JSON via browser event para contornar bug do morphdom com WireUI modals
            $this->dispatchBrowserEvent('installment-fees-data', [
                'json' => $gateway->pay_gateway_installment_fees_json ?? '',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao abrir modal de taxas: ' . $e->getMessage());
        }
    }

    /**
     * Fecha o modal de taxas de parcelamento sem salvar.
     */
    public function closeInstallmentFeesModal(): void
    {
        // Recarrega o JSON original do gateway para descartar alterações
        if ($this->selectedGatewayForInstallmentFees) {
            $this->gatewayInstallmentFeesJson = $this->selectedGatewayForInstallmentFees->pay_gateway_installment_fees_json ?? '';
        }

        $this->showInstallmentFeesModal = false;
        $this->selectedGatewayForInstallmentFees = null;
    }

    /**
     * Salva as taxas de parcelamento.
     */
    public function saveInstallmentFees(): void
    {
        if (!$this->selectedGatewayForInstallmentFees) {
            session()->flash('error', 'Gateway não selecionado.');
            return;
        }

        // Valida e normaliza o JSON antes de salvar
        if (!empty($this->gatewayInstallmentFeesJson)) {
            $decoded = json_decode($this->gatewayInstallmentFeesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                session()->flash('error', 'JSON inválido. Por favor, verifique o formato.');
                return;
            }

            // Normaliza os valores: converte vírgula para ponto em todos os valores
            $normalized = [];
            foreach ($decoded as $key => $value) {
                $normalized[$key] = str_replace(',', '.', (string) $value);
            }

            // Reconstrói o JSON com valores normalizados
            $this->gatewayInstallmentFeesJson = json_encode($normalized);
        }

        // Salva as alterações no gateway
        $this->selectedGatewayForInstallmentFees->update([
            'pay_gateway_installment_fees_json' => $this->gatewayInstallmentFeesJson
        ]);

        session()->flash('success', 'Taxas de parcelamento atualizadas com sucesso!');

        // Recarrega os gateways separados por status
        $customer = $this->findCustomerIgnoringActiveScope();
        if ($customer) {
            $gateways = CustomerPayGateway::where('customer_id', $customer->id)->get();
            $this->customerGateways = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 1;
            })->sortBy('pay_gateway_label')->values();

            $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 0;
            })->sortBy('pay_gateway_label')->values();
        }

        $this->showInstallmentFeesModal = false;
        $this->selectedGatewayForInstallmentFees = null;
    }

    /**
     * Abre o modal de gerenciamento de taxas do Slip PIX para um gateway específico.
     */
    public function openSlipPixFeesModalForGateway(string $gatewayId): void
    {
        try {
            $gateway = CustomerPayGateway::find($gatewayId);
            if (!$gateway) {
                session()->flash('error', 'Gateway não encontrado.');
                return;
            }

            $this->selectedGatewayForSlipPixFees = $gateway;
            $this->gatewaySlipPixFeesJson = $gateway->pay_slip_pix_fees_json ?? '';
            $this->showSlipPixFeesModal = true;

            // Envia o JSON via browser event para contornar bug do morphdom com WireUI modals
            $this->dispatchBrowserEvent('slip-pix-fees-data', [
                'json' => $gateway->pay_slip_pix_fees_json ?? '',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao abrir modal de taxas: ' . $e->getMessage());
        }
    }

    /**
     * Fecha o modal de taxas do Slip PIX sem salvar.
     */
    public function closeSlipPixFeesModal(): void
    {
        // Recarrega o JSON original do gateway para descartar alterações
        if ($this->selectedGatewayForSlipPixFees) {
            $this->gatewaySlipPixFeesJson = $this->selectedGatewayForSlipPixFees->pay_slip_pix_fees_json ?? '';
        }

        $this->showSlipPixFeesModal = false;
        $this->selectedGatewayForSlipPixFees = null;
    }

    /**
     * Salva as taxas do Slip PIX.
     */
    public function saveSlipPixFees(): void
    {
        if (!$this->selectedGatewayForSlipPixFees) {
            session()->flash('error', 'Gateway não selecionado.');
            return;
        }

        // Valida e normaliza o JSON antes de salvar
        if (!empty($this->gatewaySlipPixFeesJson)) {
            $decoded = json_decode($this->gatewaySlipPixFeesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                session()->flash('error', 'JSON inválido. Por favor, verifique o formato.');
                return;
            }

            // Normaliza os valores: converte vírgula para ponto em todos os valores
            $normalized = [];
            foreach ($decoded as $key => $value) {
                $normalized[$key] = str_replace(',', '.', (string) $value);
            }

            // Reconstrói o JSON com valores normalizados
            $this->gatewaySlipPixFeesJson = json_encode($normalized);
        }

        // Salva as alterações no gateway
        $this->selectedGatewayForSlipPixFees->update([
            'pay_slip_pix_fees_json' => $this->gatewaySlipPixFeesJson
        ]);

        session()->flash('success', 'Taxas do Slip PIX atualizadas com sucesso!');

        // Recarrega os gateways separados por status
        $customer = $this->findCustomerIgnoringActiveScope();
        if ($customer) {
            $gateways = CustomerPayGateway::where('customer_id', $customer->id)->get();
            $this->customerGateways = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 1;
            })->sortBy('pay_gateway_label')->values();

            $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 0;
            })->sortBy('pay_gateway_label')->values();
        }

        $this->showSlipPixFeesModal = false;
        $this->selectedGatewayForSlipPixFees = null;
    }

    /**
     * Abre o modal de gerenciamento de taxas do PIX para um gateway específico.
     */
    public function openPixFeesModalForGateway(string $gatewayId): void
    {
        try {
            $gateway = CustomerPayGateway::find($gatewayId);
            if (!$gateway) {
                session()->flash('error', 'Gateway não encontrado.');
                return;
            }

            $this->selectedGatewayForPixFees = $gateway;
            $this->gatewayPixFeesJson = $gateway->pay_pix_fees_json ?? '';
            $this->showPixFeesModal = true;

            $this->dispatchBrowserEvent('pix-fees-data', [
                'json' => $gateway->pay_pix_fees_json ?? '',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao abrir modal de taxas: ' . $e->getMessage());
        }
    }

    /**
     * Fecha o modal de taxas do PIX sem salvar.
     */
    public function closePixFeesModal(): void
    {
        if ($this->selectedGatewayForPixFees) {
            $this->gatewayPixFeesJson = $this->selectedGatewayForPixFees->pay_pix_fees_json ?? '';
        }

        $this->showPixFeesModal = false;
        $this->selectedGatewayForPixFees = null;
    }

    /**
     * Salva as taxas do PIX.
     */
    public function savePixFees(): void
    {
        if (!$this->selectedGatewayForPixFees) {
            session()->flash('error', 'Gateway não selecionado.');
            return;
        }

        if (!empty($this->gatewayPixFeesJson)) {
            $decoded = json_decode($this->gatewayPixFeesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                session()->flash('error', 'JSON inválido. Por favor, verifique o formato.');
                return;
            }

            $normalized = [];
            foreach ($decoded as $key => $value) {
                $normalized[$key] = str_replace(',', '.', (string) $value);
            }

            $this->gatewayPixFeesJson = json_encode($normalized);
        }

        $this->selectedGatewayForPixFees->update([
            'pay_pix_fees_json' => $this->gatewayPixFeesJson,
        ]);

        session()->flash('success', 'Taxas do PIX atualizadas com sucesso!');

        $customer = $this->findCustomerIgnoringActiveScope();
        if ($customer) {
            $gateways = CustomerPayGateway::where('customer_id', $customer->id)->get();
            $this->customerGateways = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 1;
            })->sortBy('pay_gateway_label')->values();

            $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 0;
            })->sortBy('pay_gateway_label')->values();
        }

        $this->showPixFeesModal = false;
        $this->selectedGatewayForPixFees = null;
    }

    /**
     * Abre o modal de gerenciamento de taxas do Boleto para um gateway específico.
     */
    public function openBoletoFeesModalForGateway(string $gatewayId): void
    {
        try {
            $gateway = CustomerPayGateway::find($gatewayId);
            if (!$gateway) {
                session()->flash('error', 'Gateway não encontrado.');
                return;
            }

            $this->selectedGatewayForBoletoFees = $gateway;
            $this->gatewayBoletoFeesJson = $gateway->pay_boleto_fees_json ?? '';
            $this->showBoletoFeesModal = true;

            $this->dispatchBrowserEvent('boleto-fees-data', [
                'json' => $gateway->pay_boleto_fees_json ?? '',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao abrir modal de taxas: ' . $e->getMessage());
        }
    }

    /**
     * Fecha o modal de taxas do Boleto sem salvar.
     */
    public function closeBoletoFeesModal(): void
    {
        if ($this->selectedGatewayForBoletoFees) {
            $this->gatewayBoletoFeesJson = $this->selectedGatewayForBoletoFees->pay_boleto_fees_json ?? '';
        }

        $this->showBoletoFeesModal = false;
        $this->selectedGatewayForBoletoFees = null;
    }

    /**
     * Salva as taxas do Boleto.
     */
    public function saveBoletoFees(): void
    {
        if (!$this->selectedGatewayForBoletoFees) {
            session()->flash('error', 'Gateway não selecionado.');
            return;
        }

        if (!empty($this->gatewayBoletoFeesJson)) {
            $decoded = json_decode($this->gatewayBoletoFeesJson, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                session()->flash('error', 'JSON inválido. Por favor, verifique o formato.');
                return;
            }

            $normalized = [];
            foreach ($decoded as $key => $value) {
                $normalized[$key] = str_replace(',', '.', (string) $value);
            }

            $this->gatewayBoletoFeesJson = json_encode($normalized);
        }

        $this->selectedGatewayForBoletoFees->update([
            'pay_boleto_fees_json' => $this->gatewayBoletoFeesJson,
        ]);

        session()->flash('success', 'Taxas do Boleto atualizadas com sucesso!');

        $customer = $this->findCustomerIgnoringActiveScope();
        if ($customer) {
            $gateways = CustomerPayGateway::where('customer_id', $customer->id)->get();
            $this->customerGateways = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 1;
            })->sortBy('pay_gateway_label')->values();

            $this->customerGatewaysInactive = $gateways->filter(function ($gateway) {
                return $gateway->pay_active == 0;
            })->sortBy('pay_gateway_label')->values();
        }

        $this->showBoletoFeesModal = false;
        $this->selectedGatewayForBoletoFees = null;
    }

    /**
     * Abre o modal de criação de novo cliente.
     */
    public function openNewCustomerModal(): void
    {
        $this->isEditingCustomer = false;
        $this->confirmingCustomerDeletion = false;
        $this->resetCustomerFields();
        $this->showCustomerModal = true;
        $this->resetErrorBag();
    }

    /**
     * Abre o modal de edição do cliente atual.
     */
    public function openEditCustomerModal(): void
    {
        if ($this->loadCustomerToEditForm()) {
            $this->showCustomerModal = true;
        }
    }

    /**
     * Carrega os dados do cliente selecionado no formulário de edição.
     */
    private function loadCustomerToEditForm(): bool
    {
        if (!$this->customerId) {
            session()->flash('error', 'Selecione um cliente antes de editar.');
            return false;
        }

        $customer = $this->findCustomerIgnoringActiveScope();

        if (!$customer) {
            session()->flash('error', 'Cliente não encontrado.');
            return false;
        }

        $this->isEditingCustomer = true;
        $this->customerNameCorporate = $customer->name_corporate ?? '';
        $this->customerNameFantasy = $customer->name_fantasy ?? '';
        $this->customerNameShort = $customer->name_short ?? '';

        // Carrega o tipo de documento do banco primeiro
        $docTypeFromDb = strtolower($customer->doc_type ?? 'cnpj');
        $docNum = $customer->doc_num ?? '';
        $docNumClean = preg_replace('/[^0-9]/', '', $docNum);

        // Verifica se o tipo salvo no banco corresponde ao número
        $docLength = strlen($docNumClean);
        $isValidCpf = ($docLength === 11);
        $isValidCnpj = ($docLength === 14);

        // Prioriza o tipo do banco se o número corresponder
        if ($docTypeFromDb === 'cpf' && $isValidCpf) {
            $this->customerDocType = 'cpf';
        } elseif ($docTypeFromDb === 'cnpj' && $isValidCnpj) {
            $this->customerDocType = 'cnpj';
        } elseif ($isValidCpf) {
            // Se o número é válido para CPF mas o banco diz CNPJ, detecta automaticamente
            $this->customerDocType = 'cpf';
        } elseif ($isValidCnpj) {
            // Se o número é válido para CNPJ mas o banco diz CPF, detecta automaticamente
            $this->customerDocType = 'cnpj';
        } else {
            // Se não conseguir detectar pelo número, usa o do banco
            $this->customerDocType = $docTypeFromDb;
        }

        $this->customerDocNum = $docNum;
        $this->customerSlug = $customer->customer_slug ?? '';
        $this->customerComercialContactName = $customer->comercial_contact_name ?? '';
        $this->customerComercialContactEmail = $customer->comercial_contact_email ?? '';
        $this->customerComercialContactDdd = $customer->comercial_contact_ddd ?? '';
        $this->customerComercialContactNum = $customer->comercial_contact_num ?? '';
        $this->customerFinancialContactName = $customer->financial_contact_name ?? '';
        $this->customerFinancialContactEmail = $customer->financial_contact_email ?? '';
        $this->customerFinancialContactDdd = $customer->financial_contact_ddd ?? '';
        $this->customerFinancialContactNum = $customer->financial_contact_num ?? '';
        $this->customerAddress = $customer->address ?? '';
        $this->customerAddressNumber = $customer->address_number ?? '';
        $this->customerAddressComplement = $customer->address_complement ?? '';
        $this->customerCityNeighborhood = $customer->city_neighborhood ?? '';
        $this->customerCity = $customer->city ?? '';
        $this->customerState = $customer->state ?? '';
        $this->customerZipCode = $customer->zip_code ?? '';
        $this->customerUrlSite = $customer->url_site ?? '';
        $this->customerUrlInstagram = $customer->url_instagram ?? '';
        $this->customerUrlFacebook = $customer->url_facebook ?? '';
        $this->customerGenerateInvoice = (bool) ($customer->generate_invoice ?? false);
        $this->confirmingCustomerDeletion = false;
        $this->resetErrorBag();

        return true;
    }

    /**
     * Fecha o modal de cliente.
     */
    public function closeCustomerModal(): void
    {
        $this->showCustomerModal = false;
        $this->isEditingCustomer = false;
        $this->confirmingCustomerDeletion = false;
        $this->resetCustomerFields();
        $this->resetErrorBag();
    }

    /**
     * Detecta automaticamente o tipo de documento baseado no número digitado.
     * Só atualiza se o tipo atual não corresponder ao número digitado.
     */
    public function updatedCustomerDocNum($value): void
    {
        if (empty($value)) {
            return;
        }

        $docNumClean = preg_replace('/[^0-9]/', '', $value);
        $docLength = strlen($docNumClean);

        // Só detecta automaticamente se o tipo atual não corresponder ao número
        if ($docLength === 11 && $this->customerDocType !== 'cpf') {
            // Número tem 11 dígitos mas tipo é CNPJ - corrige para CPF
            $this->customerDocType = 'cpf';
        } elseif ($docLength === 14 && $this->customerDocType !== 'cnpj') {
            // Número tem 14 dígitos mas tipo é CPF - corrige para CNPJ
            $this->customerDocType = 'cnpj';
        }
        // Se o número não tem 11 nem 14 dígitos, mantém o tipo atual
    }

    /**
     * Reseta os campos do formulário de cliente.
     */
    private function resetCustomerFields(): void
    {
        $this->customerNameCorporate = '';
        $this->customerNameFantasy = '';
        $this->customerNameShort = '';
        $this->customerDocType = 'cnpj';
        $this->customerDocNum = '';
        $this->customerSlug = '';
        $this->customerComercialContactName = '';
        $this->customerComercialContactEmail = '';
        $this->customerComercialContactDdd = '';
        $this->customerComercialContactNum = '';
        $this->customerFinancialContactName = '';
        $this->customerFinancialContactEmail = '';
        $this->customerFinancialContactDdd = '';
        $this->customerFinancialContactNum = '';
        $this->customerAddress = '';
        $this->customerAddressNumber = '';
        $this->customerAddressComplement = '';
        $this->customerCityNeighborhood = '';
        $this->customerCity = '';
        $this->customerState = '';
        $this->customerZipCode = '';
        $this->customerUrlSite = '';
        $this->customerUrlInstagram = '';
        $this->customerUrlFacebook = '';
        $this->customerGenerateInvoice = false;
        $this->confirmingCustomerDeletion = false;
    }

    /**
     * Salva ou atualiza um cliente.
     */
    public function saveCustomer(): void
    {
        $dataValidate = $this->validate([
            'customerNameCorporate' => ['required', 'string', 'max:255'],
            'customerDocType' => ['required', 'string', 'in:cpf,cnpj'],
            'customerDocNum' => ['required', 'cpf_cnpj'],
            'customerComercialContactName' => ['required', 'string', 'max:255'],
            'customerComercialContactEmail' => ['required', 'email', 'max:255'],
            'customerComercialContactDdd' => ['required', 'numeric'],
            'customerComercialContactNum' => ['required', 'numeric'],
            'customerFinancialContactName' => ['nullable', 'string', 'max:255'],
            'customerFinancialContactEmail' => ['nullable', 'email', 'max:255'],
            'customerFinancialContactDdd' => ['nullable', 'numeric'],
            'customerFinancialContactNum' => ['nullable', 'numeric'],
            'customerAddress' => ['required', 'string', 'max:255'],
            'customerAddressNumber' => ['required', 'string', 'max:255'],
            'customerAddressComplement' => ['nullable', 'string', 'max:255'],
            'customerCityNeighborhood' => ['required', 'string', 'max:255'],
            'customerCity' => ['required', 'string', 'max:255'],
            'customerState' => ['required', 'string', 'max:255'],
            'customerZipCode' => ['required', 'numeric'],
            'customerUrlSite' => ['required', 'url', 'max:255'],
            'customerUrlInstagram' => ['nullable', 'url', 'max:255'],
            'customerUrlFacebook' => ['nullable', 'url', 'max:255'],
        ], [
            'customerNameCorporate.required' => 'A razão social é obrigatória.',
            'customerDocType.required' => 'O tipo de documento é obrigatório.',
            'customerDocType.in' => 'O tipo de documento selecionado é inválido.',
            'customerDocNum.required' => 'O número do documento é obrigatório.',
            'customerSlug.required' => 'O slug do cliente é obrigatório.',
        ]);

        // Normaliza o tipo de documento e remove espaços
        $docTypeInput = strtolower(trim($this->customerDocType));

        // Valida se o tipo é válido
        if (!in_array($docTypeInput, ['cpf', 'cnpj'])) {
            $this->addError('customerDocType', 'Tipo de documento inválido. Deve ser CPF ou CNPJ.');
            return;
        }

        // Converte para maiúsculas para salvar no banco (como usado em outros lugares do código)
        $docTypeForDb = strtoupper($docTypeInput);

        // Validação customizada: verifica se o número do documento corresponde ao tipo
        $docNumClean = preg_replace('/[^0-9]/', '', $this->customerDocNum);

        if ($docTypeInput === 'cpf' && strlen($docNumClean) !== 11) {
            $this->addError('customerDocNum', 'CPF deve conter 11 dígitos.');
            return;
        }

        if ($docTypeInput === 'cnpj' && strlen($docNumClean) !== 14) {
            $this->addError('customerDocNum', 'CNPJ deve conter 14 dígitos.');
            return;
        }

        $app = sessionApp();

        if (!$app || !$app->id) {
            session()->flash('error', 'App não encontrado.');
            return;
        }

        $toNullableInt = function ($value) {
            $v = trim((string) $value);
            return $v === '' ? null : $v;
        };

        try {

            DB::beginTransaction();

            // Prepara os dados - mantém valores vazios como strings vazias se o usuário quiser
            $data = [
                'app_id' => $app->id,
                'name_corporate' => trim($this->customerNameCorporate),
                'name_fantasy' => trim($this->customerNameFantasy ?? ''),
                'name_short' => trim($this->customerNameShort ?? ''),
                'prefix_url' => Str::slug($this->customerSlug ?: $this->customerNameShort ?: $this->customerNameCorporate),
                'doc_type' => $docTypeForDb, // Salva em maiúsculas (CPF ou CNPJ) como esperado pelo banco
                'doc_num' => $docNumClean, // Salva sem formatação
                'customer_slug' => Str::slug($this->customerSlug),
                'comercial_contact_name' => trim($this->customerComercialContactName ?? ''),
                'comercial_contact_email' => trim($this->customerComercialContactEmail ?? ''),
                'comercial_contact_ddd' => $toNullableInt($this->customerComercialContactDdd ?? ''),
                'comercial_contact_num' => $toNullableInt($this->customerComercialContactNum ?? ''),
                'financial_contact_name' => trim($this->customerFinancialContactName ?? ''),
                'financial_contact_email' => trim($this->customerFinancialContactEmail ?? ''),
                'financial_contact_ddd' => $toNullableInt($this->customerFinancialContactDdd ?? ''),
                'financial_contact_num' => $toNullableInt($this->customerFinancialContactNum ?? ''),
                'address' => trim($this->customerAddress ?? ''),
                'address_number' => trim($this->customerAddressNumber ?? ''),
                'address_complement' => trim($this->customerAddressComplement ?? ''),
                'city_neighborhood' => trim($this->customerCityNeighborhood ?? ''),
                'city' => trim($this->customerCity ?? ''),
                'state' => !empty(trim($this->customerState ?? '')) ? strtoupper(trim($this->customerState)) : '',
                'zip_code' => trim($this->customerZipCode ?? ''),
                'url_site' => trim($this->customerUrlSite ?? ''),
                'url_instagram' => trim($this->customerUrlInstagram ?? ''),
                'url_facebook' => trim($this->customerUrlFacebook ?? ''),
                'generate_invoice' => $this->customerGenerateInvoice ? 1 : 0,
            ];

            if ($this->isEditingCustomer && $this->customerId) {
                // Atualiza cliente existente
                $customer = $this->findCustomerIgnoringActiveScope();
                if (!$customer) {
                    session()->flash('error', 'Cliente não encontrado para atualização.');
                    return;
                }

                // Verifica se o slug já existe em outro cliente
                $existingSlug = Customer::withoutGlobalScope(ActiveCustomerScope::class)
                    ->where('customer_slug', $data['customer_slug'])
                    ->where('id', '!=', $this->customerId)
                    ->first();

                if ($existingSlug) {
                    $this->addError('customerSlug', 'Este slug já está em uso por outro cliente.');
                    return;
                }

                // Atualiza o cliente
                $customer->update($data);
                $message = "Cliente {$this->customerNameCorporate} atualizado com sucesso!";
            } else {
                // Verifica se o slug já existe
                $existingSlug = Customer::withoutGlobalScope(ActiveCustomerScope::class)
                    ->where('customer_slug', $data['customer_slug'])->first();
                if ($existingSlug) {
                    DB::rollBack();
                    $this->addError('customerSlug', 'Este slug já está em uso. Por favor, escolha outro.');
                    return;
                }

                // Cria novo cliente
                $customer = Customer::create($data);
                $message = "Cliente {$this->customerNameCorporate} criado com sucesso!";

                // Seleciona o novo cliente
                $this->customerId = $customer->id;
                sessionCustomer($this->customerId);
            }

            // Força atualização da lista de clientes e recarrega o cliente na sessão
            sessionCustomers(true);
            sessionCustomer($this->customerId);

            // Recarrega as propriedades do componente ANTES de fechar o modal
            $this->customer = sessionCustomer();
            $this->customers = sessionCustomers();

            session()->flash('success', $message);

            $this->dispatchBrowserEvent('showNotification', ['type' => 'success', 'message' => $message]);

            DB::commit();

            // Redireciona após salvar
            if ($this->standaloneEdit && $this->customerId) {
                redirect()->route('configuracoes-editar-cliente', ['customer_id' => $this->customerId]);
            } else {
                redirect()->route('configuracoes');
            }

        } catch (\Exception $e) {

            DB::rollBack();

            session()->flash('error', 'Erro ao salvar cliente: ' . $e->getMessage());
            $this->addError('Exception', 'Erro ao salvar cliente: ' . $e->getMessage());
            $this->dispatchBrowserEvent('showNotification', ['type' => 'error', 'message' => 'Erro ao salvar cliente: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirma e remove um cliente, se não possuir eventos ou campanhas.
     */
    public function confirmDeleteCustomer(bool $force = false): mixed
    {
        if (!$this->customerId) {
            session()->flash('error', 'Selecione um cliente para remover.');
            return null;
        }

        $customer = $this->findCustomerIgnoringActiveScope();
        if (!$customer) {
            session()->flash('error', 'Cliente não encontrado.');
            return null;
        }

        if (!$force) {
            $this->confirmingCustomerDeletion = true;
            return null;
        }

        // Checagem de segurança: não remover se houver eventos ou campanhas
        $hasEvents = Event::where('customer_id', $customer->id)->exists();
        $hasCampaigns = Campaign::where('customer_id', $customer->id)->exists();

        if ($hasEvents || $hasCampaigns) {
            $msg = 'Não é possível remover: existem ' . (($hasEvents ? 'eventos ' : '') . ($hasCampaigns ? 'ou campanhas ' : '')) . 'vinculados.';
            session()->flash('error', trim($msg));
            $this->dispatchBrowserEvent('showNotification', ['type' => 'error', 'message' => trim($msg)]);
            $this->confirmingCustomerDeletion = false;
            return null;
        }

        $summary = [];

        DB::transaction(function () use ($customer, &$summary) {
            // IDs para limpeza
            $organizerIds = CustomerOrganizer::where('customer_id', $customer->id)->pluck('id');
            $campaignOrganizerIds = CampaignOrganizer::where('customer_id', $customer->id)->pluck('id');
            $orgSubIds = CustomerOrganizationSub::where('customer_id', $customer->id)->pluck('id');
            $orgIds = CustomerOrganization::where('customer_id', $customer->id)->pluck('id');

            $summary = [
                'organizers_event' => $organizerIds->count(),
                'organizers_campaign' => $campaignOrganizerIds->count(),
                'organizations' => $orgIds->count(),
                'org_subs' => $orgSubIds->count(),
                'users' => $customer->users()->count(),
            ];

            // Remove módulos do cliente (tb_customers_app_modules)
            CustomerAppModule::where('customer_id', $customer->id)->delete();

            // Remove gateways do cliente (tb_customers_pay_gateways)
            CustomerPayGateway::where('customer_id', $customer->id)->delete();

            // Desvincula usuários
            $customer->users()->detach();
            if ($organizerIds->count()) {
                CustomerOrganizerUser::whereIn('organizer_id', $organizerIds)->delete();
            }
            if ($campaignOrganizerIds->count()) {
                UserCampaignOrganizer::whereIn('organizer_id', $campaignOrganizerIds)->delete();
            }

            // Remove organizadores e estruturas
            CustomerOrganizer::whereIn('id', $organizerIds)->delete();
            CampaignOrganizer::whereIn('id', $campaignOrganizerIds)->delete();
            CustomerOrganizationSub::whereIn('id', $orgSubIds)->delete();
            CustomerOrganization::whereIn('id', $orgIds)->delete();

            // Remove o cliente
            $customer->delete();
        });

        $message = 'Cliente removido. Eventos: ' . $summary['organizers_event'] . ' organizadores; Campanhas: ' . $summary['organizers_campaign'] . ' organizadores; Filiais: ' . $summary['organizations'] . '; Subdivisões: ' . $summary['org_subs'] . '; Usuários desvinculados: ' . $summary['users'] . '.';

        session()->flash('success', $message);
        $this->dispatchBrowserEvent('showNotification', ['type' => 'success', 'message' => $message]);

        // Reseta estado e volta para a listagem
        $this->confirmingCustomerDeletion = false;
        $this->customerId = null;
        $this->customer = null;
        $this->resetCustomerFields();

        sessionCustomers(true);
        session()->forget('customer');

        return redirect()->route('configuracoes');
    }
}
