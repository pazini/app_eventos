<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Services\ModuleAccessService;
use Livewire\Component;

class Home extends Component
{
    public $canEvents = false;
    public $canCampaigns = false;
    public $checkedModules = false;
    public $showModuleSelectionModal = false;

    public function mount()
    {
        $this->checkModules();

        // Verifica se precisa redirecionar ou mostrar modal
        $this->handleModuleAccess();
    }

    public function handleModuleAccess()
    {
        if (! auth()->check()) {
            return;
        }

        $user = auth()->user();

        $availableModules = [
            'events' => $this->canEvents,
            'campaigns' => $this->canCampaigns,
        ];

        $enabledModules = array_keys(array_filter($availableModules));

        // Admin global não redireciona (tem acesso a tudo)
        if ($user && ModuleAccessService::userIsAppAdmin($user)) {
            if (count($enabledModules) > 1) {
                $this->showModuleSelectionModal = true;
            } elseif (count($enabledModules) === 1) {
                return $this->redirectToModule($enabledModules[0]);
            }
            return;
        }

        if (count($enabledModules) === 1) {
            return $this->redirectToModule($enabledModules[0]);
        }

        if (count($enabledModules) > 1) {
            $this->showModuleSelectionModal = true;
        }
    }

    public function closeModal()
    {
        $this->showModuleSelectionModal = false;
    }

    public function checkModules()
    {
        if (! auth()->check()) {
            $this->checkedModules = true;
            return;
        }

        $user = auth()->user();

        // Admin global da aplicação enxerga todos os módulos, independente de customer
        if ($user && ModuleAccessService::userIsAppAdmin($user)) {
            $this->canEvents = true;
            $this->canCampaigns = true;
            $this->checkedModules = true;
            return;
        }

        // Garante que a sessão de customers está inicializada
        $customers = sessionCustomers();
        $customer = sessionCustomer();

        // Se houver um customer selecionado, verifica os módulos para ele
        if ($user && $customer instanceof Customer) {
            $this->canEvents = ModuleAccessService::userCanAccessEvents($user, $customer);
            $this->canCampaigns = ModuleAccessService::userCanAccessCampaigns($user, $customer);
        }
        // Se não houver customer selecionado, verifica todos os customers do usuário
        elseif ($user && $customers && $customers->count() > 0) {
            foreach ($customers as $cust) {
                if (ModuleAccessService::userCanAccessEvents($user, $cust)) {
                    $this->canEvents = true;
                }
                if (ModuleAccessService::userCanAccessCampaigns($user, $cust)) {
                    $this->canCampaigns = true;
                }
                // Se já encontrou todos, pode parar
                if ($this->canEvents && $this->canCampaigns) {
                    break;
                }
            }
        }

        $this->checkedModules = true;
    }

    public function render()
    {
        // Verifica novamente no render para garantir que os módulos sejam carregados
        // mesmo se a sessão não estiver inicializada no mount
        if (!$this->checkedModules) {
            $this->checkModules();
        }

        // Verifica novamente no render caso não tenha funcionado no mount
        // (pode acontecer se a sessão não estava totalmente inicializada)
        if (auth()->check() && !$this->showModuleSelectionModal) {
            $this->handleModuleAccess();
        }

        return view('livewire.dashboard.home')
            ->layout('layouts.app-pep-auth');
    }

    private function redirectToModule(string $module)
    {
        if ($module === 'events') {
            return redirect()->route('dashboard-eventos');
        }

        if ($module === 'campaigns') {
            return redirect()->route('dashboard-campanhas');
        }
    }
}


