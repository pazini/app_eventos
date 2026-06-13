<?php

namespace App\Http\Livewire\Navigation;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class NavigationMenuAppVersion extends Component
{
    protected $listeners = [
        'refresh-navigation-menu' => '$refresh',
    ];

    // NÃO sincroniza com URL - AppEvento cuida disso
    public $search = '';
    public $filterStatus = 'ativas';
    public $filterOrganizer = '';
    public $organizers = [];
    public $showStatusDropdown = false;
    public $showOrganizerDropdown = false;

    // App-Version específico
    public $appCustomerId = null;
    public $appCustomerName = null;

    public function mount()
    {
        // Carrega dados da sessão (empresa selecionada)
        $this->appCustomerId = session('app_customer_id');
        $this->appCustomerName = session('app_customer_name');

        // Se não há empresa selecionada, não carrega organizadores
        if (!$this->appCustomerId) {
            return;
        }

        // Carrega lista de organizadores para filtro (apenas da empresa selecionada)
        $organizerIds = DB::table('tev_events')
            ->where('active', true)
            ->where('customer_id', $this->appCustomerId)
            ->whereNotNull('organizer_id')
            ->distinct()
            ->pluck('organizer_id')
            ->toArray();

        $this->organizers = \App\Models\CustomerOrganizer::whereIn('id', $organizerIds)
            ->with(['customer:id,name_corporate', 'organization:id,organization_name'])
            ->orderBy('organizer_slug')
            ->get(['id', 'customer_id', 'organization_id', 'organizer_slug', 'organizer_name', 'organizer_name_full']);
    }

    public function updated($propertyName)
    {
        // Quando algum filtro é atualizado, emite evento para o componente pai (AppEvento)
        if (in_array($propertyName, ['search', 'filterStatus', 'filterOrganizer'])) {
            // Envia no formato que AppEvento espera
            $this->emit('filterChanged', [
                'status' => $this->filterStatus,
                'organizer' => $this->filterOrganizer,
                'customer' => '', // App-version não altera customer (é fixo)
            ]);

            // Envia search separadamente
            if ($propertyName === 'search') {
                $this->emit('searchChanged', $this->search);
            }
        }
    }

    public function render()
    {
        return view('livewire.navigation.navigation-menu-app-version');
    }
}
