<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\ModEvent\Event;
use App\Services\ModuleAccessService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Dashboard extends Component
{
    public $exibir = 'evento_exibir_todos';
    //
    public $customers;
    public $customer;
    public $customer_id;
    public $customerRole;

    //
    public $owner;

    //
    public $organizations;
    public $organization_id;

    //
    public $organization_subs;
    public $organization_sub_id;

    //
    public $organizer;
    public $organizers;
    public $organizer_id;
    //
    public $targetList;
    public $target;
    public $target_ref;
    public $target_id;
    //
    public $canEvents = false;
    public $canCampaigns = false;

    public function mount()
    {
        // sessionClear('all');

        // GET ORGANIZERS
        $this->customers     = sessionCustomers(true);
        $this->organizers    = sessionOrganizers();

        //
        $this->customer     = sessionCustomer();
        $this->customer_id  = $this->customer->id ?? false;
        $this->customerRole = $this->customer->user_role ?? false;

        //
        if (($this->customerRole ?? false) && $this->customerRole == 'owner') {
            $this->owner = true;
        }

        // GET ORGANIZER
        $this->organizer            = sessionOrganizer();
        $this->customer_id          = $this->organizer->customer_id ?? $this->customer_id;
        $this->organization_id      = $this->organizer->organization_id ?? false;
        $this->organization_sub_id  = $this->organizer->organization_sub_id ?? false;
        $this->organizer_id         = $this->organizer->id ?? false;

        // Se há apenas uma empresa e um organizador, auto-seleciona
        if (!$this->organizer_id && $this->customers && $this->customers->count() == 1) {
            $singleCustomer = $this->customers->first();
            $customerOrganizers = $singleCustomer->organizers ?? collect();

            if ($customerOrganizers->count() == 1) {
                $autoOrganizer = $customerOrganizers->first();
                $this->organizer_id = $autoOrganizer->id;
                sessionOrganizer($this->organizer_id);

                $this->organizer = $autoOrganizer;
                $this->customer_id = $autoOrganizer->customer_id;
                $this->organization_id = $autoOrganizer->organization_id;
                $this->organization_sub_id = $autoOrganizer->organization_sub_id;
            }
        }

        // Controle de acesso a módulos:
        // o usuário precisa ter acesso a pelo menos um módulo (eventos ou campanhas)
        // para conseguir acessar o /painel. A escolha visual do módulo é feita
        // na própria página com botões de navegação.
        if (auth()->check() && $this->customer_id) {
            $user = auth()->user();
            $customer = $this->customers->find($this->customer_id);

            if ($customer) {
                $this->canEvents = ModuleAccessService::userCanAccessEvents($user, $customer);
                $this->canCampaigns = ModuleAccessService::userCanAccessCampaigns($user, $customer);

                if (! $this->canEvents && ! $this->canCampaigns) {
                    abort(403, 'Você não tem permissão para acessar este painel.');
                }

                // /eventos exige permissão explícita para eventos (exceto super admin)
                $isSuperAdmin = ModuleAccessService::userIsAppAdmin($user);
                if (! $isSuperAdmin && ! $this->canEvents) {
                    abort(403, 'Você não tem permissão para acessar eventos.');
                }
            }
        }
    }

    public function render()
    {
        //
        if((Auth()->user()->app->first()->pivot->user_role ?? false) && in_array(Auth()->user()->app->first()->pivot->user_role,['admin', 'super-admin']))
            $this->owner = true;

        //
        if(($this->customerRole ?? false) && in_array($this->customerRole,['owner']))
            $this->owner = true;

        // SE DONO ou ADMIN
        if($this->owner ?? false)
        {
            // SET EMPRESA
            if($this->customer_id ?? false)
            {
                $customer = $this->customers->find($this->customer_id);
                $this->organizations = $customer->organizations;
                $this->organizers    = $customer->organizers;

                // SET INSTIUIÇÃO
                if($this->organization_id ?? false)
                {
                    $organization = $this->organizations->find($this->organization_id);
                    $this->organization_subs = $organization->organization_subs ?? [];

                    $this->organizers = $this->organizers->where('organization_id',$this->organization_id);
                }
            }
        }

        //
        if ($this->organizer_id ?? false)
        {
            sessionOrganizer($this->organizer_id);
            $this->organizer           = $this->organizers->find($this->organizer_id);
            $this->customer_id         = $this->organizer->customer_id ?? false;
            $this->organization_id     = $this->organizer->organization_id ?? false;
            $this->organization_sub_id = $this->organizer->organization_sub_id ?? false;

        }
        elseif (($this->organizers ?? false) && $this->organizers->count() == 1)
        {
            // Auto-seleciona o único organizador disponível
            $this->organizer_id = $this->organizers->first()->id;

            sessionOrganizer($this->organizer_id);
            $this->organizer           = $this->organizers->find($this->organizer_id);
            $this->customer_id         = $this->organizer->customer_id ?? false;
            $this->organization_id     = $this->organizer->organization_id ?? false;
            $this->organization_sub_id = $this->organizer->organization_sub_id ?? false;
        }
        // Se há uma única empresa selecionada, auto-seleciona o primeiro organizador dela
        elseif (($this->customer_id ?? false) && ($this->organizers ?? false) && $this->organizers->count() > 0 && !($this->organizer_id ?? false))
        {
            // Verifica se há apenas uma empresa ativa e organizadores válidos
            $validOrganizers = $this->organizers->where('customer_id', $this->customer_id);
            if ($validOrganizers->count() == 1) {
                $this->organizer_id = $validOrganizers->first()->id;

                sessionOrganizer($this->organizer_id);
                $this->organizer           = $validOrganizers->first();
                $this->customer_id         = $this->organizer->customer_id ?? false;
                $this->organization_id     = $this->organizer->organization_id ?? false;
                $this->organization_sub_id = $this->organizer->organization_sub_id ?? false;
            }
        }

        if(!$this->target && $this->organizer_id && $this->organizer = sessionOrganizer($this->organizer_id))
        {
            $now = Carbon::now();
            if($this->exibir == 'evento_exibir_realizado') {
                // Realizados: data de término já passou
                $this->targetList = Event::where('organizer_id', $this->organizer_id)
                    ->whereNotNull('event_datetime_finish')
                    ->where('event_datetime_finish', '<', $now)
                    ->orderBy('event_datetime_start')
                    ->get();
            } else if($this->exibir == 'evento_exibir_andamento') {
                // Em andamento: já começou e não terminou (ou sem término definido)
                $this->targetList = Event::where('organizer_id', $this->organizer_id)
                    ->where('event_datetime_start', '<=', $now)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('event_datetime_finish')
                          ->orWhere('event_datetime_finish', '>=', $now);
                    })
                    ->orderBy('event_datetime_start')
                    ->get();
            } else {
                $this->targetList = Event::where('organizer_id', $this->organizer_id)
                    ->orderBy('event_datetime_start')
                    ->get();
            }
        }

        return view('livewire.dashboard.dashboard')->layout('layouts.app-pep-auth');
    }

    public function selecionaTarget($target_ref,$target_id)
    {
        $this->target_ref = $target_ref;
        $this->target_id  = $target_id;

        //
        switch ($this->target_ref) {
            case 'evento':
                sessionTargetRef($this->target_ref);
                sessionTargetId($this->target_id);
                return redirect()->route('dashboard-evento');

            default:
                session()->flash('info','Tipo de busca inexistente');
                $this->alterarTarget();
                return;
        }
    }

    public function alterarTarget()
    {
        $this->target       = false;
        $this->target_id    = false;
        $this->targetList   = false;
        $this->organizer    = false;
        $this->organizer_id = false;
        //
        sessionClear('organizer');
    }

    public function updated($name, $value)
    {
        //
        if($name == 'customer_id')
        {
            $this->organizations       = false;
            $this->organization_id     = false;
            $this->organization_subs   = false;
            $this->organization_sub_id = false;
            $this->organizers          = false;
            $this->organizer           = false;
            $this->organizer_id        = false;
            //
            sessionClear('organizer');
        }

        //
        if($name == 'organization_id')
        {
            $this->organization_subs   = false;
            $this->organization_sub_id = false;
            $this->organizers          = false;
            $this->organizer           = false;
            $this->organizer_id        = false;
            //
            sessionClear('organizer');
        }

        //
        if($name == 'organization_sub_id')
        {
            $this->organizers   = false;
            $this->organizer    = false;
            $this->organizer_id = false;
            //
            sessionClear('organizer');
        }

        //
        if($name == 'organizer_id')
        {
            $this->alterarTarget();
            $this->organizers          = sessionOrganizers();
            $this->organizer           = sessionOrganizer($value);

            //
            $this->organization_id     = $this->organizer->organization_id ?? false;
            $this->organization_sub_id = $this->organizer->organization_sub_id ?? false;
            $this->organizer_id        = $this->organizer->id ?? false;

            // dd(
            //     $value,
            //     $this->organizer ? $this->organizer->toArray() : 'sem organizer',
            //     $this->organizers->find($value),
            //     $this->organizers->toArray(),
            // );

            // return redirect()->route('dashboard');
        }
    }
}
