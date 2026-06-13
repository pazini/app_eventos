<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\AppEvent\AppEvent;
use App\Models\AppEvent\AppEventOrder;
use App\Models\CustomerOrganization;
use App\Models\CustomerOrganizationSub;
use App\Models\CustomerOrganizer;
use App\Models\ModEvent\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class DashboardVendas extends Component
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
    public $organizationSubs;
    public $organizationSub;
    public $organizationSubId;
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

        // LIMPEZA DOS DADOS DE EVENTOS DE TESTE
        if (false) {
            DB::beginTransaction();

            $eventIds = Event::where('organizer_id', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa')->pluck('id')->toArray();
            $this->orders = AppEventOrder::whereIn('event_id', $eventIds)->orderBy('created_at', 'desc')->take(100)->get();

            foreach ($this->orders as $orderkey => $ordervalue) {
                // pay
                foreach ($ordervalue->payments ?? [] as $paykey => $payvalue) {
                    $ordervalue->payments[$paykey]->delete();
                }

                // item
                foreach ($ordervalue->itens ?? [] as $itemkey => $itemvalue) {
                    $ordervalue->itens[$itemkey]->delete();
                }

                // slip
                foreach ($ordervalue->paymentsSlip ?? [] as $slipkey => $slipvalue) {
                    $ordervalue->paymentsSlip[$slipkey]->delete();
                }

                // ticekts
                foreach ($ordervalue->tickets ?? [] as $ticketkey => $ticketvalue) {
                    $ordervalue->tickets[$ticketkey]->delete();
                }

                // delete order
                $this->orders[$orderkey]->delete();
            }

            $eventIds = Event::where('organizer_id', 'aaaaaaaa-aaaa-aaaa-aaaa-aaaaaaaaaaaa')->pluck('id')->toArray();
            $this->orders = AppEventOrder::whereIn('event_id', $eventIds)->orderBy('created_at', 'desc')->take(100)->get();

            // DB::commit();

            dd(
                ($payvalue ?? FALSE) ? $payvalue->toArray() : 'no $payvalue',
                ($itemvalue ?? FALSE) ? $itemvalue->toArray() : 'no $itemvalue',
                ($slipvalue ?? FALSE) ? $slipvalue->toArray() : 'no $slipvalue',
                ($ticketvalue ?? FALSE) ? $ticketvalue->toArray() : 'no $ticketvalue',
                ($ordervalue ?? FALSE) ? $ordervalue->toArray() : 'no $ordervalue',
                $this->orders->toArray(),
            );
        }
        // LIMPEZA DOS DADOS DE EVENTOS DE TESTE - FIM

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
        //
        $this->organization = sessionOrganization();
        $this->organizationId = $this->organization->id ?? false;
        //
        $this->organizationSub = sessionOrganizationSub();
        $this->organizationSubId = $this->organizationSub->id ?? false;

        //
        $this->organizer = sessionOrganizer();
        $this->organizerId = $this->organizer->id ?? false;
    }

    public $orders;
    protected function getEventIds(): array
    {
        if ($this->organizerId ?? false) {
            return Event::where('organizer_id', $this->organizerId)->pluck('id')->toArray();
        }

        if ($this->organizationId ?? false) {
            return Event::whereIn('organizer_id', $this->organizers->pluck('id')->toArray())->pluck('id')->toArray();
        }

        if ($this->customerId ?? false) {
            return Event::where('customer_id', $this->customerId)->pluck('id')->toArray();
        }

        return Event::whereIn('customer_id', $this->customers->pluck('id')->toArray())->pluck('id')->toArray();
    }

    public function getOrders()
    {
        $eventIds = $this->getEventIds();

        $query = AppEventOrder::with(['payments', 'event.organizer'])->whereIn('event_id', $eventIds);

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
        $this->organizationSub = [];
        $this->organizers = [];

        //
        if ($this->customerId ?? false) {
            $this->organizations = CustomerOrganization::where('customer_id', $this->customerId)->get();
            $this->organizers = CustomerOrganizer::where('customer_id', $this->customerId)->get();
        }

        //
        if ($this->organizationId ?? false) {
            $this->organizationSubs = CustomerOrganizationSub::where('organization_id', $this->organizationId)->get();
            $this->organizers = CustomerOrganizer::where('customer_id', $this->customerId)->where('organization_id', $this->organizationId)->orderBy('organizer_name_full')->get();
        }

        //
        if ($this->organizationSubId ?? false) {
            $this->organizers = CustomerOrganizer::where('customer_id', $this->customerId)->where('organization_id', $this->organizationId)->where('organization_sub_id', $this->organizationSubId)->orderBy('organizer_name_full')->get();
        }

        //
        if (($this->organizers ?? false) && $this->organizers->count() == 1) {
            $this->organizerId = $this->organizers->first()->id;
            $this->updatedOrganizerId();
        }

        $this->orders = $this->getOrders();

        // dd(
        //     $this->organizers,
        //     $this->referer,
        // );

        return view('livewire.dashboard.dashboard-vendas-realtime')->layout('layouts.app-pep-auth');
    }

    public function updatedOrganizationId()
    {
        $this->organizationSubId = false;
        $this->organizerId = false;
        sessionClear('organization');
        sessionOrganization($this->organizationId);
    }

    public function updatedOrganizationSubId()
    {
        $this->organizerId = false;
        $this->organizationSubs = false;
        sessionClear('organizationSub');
        sessionOrganizationSub($this->organizationSubId);
    }

    public function updatedOrganizerId()
    {
        sessionClear('organizer');
        sessionOrganizers();
        sessionOrganizer($this->organizerId);
    }

    public function updatedCustomerId()
    {
        $this->organizerId = false;
        $this->organizationId = false;
        $this->organizationSubId = false;
        //
        sessionClear('customer');
        sessionCustomer($this->customerId);
    }

    public function resetPerfil()
    {
        // RESET
        sessionClear();
        //
        $this->customerId = false;
        $this->organizationId = false;
        $this->organizationSubId = false;
        $this->organizerId = false;
        //
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
        $eventIds = $this->getEventIds();

        $this->selectedOrderId = $orderId;
        $this->selectedOrder = AppEventOrder::with(['payments', 'event.organizer'])
            ->whereIn('event_id', $eventIds)
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
