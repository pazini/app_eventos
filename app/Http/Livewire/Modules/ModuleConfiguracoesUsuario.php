<?php

namespace App\Http\Livewire\Modules;

use App\Models\Customer;
use App\Models\User;
use App\Models\UserCustomer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ModuleConfiguracoesUsuario extends Component
{
    public $app;
    public $customer;
    public $customerId;
    public $userId;

    public $standaloneCreate = false;
    public $standaloneEdit = false;

    public $name = '';
    public $email = '';
    public $role = 'user';
    public $canEvents = false;
    public $canCampaigns = false;
    public $canSubscriptions = false;

    public $password = '';
    public $passwordConfirmation = '';

    public $showPasswordSection = false;
    public $newPassword = '';
    public $newPasswordConfirmation = '';

    public $confirmingDelete = false;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount($customer_id, $user_id = null, $standaloneCreate = false, $standaloneEdit = false)
    {
        $this->app = sessionApp();
        $this->standaloneCreate = (bool) ($standaloneCreate ?: request()->routeIs('configuracoes-novo-usuario'));
        $this->standaloneEdit = (bool) ($standaloneEdit ?: request()->routeIs('configuracoes-editar-usuario'));

        if (!isAdmin()) {
            session()->flash('error', 'Acesso Negado');
            return redirect()->route('dashboard');
        }

        $this->customerId = $customer_id;
        sessionClear('customer');
        sessionCustomer($this->customerId);

        $this->customer = Customer::find($this->customerId);
        if (!$this->customer) {
            session()->flash('error', 'Cliente não encontrado.');
            return redirect()->route('configuracoes');
        }

        if ($user_id) {
            $this->userId = $user_id;
            $this->standaloneEdit = true;

            if (!$this->loadUserToEditForm()) {
                return redirect()->route('configuracoes-customer', [
                    'customer_id' => $this->customerId,
                    'tab' => 'usuarios',
                ]);
            }
        } else {
            $this->resetCreateFields();
        }
    }

    private function resetCreateFields(): void
    {
        $this->name = '';
        $this->email = '';
        $this->role = 'user';
        $this->canEvents = false;
        $this->canCampaigns = false;
        $this->canSubscriptions = false;
        $this->password = '';
        $this->passwordConfirmation = '';
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->confirmingDelete = false;
    }

    private function loadUserToEditForm(): bool
    {
        if (!$this->userId) {
            session()->flash('error', 'Usuário não informado para edição.');
            return false;
        }

        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return false;
        }

        $userCustomer = UserCustomer::where('user_id', $user->id)
            ->where('customer_id', $this->customerId)
            ->first();

        if (!$userCustomer) {
            session()->flash('error', 'Usuário não está vinculado a este cliente.');
            return false;
        }

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->role = $userCustomer->user_role ?? 'user';
        $this->canEvents = (bool) ($userCustomer->can_events ?? 0);
        $this->canCampaigns = (bool) ($userCustomer->can_campaigns ?? 0);
        $this->canSubscriptions = (bool) ($userCustomer->can_subscriptions ?? 0);
        $this->password = '';
        $this->passwordConfirmation = '';
        $this->showPasswordSection = false;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->confirmingDelete = false;

        return true;
    }

    public function createUser(): mixed
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'passwordConfirmation' => ['required', 'same:password'],
            'role' => ['required', 'string', 'in:admin,owner,user'],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'passwordConfirmation.required' => 'A confirmação de senha é obrigatória.',
            'passwordConfirmation.same' => 'A confirmação de senha não confere.',
            'role.required' => 'O papel do usuário é obrigatório.',
        ]);

        try {
            $emailLower = strtolower(trim($this->email));
            $existingUser = User::where('email', $emailLower)->first();

            if ($existingUser) {
                $alreadyAssociated = UserCustomer::where('user_id', $existingUser->id)
                    ->where('customer_id', $this->customerId)
                    ->exists();

                if ($alreadyAssociated) {
                    $this->addError('email', 'Este e-mail já está associado a este cliente.');
                    return null;
                }

                UserCustomer::create([
                    'user_id' => $existingUser->id,
                    'customer_id' => $this->customerId,
                    'user_active' => true,
                    'user_role' => $this->role,
                    'can_events' => $this->canEvents ? 1 : 0,
                    'can_campaigns' => $this->canCampaigns ? 1 : 0,
                    'can_subscriptions' => $this->canSubscriptions ? 1 : 0,
                ]);

                session()->flash('success', "Usuário {$existingUser->name} associado ao cliente com sucesso!");
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $emailLower,
                    'password' => Hash::make($this->password),
                ]);

                UserCustomer::create([
                    'user_id' => $user->id,
                    'customer_id' => $this->customerId,
                    'user_active' => true,
                    'user_role' => $this->role,
                    'can_events' => $this->canEvents ? 1 : 0,
                    'can_campaigns' => $this->canCampaigns ? 1 : 0,
                    'can_subscriptions' => $this->canSubscriptions ? 1 : 0,
                ]);

                session()->flash('success', "Usuário {$user->name} criado e associado ao cliente com sucesso!");
            }

            return redirect()->route('configuracoes-customer', [
                'customer_id' => $this->customerId,
                'tab' => 'usuarios',
            ]);
        } catch (\Throwable $e) {
            $this->addError('email', 'Erro ao criar usuário: ' . $e->getMessage());
            return null;
        }
    }

    public function updateUser(): mixed
    {
        if (!$this->userId) {
            session()->flash('error', 'Usuário não informado para edição.');
            return null;
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:admin,owner,user,super-admin'],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'role.required' => 'O papel do usuário é obrigatório.',
            'role.in' => 'O papel selecionado é inválido.',
        ]);

        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return null;
        }

        $userCustomer = UserCustomer::where('user_id', $user->id)
            ->where('customer_id', $this->customerId)
            ->first();

        if (!$userCustomer) {
            session()->flash('error', 'O vínculo do usuário com este cliente não foi encontrado.');
            return null;
        }

        $emailLower = strtolower(trim($this->email));
        $existingUser = User::where('email', $emailLower)
            ->where('id', '!=', $this->userId)
            ->first();

        if ($existingUser) {
            $this->addError('email', 'Este e-mail já está em uso por outro usuário.');
            return null;
        }

        try {
            DB::transaction(function () use ($user, $userCustomer, $emailLower) {
                $user->update([
                    'name' => $this->name,
                    'email' => $emailLower,
                ]);

                $userCustomer->update([
                    'user_role' => $this->role,
                    'can_events' => $this->canEvents ? 1 : 0,
                    'can_campaigns' => $this->canCampaigns ? 1 : 0,
                    'can_subscriptions' => $this->canSubscriptions ? 1 : 0,
                ]);
            });

            session()->flash('success', "Dados do usuário {$this->name} atualizados com sucesso!");

            return redirect()->route('configuracoes-customer', [
                'customer_id' => $this->customerId,
                'tab' => 'usuarios',
            ]);
        } catch (\Throwable $e) {
            $this->addError('email', 'Erro ao atualizar usuário: ' . $e->getMessage());
            return null;
        }
    }

    public function togglePasswordSection(): void
    {
        $this->showPasswordSection = !$this->showPasswordSection;
        if (!$this->showPasswordSection) {
            $this->newPassword = '';
            $this->newPasswordConfirmation = '';
        }
    }

    public function updateUserPassword(): void
    {
        if (!$this->userId) {
            session()->flash('error', 'Usuário não informado para alteração de senha.');
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

        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return;
        }

        $userCustomer = UserCustomer::where('user_id', $user->id)
            ->where('customer_id', $this->customerId)
            ->first();

        if (!$userCustomer) {
            session()->flash('error', 'O vínculo do usuário com este cliente não foi encontrado.');
            return;
        }

        $user->forceFill([
            'password' => Hash::make($this->newPassword),
        ])->save();

        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->showPasswordSection = false;
        session()->flash('success', "Senha do usuário {$user->name} atualizada com sucesso!");
    }

    public function startDeleteConfirmation(): void
    {
        $this->confirmingDelete = true;
    }

    public function cancelDeleteConfirmation(): void
    {
        $this->confirmingDelete = false;
    }

    public function removeUser(): mixed
    {
        if (!$this->userId) {
            session()->flash('error', 'Usuário não informado para remoção.');
            return null;
        }

        if (!$this->confirmingDelete) {
            $this->confirmingDelete = true;
            return null;
        }

        $user = User::find($this->userId);
        if (!$user) {
            session()->flash('error', 'Usuário não encontrado.');
            return null;
        }

        UserCustomer::where('user_id', $user->id)
            ->where('customer_id', $this->customerId)
            ->delete();

        session()->flash('success', "Usuário {$user->name} removido do cliente com sucesso!");

        return redirect()->route('configuracoes-customer', [
            'customer_id' => $this->customerId,
            'tab' => 'usuarios',
        ]);
    }

    public function render()
    {
        return view('livewire.modules.module-configuracoes-usuario')->layout('layouts.app-pep-auth');
    }
}
