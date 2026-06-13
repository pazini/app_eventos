<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\CustomerOrganizerUser;
use App\Models\CustomerUser;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Models\UserCampaignOrganizer;
use App\Services\ModuleAccessService;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Organizadores extends Component
{
    public $app;
    public $customer;
    public $customerRole;
    public $organizers;
    public $organizer;
    public $context = 'eventos'; // 'eventos' ou 'campanhas' - padrão é eventos (estrutura original)

    public $novoOrganizer;
    public $novoOrganizerOrganizationId;
    public $removerOrganizer;
    public $organizations;
    public $organization_id;
    public $organizationsSub;
    public $organization_sub_id;

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

        // Detectar contexto pela rota atual - prioriza eventos (estrutura original)
        $routeName = request()->route()->getName() ?? '';
        if (str_contains($routeName, 'campanhas-organizadores') || str_contains($routeName, 'campanhas/organizadores')) {
            $this->context = 'campanhas';
        } else {
            $this->context = 'eventos'; // Padrão: estrutura original de eventos
        }

        // SE NAO ADMIN
        $this->customer     = sessionCustomer();
        $this->customerRole = $this->customer->user_role ?? false;
        $this->customer_id  = $this->customer->id ?? false;

        if(!isAdmin() && !isOwner())
        {
            session()->flash('error','Acesso negado');
            return redirect()->route('dashboard');
        }

        // Verificar se o usuário tem acesso ao módulo apropriado baseado no contexto
        if (auth()->check() && $this->customer) {
            $user = auth()->user();

            if ($this->context === 'campanhas') {
                // Se for contexto de campanhas, verifica acesso a campanhas
                if (!ModuleAccessService::userCanAccessCampaigns($user, $this->customer)) {
                    session()->flash('error', 'Você não tem permissão para acessar organizadores de campanhas.');
                    return redirect()->route('dashboard');
                }
            } else {
                // Se for contexto de eventos (padrão), verifica acesso a eventos
                if (!ModuleAccessService::userCanAccessEvents($user, $this->customer)) {
                    session()->flash('error', 'Você não tem permissão para acessar organizadores de eventos.');
                    return redirect()->route('dashboard');
                }
            }
        }
    }

    public function render()
    {
        // Garante que o contexto está correto - prioriza eventos (estrutura original)
        $routeName = request()->route()->getName() ?? '';
        if (str_contains($routeName, 'campanhas-organizadores') || str_contains($routeName, 'campanhas/organizadores')) {
            $this->context = 'campanhas';
        } else {
            $this->context = 'eventos'; // Padrão: estrutura original de eventos
        }

        //
        $this->getOrganizations();
        $this->getOrganizers();

        // Garante que organizers seja uma collection
        if (!$this->organizers) {
            $this->organizers = collect([]);
        }

        //
        if($this->organizers && $this->organizers->count() > 0 && $this->organization_id ?? false)
        {
            $this->organizers = $this->organizers->where('organization_id', $this->organization_id);
        }

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
            })->sortBy('organizer_name');
        }
        else
        {
            // Garante ordenação alfabética mesmo sem busca
            if($this->organizers && $this->organizers->count() > 0)
            {
                $this->organizers = $this->organizers->sortBy('organizer_name');
            }
        }

        return view('livewire.organizadores.organizadores')->layout('layouts.app-pep-auth');
    }

    public function getOrganizers($organizerId=false)
    {
        // Se for contexto de campanhas, usa CampaignOrganizer
        if ($this->context === 'campanhas') {
            if($organizerId ?? false)
            {
                return CampaignOrganizer::with(['users','campaigns'])->find($organizerId);
            }
            else
            {
                if($this->customer ?? false)
                {
                    $this->organizers = CampaignOrganizer::with(['users','campaigns'])->where('customer_id',$this->customer->id)->get();
                }
                elseif(isAdmin())
                {
                    $this->organizers = CampaignOrganizer::with(['users','campaigns'])->get();
                }
                else
                {
                    $this->organizers = collect([]);
                }

                return $this->organizers ?? collect([]);
            }
        }

        // Contexto de eventos (comportamento original)
        if($organizerId ?? false)
        {
            return CustomerOrganizer::with(['users','events'])->find($organizerId);
        }
        else
        {
            if($this->customer ?? false)
            {
                $this->organizers = CustomerOrganizer::with(['users','events'])
                    ->where('customer_id',$this->customer->id)
                    ->orderBy('organizer_name', 'asc')
                    ->get();
            }
            elseif(isAdmin())
            {
                $this->organizers = CustomerOrganizer::with(['users','events'])
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

        //
        if($name == 'novoOrganizerOrganizationId')
        {
            $this->organization_id = $value;

            // Para campanhas, não precisa carregar setores
            if ($this->context === 'campanhas') {
                $this->organizationsSub = collect([]);
                return;
            }

            // PEGA ALL SETORES (apenas para eventos)
            $setoresTodos    = CustomerOrganizationSub::where('customer_id',$this->customer->id)->where('organization_id',$value)->get();
            $setoresTodosIds = array_column($setoresTodos->toArray(),'id');

            // PEGA TODOS OS ORGANIZADORES
            $organizadoresTodos = $this->getOrganizers();

            // FILTRA PELO ORGANIZADOR DA INTIUIÇÃO ATUAL
            $setoresInstituicaoIds = [];
            //
            if($organizadoresInstituicao = $organizadoresTodos->where('organization_id',$value))
            {
                $setoresInstituicaoIds = array_column($organizadoresInstituicao->toArray(),'organization_sub_id');
            }

            // PERCORRE TODOS OS SETORES
            $setoresLivres = [];
            foreach ($setoresTodosIds as $setorId)
            {
                // VALIDA SE AINDA NÃO FOI CRIADO AQUELE SETOR
                if(!in_array($setorId,$setoresInstituicaoIds))
                {
                    $setoresLivres[$setorId] = $setorId;
                }
            }

            // PEGA OS SETORES REALMENTE DISPONIVEIS
            $this->organizationsSub = $setoresTodos->whereIn('id',$setoresLivres);
        }
    }

    public function setNovoOrganizer()
    {
        $this->resetNovoOrganizer();
        $this->novoOrganizer = true;
        $this->organizations = CustomerOrganization::where('customer_id',$this->customer->id)->get();
        //
        if($this->organization_id ?? false)
        {
            $this->novoOrganizerOrganizationId = $this->organization_id;
            $this->updated('novoOrganizerOrganizationId',$this->organization_id);
        }
    }

    public function resetNovoOrganizer()
    {
        $this->organizer           = false;
        $this->novoOrganizer       = false;
        $this->organizationsSub    = false;
        $this->organization_sub_id = '';
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
        if ($this->context !== 'campanhas') {
            $this->organization_sub_id = $this->organizer->organization_sub_id ?? null;
        }
        $this->owner_name          = $this->organizer->owner_name;
        $this->owner_email         = $this->organizer->owner_email;
        $this->owner_phone_ddd     = $this->organizer->owner_phone_ddd;
        $this->owner_phone_num     = $this->organizer->owner_phone_num;
    }

    public function cadastrarOrganizer()
    {
        // return;

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
            // Para campanhas, não exige organization_sub_id
            if ($this->context === 'campanhas') {
                $validateData = $this->validate([
                    'organization_id'    => ['required','string'],
                    'owner_name'      => ['required','string'],
                    'owner_email'     => ['nullable','email'],
                    'owner_phone_ddd' => ['nullable','integer'],
                    'owner_phone_num' => ['nullable','integer'],
                ]);
            } else {
                $validateData = $this->validate([
                    'organization_id'    => ['required','string'],
                    'organization_sub_id' => ['required','string'],
                    'owner_name'      => ['required','string'],
                    'owner_email'     => ['nullable','email'],
                    'owner_phone_ddd' => ['nullable','integer'],
                    'owner_phone_num' => ['nullable','integer'],
                ]);
            }

            $organization    = $this->organizations->find($this->organization_id);

            //
            // Para campanhas, não usa organization_sub_id (campanhas só têm customer + organization)
            if ($this->context === 'campanhas') {
                // Remove organization_sub_id para campanhas
                unset($validateData['organization_sub_id']);
                $validateData['organizer_name'] = $organization->organization_name;
                $validateData['organizer_name_full'] = $this->customer->name_corporate . ' | ' . $organization->organization_name;
                $validateData['organizer_slug'] = Str::slug($validateData['organizer_name_full']);

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
            } else {
                // Eventos (comportamento original)
                $organizationSub = $this->organizationsSub->find($this->organization_sub_id);
                $validateData['owner_phone_country'] = 55;
                $validateData['customer_id']         = $this->customer->id;
                $validateData['organizer_name']      = $organizationSub->organization_sub_name;
                $validateData['organizer_name_full'] = $this->customer->name_corporate . ' | ' . $organization->organization_name . ' | ' . $organizationSub->organization_sub_name;
                $validateData['organizer_slug']      = Str::slug($validateData['organizer_name_full']);

                if($organizer = CustomerOrganizer::where('organizer_slug',$validateData['organizer_slug'])->first())
                {
                    $organizer->update($validateData);
                    session()->flash('success','Organizador ' . $organizer->organizer_name_full . ' atualizado');
                }
                else
                {
                    $organizer = CustomerOrganizer::create($validateData);
                    session()->flash('success','Organizador ' . $organizer->organizer_name_full . ' criado');
                }
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
            if ($this->context === 'campanhas') {
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
            } else {
                // Eventos (comportamento original)
                if($this->organizer->events->count() ?? 0)
                {
                    return session()->flash('error','Organizador possui eventos. Não pode ser removido');
                }
                elseif($this->organizer->users->count() ?? 0)
                {
                    return session()->flash('error','Organizador possui usuários vinculados.');
                }
                else
                {
                    $organizer = CustomerOrganizer::find($organizerId);
                    $organizer->delete();

                    $this->getOrganizers();
                    $this->removerOrganizer = false;
                    return session()->flash('success','Organizador removido');
                }
            }
        }
    }

    public function setNovoUsuario($organizerId)
    {
        $this->novoUsuario = $organizerId;
        $emailNotSearch    = [auth()->user()->email,'proeventpay@gmail.com','admin@empresateste.com'];
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
        $organizerUser = null;

        if ($this->context === 'campanhas') {
            $organizerUser = UserCampaignOrganizer::where('organizer_id',$organizerId)->where('user_id',$userId)->first();
        } else {
            $organizerUser = CustomerOrganizerUser::where('organizer_id',$organizerId)->where('user_id',$userId)->first();
        }

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
        // return;

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

                // Define permissões baseado no contexto
                $canEvents = ($this->context === 'eventos');
                $canCampaigns = ($this->context === 'campanhas');

                $customerUser = CustomerUser::create([
                    'customer_id'     => $this->customer->id,
                    'user_id'         => $user->id,
                    'user_active'     => true,
                    'user_role'       => 'user',
                    'organization_id' => $organizer->organization_id,
                    'can_events'      => $canEvents,      // Define baseado no contexto
                    'can_campaigns'   => $canCampaigns,   // Define baseado no contexto
                ]);
            }
        }

        if ($this->context === 'campanhas') {
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
        } else {
            if ($organizerUser = CustomerOrganizerUser::where('organizer_id',$organizerId)->where('user_id',$this->novoUsuarioId)->first())
            {
                $this->getOrganizers();
                return session()->flash('associarUsuario_success_' . $organizerId,'Esse usuário já esta associado a esse organizador');
            }
            else
            {
                $organizerUser = CustomerOrganizerUser::create([
                    'user_id'      => $this->novoUsuarioId,
                    'organizer_id' => $organizerId,
                    'user_active'  => true,
                    'user_role'    => 'user',
                ]);
            }
        }

        if(isset($organizerUser) && $organizerUser)
        {

            $this->getOrganizers();
            $this->cancelNovoUsuario();

            return session()->flash('associarUsuario_success_' . $organizerId,'Usuário associado');
        }
    }
}


