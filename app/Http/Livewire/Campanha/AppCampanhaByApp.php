<?php

namespace App\Http\Livewire\Campanha;

use App\Models\ModCampaign\Campaign;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AppCampanhaByApp extends Component
{
    public $slug;
    public $campaignList;

    // Filtros (sincronizados com navigation)
    public $search = '';
    public $filterStatus = 'ativas'; // ativas, todas, finalizadas
    public $filterCustomer = '';
    public $customers = [];

    protected $listeners = ['filterChanged', 'searchChanged'];

    public $appUserUuid;
    public $appSource;

    public function mount($appUserUuid = null)
    {
        // Captura appUserUuid do parâmetro da rota ou da query string
        $this->appUserUuid = $appUserUuid ?? request()->get('appUserUuid');
        $this->appSource = request()->get('appSource') ?: getAppSource();

        // Salva na sessão se capturado
        if ($this->appUserUuid) {
            setAppUserUuid($this->appUserUuid);
            \Log::info('AppCampanhaByApp: appUserUuid capturado e salvo na sessão', ['uuid' => $this->appUserUuid]);
        }

        if ($this->appSource) {
            setAppSource($this->appSource);
        }

        // Carrega lista de empresas para filtro
        $customerIds = \DB::table('tbc_campaign')
            ->where('visibility_public', true)
            ->distinct()
            ->pluck('customer_id')
            ->toArray();

        $this->customers = \App\Models\Customer::whereIn('id', $customerIds)
            ->orderBy('name_corporate')
            ->get(['id', 'name_corporate']);
    }

    public function updatedSearch()
    {
        $this->loadCampaigns();
    }

    public function updatedFilterStatus()
    {
        $this->loadCampaigns();
    }

    public function updatedFilterCustomer()
    {
        $this->loadCampaigns();
    }

    public function filterChanged($filters)
    {
        $this->filterStatus = $filters['status'] ?? 'ativas';
        $this->filterCustomer = $filters['customer'] ?? '';
        $this->loadCampaigns();
    }

    public function searchChanged($search)
    {
        $this->search = $search;
        $this->loadCampaigns();
    }

    protected function loadCampaigns()
    {
        $query = Campaign::with(['customer', 'organization'])
            ->where('visibility_public', true)
            ->whereHas('customer');

        // Filtro de status
        if ($this->filterStatus === 'ativas') {
            $query->where('status', 'active')
                ->where(function($q) {
                    $q->whereNull('datetime_finish')
                        ->orWhere('datetime_finish', '>=', now()->format('Y-m-d H:i:s'));
                });
        } elseif ($this->filterStatus === 'finalizadas') {
            $query->where(function($q) {
                $q->where('status', 'finished')
                    ->orWhere(function($q2) {
                        $q2->where('status', 'active')
                            ->where('datetime_finish', '<', now()->format('Y-m-d H:i:s'));
                    });
            });
        } else {
            // Todas - excluir active_direct das listagens públicas
            $query->whereIn('status', ['active', 'finished']);
        }

        // Filtro por empresa
        if ($this->filterCustomer) {
            $query->where('customer_id', $this->filterCustomer);
        }

        // Busca por nome
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name_short', 'ilike', '%' . $this->search . '%')
                    ->orWhere('description', 'ilike', '%' . $this->search . '%');
            });
        }

        $this->campaignList = $query->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        // Se tem slug, busca a campanha específica e redireciona para a página pública
        if ($this->slug) {
            $campaign = Campaign::where('slug', $this->slug)
                ->where('visibility_public', true)
                ->whereIn('status', ['active', 'active_direct'])
                ->first();

            if ($campaign) {
                return redirect(campanhaUrl(
                    $campaign->customer_organization_slug,
                    $campaign->slug,
                    null,
                    $this->appUserUuid,
                    $this->appSource
                ));
            }
        }

        // Carrega campanhas
        $this->loadCampaigns();

        return view('livewire.campanha.app-campanhas-user-home')->layout('layouts.app-guest-by-app');
    }
}
