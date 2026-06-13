<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\CustomerOrganizerUser;
use App\Models\CustomerUser;
use App\Models\User;
use App\Services\ModuleAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class GerenciarEventos extends Component
{
    // Sessão e Customer
    public $app;
    public $customer;
    public $customerRole;
    public $customers;
    public $customer_id;

    // Contexto (eventos ou campanhas)
    public $context = 'eventos'; // Este componente é específico para eventos

    // Aba ativa
    public $activeTab = 'organizadores'; // organizadores, usuarios, filiais, centros-custo

    // ========== ORGANIZADORES ==========
    public $organizers;
    public $organizer;
    public $novoOrganizer = false;
    public $removerOrganizer = false;
    public $organizations;
    public $organization_id;
    public $organizationsSub;
    public $organization_sub_id;
    public $novoOrganizerOrganizationId;

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

    // ========== FILIAIS (INSTITUIÇÕES) ==========
    public $organizationCadastrar = false;
    public $organization;
    public $organization_name;
    public $organization_description;
    public $editingOrganizationId;

    // ========== CENTROS DE CUSTO (SETORES) ==========
    public $organizationSubCadastrar = false;
    public $organization_sub;
    public $organization_subs;
    public $organization_sub_name;
    public $organization_sub_description;
    public $editingOrganizationSubId;
    public $organization_id_for_subs;
    public $searchOrganizationSubs = '';
    protected $syncingOrganization = false;

    // Editar subdivisão (setor)
    public function alterarOrganizationSub($organizationSubId, $confirm = false)
    {
        $this->organization_sub_id = $organizationSubId;
        $this->editingOrganizationSubId = $organizationSubId;
        $this->organizationSubCadastrar = false;

        $organizationSub = CustomerOrganizationSub::find($organizationSubId);
        if (!$organizationSub) {
            session()->flash('error', 'Centro de Custo não encontrado.');
            return;
        }

        if (!$confirm) {
            $this->organization_sub = $organizationSub;
            $this->organization_sub_name = $organizationSub->organization_sub_name;
            $this->organization_sub_description = $organizationSub->organization_sub_description;
            return;
        }

        $validateData = $this->validate([
            'organization_sub_name' => ['required', 'string'],
            'organization_sub_description' => ['nullable', 'string'],
        ]);

        $organization = CustomerOrganization::find($organizationSub->organization_id);
        if (!$organization) {
            session()->flash('error', 'Filial vinculada ao Centro de Custo não encontrada.');
            return;
        }

        $organizationSubName = mb_strtoupper(trim($validateData['organization_sub_name']));
        $organizationSubDescription = isset($validateData['organization_sub_description'])
            ? trim($validateData['organization_sub_description'])
            : null;
        $slug = toSlug($organization->organization_slug . '-' . $organizationSubName, '-');

        if (CustomerOrganizationSub::where('organization_sub_slug', $slug)->whereNot('id', $organizationSubId)->exists()) {
            session()->flash('error', 'Já existe um centro de custo com esse nome');
            return;
        }

        DB::beginTransaction();

        try {
            $organizationSub->update([
                'organization_sub_slug' => $slug,
                'organization_sub_name' => $organizationSubName,
                'organization_sub_description' => $organizationSubDescription,
            ]);

            $organizers = CustomerOrganizer::where('organization_sub_id', $organizationSubId)->get();
            foreach ($organizers as $organizer) {
                $organizerNameFull = mb_strtoupper($this->customer->name_corporate . ' | ' . $organization->organization_name . ' | ' . $organizationSubName);
                $organizerSlug = toSlug($organizerNameFull, '-');

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
            session()->flash('error', $e->getMessage());
            return;
        }

        $this->organization_sub = null;
        $this->organization_sub_name = '';
        $this->organization_sub_description = '';
        $this->editingOrganizationSubId = null;
        $this->organization_sub_id = null;
        $this->getOrganizationsSubs($organizationSub->organization_id);

        session()->flash('success', 'Centro de Custo alterado com sucesso.');
        session()->flash('organization_success_' . $organizationSubId, 'Centro de Custo alterado com sucesso.');
        session()->flash('organization_sub_success', 'Centro de Custo alterado com sucesso.');
        session()->flash('organization_sub_success_' . $organizationSubId, 'Centro de Custo alterado com sucesso.');
    }
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

        // Verificação de acesso - CORRIGIDO para permitir super admin
        $user = auth()->user();
        $isSuperAdmin = $user && ModuleAccessService::userIsAppAdmin($user);

        if (!$isSuperAdmin) {
            if (!isAdmin() && !isOwner()) {
                session()->flash('error', 'Acesso negado');
                return redirect()->route('dashboard');
            }

            // Verificar se o usuário tem acesso ao módulo de eventos
            if (auth()->check() && $this->customer) {
                if (!ModuleAccessService::userCanAccessEvents($user, $this->customer)) {
                    session()->flash('error', 'Você não tem permissão para acessar gerenciamento de eventos.');
                    return redirect()->route('dashboard');
                }
            }
        }

        // Carrega dados iniciais
        $this->loadInitialData();

        // Restaura última filial usada na aba de centros de custo
        $lastOrgForSubs = session('organization_id_for_subs');
        if ($lastOrgForSubs) {
            $this->syncOrganizationSelection($lastOrgForSubs);
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
                } elseif (($this->activeTab ?? 'organizadores') === 'setores') {
                    $this->getOrganizationsSubs(null);
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.organizadores.gerenciar-eventos', [
            'organizers' => $this->getFilteredOrganizers()
        ])->layout('layouts.app-pep-auth');
    }

    // ========== MÉTODOS GERAIS ==========

    public function updated($name, $value)
    {
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

            // Resetar dados relacionados
            $this->resetAllData();

            // Recarregar dados
            $this->loadInitialData();
        }

        if ($name == 'novoOrganizerOrganizationId') {
            $this->organization_id = $value;
            $this->loadOrganizationsSub($value);
        }

        if ($name == 'organization_id') {
            $this->updatedOrganizationId($value);
        }

        if ($name == 'organization_id_for_subs') {
            $this->updatedOrganizationIdForSubs($value);
        }
    }

    public function resetAllData()
    {
        // Organizadores
        $this->organization_id = null;
        $this->organizations = null;
        $this->organizers = null;
        $this->novoOrganizer = false;
        $this->removerOrganizer = false;
        $this->organizer = null;
        $this->searchOrganizadores = '';
        $this->resetNovoOrganizer();

        // Usuários
        $this->customerUsers = [];
        $this->searchUsuarios = '';
        $this->closeEditModal();

        // Filiais
        $this->organizationCadastrar = false;
        $this->organization = null;
        $this->organization_name = '';
        $this->organization_description = '';
        $this->editingOrganizationId = null;

        // Centros de Custo
        $this->organizationSubCadastrar = false;
        $this->organization_sub = null;
        $this->organization_subs = null;
        $this->organization_sub_name = '';
        $this->organization_sub_description = '';
        $this->editingOrganizationSubId = null;
        $this->organization_id_for_subs = null;
        $this->searchOrganizationSubs = '';
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;

        // Reset de todas as variáveis de modais e edição ao trocar de tab
        $this->resetModalStates();

        // Recarrega dados quando necessário
        if ($tab === 'usuarios') {
            $this->loadUsers();
        }
        if ($tab === 'organizadores' && empty($this->organizers)) {
            $this->getOrganizers();
        }
        if ($tab === 'filiais' && empty($this->organizations)) {
            $this->getOrganizations();
        }
        if ($tab === 'centros-custo') {
            $this->getOrganizations();
            $preferredOrg = $this->organization_id ?? $this->organization_id_for_subs ?? session('organization_id_for_subs');
            if ($preferredOrg && !$this->organization_id) {
                $this->syncOrganizationSelection($preferredOrg);
            }
        }
        if ($tab === 'setores') {
            if (empty($this->organizations)) {
                $this->getOrganizations();
            }

            $this->organization_id_for_subs = null;
            session()->forget('organization_id_for_subs');
            $this->getOrganizationsSubs(null);
        }
    }

    // Método para resetar todos os estados de modais e edição
    private function resetModalStates()
    {
        // Modais
        $this->novoOrganizer = false;
        $this->removerOrganizer = false;
        $this->organizationCadastrar = false;
        $this->organizationSubCadastrar = false;
        $this->showEditModal = false;
        $this->novoUsuario = false;

        // Variáveis de edição de filiais
        $this->organization_id = null;
        $this->editingOrganizationId = null;
        $this->organization_name = '';
        $this->organization_description = '';

        // Variáveis de edição de subdivisões
        $this->organization_sub_id = null;
        $this->editingOrganizationSubId = null;
        $this->organization_sub_name = '';
        $this->organization_sub_description = '';

        // Variáveis de edição de usuários
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

        // Variáveis de novo usuário
        $this->novoUsuarioId = null;
        $this->name = '';
        $this->email = '';
        $this->telefone = '';

        // Variáveis de organizador
        $this->organizer = null;
        $this->owner_name = '';
        $this->owner_email = '';
        $this->owner_phone_ddd = '';
        $this->owner_phone_num = '';
    }

    public function updatedOrganizationId($value)
    {
        $this->syncOrganizationSelection($value);
    }

    public function updatedOrganizationIdForSubs($value)
    {
        if (($this->activeTab ?? '') === 'setores') {
            $this->organization_id_for_subs = $value ?: null;
            $this->getOrganizationsSubs(null);
            return;
        }

        $this->syncOrganizationSelection($value);
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
            $this->getOrganizationsSubs(null);
        }

        $this->syncingOrganization = false;
    }

    // ========== ORGANIZADORES ==========

    public function getOrganizers($organizerId = false)
    {
        if ($organizerId ?? false) {
            return CustomerOrganizer::with(['users', 'events'])->find($organizerId);
        } else {
            if ($this->customer ?? false) {
                $query = CustomerOrganizer::with(['users', 'events'])
                    ->where('customer_id', $this->customer->id);
                if ($this->organization_id ?? false) {
                    $query = $query->where('organization_id', $this->organization_id);
                }
                $this->organizers = $query->orderBy('organizer_name_full', 'asc')->get();
            } elseif (isAdmin()) {
                $query = CustomerOrganizer::with(['users', 'events']);
                if ($this->organization_id ?? false) {
                    $query = $query->where('organization_id', $this->organization_id);
                }
                $this->organizers = $query->orderBy('organizer_name_full', 'asc')->get();
            } else {
                $this->organizers = collect([]);
            }

            return $this->organizers ?? collect([]);
        }
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
            $searchTerm = strtolower(trim($this->searchOrganizadores));
            $organizers = $organizers->filter(function ($organizer) use ($searchTerm) {
                return
                    str_contains(strtolower($organizer->organizer_name ?? ''), $searchTerm) ||
                    str_contains(strtolower($organizer->organizer_name_full ?? ''), $searchTerm) ||
                    str_contains(strtolower($organizer->owner_name ?? ''), $searchTerm) ||
                    str_contains(strtolower($organizer->owner_email ?? ''), $searchTerm);
            })->sortBy('organizer_name_full');
        } else {
            if ($organizers && $organizers->count() > 0) {
                $organizers = $organizers->sortBy('organizer_name_full');
            }
        }

        return $organizers;
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

    public function loadOrganizationsSub($organizationId)
    {
        // PEGA TODOS OS SETORES DA FILIAL
        $this->organizationsSub = CustomerOrganizationSub::where('customer_id', $this->customer->id)
            ->where('organization_id', $organizationId)
            ->get();
    }

    public function setNovoOrganizer()
    {
        $this->resetNovoOrganizer();
        $this->novoOrganizer = true;
        $this->organizations = CustomerOrganization::where('customer_id', $this->customer->id)->get();

        if ($this->organization_id ?? false) {
            $this->novoOrganizerOrganizationId = $this->organization_id;
            $this->loadOrganizationsSub($this->organization_id);
        }
    }

    public function resetNovoOrganizer()
    {
        $this->organizer = false;
        $this->novoOrganizer = false;
        $this->organizationsSub = false;
        $this->organization_sub_id = '';
        $this->owner_name = '';
        $this->owner_email = '';
        $this->owner_phone_ddd = '';
        $this->owner_phone_num = '';
    }

    public function setAlteraOrganizer($organizerId)
    {
        $this->resetNovoOrganizer();
        $this->novoOrganizer = true;

        $this->organizer = $this->getOrganizers($organizerId);
        $this->organization_id = $this->organizer->organization_id;
        $this->organization_sub_id = $this->organizer->organization_sub_id ?? null;
        $this->owner_name = $this->organizer->owner_name;
        $this->owner_email = $this->organizer->owner_email;
        $this->owner_phone_ddd = $this->organizer->owner_phone_ddd;
        $this->owner_phone_num = $this->organizer->owner_phone_num;
    }

    public function cadastrarOrganizer()
    {
        if ($this->organizer ?? false) {

            $validateData = $this->validate([
                'owner_name' => ['required', 'string'],
                'owner_email' => ['required', 'email'],
                'owner_phone_ddd' => ['required', 'integer'],
                'owner_phone_num' => ['required', 'integer'],
            ]);

            // Mantém organizer_name/full normalizados em UPPERCASE ao salvar a edição.
            $validateData['organizer_name'] = $this->organizer->organizer_name;
            $validateData['organizer_name_full'] = $this->organizer->organizer_name_full;
            $validateData['organizer_slug'] = Str::slug($validateData['organizer_name_full']);

            $this->organizer->update($validateData);

            session()->flash('success', 'Organizador ' . $this->organizer->organizer_name_full . ' atualizado');
            session()->flash('organizer_success_' . $this->organizer->id, 'Organizador atualizado com sucesso');
        } else {
            $validateData = $this->validate([
                'organization_id' => ['required', 'string'],
                'organization_sub_id' => ['required', 'string'],
                'owner_name' => ['required', 'string'],
                'owner_email' => ['required', 'email'],
                'owner_phone_ddd' => ['required', 'integer'],
                'owner_phone_num' => ['required', 'integer'],
            ]);

            $organization = $this->organizations->find($this->organization_id);
            $organizationSub = CustomerOrganizationSub::find($this->organization_sub_id);

            $validateData['owner_phone_country'] = 55;
            $validateData['customer_id'] = $this->customer->id;
            $validateData['organizer_name'] = $organizationSub->organization_sub_name;
            $validateData['organizer_name_full'] = $this->customer->name_corporate . ' | ' . $organization->organization_name . ' | ' . $organizationSub->organization_sub_name;
            $validateData['organizer_slug'] = Str::slug($validateData['organizer_name_full']);



            if ($organizer = CustomerOrganizer::where('organizer_slug', $validateData['organizer_slug'])->first()) {
                $organizer->update($validateData);
                session()->flash('success', 'Organizador ' . $organizer->organizer_name_full . ' atualizado');
            } else {
                $organizer = CustomerOrganizer::create($validateData);
                session()->flash('success', 'Organizador ' . $organizer->organizer_name_full . ' criado');
            }
        }

        $this->getOrganizers();
        $this->resetNovoOrganizer();
    }

    public function removerOrganizer($organizerId, $confirm = false)
    {
        $this->organizer = $this->getOrganizers($organizerId);

        if (!$this->organizer) {
            $this->removerOrganizer = false;
            return session()->flash('error', 'Organizador não encontrado.');
        }

        if ($this->isPropriaEmpresa($this->organizer)) {
            $this->removerOrganizer = false;
            return session()->flash('error', 'O centro de custo da própria empresa não pode ser removido, apenas editado.');
        }

        if (!$confirm) {
            $this->removerOrganizer = true;
            return;
        }

        if ($this->organizer->events->count() ?? 0) {
            return session()->flash('error', 'Organizador possui eventos. Não pode ser removido');
        } elseif ($this->organizer->users->count() ?? 0) {
            return session()->flash('error', 'Organizador possui usuários vinculados.');
        } else {
            $organizer = CustomerOrganizer::find($organizerId);
            $organizer->delete();

            $this->getOrganizers();
            $this->removerOrganizer = false;
            return session()->flash('success', 'Organizador removido');
        }
    }

    private function isPropriaEmpresa($organizer): bool
    {
        return is_null($organizer->organization_id);
    }

    // Métodos de usuários do organizador
    public function setNovoUsuario($organizerId)
    {
        $this->novoUsuario = $organizerId;
        $emailNotSearch = ['proeventpay@gmail.com', 'admin@empresateste.com'];

        if ($organizer = $this->organizers->find($organizerId)) {
            $organizerUsersIds = array_column($organizer->users->toArray(), 'id');
            $this->novoUsuarioListUsers = $this->customer->users->whereNotIn('email', $emailNotSearch)->whereNotIn('id', $organizerUsersIds);
        } else {
            $this->novoUsuarioListUsers = $this->customer->users->whereNotIn('email', $emailNotSearch);
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
        $organizerUser = CustomerOrganizerUser::where('organizer_id', $organizerId)->where('user_id', $userId)->first();

        if ($organizerUser) {
            $organizerUser->delete();
            $this->getOrganizers();
            $this->cancelNovoUsuario();
            return session()->flash('associarUsuario_success_' . $organizerId, 'Usuário removido');
        } else {
            return session()->flash('associarUsuario_status_' . $organizerId, 'Usuário não pertence a esse organizador');
        }
    }

    public function associarUsuario($organizerId)
    {
        if (!$this->novoUsuarioId ?? false) {
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

            if (!$customerUser = $this->customer->users->where('user_id', $user->id)->first()) {
                $organizer = $this->getOrganizers($organizerId);

                $customerUser = CustomerUser::create([
                    'customer_id' => $this->customer->id,
                    'user_id' => $user->id,
                    'user_active' => true,
                    'user_role' => 'user',
                    'organization_id' => $organizer->organization_id,
                    'can_events' => true,
                    'can_campaigns' => false,
                ]);
            }
        }

        if ($organizerUser = CustomerOrganizerUser::where('organizer_id', $organizerId)->where('user_id', $this->novoUsuarioId)->first()) {
            $this->getOrganizers();
            return session()->flash('associarUsuario_success_' . $organizerId, 'Esse usuário já está associado');
        } else {
            $organizerUser = CustomerOrganizerUser::create([
                'user_id' => $this->novoUsuarioId,
                'organizer_id' => $organizerId,
                'user_active' => true,
                'user_role' => 'user',
            ]);
        }

        if (isset($organizerUser) && $organizerUser) {
            $this->getOrganizers();
            $this->cancelNovoUsuario();
            return session()->flash('associarUsuario_success_' . $organizerId, 'Usuário associado');
        }
    }

    // ========== USUÁRIOS ==========

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
            return !ModuleAccessService::userIsAppAdmin($user);
        })->sortBy('name')->values();
    }

    public function updatedSearchUsuarios()
    {
        $this->loadUsers();
    }

    public function openEditModal(string $userId): void
    {
        try {
            $preservedUsers = $this->customerUsers;
            $user = User::find($userId);

            if (!$user) {
                session()->flash('error', 'Usuário não encontrado.');
                return;
            }

            $customer = \App\Models\Customer::find($this->customer_id);
            $customerRelation = $customer ? $customer->users()->where('users_customer.user_id', $userId)->first() : null;
            $pivot = $customerRelation?->pivot;

            if (!$pivot) {
                session()->flash('error', 'Usuário não está vinculado a este cliente.');
                return;
            }

            $this->selectedUserId = $userId;
            $this->selectedUser = $user;
            $this->editName = $user->name ?? '';
            $this->editEmail = $user->email ?? '';
            $this->editContactCountry = $user->contact_country ?? '';
            $this->editContactDdd = $user->contact_ddd ?? '';
            $this->editContactNum = $user->contact_num ?? '';
            $this->showPasswordSection = false;
            $this->newPassword = '';
            $this->newPasswordConfirmation = '';
            $this->showDeleteConfirmation = false;
            $this->showEditModal = true;
            $this->resetErrorBag();

            $this->customerUsers = $preservedUsers;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao abrir modal: ' . $e->getMessage());
        }
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

    // ========== FILIAIS (INSTITUIÇÕES) ==========

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

            $this->getOrganizations();
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
        $this->editingOrganizationId = $organization_id;

        if ($execute ?? false) {
            $validateData = $this->validate([
                'organization_name' => ['required', 'string'],
                'organization_description' => ['required', 'string'],
            ]);

            if (CustomerOrganization::where('organization_name', mb_strtoupper($this->organization_name))->whereNot('id', $organization_id)->count()) {
                return session()->flash('error', 'Já existe uma filial com esse nome');
            }

            DB::beginTransaction();

            $organization = CustomerOrganization::find($organization_id);
            $organization->update([
                'organization_name' => mb_strtoupper($this->organization_name),
                'organization_description' => trim((string) $this->organization_description),
            ]);

            // Atualiza organizadores relacionados
            if ($organizers = CustomerOrganizer::where('organization_id', $organization_id)->get()) {
                foreach ($organizers as $organizer) {
                    $organizer_name_full = mb_strtoupper($this->customer->name_corporate . ' | ' . $this->organization_name);

                    if ($organization_sub = CustomerOrganizationSub::find($organizer->organization_sub_id)) {
                        $organizer_name_full .= mb_strtoupper(' | ' . $organization_sub->organization_sub_name);
                    }

                    $organizer_slug = toSlug($organizer_name_full, '-');

                    $organizer->organizer_name_full = $organizer_name_full;
                    $organizer->organizer_slug = $organizer_slug;
                    $organizer->save();
                }
            }

            DB::commit();

            $this->getOrganizations();
            $this->editingOrganizationId = false;
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
            session()->flash('organization_error_' . $organization_id, 'Não é possível remover! Temos ' . $organization->organizationSubs->count() . ' subdivisão(ões) vinculada(s) a esta filial.');
            return;
        }

        // Verificar se há usuários vinculados (users_customer)
        if ($organization->users->count() ?? 0) {
            session()->flash('organization_error_' . $organization_id, 'Não é possível remover! Existem ' . $organization->users->count() . ' usuário(s) vinculado(s) a esta filial.');
            return;
        }

        try {
            $organization->delete();
            $this->getOrganizations();
            session()->flash('success', 'Filial removida com sucesso!');
        } catch (\Exception $e) {
            session()->flash('organization_error_' . $organization_id, 'Erro ao remover filial: ' . $e->getMessage());
        }
    }

    public function cancelarEdicaoFilial()
    {
        $this->editingOrganizationId = null;
        $this->organization_name = '';
        $this->organization_description = '';
    }

    // ========== CENTROS DE CUSTO (SETORES) ==========

    public function getOrganizationsSubs($organization_id, $organization_sub_id = false)
    {
        if ($organization_sub_id ?? false) {
            return CustomerOrganizationSub::where('customer_id', $this->customer->id)->where('id', $organization_sub_id)->first();
        } elseif ($organization_id ?? false) {
            $this->organization_subs = CustomerOrganizationSub::where('organization_id', $organization_id)
                ->orderBy('organization_sub_name')
                ->get();
        } else {
            if ($this->customer ?? false) {
                $this->organization_subs = CustomerOrganizationSub::where('customer_id', $this->customer->id)
                    ->orderBy('organization_sub_name')
                    ->get();
            } elseif (isAdmin()) {
                $this->organization_subs = CustomerOrganizationSub::orderBy('organization_sub_name')->get();
            } else {
                $this->organization_subs = collect([]);
            }
        }

        return $this->organization_subs;
    }

    public function getFilteredOrganizationSubs()
    {
        $subs = $this->organization_subs ?? collect([]);

        if (!empty($this->searchOrganizationSubs)) {
            $term = strtolower(trim($this->searchOrganizationSubs));
            $subs = $subs->filter(function ($sub) use ($term) {
                return str_contains(strtolower($sub->organization_sub_name ?? ''), $term)
                    || str_contains(strtolower($sub->organization_sub_description ?? ''), $term);
            });
        }

        // Ordenação alfabética case-insensitive (natural) para garantir consistência
        return $subs
            ->sortBy(fn ($sub) => mb_strtolower($sub->organization_sub_name ?? ''), SORT_NATURAL)
            ->values();
    }

    public function cadastrarCentroCusto($execute = false)
    {
        // Ao abrir o modal (quando $execute é false), carregar a organização
        if ($this->organization_id_for_subs && !$execute) {
            $this->organization = CustomerOrganization::find($this->organization_id_for_subs);
        }

        $this->organizationSubCadastrar = true;

        if ($execute) {
            $validateData = $this->validate([
                'organization_sub_name' => ['required', 'string'],
                'organization_sub_description' => ['required', 'string'],
                'organization_id_for_subs' => ['required'],
            ], [
                'organization_id_for_subs.required' => 'Por favor, selecione uma filial antes de criar uma subdivisão.',
            ]);

            $organization = CustomerOrganization::find($this->organization_id_for_subs);
            $organization_sub_slug = toSlug($organization->organization_slug . '-' . $this->organization_sub_name, '-');

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
        return $this->alterarOrganizationSub($organization_sub_id, (bool) $execute);
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
}

