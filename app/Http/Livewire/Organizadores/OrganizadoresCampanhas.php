<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\CustomerOrganization;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Models\UserCampaignOrganizer;
use App\Models\CustomerUser;
use App\Services\ModuleAccessService;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class OrganizadoresCampanhas extends Component
{
    public $app;
    public $customer;
    public $customerRole;
    public $organizers;
    public $organizer;

    public $novoOrganizer;
    public $novoOrganizerOrganizationId;
    public $removerOrganizer;
    public $organizations;
    public $organization_id;

    public $owner_name;
    public $owner_email;
    public $owner_phone_telefone;
    public $owner_phone_country=55;
    public $owner_phone_ddd;
    public $owner_phone_num;

    public $novoUsuario;
    public $novoUsuarioId;
    public $novoUsuarioListUsers;
    //
    public $name;
    public $email;
    public $telefone;

    // Campo de busca
    public $search = '';

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public $customers;
    public $customer_id;
    public function mount()
    {
        // APP
        $this->app = sessionApp();
        $this->customers = sessionCustomers();

        // SE NAO ADMIN
        $this->customer     = sessionCustomer();
        $this->customerRole = $this->customer->user_role ?? false;
        $this->customer_id  = $this->customer->id ?? false;

        if(!isAdmin() && !isOwner())
        {
            session()->flash('error','Acesso negado');
            return redirect()->route('dashboard');
        }

        // Verificar se o usuário tem acesso ao módulo de campanhas
        // Super-admin tem acesso mesmo sem customer específico
        if (auth()->check() && $this->customer && !isSuperAdmin()) {
            $user = auth()->user();
            if (!ModuleAccessService::userCanAccessCampaigns($user, $this->customer)) {
                session()->flash('error', 'Você não tem permissão para acessar organizadores de campanhas.');
                return redirect()->route('dashboard');
            }
        }

        // Zerar a variável de filial da sessão para campanhas
        sessionClear('organization');
        $this->organization_id = null;
    }

    public function render()
    {
        //
        $this->getOrganizations();
        $this->getOrganizers();

        // Garante que organizers seja uma collection
        if (!$this->organizers) {
            $this->organizers = collect([]);
        }

        // Para campanhas, não filtramos por organization_id

        // Aplica filtro de busca se houver texto
        if($this->organizers && $this->organizers->count() > 0 && !empty($this->search))
        {
            $searchTerm = strtolower(trim($this->search));
            $this->organizers = $this->organizers->filter(function($organizer) use ($searchTerm) {
                return
                    str_contains(strtolower($organizer->organizer_name ?? ''), $searchTerm) ||
                    str_contains(strtolower($organizer->organizer_name_full ?? ''), $searchTerm) ||
                    str_contains(strtolower($organizer->owner_name ?? ''), $searchTerm) ||
                    str_contains(strtolower($organizer->owner_email ?? ''), $searchTerm);
            });
        }

        return view('livewire.organizadores.organizadores-campanhas')->layout('layouts.app-pep-auth');
    }

    public function getOrganizers($organizerId=false)
    {
        if($organizerId ?? false)
        {
            return CampaignOrganizer::with(['users','campaigns'])->find($organizerId);
        }
        else
        {
            if($this->customer ?? false)
            {
                $this->organizers = CampaignOrganizer::with(['users','campaigns'])
                    ->where('customer_id',$this->customer->id)
                    ->orderBy('organizer_name', 'asc')
                    ->get();
            }
            elseif(isAdmin())
            {
                $this->organizers = CampaignOrganizer::with(['users','campaigns'])
                    ->orderBy('organizer_name', 'asc')
                    ->get();
            }
            else
            {
                $this->organizers = collect([]);
            }

            return $this->organizers ?? collect([]);
        }
    }

    public function getOrganizations()
    {
        if($this->customer ?? false)
        {
            $this->organizations = CustomerOrganization::where('customer_id',$this->customer->id)->get();
        }
        elseif(isAdmin())
        {
            $this->organizations = CustomerOrganization::all();
        }

        return $this->organizations;
    }

    public function updated($name, $value)
    {
        //
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
            $this->organization_id = null;
            $this->organizations = null;
            $this->organizers = null;
            $this->novoOrganizer = false;
            $this->removerOrganizer = false;
            $this->organizer = null;
            $this->search = '';
            $this->resetNovoOrganizer();

            // Recarregar dados
            $this->getOrganizations();
            $this->getOrganizers();
        }
    }

    public function setNovoOrganizer()
    {
        $this->resetNovoOrganizer();
        $this->novoOrganizer = true;
        $this->organizations = CustomerOrganization::where('customer_id',$this->customer->id)->get();
        // Para campanhas, não pré-seleciona filial
        $this->novoOrganizerOrganizationId = null;
    }

    public function resetNovoOrganizer()
    {
        $this->organizer           = false;
        $this->novoOrganizer       = false;
        $this->owner_name          = '';
        $this->owner_email         = '';
        $this->owner_phone_ddd     = '';
        $this->owner_phone_num     = '';
    }

    public function setAlteraOrganizer($organizerId)
    {
        $this->resetNovoOrganizer();
        $this->novoOrganizer = true;

        $this->organizer = $this->getOrganizers($organizerId);
        //
        $this->organization_id     = $this->organizer->organization_id;
        $this->owner_name          = $this->organizer->owner_name;
        $this->owner_email         = $this->organizer->owner_email;
        $this->owner_phone_ddd     = $this->organizer->owner_phone_ddd;
        $this->owner_phone_num     = $this->organizer->owner_phone_num;
    }

    public function cadastrarOrganizer()
    {
        if($this->organizer ?? false)
        {
            $validateData = $this->validate([
                'owner_name'      => ['required','string'],
                'owner_email'     => ['nullable','email'],
                'owner_phone_ddd' => ['nullable','integer'],
                'owner_phone_num' => ['nullable','integer'],
            ]);

            $this->organizer->update($validateData);

            session()->flash('success' ,'Organizador ' . $this->organizer->organizer_name_full . ' atualizado');
            session()->flash('organizer_success_' . $this->organizer->id ,'Organizador ' . $this->organizer->organizer_name_full . ' atualizado');
        }
        else
        {
            $validateData = $this->validate([
                'novoOrganizerOrganizationId' => ['nullable','string'],
                'owner_name'      => ['required','string'],
                'owner_email'     => ['nullable','email'],
                'owner_phone_ddd' => ['nullable','integer'],
                'owner_phone_num' => ['nullable','integer'],
            ]);

            $validateData['owner_phone_country'] = 55;
            $validateData['customer_id']         = $this->customer->id;

            // Se tiver organization_id (do novoOrganizerOrganizationId), usa o nome da organização
            if($this->novoOrganizerOrganizationId ?? false)
            {
                $organization = $this->organizations->find($this->novoOrganizerOrganizationId);
                if($organization)
                {
                    $validateData['organization_id'] = $this->novoOrganizerOrganizationId;
                    $validateData['organizer_name'] = $organization->organization_name;
                    $validateData['organizer_name_full'] = mb_strtoupper($this->customer->name_corporate . ' | ' . $organization->organization_name);
                }
                else
                {
                    // Sem filial válida, usa apenas o nome corporativo
                    $validateData['organizer_name'] = $this->customer->name_corporate;
                    $validateData['organizer_name_full'] = mb_strtoupper($this->customer->name_corporate);
                }
            }
            else
            {
                // Sem filial, usa apenas o nome corporativo
                $validateData['organizer_name'] = $this->customer->name_corporate;
                $validateData['organizer_name_full'] = mb_strtoupper($this->customer->name_corporate);
            }

            $validateData['organizer_slug'] = Str::slug($validateData['organizer_name_full']);

            // Remove novoOrganizerOrganizationId do array de validação antes de salvar
            unset($validateData['novoOrganizerOrganizationId']);

            if($organizer = CampaignOrganizer::where('organizer_slug',$validateData['organizer_slug'])->first())
            {
                $organizer->update($validateData);
                session()->flash('success','Organizador ' . $organizer->organizer_name_full . ' atualizado');
            }
            else
            {
                $organizer = CampaignOrganizer::create($validateData);
                session()->flash('success','Organizador ' . $organizer->organizer_name_full . ' criado');
            }
        }

        $this->getOrganizers();
        $this->resetNovoOrganizer();

        return;
    }

    public function removerOrganizer($organizerId,$confirm=false)
    {
        $this->removerOrganizer = true;
        $this->organizer        = $this->getOrganizers($organizerId);

        if($confirm ?? false)
        {
            if($this->organizer->campaigns->count() ?? 0)
            {
                return session()->flash('error','Organizador possui campanhas. Não pode ser removido');
            }
            elseif($this->organizer->users->count() ?? 0)
            {
                return session()->flash('error','Organizador possui usuários vinculados.');
            }
            else
            {
                $organizer = CampaignOrganizer::find($organizerId);
                $organizer->delete();

                $this->getOrganizers();
                $this->removerOrganizer = false;
                return session()->flash('success','Organizador removido');
            }
        }
    }

    public function setNovoUsuario($organizerId)
    {
        $this->novoUsuario = $organizerId;
        $emailNotSearch    = ['proeventpay@gmail.com','admin@empresateste.com'];

        // BUSCAR USUARIOS DISPONIVEIS EM CUSTOMER
        if($organizer = $this->organizers->find($organizerId))
        {
            $organizerUsersIds = array_column($organizer->users->toArray(),'id');
            $this->novoUsuarioListUsers = $this->customer->users->whereNotIn('email',$emailNotSearch)->whereNotIn('id',$organizerUsersIds);
        }
        else
        {
            $this->novoUsuarioListUsers = $this->customer->users->whereNotIn('email',$emailNotSearch);
        }
    }

    public function cancelNovoUsuario()
    {
        $this->novoUsuario          = '';
        $this->novoUsuarioId        = '';
        $this->novoUsuarioListUsers = [];
        $this->name                 = '';
        $this->email                = '';
        $this->telefone             = '';
    }

    public function desassociarUsuario($organizerId,$userId)
    {
        $organizerUser = UserCampaignOrganizer::where('organizer_id',$organizerId)->where('user_id',$userId)->first();

        if($organizerUser)
        {
            $organizerUser->delete();

            $this->getOrganizers();
            $this->cancelNovoUsuario();

            return session()->flash('associarUsuario_success_' . $organizerId,'Usuário removido');
        }
        else
        {
            return session()->flash('associarUsuario_status_' . $organizerId,'Usuário não pertence a esse organizador');
        }
    }

    public $telefone_add;
    public function associarUsuario($organizerId)
    {
        if(!$this->novoUsuarioId ?? false)
        {
            $validateData = $this->validate([
                'name'          => ['required','string'],
                'email'         => ['required','email'],
                'telefone'      => ['required','string'],
            ]);

            // SE USER
            if (!$user = User::where('email',trim($validateData['email']))->first())
            {
                $validateData['contact_ddd'] = substr($validateData['telefone'],0,2);
                $validateData['contact_num'] = substr($validateData['telefone'],strlen($validateData['telefone']) > 10 ? -9 : -8);
                $validateData['password']    = substr($validateData['telefone'],-4);

                // CRIA USUÁRIO
                $user = User::create([
                    'name'        => strtolower($validateData['name']),
                    'email'       => strtolower($validateData['email']),
                    'contact_ddd' => $validateData['contact_ddd'],
                    'contact_num' => $validateData['contact_num'],
                    'password'    => Hash::make($validateData['password']),
                ]);

                session()->flash('associarUsuario_status_' . $organizerId,'Usuário criado - Senha inicial de acesso: ' . $validateData['password'] .' (4 ultimos digitos do telefone)');
            }

            // Define o ID do usuário (seja novo ou existente)
            $this->novoUsuarioId = $user->id;

            // SE CUSTOMER USER
            if(!$customerUser = $this->customer->users->where('user_id',$user->id)->first())
            {
                $organizer = $this->getOrganizers($organizerId);

                $customerUser = CustomerUser::create([
                    'customer_id'     => $this->customer->id,
                    'user_id'         => $user->id,
                    'user_active'     => true,
                    'user_role'       => 'user',
                    'organization_id' => $organizer->organization_id,
                    'can_events'      => false,  // Organizador de campanhas não tem acesso a eventos
                    'can_campaigns'   => true,   // Organizador de campanhas tem acesso a campanhas
                ]);
            }
        }

        if ($organizerUser = UserCampaignOrganizer::where('organizer_id',$organizerId)->where('user_id',$this->novoUsuarioId)->first())
        {
            $this->getOrganizers();
            return session()->flash('associarUsuario_success_' . $organizerId,'Esse usuário já esta associado a esse organizador');
        }
        else
        {
            $organizerUser = UserCampaignOrganizer::create([
                'user_id'      => $this->novoUsuarioId,
                'organizer_id' => $organizerId,
                'user_active'  => true,
                'user_role'    => 'user',
            ]);
        }

        if(isset($organizerUser) && $organizerUser)
        {
            $this->getOrganizers();
            $this->cancelNovoUsuario();

            return session()->flash('associarUsuario_success_' . $organizerId,'Usuário associado');
        }
    }
}


