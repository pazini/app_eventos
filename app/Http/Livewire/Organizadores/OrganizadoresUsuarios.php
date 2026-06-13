<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\User;
use App\Models\Customer;
use App\Services\ModuleAccessService;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class OrganizadoresUsuarios extends Component
{
    public $customer;
    public $customerRole;
    public $customers;
    public $customer_id;
    // Lista de usuários
    public $customerUsers = [];

    // Busca
    public $search = '';

    // Modal de edição de usuário
    public $showEditModal = false;
    public $selectedUserId = null;
    public $selectedUser = null;

    // Dados do usuário para edição
    public $editName = '';
    public $editEmail = '';
    public $editContactCountry = '';
    public $editContactDdd = '';
    public $editContactNum = '';

    // Alteração de senha
    public $showPasswordSection = false;
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    // Confirmação de remoção
    public $showDeleteConfirmation = false;
    public $userToDelete = null;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedShowEditModal($value)
    {
        // Quando o modal é fechado (valor muda de true para false), recarrega os dados
        if (!$value && $this->customer_id) {
            $this->loadUsers();
        }
    }

    public function mount()
    {
        $this->customers = sessionCustomers();
        $this->customer   = sessionCustomer();
        $this->customerRole = $this->customer->user_role ?? false;
        $this->customer_id  = $this->customer->id ?? false;

        // Verificação de acesso - usando a mesma lógica do GerenciarEventos
        $user = auth()->user();
        $isSuperAdmin = $user && ModuleAccessService::userIsAppAdmin($user);

        if (!$isSuperAdmin) {
            if (!isAdmin() && !isOwner()) {
                session()->flash('error', 'Acesso negado');
                return redirect()->route('dashboard');
            }
        }

        $this->loadUsers();
    }

    public function updated($name, $value)
    {
        // Validação para evitar erro se $name estiver vazio
        if (empty($name)) {
            return;
        }

        if($name == 'customer_id')
        {
            $this->customer_id = $value;

            if($value ?? false)
            {
                sessionCustomer($value);
                $this->customer     = sessionCustomer();
                $this->customerRole = $this->customer->user_role ?? false;
            }
            else
            {
                $this->customer = false;
                sessionClear('customer');
            }

            // Resetar dados relacionados
            $this->customerUsers = [];
            $this->search = '';
            $this->showEditModal = false;
            $this->selectedUserId = null;
            $this->selectedUser = null;
            $this->closeEditModal();

            // Recarregar usuários
            $this->loadUsers();
        }
    }

    /**
     * Listener específico para mudança de customer_id
     * Alternativa mais segura ao método updated() genérico
     */
    public function updatedCustomerId($value)
    {
        $this->customer_id = $value;

        if($value ?? false)
        {
            sessionCustomer($value);
            $this->customer     = sessionCustomer();
            $this->customerRole = $this->customer->user_role ?? false;
        }
        else
        {
            $this->customer = false;
            sessionClear('customer');
        }

        // Resetar dados relacionados
        $this->customerUsers = [];
        $this->search = '';
        $this->showEditModal = false;
        $this->selectedUserId = null;
        $this->selectedUser = null;

        // Recarregar usuários
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $user = auth()->user();
        $isAdmin = $user && ModuleAccessService::userIsAppAdmin($user);

        // Se não houver customer_id selecionado, não mostra usuários
        if (!$this->customer_id) {
            $this->customerUsers = [];
            return;
        }

        $customer = Customer::find($this->customer_id);
        if (!$customer) {
            $this->customerUsers = [];
            return;
        }

        // Busca TODOS os usuários do customer_id selecionado
        $query = $customer->users()
            ->withPivot(['user_active', 'user_role', 'can_events', 'can_campaigns']);

        // Aplica filtro de busca se houver
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $query->orderBy('name', 'asc')->get();

        // Filtra removendo usuários que são admins globais (verificado na tabela users_app)
        // e mantém a ordenação alfabética
        $this->customerUsers = $users->filter(function($user) {
            return !ModuleAccessService::userIsAppAdmin($user);
        })->sortBy('name')->values();
    }

    public function updatedSearch()
    {
        $this->loadUsers();
    }

    public function render()
    {
        // Garante que os usuários estejam carregados se houver customer_id
        // mas apenas se não estiverem já carregados (para evitar recarregamentos desnecessários)
        if ($this->customer_id && (empty($this->customerUsers) || (is_countable($this->customerUsers) && count($this->customerUsers) === 0))) {
            $this->loadUsers();
        }

        return view('livewire.organizadores.organizadores-usuarios')->layout('layouts.app-pep-auth');
    }

    /**
     * Abre o modal de edição para um usuário específico.
     */
    public function openEditModal(string $userId): void
    {
        try {
            // Preserva a lista de usuários antes de abrir o modal
            $preservedUsers = $this->customerUsers;

            $user = User::find($userId);

            if (!$user) {
                session()->flash('error', 'Usuário não encontrado.');
                return;
            }

            // Busca o relacionamento com o customer atual
            $customer = Customer::find($this->customer_id);
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

            // Restaura a lista de usuários após abrir o modal
            $this->customerUsers = $preservedUsers;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao abrir modal de edição: ' . $e->getMessage());
            \Log::error('Erro ao abrir modal de edição de usuário', [
                'user_id' => $userId,
                'customer_id' => $this->customer_id,
                'error' => $e->getMessage(),
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
        $this->editContactCountry = '';
        $this->editContactDdd = '';
        $this->editContactNum = '';
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->showDeleteConfirmation = false;
        $this->userToDelete = null;
        $this->resetErrorBag();

        // Sempre recarrega os usuários após fechar o modal para garantir que os dados estejam atualizados
        if ($this->customer_id) {
            $this->loadUsers();
        }
    }

    /**
     * Atualiza os dados do usuário.
     */
    public function updateUser(): void
    {
        $this->validate([
            'editName' => ['required', 'string', 'max:255'],
            'editEmail' => ['required', 'email', 'max:255'],
            'editContactCountry' => ['nullable', 'max:10'],
            'editContactDdd' => ['nullable', 'max:5'],
            'editContactNum' => ['nullable', 'max:20'],
        ], [
            'editName.required' => 'O nome é obrigatório.',
            'editEmail.required' => 'O e-mail é obrigatório.',
            'editEmail.email' => 'O e-mail deve ser válido.',
        ]);

        $user = User::find($this->selectedUserId);

        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            $this->closeEditModal();
            return;
        }

        // Verifica se o email já existe em outro usuário
        $existingUser = User::where('email', $this->editEmail)
            ->where('id', '!=', $this->selectedUserId)
            ->first();

        if ($existingUser) {
            $this->addError('editEmail', 'Este e-mail já está em uso por outro usuário.');
            return;
        }

        try {
            // Atualiza dados do usuário
            $user->update([
                'name' => $this->editName,
                'email' => $this->editEmail,
                'contact_country' => !empty($this->editContactCountry) ? (string)$this->editContactCountry : null,
                'contact_ddd' => !empty($this->editContactDdd) ? (string)$this->editContactDdd : null,
                'contact_num' => !empty($this->editContactNum) ? (string)$this->editContactNum : null,
            ]);

            session()->flash('success', "Dados do usuário {$user->name} atualizados com sucesso!");

            // Fecha o modal após salvar
            $this->closeEditModal();

            // Recarrega a lista de usuários
            $this->loadUsers();
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
            \Log::error('Erro ao atualizar usuário', [
                'user_id' => $this->selectedUserId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Atualiza a senha do usuário selecionado.
     */
    public function updateUserPassword(): void
    {
        $this->validate([
            'newPassword' => ['required', 'string', 'min:8'],
            'newPasswordConfirmation' => ['required', 'same:newPassword'],
        ], [
            'newPassword.required' => 'A nova senha é obrigatória.',
            'newPassword.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'newPasswordConfirmation.required' => 'A confirmação de senha é obrigatória.',
            'newPasswordConfirmation.same' => 'A confirmação de senha não confere.',
        ]);

        $user = User::find($this->selectedUserId);

        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        session()->flash('success', "Senha do usuário {$user->name} ({$user->email}) atualizada com sucesso!");

        // Fecha o modal após alterar a senha
        $this->closeEditModal();
    }

    /**
     * Confirma a remoção do usuário.
     */
    public function confirmDelete(string $userId): void
    {
        $this->userToDelete = $userId;
        $this->showDeleteConfirmation = true;
    }

    /**
     * Remove o usuário do customer.
     */
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

        // Previne que o usuário remova a si mesmo
        $currentUser = auth()->user();
        if ($currentUser && $currentUser->id === $user->id) {
            session()->flash('error', 'Você não pode remover a si mesmo.');
            $this->closeEditModal();
            return;
        }

        // Remove o relacionamento com o customer (não remove o usuário do sistema)
        $user->customers()->detach($this->customer_id);

        session()->flash('success', "Usuário {$user->name} removido do cliente com sucesso!");

        // Fecha o modal e recarrega a lista
        $this->closeEditModal();
        $this->loadUsers();
    }
}

