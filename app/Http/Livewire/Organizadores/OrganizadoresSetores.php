<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Services\ModuleAccessService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class OrganizadoresSetores extends Component
{
    public $customer;
    public $customerRole;
    public $customers;
    public $customer_id;
    public $context = 'campanhas'; // 'eventos' ou 'campanhas'
    //
    public $organizationSubCadastrar;
    public $organization_id;
    public $organization;
    public $organizations;

    public $organization_sub_id;
    public $organization_sub;
    public $organization_subs;

    public $organization_sub_name;
    public $organization_sub_description;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->customers = sessionCustomers();
        $this->customer   = sessionCustomer();
        $this->customerRole = $this->customer->user_role ?? false;
        $this->customer_id  = $this->customer->id ?? false;

        // Detectar contexto pela rota atual
        $routeName = request()->route()->getName() ?? '';
        $this->context = str_contains($routeName, 'eventos') ? 'eventos' : 'campanhas';

        // Verificação de acesso - usando a mesma lógica do GerenciarEventos
        $user = auth()->user();
        $isSuperAdmin = $user && ModuleAccessService::userIsAppAdmin($user);

        if (!$isSuperAdmin) {
            if (!isAdmin() && !isOwner()) {
                session()->flash('error', 'Acesso negado');
                return redirect()->route('dashboard');
            }
        }

        //
        $this->getOrganizations();
    }

    public function getOrganizations($organization_id=false)
    {
        if($organization_id ?? false)
        {
            return CustomerOrganization::find($organization_id);
        }
        else
        {
            $this->organizations = CustomerOrganization::where('customer_id',$this->customer->id)->orderBy('organization_name')->get();
            return $this->organizations;
        }
    }

    public function getOrganizationsSubs($organization_id,$organization_sub_id=false)
    {
        if($organization_sub_id ?? false)
        {
            return CustomerOrganizationSub::where('customer_id',$this->customer->id)->where('id',$organization_sub_id)->first();
        }
        elseif($organization_id ?? false)
        {
            $this->organization_subs = CustomerOrganizationSub::where('organization_id',$organization_id)->orderBy('organization_sub_name')->get();

            return $this->organization_subs;
        }
    }

    public function render()
    {
        return view('livewire.organizadores.organizadores-setores')->layout('layouts.app-pep-auth');
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
            $this->organization = null;
            $this->organization_sub_id = null;
            $this->organization_sub = null;
            $this->organization_subs = null;
            $this->organizationSubCadastrar = false;
            $this->organization_sub_name = '';
            $this->organization_sub_description = '';

            // Recarregar organizações
            $this->getOrganizations();
        }

        //
        if($name == 'organization_id')
        {
            $this->organizationSubCadastrar = false;
            $this->organization_id          = $value;
            $this->organization             = $this->getOrganizations($value);
            $this->organization_subs        = $this->getOrganizationsSubs($value);
        }
    }

    public function cadastrar($execute=false)
    {
        $this->organization_sub_id      = false;
        $this->organizationSubCadastrar = true;

        //
        if($execute ?? false)
        {
            $validateData = $this->validate([
                'organization_sub_name'        => ['required','string'],
                'organization_sub_description' => ['required','string'],
            ]);

            $organization_sub_slug = toSlug($this->organization->organization_slug . '-' . $this->organization_sub_name,'-');
            //
            $validateData['customer_id']           = $this->customer->id;
            $validateData['organization_id']       = $this->organization_id;
            $validateData['organization_sub_name'] = strtoupper($validateData['organization_sub_name']);
            $validateData['organization_sub_slug'] = $organization_sub_slug;

            //
            if($organization_sub = CustomerOrganizationSub::where('customer_id',$this->customer->id)->where('organization_id',$this->organization_id)->where('organization_sub_slug',$organization_sub_slug)->first())
            {
                $organization_sub->update($validateData);
                session()->flash('success','Setor ' . $organization_sub->organization_sub_name . ' já existente foi atualizado');
            }
            else
            {
                $organization_sub = CustomerOrganizationSub::create($validateData);
                session()->flash('success' ,'Setor ' . $organization_sub->organization_sub_name . ' criado');
            }

            $this->updated('organization_id', $this->organization_id);
            $this->organizationSubCadastrar     = false;
            $this->organization_sub_name        = '';
            $this->organization_sub_description = '';

            // Fecha o modal após cadastrar
            $this->organizationSubCadastrar = false;

            return;
        }
        else
        {

            $this->organization                  = $this->getOrganizations($this->organization_id);
            $this->organization_sub_name         = '';
            $this->organization_sub_description  = '';
        }
    }

    public function alterarOrganizationSub($organization_sub_id, $execute=false)
    {
        $this->organization_sub_id = $organization_sub_id;
        $this->organizationSubCadastrar = false;

        //
        if($execute ?? false)
        {
            $validateData = $this->validate([
                'organization_sub_name'        => ['required','string'],
                'organization_sub_description' => ['required','string'],
            ]);

            $slug = toSlug($this->customer->customer_slug . '-' . $this->organization->organization_slug . '-' . $this->organization_sub_name,'-');

            //
            if(CustomerOrganizationSub::where('organization_sub_slug',$slug)->whereNot('id',$organization_sub_id)->count())
            {
                return session()->flash('error','Já existe um setor com esse nome');
            }

            DB::beginTransaction();

            //
            $this->organization_sub->update([
                'organization_sub_slug' => $slug,
                'organization_sub_name' => mb_strtoupper($this->organization_sub_name),
                'organization_sub_description' => trim((string) $this->organization_sub_description),
            ]);

            //
            if($organizers = CustomerOrganizer::where('organization_sub_id',$organization_sub_id)->get())
            {
                foreach ($organizers as $key => $organizer)
                {
                    $organization = CustomerOrganization::find($organizer->organization_id);

                    $organizer_name_full = mb_strtoupper($this->customer->name_corporate . ' | ' . $organization->organization_name . ' | ' . $this->organization_sub_name);
                    $organizer_slug      = toSlug($organizer_name_full,'-');

                    //
                    if(CustomerOrganizer::where('organizer_slug',$organizer_slug)->whereNot('id',$organizer->id)->count())
                    {
                        return session()->flash('error','Já existe um organizador com esse nome de filial');
                    }

                    //
                    $organizers[$key]->organizer_name = mb_strtoupper($this->organization_sub_name);
                    $organizers[$key]->organizer_name_full = $organizer_name_full;
                    $organizers[$key]->organizer_slug = $organizer_slug;
                    $organizers[$key]->save();
                }
            }

            DB::commit();

            $this->getOrganizationsSubs($this->organization_id);
            $this->organization_sub_id = false;

            return session()->flash('organization_success_' . $organization_sub_id ,'Setor atualizado');

        }
        else
        {
            if ($this->organization_sub = CustomerOrganizationSub::find($organization_sub_id))
            {
                $this->organization_sub_name = $this->organization_sub->organization_sub_name;
                $this->organization_sub_description = $this->organization_sub->organization_sub_description;
            }
        }
    }

    public function remover($organization_sub_id)
    {
        $this->organization_sub_id = $organization_sub_id;

        $this->organization_sub = CustomerOrganizationSub::with(['organizers'])->where('customer_id',$this->customer->id)->where('organization_id',$this->organization_id)->where('id',$this->organization_sub_id)->first();

        if($this->organization_sub->organizers->count() ?? 0)
        {
            session()->flash('organization_error_' . $this->organization_sub_id , 'Ops, existem organizadores vincualados a essa instiuição!');
            $this->organization_sub_id = false;
            return;
        }

        $this->organization_sub->delete();
        $this->getOrganizationsSubs($this->organization_id);
        $this->organization_sub_id = false;

        return session()->flash('success' , 'Instiuição ' . $this->organization_sub->organization_name . ' removda');
    }
}
