<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\ModCampaign\Campaign;
use App\Models\ModCampaign\CampaignOrder;
use App\Models\CustomerOrganization;
use Livewire\Component;

class DashboardVendasCampanhas extends Component
{
    public $app;
    public $appUserRole;
    public $appModules;
    //
    public $customers;
    public $customer;
    public $customerId;
    //
    public $organizations;
    public $organization;
    public $organizationId;
    //
    public $organizers;
    public $organizer;
    public $organizerId;
    //
    public $referer;

    // FILTROS
    public $filterStatus = '';
    public $filterPayType = '';
    public $filterSearch = '';
    public $filterDate = '';
    public $filterRows = '300';

    public $showOrderModal = false;
    public $selectedOrderId;
    public $selectedOrder;
    public $showAdvancedFilters = false;
    public $lastRefreshAt = '';
    public $pollInterval = 30;

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        // APP
        $this->app = sessionApp();

        // SE NAO ADMIN
        if (!isAdmin()) {
            session()->flash('error', 'Acesso Negado');
            return redirect()->route('dashboard');
        }

        // GET REFERER
        $this->referer = sessionReferer();

        // CUSTOMERS
        $this->customers = sessionCustomers();
        $this->customer = sessionCustomer();
        $this->customerId = $this->customer->id ?? false;

        // Campanhas usa estado próprio de organizador para não depender do módulo de eventos
        $this->organization = false;
        $this->organizationId = false;

        $this->organizer = sessionCampaignOrganizer();
        $this->organizerId = $this->organizer->id ?? false;

