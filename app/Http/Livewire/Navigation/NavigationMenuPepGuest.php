<?php

namespace App\Http\Livewire\Navigation;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class NavigationMenuPepGuest extends Component
{
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'filterStatus' => ['except' => 'ativas'],
        'filterCustomer' => ['except' => ''],
        'filterCustomerSlug' => ['except' => ''],
        'filterOrganizer' => ['except' => ''],
    ];

    public $isCampanhasPage = false;
    public $isEventosPage = false;
    public $isParticipantesPage = false;
    public $isCheckinPage = false;
    public $search = '';
    public $filterStatus = 'ativas';
    public $filterCustomer = '';
    public $filterCustomerSlug = '';
    public $filterOrganizer = '';
    public $customers = [];
    public $organizers = [];
    public $showStatusDropdown = false;
    public $showCustomerDropdown = false;
    public $showOrganizerDropdown = false;
    public $activeCustomerId = ''; // ID resolvido do customer ativo (para uso no select)

    public function mount()
    {
        // Detecta se está na página de campanhas
        $this->isCampanhasPage = request()->routeIs('campanhas-home') || request()->routeIs('campanha-home');

        // Detecta se está na página de eventos
        $this->isEventosPage = request()->routeIs('eventos-home') || request()->routeIs('evento-home');

        // Detecta se está na página de participantes
        $this->isParticipantesPage = request()->is('*/participantes') || request()->routeIs('dashboard-vendas', 'dashboard-vendas-participantes-public');

        // Detecta se está na página de checkin
        $this->isCheckinPage = request()->is('checkin/*') || request()->routeIs('checkin-target');

        if ($this->isCampanhasPage) {
            // Carrega lista de empresas para filtro (campanhas)
            $customerIds = DB::table('tbc_campaign')
                ->where('visibility_public', true)
                ->distinct()
                ->pluck('customer_id')
                ->toArray();

            $this->customers = Customer::whereIn('id', $customerIds)
                ->orderBy('name_corporate')
                ->get(['id', 'name_corporate']);

            // Carrega lista de organizadores para filtro (campanhas)
            $organizerIds = DB::table('tbc_campaign')
                ->where('visibility_public', true)
                ->whereNotNull('organizer_id')
                ->distinct()
                ->pluck('organizer_id')
                ->toArray();

            $this->organizers = \App\Models\ModCampaign\CampaignOrganizer::whereIn('id', $organizerIds)
                ->whereHas('customer')
                ->with(['customer:id,name_corporate'])
                ->orderBy('organizer_name_full')
                ->get(['id', 'customer_id', 'organizer_name', 'organizer_name_full']);
        } elseif ($this->isEventosPage) {
            // Carrega lista de empresas para filtro (eventos)
            $customerIds = DB::table('tev_events')
                ->where('active', true)
                ->distinct()
                ->pluck('customer_id')
                ->toArray();

            $this->customers = Customer::whereIn('id', $customerIds)
                ->orderBy('name_corporate')
                ->get(['id', 'name_corporate']);

            // Carrega lista de organizadores para filtro (eventos)
            $organizerIds = DB::table('tev_events')
                ->where('active', true)
                ->whereNotNull('organizer_id')
                ->distinct()
                ->pluck('organizer_id')
                ->toArray();

            $this->organizers = \App\Models\CustomerOrganizer::whereIn('id', $organizerIds)
                ->whereHas('customer')
                ->with(['customer:id,name_corporate', 'organization:id,organization_name'])
                ->orderBy('organizer_slug')
                ->get(['id', 'customer_id', 'organization_id', 'organizer_slug', 'organizer_name', 'organizer_name_full']);
        }

        // Resolve o ID do customer ativo (seja por filterCustomer ou filterCustomerSlug)
        // Isso é usado apenas para exibir o select corretamente, NÃO afeta a URL
        $this->resolveActiveCustomerId();
    }

    /**
     * Resolve qual customer está ativo e seta na propriedade activeCustomerId
     * Usado para exibir o select correto sem alterar a URL
     */
    protected function resolveActiveCustomerId()
    {
        // Se tem filterCustomer direto, usa ele
        if ($this->filterCustomer) {
            $this->activeCustomerId = $this->filterCustomer;
            return;
        }

        // Se tem filterCustomerSlug, resolve dinamicamente
        if ($this->filterCustomerSlug) {
            $customers = Customer::all(['id', 'name_corporate']);
            foreach ($customers as $customer) {
                if (Str::slug($customer->name_corporate) === $this->filterCustomerSlug) {
                    $this->activeCustomerId = $customer->id;
                    return;
                }
            }
        }

        $this->activeCustomerId = '';
    }

    public function updatedSearch()
    {
        $this->emit('searchChanged', $this->search);
    }

    public function updatedFilterStatus()
    {
        $this->emit('filterChanged', [
            'status' => $this->filterStatus,
            'customer' => $this->filterCustomer,
            'organizer' => $this->filterOrganizer
        ]);
        $this->showStatusDropdown = false;
    }

    public function updatedFilterCustomer()
    {
        $this->resolveActiveCustomerId();
        $this->emit('filterChanged', [
            'status' => $this->filterStatus,
            'customer' => $this->filterCustomer,
            'organizer' => $this->filterOrganizer
        ]);
        $this->showCustomerDropdown = false;
    }

    public function updatedFilterCustomerSlug()
    {
        $this->resolveActiveCustomerId();
        $this->emit('filterChanged', [
            'status' => $this->filterStatus,
            'customer' => $this->filterCustomer,
            'organizer' => $this->filterOrganizer
        ]);
        $this->showCustomerDropdown = false;
    }

    public function updatedFilterOrganizer()
    {
        $this->emit('filterChanged', [
            'status' => $this->filterStatus,
            'customer' => $this->filterCustomer,
            'organizer' => $this->filterOrganizer
        ]);
        $this->showOrganizerDropdown = false;
    }

    public function toggleStatusDropdown()
    {
        $this->showStatusDropdown = !$this->showStatusDropdown;
        $this->showCustomerDropdown = false;
        $this->showOrganizerDropdown = false;
    }

    public function toggleCustomerDropdown()
    {
        $this->showCustomerDropdown = !$this->showCustomerDropdown;
        $this->showStatusDropdown = false;
        $this->showOrganizerDropdown = false;
    }

    public function toggleOrganizerDropdown()
    {
        $this->showOrganizerDropdown = !$this->showOrganizerDropdown;
        $this->showStatusDropdown = false;
        $this->showCustomerDropdown = false;
    }

    public function render()
    {
        return view('livewire.navigation.navigation-menu-pep-guest');
    }
}
