<?php

namespace App\Http\Livewire\Campanha;

use App\Models\ModCampaign\Campaign;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AppCampanha extends Component
{
    public $slug;
    public $campaignList;

    // Filtros (sincronizados com navigation)
    public $search = '';
    public $filterStatus = 'ativas'; // ativas, todas, finalizadas
    public $filterCustomer = '';
    public $filterOrganizer = '';
    public $customers = [];
    public $organizers = [];

    protected $listeners = ['filterChanged', 'searchChanged'];

    public function mount($slug = null)
    {
        $this->slug = $slug;

        // Carrega lista de empresas para filtro
        $customerIds = \DB::table('tbc_campaign')
            ->where('visibility_public', true)
            ->distinct()
            ->pluck('customer_id')
            ->toArray();

        $this->customers = \App\Models\Customer::whereIn('id', $customerIds)
            ->orderBy('name_corporate')
            ->get(['id', 'name_corporate']);

        // Carrega lista de organizadores para filtro
        $organizerIds = \DB::table('tbc_campaign')
            ->where('visibility_public', true)
            ->whereNotNull('organizer_id')
            ->distinct()
            ->pluck('organizer_id')
            ->toArray();

        $this->organizers = \App\Models\ModCampaign\CampaignOrganizer::whereIn('id', $organizerIds)
            ->orderBy('organizer_name_full')
            ->get(['id', 'organizer_name_full']);
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

    public function updatedFilterOrganizer()
    {
        $this->loadCampaigns();
    }

    public function filterChanged($filters)
    {
        $this->filterStatus = $filters['status'] ?? 'ativas';
        $this->filterCustomer = $filters['customer'] ?? '';
        $this->filterOrganizer = $filters['organizer'] ?? '';
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
            ->where('visibility_public', true);

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

        // Filtro por organizador
        if ($this->filterOrganizer) {
            $query->where('organizer_id', $this->filterOrganizer);
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
                return redirect(campanhaUrl($campaign->customer_organization_slug, $campaign->slug));
            }
        }

        // Carrega campanhas
        $this->loadCampaigns();

        return view('livewire.app-campanha-home')->layout('layouts.app-pep-guest');
    }
}

