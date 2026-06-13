<?php

namespace App\Http\Livewire\Organizadores;

use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Services\ModuleAccessService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class OrganizadoresInstituicoes extends Component
{
    public $customer;
    public $customerRole;
    public $customers;
    public $customer_id;
    //
    public $organizationCadastrar;
    public $organization_id;
    public $organization;
    public $organizations;

    public $organization_name;
    public $organization_description;

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

    public function render()
    {
        return view('livewire.organizadores.organizadores-instituicoes')->layout('layouts.app-pep-auth');
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
            $this->organizationCadastrar = false;
            $this->organization_name = '';
            $this->organization_description = '';

            // Recarregar organizações
            $this->getOrganizations();
        }
    }

    public function cadastrar($execute=false)
    {
        $this->organizationCadastrar = true;

        //
        if($execute ?? false)
        {
            $validateData = $this->validate([
                'organization_name'        => ['required','string'],
                'organization_description' => ['required','string'],
            ]);

            $validateData['customer_id']       = $this->customer->id;
            $validateData['organization_slug'] = Str::slug($validateData['organization_name']);

            //
            if($organization = CustomerOrganization::where('customer_id',$this->customer->id)->where('organization_slug',$validateData['organization_slug'])->first())
            {
                $organization->update($validateData);
                session()->flash('success','Filial ' . $organization->organization_name . ' já existente foi atualizada');
            }
            else
            {
                $organization = CustomerOrganization::create($validateData);
                session()->flash('success' ,'Filial ' . $organization->organization_name . ' criada');
            }

            $this->getOrganizations();
            $this->organizationCadastrar = false;
            $this->organization_name = '';
            $this->organization_description = '';

            return;
        }
        else
        {
            $this->organization_id          = false;
            $this->organization_name        = '';
            $this->organization_description = '';
        }
    }

    public function alterarInstituicao($organization_id, $execute=false)
    {
        $this->organization_id = $organization_id;

        //
        if($execute ?? false)
        {
            $validateData = $this->validate([
                'organization_name'        => ['required','string'],
                'organization_description' => ['required','string'],
            ]);

            //
            if(CustomerOrganization::where('organization_name',mb_strtoupper($this->organization_name))->whereNot('id',$organization_id)->count())
            {
                return session()->flash('error','Já existe uma filial com esse nome');
            }

            DB::beginTransaction();

            //
            $this->organization->update([
                'organization_name' => mb_strtoupper($this->organization_name),
                'organization_description' => trim((string) $this->organization_description),
            ]);

            //
            // Atualiza organizadores de eventos
            if($organizers = CustomerOrganizer::where('organization_id',$this->organization_id)->get())
            {
                foreach ($organizers as $key => $organizer)
                {
                    $organizer_name_full = mb_strtoupper($this->customer->name_corporate . ' | ' . $this->organization_name);

                    //
                    if($organization_sub = CustomerOrganizationSub::find($organizer->organization_sub_id))
                    {
                        $organizer_name_full .= mb_strtoupper(' | ' . $organization_sub->organization_sub_name);
                    }

                    //
                    $organizer_slug = toSlug($organizer_name_full,'-');

                    //
                    if(CustomerOrganizer::where('organizer_slug',$organizer_slug)->whereNot('id',$organizer->id)->count())
                    {
                        return session()->flash('error','Já existe um organizador com esse nome de filial');
                    }

                    //
                    $organizers[$key]->organizer_name_full = $organizer_name_full;
                    $organizers[$key]->organizer_slug = $organizer_slug;
                    $organizers[$key]->save();
                }
            }

            //
            // Atualiza organizadores de campanhas
            if($campaignOrganizers = CampaignOrganizer::where('organization_id',$this->organization_id)->get())
            {
                foreach ($campaignOrganizers as $key => $organizer)
                {
                    $organizer_name_full = mb_strtoupper($this->customer->name_corporate . ' | ' . $this->organization_name);
                    $organizer_slug = toSlug($organizer_name_full,'-');

                    //
                    if(CampaignOrganizer::where('organizer_slug',$organizer_slug)->whereNot('id',$organizer->id)->count())
                    {
                        return session()->flash('error','Já existe um organizador de campanhas com esse nome de filial');
                    }

                    //
                    $campaignOrganizers[$key]->organizer_name_full = $organizer_name_full;
                    $campaignOrganizers[$key]->organizer_slug = $organizer_slug;
                    $campaignOrganizers[$key]->organizer_name = $this->organization_name;
                    $campaignOrganizers[$key]->save();
                }
            }

            DB::commit();

            $this->getOrganizations();

            $this->organization_id = false;

            return session()->flash('organization_success_' . $organization_id ,'Filial atualizada');
        }
        else
        {
            if ($this->organization = CustomerOrganization::find($organization_id))
            {
                $this->organization_name = $this->organization->organization_name;
                $this->organization_description = $this->organization->organization_description;
            }
        }
    }

    public function remover($organization_id)
    {
        $this->organization_id = $organization_id;

        $this->organization = CustomerOrganization::with(['organizers','organizationSubs'])->where('customer_id',$this->customer->id)->where('id',$this->organization_id)->first();

        // Verifica organizadores de eventos
        if($this->organization->organizers->count() ?? 0)
        {
            $this->organization_id = false;
            session()->flash('organization_error_' . $organization_id , 'Ops, existem organizadores de eventos vinculados a essa instituição!');
            return;
        }

        // Verifica organizadores de campanhas
        $campaignOrganizers = CampaignOrganizer::where('organization_id',$this->organization_id)->count();
        if($campaignOrganizers > 0)
        {
            $this->organization_id = false;
            session()->flash('organization_error_' . $organization_id , 'Ops, existem organizadores de campanhas vinculados a essa instituição!');
            return;
        }

        if($this->organization->organizationSubs->count() ?? 0)
        {
            $this->organization_id = false;
            session()->flash('organization_error_' . $this->organization_id , 'Ops, existem setores vincualados a essa instiuição!');
            return;
        }

        $this->organization->delete();
        $this->getOrganizations();
        $this->organization_id = false;

        return session()->flash('success' , 'Instiuição ' . $this->organization->organization_name . ' removda');
    }
}
