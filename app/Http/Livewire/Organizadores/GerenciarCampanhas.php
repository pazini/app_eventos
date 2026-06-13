<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Models\CustomerOrganizerUser;
use App\Models\CustomerUser;
use App\Models\User;
use App\Services\ModuleAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class GerenciarCampanhas extends Component
{
    // Sessão e Customer
    public $app;
    public $customer;
    public $customerRole;
    public $customers;
    public $customer_id;

    // Contexto (eventos ou campanhas)
    public $context = 'campanhas'; // Este componente é específico para campanhas

    // Aba ativa
    public $activeTab = 'organizadores'; // organizadores, usuarios, filiais, centros-custo

    // ========== ORGANIZADORES ==========
    public $organizers;
    public $organizer;
    public $novoOrganizer = false;
    public $removerOrganizer = false;
    public $organizerToDelete = null;  // ID do organizer a ser removido
    public $confirmDelete = false;     // Estado da primeira confirmação
    public $confirmDeleteText = '';    // Texto de confirmação digitado
    public $organizations;
    public $organization_id;
    public $organizationsSub;
    public $organization_sub_id;
    public $novoOrganizerOrganizationId;

    // ========== FILIAIS ==========
    public $organizationCadastrar = false;
    public $organizationEditar = false;
    public $organizationRemover = false;
    public $organization;
    public $organization_name;
    public $organization_description;
    public $editingOrganizationId;

    // ========== CENTROS DE CUSTO (SETORES) ==========
    public $organizationSubCadastrar = false;
    public $organizationSubEditar = false;
    public $organizationSubRemover = false;
    public $organization_sub;
    public $organization_subs;
    public $organization_sub_name;
    public $organization_sub_description;
    public $editingOrganizationSubId;
    public $organization_id_for_subs;
    public $searchOrganizationSubs = '';
    protected $syncingOrganization = false;

    // Dados do organizador
    public $owner_name;
    public $owner_email;
    public $owner_phone_telefone;
    public $owner_phone_country = 55;
    public $owner_phone_ddd;
    public $owner_phone_num;

    // Usuários do organizador
    public $novoUsuario;
    public $novoUsuarioId;
    public $novoUsuarioListUsers;
    public $name;
    public $email;
    public $telefone;

    // Busca organizadores
    public $searchOrganizadores = '';

    // ========== USUÁRIOS ==========
    public $customerUsers = [];
    public $searchUsuarios = '';
    public $showEditModal = false;
    public $selectedUserId = null;
    public $selectedUser = null;
    public $editName = '';
    public $editEmail = '';
    public $editContactCountry = '';
    public $editContactDdd = '';
    public $editContactNum = '';
    public $showPasswordSection = false;
    public $newPassword = '';
    public $newPasswordConfirmation = '';
    public $showDeleteConfirmation = false;
    public $userToDelete = null;
    public $organizationSubSelecionada;

    // Dados do centro de custo
    public $subName = '';
    public $subSlug = '';
    public $subDescription = '';

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        // APP
        $this->app = sessionApp();
        $this->customers = sessionCustomers();
        $this->customer = sessionCustomer();
        $this->customerRole = $this->customer->user_role ?? false;
        $this->customer_id = $this->customer->id ?? false;

        // Inicializar as coleções como vazias para evitar erros de null
        $this->organizers = collect([]);
        $this->organizations = collect([]);
        $this->organizationsSub = collect([]);
        $this->customerUsers = [];

        // Verificação de acesso - permitir super admin e admin/owner
        $user = auth()->user();
        $isSuperAdmin = $user && ModuleAccessService::userIsAppAdmin($user);

        if (!$isSuperAdmin) {
            if (!isAdmin() && !isOwner()) {
                session()->flash('error', 'Acesso negado');
                return redirect()->route('dashboard');
            }

            // Verificar se o usuário tem acesso ao módulo de campanhas
            if (auth()->check() && $this->customer) {
                // Para campanhas, usamos verificação similar mas ajustada
                if (!ModuleAccessService::userCanAccessCampaigns($user, $this->customer)) {
                    session()->flash('error', 'Você não tem permissão para acessar gerenciamento de campanhas.');
                    return redirect()->route('dashboard');
                }
            }
        }

        // Carregamento inicial baseado na aba ativa
        $this->loadInitialData();
    }

    public function updated($name, $value)
    {
        if (empty($name)) {
            return;
        }

        if ($name == 'customer_id') {
            $this->customer_id = $value;

            if ($value ?? false) {
                sessionCustomer($value);
                $this->customer = sessionCustomer();
                $this->customerRole = $this->customer->user_role ?? false;
            } else {
                $this->customer = false;
                sessionClear('customer');
            }

            $this->loadInitialData();
        }

        if ($name == 'activeTab') {
            $this->loadInitialData();
        }

        // Manter formatação do telefone
        if ($name == 'owner_phone_num' && !empty($value)) {
            $cleaned = preg_replace('/[^0-9]/', '', $value);
            if (strlen($cleaned) > 8) {
                $this->owner_phone_num = substr($cleaned, 0, 5) . '-' . substr($cleaned, 5, 4);
            } elseif (strlen($cleaned) >= 8) {
                $this->owner_phone_num = substr($cleaned, 0, 4) . '-' . substr($cleaned, 4);
            }
        }
    }

    public function loadInitialData()
    {
        if ($this->customer_id) {
            $this->getOrganizations();
            $this->getOrganizers();
            $this->loadUsers();

            if (!$this->organization_id_for_subs) {
                $preferredOrg = session('organization_id_for_subs') ?? $this->organization_id;
                if ($preferredOrg) {
                    $this->syncOrganizationSelection($preferredOrg);
                }
            }
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetAllData();
        $this->loadInitialData();

        $this->dispatchBrowserEvent('tab-switched', ['tab' => $tab]);
    }

    public function resetAllData()
    {
        // Reset organizadores
        $this->novoOrganizer = false;
        $this->removerOrganizer = false;
        $this->organizer = null;
        $this->owner_name = '';
        $this->owner_email = '';
        $this->owner_phone_ddd = '';
        $this->owner_phone_num = '';

        // Reset usuários
        $this->showEditModal = false;
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editContactCountry = '';
        $this->editContactDdd = '';
        $this->editContactNum = '';
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->novoUsuario = false;
        $this->novoUsuarioId = null;
        $this->name = '';
        $this->email = '';
        $this->telefone = '';

        // Reset organizações (filiais)
        $this->organizationCadastrar = false;
        $this->organizationEditar = false;
        $this->organizationRemover = false;
        $this->organization_id = null;
        $this->editingOrganizationId = null;
        $this->organization_name = '';
        $this->organization_description = '';

        // Reset centros de custo (subdivisões)
        $this->organizationSubCadastrar = false;
        $this->organizationSubEditar = false;
        $this->organizationSubRemover = false;
        $this->organization_sub_id = null;
        $this->editingOrganizationSubId = null;
        $this->organization_sub_name = '';
        $this->organization_sub_description = '';
    }

    // ========== MÉTODOS DE ORGANIZADORES ==========
    public function getOrganizers($organizerId = false)
    {
        if ($organizerId ?? false) {
            return CampaignOrganizer::with(['users', 'campaigns', 'organization'])->find($organizerId);
        } else {
            if ($this->customer ?? false) {
                $query = CampaignOrganizer::with(['users', 'campaigns', 'organization'])
                    ->where('customer_id', $this->customer->id);
                if ($this->organization_id ?? false) {
                    $query = $query->where('organization_id', $this->organization_id);
                }
                $this->organizers = $query->orderBy('organizer_name', 'asc')->get();
            } elseif (isAdmin()) {
                $query = CampaignOrganizer::with(['users', 'campaigns', 'organization']);
                if ($this->organization_id ?? false) {
                    $query = $query->where('organization_id', $this->organization_id);
                }
                $this->organizers = $query->orderBy('organizer_name', 'asc')->get();
            } else {
                $this->organizers = collect([]);
            }

            return $this->organizers;
        }
    }

    public function resetNovoOrganizer()
    {
        $this->organizer = false;
        $this->novoOrganizer = false;
        $this->organizationsSub = collect([]);
        $this->organization_sub_id = '';
        $this->novoOrganizerOrganizationId = '';
        $this->owner_name = '';
        $this->owner_email = '';
        $this->owner_phone_ddd = null;
        $this->owner_phone_num = '';
        // NÃO resetar organization_id para manter o filtro da lista
    }

    public function setAlteraOrganizer($organizerId)
    {
        $this->resetNovoOrganizer();
        $this->novoOrganizer = true;

        $this->organizer = $this->getOrganizers($organizerId);

        // Não alterar organization_id global - pode estar sendo usado como filtro
        $this->novoOrganizerOrganizationId = $this->organizer->organization_id;
        $this->organization_sub_id = $this->organizer->organization_sub_id ?? null;
        $this->owner_name = $this->organizer->owner_name;
        $this->owner_email = $this->organizer->owner_email;
        $this->owner_phone_ddd = (string) ($this->organizer->owner_phone_ddd ?? '');
        $this->owner_phone_num = (string) ($this->organizer->owner_phone_num ?? '');
    }

    public function cadastrarOrganizer()
    {
        // Debug para verificar se o método está sendo chamado
        logger('cadastrarOrganizer method called', [
            'organizer' => $this->organizer,
            'owner_name' => $this->owner_name,
            'owner_email' => $this->owner_email,
            'owner_phone_ddd' => $this->owner_phone_ddd,
            'owner_phone_num' => $this->owner_phone_num,
        ]);

        if ($this->organizer ?? false) {
            // ALTERAR ORGANIZER EXISTENTE
            $validateData = $this->validate([
                'owner_name' => ['required', 'string'],
                'owner_email' => ['nullable', 'email'],
                'owner_phone_ddd' => ['nullable', 'integer'],
                'owner_phone_num' => ['nullable', 'string'],
            ]);

            // Mantém organizer_name/full normalizados em UPPERCASE ao salvar a edição.
            $validateData['organizer_name'] = $this->organizer->organizer_name;
            $validateData['organizer_name_full'] = $this->organizer->organizer_name_full;
            $validateData['organizer_slug'] = Str::slug($validateData['organizer_name_full']);

            $this->organizer->update($validateData);

            session()->flash('success', 'Organizador ' . $this->organizer->organizer_name_full . ' atualizado');
            $this->resetNovoOrganizer();
            $this->getOrganizers();
        }
        else
        {
            // CRIAR NOVO ORGANIZER

            // Limpar e tratar campos de telefone
            $this->owner_phone_ddd = trim($this->owner_phone_ddd) ?: null;
            // Remover máscara do telefone para validação
            $phoneClean = preg_replace('/[^0-9]/', '', $this->owner_phone_num);
            $this->owner_phone_num = $phoneClean ?: null;

            $validateData = $this->validate([
                'owner_name' => ['required', 'string'],
                'owner_email' => ['required', 'email'],
                'owner_phone_ddd' => ['required', 'integer', 'min:10', 'max:99'],
                'owner_phone_num' => ['required', 'integer'],
                'novoOrganizerOrganizationId' => ['required', 'exists:tb_customers_organizations,id'],
            ], [
                'owner_phone_ddd.required' => 'DDD é obrigatório',
                'owner_phone_ddd.integer' => 'DDD deve ser um número válido',
                'owner_phone_ddd.min' => 'DDD inválido',
                'owner_phone_ddd.max' => 'DDD inválido',
                'owner_phone_num.required' => 'Telefone é obrigatório',
                'owner_phone_num.regex' => 'Telefone deve ter entre 8 e 11 dígitos',
            ]);

            // Para campanhas, usa apenas a organização
            if ($this->context === 'campanhas') {
                $organizationId = $validateData['novoOrganizerOrganizationId'];
                $organizationSubId = null;
            } else {
                // Para eventos, precisa da subdivisão
                $this->validate([
                    'organization_sub_id' => ['required', 'exists:tb_customers_organizations_subs,id'],
                ]);
                $organizationId = $validateData['novoOrganizerOrganizationId'];
                $organizationSubId = $this->organization_sub_id;
            }

            try
            {
                DB::beginTransaction();

                $organization  = $this->organizations->find($this->novoOrganizerOrganizationId);
                $organizerName = $this->customer->name_fantasy . ' // ' . $organization->organization_name;
                $baseSlug      = Str::slug($organizerName,'-');
                $slug          = $baseSlug;

                // Criar o organizer
                $organizerData = [
                    'customer_id'         => $this->customer->id,
                    'organization_id'     => $organizationId,
                    'organization_sub_id' => $organizationSubId,
                    'organizer_name'      => $organization->organization_name,
                    'organizer_name_full' => $organizerName,
                    'organizer_slug'      => $slug,
                    'owner_name'          => $validateData['owner_name'],
                    'owner_email'         => $validateData['owner_email'],
                    'owner_phone_country' => 55,
                    'owner_phone_ddd'     => $validateData['owner_phone_ddd'],
                    'owner_phone_num'     => $validateData['owner_phone_num'],
                ];

                // Garantir que o slug seja único
                $existingOrganizer = CampaignOrganizer::where('customer_id', $this->customer->id)
                    ->where('organization_id', $organizationId)
                    ->where('organizer_name_full', $organizerName)
                    ->first();

                if ($existingOrganizer) {
                    $existingOrganizer->update($organizerData);

                    DB::commit();

                    session()->flash('success', 'Centro de Custo já existe e foi atualizado');
                    $this->resetNovoOrganizer();
                    return redirect()->route('campanhas-organizadores');
                }

                $organizer = CampaignOrganizer::create($organizerData);

                DB::commit();

                session()->flash('success', 'Centro de Custo criado com sucesso');

                $this->resetNovoOrganizer();

                redirect()->route('campanhas-organizadores');

            } catch (\Exception $e) {
                DB::rollback();
                session()->flash('error', 'Erro ao criar centro de custo: ' . $e->getMessage());
            }
        }
    }

    public function setNovoOrganizer()
    {
        $this->resetNovoOrganizer();
        $this->loadOrganizations();
        $this->novoOrganizer = true;
    }

    public function cancelNovoOrganizer()
    {
        $this->resetNovoOrganizer();
    }

    public function removerOrganizer($organizerId)
    {
        try {
            $organizer = CampaignOrganizer::find($organizerId);

            if (!$organizer) {
                session()->flash('error', 'Centro de custo não encontrado');
                return;
            }

            // Verificar se há campanhas associadas
            if ($organizer->campaigns && $organizer->campaigns->count() > 0) {
                session()->flash('error', 'Não é possível remover este centro de custo pois há campanhas associadas a ele');
                return;
            }

            // Verificar se há usuários associados
            if ($organizer->users && $organizer->users->count() > 0) {
                session()->flash('error', 'Não é possível remover este centro de custo pois há usuários associados a ele');
                return;
            }

            $organizer->delete();
            $this->getOrganizers();
            $this->resetDeleteState();

            session()->flash('success', 'Centro de custo removido com sucesso');

        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao remover centro de custo: ' . $e->getMessage());
        }
    }

    public function confirmarRemocaoOrganizer($organizerId)
    {
        $organizer = CampaignOrganizer::with(['users', 'campaigns'])->find($organizerId);

        if (!$organizer) {
            session()->flash('error', 'Centro de custo não encontrado');
            return;
        }

        // Verificar se é da própria empresa
        if ($this->isPropriaEmpresa($organizer)) {
            session()->flash('error', 'O centro de custo da própria empresa não pode ser removido, apenas editado.');
            return;
        }

        $this->organizer = $organizer;
        $this->organizerToDelete = $organizerId;
        $this->confirmDelete = false;
        $this->confirmDeleteText = '';
        $this->removerOrganizer = true;
    }

    public function confirmarPrimeiraEtapa()
    {
        if (!$this->organizerToDelete) {
            session()->flash('error', 'Centro de custo não encontrado');
            return;
        }

        $this->confirmDelete = true;
    }

    public function executarRemocao()
    {
        // Debug para verificar os valores
        logger('executarRemocao called', [
            'confirmDelete' => $this->confirmDelete,
            'organizerToDelete' => $this->organizerToDelete,
            'confirmDeleteText' => $this->confirmDeleteText,
            'text_match' => $this->confirmDeleteText === 'CONFIRMAR'
        ]);

        if ($this->confirmDelete && $this->organizerToDelete && trim($this->confirmDeleteText) === 'CONFIRMAR') {
            logger('Conditions met, calling removerOrganizer');
            $this->removerOrganizer($this->organizerToDelete);
        } else {
            logger('Conditions NOT met for removal');
            session()->flash('error', 'Por favor, digite exatamente "CONFIRMAR" para confirmar a remoção.');
        }
    }

    public function cancelarRemocao()
    {
        $this->resetDeleteState();
    }

    private function resetDeleteState()
    {
        $this->organizerToDelete = null;
        $this->confirmDelete = false;
        $this->confirmDeleteText = '';
        $this->removerOrganizer = false;
        $this->organizer = false;
    }

    // Verifica se o organizador é da própria empresa (sem filial específica)
    private function isPropriaEmpresa($organizer)
    {
        return is_null($organizer->organization_id) || $organizer->organization_id === null;
    }

    // Verifica se pode remover o organizador
    private function podeRemoverOrganizer($organizer)
    {
        return !$this->isPropriaEmpresa($organizer);
    }

    // ========== MÉTODOS DE USUÁRIOS ==========
    public function loadUsers()
    {
        $user = auth()->user();

        if (!$this->customer_id) {
            $this->customerUsers = [];
            return;
        }

        $customer = \App\Models\Customer::find($this->customer_id);
        if (!$customer) {
            $this->customerUsers = [];
            return;
        }

        $query = $customer->users()->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns']);

        if (!empty($this->searchUsuarios)) {
            $searchTerm = '%' . $this->searchUsuarios . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->orderBy('name', 'asc')->get();

        $this->customerUsers = $users->filter(function ($user) {
            return $user->pivot->user_active == 1 && $user->pivot->can_campaigns == 1;
        });
    }

    // ========== MÉTODOS DE FILIAIS ==========
    public function loadOrganizations()
    {
        if (!$this->customer_id) {
            $this->organizations = collect([]);
            return;
        }

        $this->organizations = CustomerOrganization::where('customer_id', $this->customer_id)
            ->orderBy('organization_name')
            ->get();
    }

    public function cadastrarFilial($execute = false)
    {
        $this->organizationCadastrar = true;

        if ($execute ?? false) {
            $validateData = $this->validate([
                'organization_name' => ['required', 'string'],
                'organization_description' => ['required', 'string'],
            ]);

            $validateData['customer_id'] = $this->customer->id;
            $validateData['organization_slug'] = Str::slug($validateData['organization_name']);

            if ($organization = CustomerOrganization::where('customer_id', $this->customer->id)->where('organization_slug', $validateData['organization_slug'])->first()) {
                $organization->update($validateData);
                session()->flash('success', 'Filial ' . $organization->organization_name . ' atualizada');
            } else {
                $organization = CustomerOrganization::create($validateData);
                session()->flash('success', 'Filial ' . $organization->organization_name . ' criada');
            }

            $this->loadOrganizations();
            $this->organizationCadastrar = false;
            $this->organization_name = '';
            $this->organization_description = '';
            $this->editingOrganizationId = null;

            return;
        } else {
            $this->editingOrganizationId = false;
            $this->organization_name = '';
            $this->organization_description = '';
        }
    }

    public function alterarFilial($organization_id, $execute = false)
    {
        $this->organization_id = $organization_id;
        $this->editingOrganizationId = $organization_id;

        if ($execute ?? false) {
            $validateData = $this->validate([
                'organization_name' => ['required', 'string'],
                'organization_description' => ['required', 'string'],
            ]);

            $newSlug = Str::slug($this->organization_name);

            if (CustomerOrganization::where('organization_slug', $newSlug)->whereNot('id', $organization_id)->count()) {
                return session()->flash('error', 'Já existe uma filial com esse nome');
            }

            DB::beginTransaction();

            $organization = CustomerOrganization::find($organization_id);
            $organization->update([
                'organization_name' => $this->organization_name,
                'organization_description' => $this->organization_description,
                'organization_slug' => $newSlug,
            ]);

            DB::commit();

            $this->loadOrganizations();
            $this->editingOrganizationId = false;
            $this->organization_id = false;
            $this->organization_name = '';
            $this->organization_description = '';

            return session()->flash('organization_success_' . $organization_id, 'Filial atualizada');
        } else {
            if ($organization = CustomerOrganization::find($organization_id)) {
                $this->organization_name = $organization->organization_name;
                $this->organization_description = $organization->organization_description;
            }
        }
    }

    public function removerFilial($organization_id)
    {
        $organization = CustomerOrganization::with(['organizers', 'organizationSubs', 'users', 'campaignOrganizers'])
            ->where('customer_id', $this->customer->id)
            ->where('id', $organization_id)
            ->first();

        if (!$organization) {
            session()->flash('organization_error_' . $organization_id, 'Filial não encontrada!');
            return;
        }

        // Verificar se há organizadores de eventos vinculados (tb_customers_organizers)
        if ($organization->organizers->count() ?? 0) {
            session()->flash('organization_error_' . $organization_id, 'Não é possível remover! Temos ' . $organization->organizers->count() . ' organizador(es) de eventos vinculado(s) a esta filial.');
            return;
        }

        // Verificar se há organizadores de campanhas vinculados (tbc_campaign_organizer)
        if ($organization->campaignOrganizers->count() ?? 0) {
            session()->flash('organization_error_' . $organization_id, 'Não é possível remover! Temos ' . $organization->campaignOrganizers->count() . ' organizador(es) de campanhas vinculado(s) a esta filial.');
            return;
        }

        // Verificar se há subdivisões vinculadas
        if ($organization->organizationSubs->count() ?? 0) {
            session()->flash('organization_error_' . $organization_id, 'Não é possível remover! Temos ' . $organization->organizationSubs->count() . ' subdivisão(ões) vinculada(s) a esta filial lá em eventos.');
            return;
        }

        // Verificar se há usuários vinculados (users_customer)
        if ($organization->users->count() ?? 0) {
            session()->flash('organization_error_' . $organization_id, 'Não é possível remover! Existem ' . $organization->users->count() . ' usuário(s) vinculado(s) a esta filial.');
            return;
        }

        try {
            $organization->delete();
            $this->loadOrganizations();
            session()->flash('success', 'Filial removida com sucesso!');
        } catch (\Exception $e) {
            session()->flash('organization_error_' . $organization_id, 'Erro ao remover filial: ' . $e->getMessage());
        }
    }

    public function cancelarEdicaoFilial()
    {
        $this->editingOrganizationId = null;
        $this->organization_id = null;
        $this->organization_name = '';
        $this->organization_description = '';
    }

    // ========== MÉTODOS DE CENTROS DE CUSTO ==========
    public function loadOrganizationsSub()
    {
        if (!$this->customer_id) {
            $this->organizationsSub = collect([]);
            return;
        }

        $this->organizationsSub = CustomerOrganizationSub::whereHas('organization', function ($query) {
            $query->where('customer_id', $this->customer_id);
        })
            ->with('organization')
            ->orderBy('organization_sub_name')
            ->get();
    }

    public function cadastrarCentroCusto($execute = false)
    {
        $this->organizationSubCadastrar = true;

        if ($execute ?? false) {
            $validateData = $this->validate([
                'organization_sub_name' => ['required', 'string'],
                'organization_sub_description' => ['required', 'string'],
                'organization_id_for_subs' => ['required'],
            ]);

            $organization = CustomerOrganization::find($this->organization_id_for_subs);
            $organization_sub_slug = Str::slug($organization->organization_slug . '-' . $this->organization_sub_name, '-');

            $validateData['customer_id'] = $this->customer->id;
            $validateData['organization_id'] = $this->organization_id_for_subs;
            $validateData['organization_sub_name'] = strtoupper($validateData['organization_sub_name']);
            $validateData['organization_sub_slug'] = $organization_sub_slug;

            unset($validateData['organization_id_for_subs']);

            if ($organization_sub = CustomerOrganizationSub::where('customer_id', $this->customer->id)->where('organization_id', $this->organization_id_for_subs)->where('organization_sub_slug', $organization_sub_slug)->first()) {
                $organization_sub->update($validateData);
                session()->flash('success', 'Centro de Custo atualizado');
            } else {
                $organization_sub = CustomerOrganizationSub::create($validateData);
                session()->flash('success', 'Centro de Custo criado');
            }

            $this->getOrganizationsSubs($this->organization_id_for_subs);
            $this->organizationSubCadastrar = false;
            $this->organization_sub_name = '';
            $this->organization_sub_description = '';

            return;
        } else {
            $this->editingOrganizationSubId = false;
            $this->organization_sub_name = '';
            $this->organization_sub_description = '';
        }
    }

    public function alterarCentroCusto($organization_sub_id, $execute = false)
    {
        $this->editingOrganizationSubId = $organization_sub_id;
        $this->organization_sub_id = $organization_sub_id;

        if ($execute ?? false) {
            $validateData = $this->validate([
                'organization_sub_name' => ['required', 'string'],
                'organization_sub_description' => ['required', 'string'],
            ]);

            $organization_sub = CustomerOrganizationSub::find($organization_sub_id);
            if (!$organization_sub) {
                return session()->flash('error', 'Centro de custo não encontrado');
            }

            $organization = CustomerOrganization::find($organization_sub->organization_id);
            if (!$organization) {
                return session()->flash('error', 'Filial vinculada ao centro de custo não encontrada');
            }

            $organizationSubName = mb_strtoupper(trim($validateData['organization_sub_name']));
            $organizationSubDescription = trim($validateData['organization_sub_description']);
            $slug = Str::slug($organization->organization_slug . '-' . $organizationSubName, '-');

            if (CustomerOrganizationSub::where('organization_sub_slug', $slug)->whereNot('id', $organization_sub_id)->count()) {
                return session()->flash('error', 'Já existe um centro de custo com esse nome');
            }

            DB::beginTransaction();

            try {
                $organization_sub->update([
                    'organization_sub_slug' => $slug,
                    'organization_sub_name' => $organizationSubName,
                    'organization_sub_description' => $organizationSubDescription,
                ]);

                $organizers = CustomerOrganizer::where('organization_sub_id', $organization_sub_id)->get();
                foreach ($organizers as $organizer) {
                    $organizerNameFull = mb_strtoupper($this->customer->name_corporate . ' | ' . $organization->organization_name . ' | ' . $organizationSubName);
                    $organizerSlug = Str::slug($organizerNameFull, '-');

                    if (CustomerOrganizer::where('organizer_slug', $organizerSlug)->whereNot('id', $organizer->id)->exists()) {
                        throw new \RuntimeException('Já existe um organizador com esse nome de filial');
                    }

                    $organizer->organizer_name = $organizationSubName;
                    $organizer->organizer_name_full = $organizerNameFull;
                    $organizer->organizer_slug = $organizerSlug;
                    $organizer->save();
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                return session()->flash('error', $e->getMessage());
            }

            $this->getOrganizationsSubs($organization_sub->organization_id);
            $this->editingOrganizationSubId = false;
            $this->organization_sub_id = false;
            $this->organization_sub_name = '';
            $this->organization_sub_description = '';

            session()->flash('organization_sub_success', 'Centro de Custo atualizado');
            return session()->flash('organization_sub_success_' . $organization_sub_id, 'Centro de Custo atualizado');
        } else {
            if ($organization_sub = CustomerOrganizationSub::find($organization_sub_id)) {
                $this->organization_sub_name = $organization_sub->organization_sub_name;
                $this->organization_sub_description = $organization_sub->organization_sub_description;
            }
        }
    }

    public function alterarOrganizationSub($organization_sub_id, $execute = false)
    {
        return $this->alterarCentroCusto($organization_sub_id, (bool) $execute);
    }

    public function removerCentroCusto($organization_sub_id)
    {
        $organization_sub = CustomerOrganizationSub::with(['organizers'])->find($organization_sub_id);

        if ($organization_sub->organizers->count() ?? 0) {
            session()->flash('organization_sub_error_' . $organization_sub_id, 'Existem organizadores vinculados!');
            return;
        }

        $organization_id = $organization_sub->organization_id;
        $organization_sub->delete();
        $this->getOrganizationsSubs($organization_id);

        session()->flash('success', 'Centro de Custo removido');
        session()->flash('organization_sub_success', 'Centro de Custo removido');
        return;
    }

    public function cancelarEdicaoCentroCusto()
    {
        $this->editingOrganizationSubId = null;
        $this->organization_sub_name = '';
        $this->organization_sub_description = '';
    }

    public function render()
    {
        return view('livewire.organizadores.gerenciar-campanhas', [
            'organizers' => $this->getFilteredOrganizers()
        ])->layout('layouts.app-pep-auth');
    }

    public function getFilteredOrganizers()
    {
        $organizers = $this->organizers ?? collect([]);

        // Filtro por filial
        if ($organizers && $organizers->count() > 0 && ($this->organization_id ?? false)) {
            $organizers = $organizers->where('organization_id', $this->organization_id);
        }

        // Filtro de busca
        if ($organizers && $organizers->count() > 0 && !empty($this->searchOrganizadores)) {
            $searchTerm = $this->normalizeSearchText($this->searchOrganizadores);

            $organizers = $organizers->filter(function ($organizer) use ($searchTerm) {
                $users = $organizer->users ?? collect([]);
                $campaigns = $organizer->campaigns ?? collect([]);

                $matchesUsers = $users->contains(function ($user) use ($searchTerm) {
                    return
                        str_contains($this->normalizeSearchText($user->name ?? ''), $searchTerm) ||
                        str_contains($this->normalizeSearchText($user->email ?? ''), $searchTerm);
                });

                $matchesCampaigns = $campaigns->contains(function ($campaign) use ($searchTerm) {
                    return
                        str_contains($this->normalizeSearchText($campaign->name ?? ''), $searchTerm) ||
                        str_contains($this->normalizeSearchText($campaign->name_short ?? ''), $searchTerm) ||
                        str_contains($this->normalizeSearchText($campaign->slug ?? ''), $searchTerm);
                });

                return
                    str_contains($this->normalizeSearchText($organizer->organizer_name ?? ''), $searchTerm) ||
                    str_contains($this->normalizeSearchText($organizer->organizer_name_full ?? ''), $searchTerm) ||
                    str_contains($this->normalizeSearchText($organizer->owner_name ?? ''), $searchTerm) ||
                    str_contains($this->normalizeSearchText($organizer->owner_email ?? ''), $searchTerm) ||
                    str_contains($this->normalizeSearchText(($organizer->owner_phone_ddd ?? '') . ($organizer->owner_phone_num ?? '')), $searchTerm) ||
                    str_contains($this->normalizeSearchText($organizer->organization?->organization_name ?? ''), $searchTerm) ||
                    $matchesUsers ||
                    $matchesCampaigns;
            })->sortBy('organizer_name_full');
        } else {
            if ($organizers && $organizers->count() > 0) {
                $organizers = $organizers->sortBy('organizer_name_full');
            }
        }

        return $organizers;
    }

    private function normalizeSearchText($value): string
    {
        return Str::of((string) $value)
            ->ascii()
            ->lower()
            ->squish()
            ->toString();
    }

    public function getFilteredOrganizationSubs()
    {
        if (!$this->organization_id_for_subs) {
            return collect([]);
        }

        $subs = $this->organization_subs ?? collect([]);

        // Filtro de busca
        if ($subs && $subs->count() > 0 && !empty($this->searchOrganizationSubs)) {
            $searchTerm = strtolower(trim($this->searchOrganizationSubs));
            $subs = $subs->filter(function ($sub) use ($searchTerm) {
                return
                    str_contains(strtolower($sub->organization_sub_name ?? ''), $searchTerm) ||
                    str_contains(strtolower($sub->organization_sub_description ?? ''), $searchTerm);
            });
        }

        return $subs->sortBy('organization_sub_name');
    }

    // Método para atualizar dropdowns baseado no contexto
    public function updatedOrganizationId($value)
    {
        if ($this->syncingOrganization) {
            return;
        }

        $this->organization_sub_id = null;
        $this->organizationsSub = collect([]);

        $orgId = $value ?: null;

        // Recarrega organizadores ao trocar o filtro de filial
        $this->organizers = $this->getOrganizers();

        // Mantém o contexto da filial sincronizado para outras abas
        $this->organization_id_for_subs = $orgId;

        if ($orgId) {
            session(['organization_id_for_subs' => $orgId]);

            $customerId = $this->customer->id ?? $this->customer_id;

            $subs = CustomerOrganizationSub::where('customer_id', $customerId)
                ->where('organization_id', $orgId)
                ->orderBy('organization_sub_name')
                ->get();

            $this->organizationsSub = $subs;
            $this->organization_subs = $subs;
        } else {
            session()->forget('organization_id_for_subs');
            $this->organization_subs = collect([]);
        }
    }

    public function updatedOrganizationIdForSubs($value)
    {
        if ($this->syncingOrganization) {
            return;
        }

        if ($value) {
            session(['organization_id_for_subs' => $value]);
            $this->getOrganizationsSubs($value);
            return;
        }

        session()->forget('organization_id_for_subs');
        $this->organization_subs = collect([]);
    }

    public function updatedSearchUsuarios($value)
    {
        $this->loadUsers();
    }

    public function getOrganizations()
    {
        if ($this->customer ?? false) {
            $this->organizations = CustomerOrganization::where('customer_id', $this->customer->id)->orderBy('organization_name')->get();
        } elseif (isAdmin()) {
            $this->organizations = CustomerOrganization::orderBy('organization_name')->get();
        }

        return $this->organizations;
    }

    protected function syncOrganizationSelection($organizationId): void
    {
        if ($this->syncingOrganization) {
            return;
        }

        $this->syncingOrganization = true;
        $orgId = $organizationId ?: null;

        if ($this->organization_id !== $orgId) {
            $this->organization_id = $orgId;
        }

        if ($this->organization_id_for_subs !== $orgId) {
            $this->organization_id_for_subs = $orgId;
        }

        $this->organizers = $this->getOrganizers();

        if ($orgId) {
            session(['organization_id_for_subs' => $orgId]);
            $this->getOrganizationsSubs($orgId);
        } else {
            session()->forget('organization_id_for_subs');
            $this->organization_subs = null;
        }

        $this->syncingOrganization = false;
    }

    public function getOrganizationsSubs($organization_id, $organization_sub_id = false)
    {
        // Similar ao GerenciarEventos, mas adaptado para campanhas
        $this->organization_subs = CustomerOrganizationSub::where('customer_id', $this->customer->id)
            ->where('organization_id', $organization_id)
            ->get();

        return $this->organization_subs;
    }

    // ========== MÉTODOS DE USUÁRIOS DOS ORGANIZADORES ==========
    public function setNovoUsuario($organizerId)
    {
        $this->novoUsuario = $organizerId;

        // Buscar todos os usuários do customer com can_campaigns = 1
        $customer = \App\Models\Customer::find($this->customer_id);
        $allUsers = $customer->users()
            ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns'])
            ->get()
            ->filter(function ($user) {
                return $user->pivot->user_active == 1;
            });

        if ($organizer = $this->organizers->find($organizerId)) {
            $organizerUsersIds = array_column($organizer->users->toArray(), 'id');
            $this->novoUsuarioListUsers = $allUsers->whereNotIn('id', $organizerUsersIds);
        } else {
            $this->novoUsuarioListUsers = $allUsers;
        }
    }

    public function cancelNovoUsuario()
    {
        $this->novoUsuario = '';
        $this->novoUsuarioId = '';
        $this->novoUsuarioListUsers = [];
        $this->name = '';
        $this->email = '';
        $this->telefone = '';
    }

    public function desassociarUsuario($organizerId, $userId)
    {
        // Para campanhas, precisamos usar a tabela correta
        $organizerUser = DB::table('users_campaign_organizer')
            ->where('organizer_id', $organizerId)
            ->where('user_id', $userId)
            ->first();

        if ($organizerUser) {
            DB::table('users_campaign_organizer')
                ->where('organizer_id', $organizerId)
                ->where('user_id', $userId)
                ->delete();

            $this->getOrganizers();
            $this->cancelNovoUsuario();
            return session()->flash('associarUsuario_success_' . $organizerId, 'Usuário removido');
        } else {
            return session()->flash('associarUsuario_status_' . $organizerId, 'Usuário não pertence a esse organizador');
        }
    }

    public function associarUsuario($organizerId)
    {
        if (empty($this->novoUsuarioId)) {
            $validateData = $this->validate([
                'name' => ['required', 'string'],
                'email' => ['required', 'email'],
                'telefone' => ['required', 'string'],
            ]);

            if (!$user = User::where('email', trim($validateData['email']))->first()) {
                $validateData['contact_ddd'] = substr($validateData['telefone'], 0, 2);
                $validateData['contact_num'] = substr($validateData['telefone'], strlen($validateData['telefone']) > 10 ? -9 : -8);
                $validateData['password'] = substr($validateData['telefone'], -4);

                $user = User::create([
                    'name' => strtolower($validateData['name']),
                    'email' => strtolower($validateData['email']),
                    'contact_ddd' => $validateData['contact_ddd'],
                    'contact_num' => $validateData['contact_num'],
                    'password' => Hash::make($validateData['password']),
                ]);

                session()->flash('associarUsuario_status_' . $organizerId, 'Usuário criado - Senha inicial: ' . $validateData['password']);
            }

            $this->novoUsuarioId = $user->id;

            // Recarregar a relação de usuários do customer para verificar se já existe
            $this->customer->load('users');

            if (!$customerUser = $this->customer->users->where('id', $user->id)->first()) {
                $organizer = $this->getOrganizers($organizerId);

                $customerUser = CustomerUser::create([
                    'customer_id' => $this->customer->id,
                    'user_id' => $user->id,
                    'user_active' => true,
                    'user_role' => 'user',
                    'organization_id' => $organizer->organization_id,
                    'can_events' => false,
                    'can_campaigns' => true, // Para campanhas
                ]);
            }
        }

        // Para campanhas, usar a tabela users_campaign_organizer
        if (DB::table('users_campaign_organizer')->where('organizer_id', $organizerId)->where('user_id', $this->novoUsuarioId)->first()) {
            $this->getOrganizers();
            $this->loadUsers(); // Recarregar lista de usuários
            return session()->flash('associarUsuario_success_' . $organizerId, 'Esse usuário já está associado');
        } else {
            DB::table('users_campaign_organizer')->insert([
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'user_id' => $this->novoUsuarioId,
                'organizer_id' => $organizerId,
                'user_active' => true,
                'user_role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->getOrganizers();
            $this->loadUsers(); // Recarregar lista de usuários
            $this->cancelNovoUsuario();
            return session()->flash('associarUsuario_success_' . $organizerId, 'Usuário associado');
        }
    }

    // ========== MÉTODOS DE EDIÇÃO DE USUÁRIOS ==========
    public function openEditModal(string $userId): void
    {
        $user = User::find($userId);

        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return;
        }

        $this->selectedUserId = $userId;
        $this->selectedUser = $user;
        $this->editName = $user->name;
        $this->editEmail = $user->email;
        $this->editContactCountry = $user->contact_country ?? '55';
        $this->editContactDdd = $user->contact_ddd ?? '';
        $this->editContactNum = $user->contact_num ?? '';
        $this->showPasswordSection = false;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->selectedUserId = null;
        $this->selectedUser = null;
        $this->editName = '';
        $this->editEmail = '';
        $this->editContactCountry = '';
        $this->editContactDdd = '';
        $this->editContactNum = '';
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->showDeleteConfirmation = false;
        $this->userToDelete = null;
        $this->resetErrorBag();

        if ($this->customer_id) {
            $this->loadUsers();
        }
    }

    public function updateUser(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255'],
            'editContactCountry' => ['nullable', 'max:10'],
            'editContactDdd' => ['nullable', 'max:5'],
            'editContactNum' => ['nullable', 'max:20'],
        ]);

        $user = User::find($this->selectedUserId);

        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            $this->closeEditModal();
            return;
        }

        $existingUser = User::where('email', $this->editEmail)
            ->where('id', '!=', $this->selectedUserId)
            ->first();

        if ($existingUser) {
            $this->addError('editEmail', 'Este e-mail já está em uso.');
            return;
        }

        try {
            $user->update([
                'name' => $this->editName,
                'email' => $this->editEmail,
                'contact_country' => !empty($this->editContactCountry) ? (string)$this->editContactCountry : null,
                'contact_ddd' => !empty($this->editContactDdd) ? (string)$this->editContactDdd : null,
                'contact_num' => !empty($this->editContactNum) ? (string)$this->editContactNum : null,
            ]);

            session()->flash('success', "Usuário {$user->name} atualizado!");
            $this->closeEditModal();
            $this->loadUsers();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar: ' . $e->getMessage());
        }
    }

    public function updateUserPassword(): void
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
            'newPasswordConfirmation' => ['required', 'same:newPassword'],
        ]);

        $user = User::find($this->selectedUserId);

        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);
        session()->flash('success', "Senha atualizada!");
        $this->closeEditModal();
    }

    public function confirmDelete(string $userId): void
    {
        $this->userToDelete = $userId;
        $this->showDeleteConfirmation = true;
    }

    public function removeUser(): void
    {
        if (!$this->userToDelete || !$this->customer_id) {
            session()->flash('error', 'Erro ao remover usuário.');
            $this->closeEditModal();
            return;
        }

        $user = User::find($this->userToDelete);

        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            $this->closeEditModal();
            return;
        }

        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id === $user->id) {
            session()->flash('error', 'Você não pode remover a si mesmo.');
            $this->closeEditModal();
            return;
        }

        $user->customers()->detach($this->customer_id);
        session()->flash('success', "Usuário {$user->name} removido!");
        $this->closeEditModal();
        $this->loadUsers();
    }
}