        if ($this->organizer && $this->organizer->organization_id) {
            $this->organizationId = $this->organizer->organization_id;
        }
    }

    public $orders;
    protected function getCampaignIds(): array
    {
        if ($this->organizerId ?? false) {
            return Campaign::where('organizer_id', $this->organizerId)->pluck('id')->toArray();
        }

        if ($this->organizationId ?? false) {
            return Campaign::where('customer_id', $this->customerId)
                ->where('organization_id', $this->organizationId)
                ->pluck('id')
                ->toArray();
        }

        if ($this->customerId ?? false) {
            return Campaign::where('customer_id', $this->customerId)->pluck('id')->toArray();
        }

        return Campaign::whereIn('customer_id', $this->customers->pluck('id')->toArray())->pluck('id')->toArray();
    }

    public function getOrders()
    {
        $campaignIds = $this->getCampaignIds();

        $query = CampaignOrder::with(['payments', 'campaign.organizer'])->whereIn('campaign_id', $campaignIds);

        // FILTRO POR STATUS
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        // FILTRO POR BUSCA (email, nome ou documento)
        if ($this->filterSearch) {
            $search = $this->filterSearch;
            $query->where(function ($q) use ($search) {
                $q->where('buyer_email', 'like', "%{$search}%")
                    ->orWhere('buyer_name', 'like', "%{$search}%")
                    ->orWhere('buyer_doc_num', 'like', "%{$search}%");
            });
        }

        // FILTRO POR FORMA DE PAGAMENTO (aplicado na query para nao perder resultados)
        if ($this->filterPayType) {
            $payType = mb_strtolower($this->filterPayType);
            $query->whereHas('payments', function ($paymentQuery) use ($payType) {
                $paymentQuery->whereRaw('LOWER(pay_type) = ?', [$payType]);
            });
        }

        // FILTRO POR DATA (dia especifico)
        if ($this->filterDate) {
            $query->whereDate('created_at', $this->filterDate);
        }

        // QUANTIDADE DE REGISTROS so pode ser usada combinada com DATA
        $applyLimit = true;
        $limit = 300;

        if ($this->filterDate) {
            if ($this->filterRows === 'all') {
                $applyLimit = false;
            } else {
                $allowedRows = [50, 100, 300, 500, 1000];
                $rows = (int) $this->filterRows;

                if (!in_array($rows, $allowedRows, true)) {
                    $rows = 300;
                }

                $limit = $rows;
            }
        }

        $query->orderBy('created_at', 'desc');

        if ($applyLimit) {
            $query->take($limit);
        }

        $this->orders = $query->get();
        $this->lastRefreshAt = date('d/m/Y H:i:s');

        return $this->orders;
    }

    public function refreshOrders(bool $force = false): void
    {
        if ($this->showOrderModal && !$force) {
            return;
        }

        $this->orders = $this->getOrders();
    }

    public function updatedPollInterval($value): void
    {
        $allowedIntervals = [0, 10, 20, 30, 60];
        $interval = (int) $value;

        if (!in_array($interval, $allowedIntervals, true)) {
            $interval = 10;
        }

        $this->pollInterval = $interval;
    }

    public function toggleAdvancedFilters(): void
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    public function render()
    {
        // CUSTOMER
        $this->organization = [];
        $this->organization = [];
        $this->organizers = [];

        if ($this->customerId ?? false) {
            $this->organizations = CustomerOrganization::where('customer_id', $this->customerId)->get();

            $this->organizers = sessionCampaignOrganizers($this->customerId);

            if ($this->organizationId) {
                $this->organizers = $this->organizers->where('organization_id', $this->organizationId)->values();
            }
        }

        if (($this->organizers ?? false) && $this->organizers->count() == 1) {
            $this->organizerId = $this->organizers->first()->id;
            $this->updatedOrganizerId();
        }

        $this->orders = $this->getOrders();

        return view('livewire.dashboard.dashboard-vendas-campanhas-realtime')->layout('layouts.app-pep-auth');
    }

    public function updatedOrganizationId()
    {
        $this->organizerId = false;
        session()->forget('campaign_organizer');
    }

    public function updatedOrganizerId()
    {
        session()->forget('campaign_organizer');

        if ($this->organizerId) {
            $organizer = sessionCampaignOrganizer($this->organizerId);

            if (!$organizer) {
                session()->flash('error', 'Você não tem permissão para acessar este organizador.');
                $this->organizerId = false;
                return;
            }

            $this->organizationId = $organizer->organization_id;
        }
    }

    public function updatedCustomerId()
    {
        $this->organizerId = false;
        $this->organizationId = false;
        session()->forget('campaign_organizer');

        if ($this->customerId) {
            sessionClear('customer');
            sessionCustomer($this->customerId);
        }
    }

    public function resetPerfil()
    {
        $this->customerId = false;
        $this->organizationId = false;
        $this->organizerId = false;

        session()->forget('campaign_organizer');

        $this->filterStatus = '';
        $this->filterPayType = '';
        $this->filterSearch = '';
        $this->filterDate = '';
        $this->filterRows = '300';
    }

    public function updatedFilterStatus()
    {
        // Atualiza automaticamente quando o filtro muda
    }

    public function updatedFilterPayType()
    {
        // Atualiza automaticamente quando o filtro muda
    }

    public function updatedFilterSearch()
    {
        // Atualiza automaticamente quando o filtro muda
    }

    public function updatedFilterDate($value): void
    {
        if (!$value) {
            $this->filterRows = '300';
        }
    }

    public function updatedFilterRows($value): void
    {
        $allowedRows = ['50', '100', '300', '500', '1000', 'all'];
        $value = (string) $value;

        if (!in_array($value, $allowedRows, true)) {
            $this->filterRows = '300';
            return;
        }

        $this->filterRows = $value;
    }

    public function selectOrder(string $orderId): void
    {
        $campaignIds = $this->getCampaignIds();

        $this->selectedOrderId = $orderId;
        $this->selectedOrder = CampaignOrder::with(['payments', 'campaign.organizer'])
            ->whereIn('campaign_id', $campaignIds)
            ->where('id', $orderId)
            ->first();

        if ($this->selectedOrder) {
            $this->showOrderModal = true;
        }
    }

    public function closeOrderModal(): void
    {
        $this->showOrderModal = false;
        $this->selectedOrderId = null;
        $this->selectedOrder = null;
    }
}
