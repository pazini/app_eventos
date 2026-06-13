<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\ModCampaign\CampaignOrganizer;
use App\Services\ModuleAccessService;
use Livewire\Component;

class DashboardCampanhas extends Component
{
    public $customers;
    public $customer;
    public $customer_id;

    public $organizers;
    public $organizer_id;

    public $canCampaigns = false;

    public function mount()
    {
        // Clientes disponíveis na sessão (filtrados por permissão geral)
        $this->customers = sessionCustomers(true);

        // Seleção atual vinda da sessão
        $this->customer = sessionCustomer();
        $this->customer_id = $this->customer->id ?? false;

        // SEGURANÇA: Carrega apenas os organizadores aos quais o usuário tem acesso
        if ($this->customer_id) {
            $this->organizers = sessionCampaignOrganizers($this->customer_id);
        } else {
            $this->organizers = collect();
        }

        // Pega organizador selecionado da sessão (se existir)
        $sessionOrganizer = session('campaign_organizer');
        $this->organizer_id = $sessionOrganizer->id ?? null;

        // Auto-seleção: Se há apenas uma empresa e um organizador, seleciona automaticamente
        if (!$this->organizer_id && $this->customers && $this->customers->count() == 1) {
            $singleCustomer = $this->customers->first();
            $this->customer_id = $singleCustomer->id;
            sessionCustomer($this->customer_id);

            // Carrega organizadores da empresa selecionada
            $this->organizers = sessionCampaignOrganizers($this->customer_id);

            // Se há apenas um organizador, seleciona automaticamente
            if ($this->organizers->count() == 1) {
                $this->organizer_id = $this->organizers->first()->id;
                sessionCampaignOrganizer($this->organizer_id);
            }
        }
        // Se já tem customer_id mas não tem organizador, verifica auto-seleção de organizador
        elseif (!$this->organizer_id && $this->customer_id && $this->organizers->count() == 1) {
            $this->organizer_id = $this->organizers->first()->id;
            sessionCampaignOrganizer($this->organizer_id);
        }

        // Checa se o usuário tem acesso ao módulo de campanhas para o customer atual
        if (auth()->check()) {
            $user = auth()->user();

            // Admin global sempre pode acessar o painel de campanhas (para configurar clientes)
            if (ModuleAccessService::userIsAppAdmin($user)) {
                $this->canCampaigns = true;
                return;
            }

            if ($this->customer_id) {
                $customer = Customer::find($this->customer_id);

                if ($customer) {
                    $this->canCampaigns = ModuleAccessService::userCanAccessCampaigns($user, $customer);

                    if (!$this->canCampaigns) {
                        abort(403, 'Você não tem permissão para acessar o módulo de campanhas para este cliente.');
                    }

                    // SEGURANÇA: Se não é admin/owner e não tem organizadores, apenas mostra a tela vazia
                    // Não bloqueia com abort(403), deixa a view mostrar a mensagem "NÃO POSSUI ORGANIZADORES"
                }
            }
        }
    }

    public function updatedCustomerId()
    {
        // Atualiza sessão e reseta organizador
        sessionClear('customer');
        sessionCustomer($this->customer_id);

        $this->organizer_id = null;
        session()->forget('campaign_organizer');

        // SEGURANÇA: Carrega apenas os organizadores aos quais o usuário tem acesso
        if ($this->customer_id) {
            $this->organizers = sessionCampaignOrganizers($this->customer_id);
        } else {
            $this->organizers = collect();
        }
    }

    public function updatedOrganizerId()
    {
        session()->forget('campaign_organizer');

        // Opção especial 'all': apenas admins/owners podem usar
        if ($this->organizer_id === 'all') {
            if (!isAdmin() && !isOwner()) {
                $this->organizer_id = null;
            }
            return;
        }

        if ($this->organizer_id) {
            // SEGURANÇA: Usa o helper que valida se o usuário tem acesso ao organizador
            $organizer = sessionCampaignOrganizer($this->organizer_id);

            if (!$organizer) {
                // Usuário não tem acesso a este organizador
                session()->flash('error', 'Você não tem permissão para acessar este organizador.');
                $this->organizer_id = null;
            }
        }
    }

    public function novaCampanha()
    {
        if (!$this->customer_id) {
            session()->flash('error', 'Selecione primeiro uma empresa para iniciar a campanha.');
            return;
        }

        // No próximo passo criaremos o fluxo completo de criação;
        // por enquanto apenas um placeholder.
        return redirect()->route('dashboard-campanhas-nova');
    }

    public function render()
    {
        // Auto-seleção adicional no render (caso não tenha sido feita no mount)
        if (!$this->organizer_id && $this->organizers && $this->organizers->count() == 1) {
            $this->organizer_id = $this->organizers->first()->id;
            sessionCampaignOrganizer($this->organizer_id);
        }

        $campaigns = collect();

        if ($this->customer_id) {

            // SEGURANÇA: Busca organizadores permitidos para este usuário
            $allowedOrganizers = sessionCampaignOrganizers($this->customer_id);
            $allowedOrganizerIds = $allowedOrganizers->pluck('id')->toArray();

            // Se não é admin/owner e não tem organizadores, não mostra nada
            if (empty($allowedOrganizerIds) && !isAdmin() && !isOwner()) {
                $campaigns = collect();
            } else {

                $query = \App\Models\ModCampaign\Campaign::where('customer_id', $this->customer_id);

                // filtra apenas campanhas dos organizadores permitidos
                if (!empty($allowedOrganizerIds)) {
                    $query->whereIn('organizer_id', $allowedOrganizerIds);
                }

                // Filtra por organizador específico se selecionado
                if ($this->organizer_id && $this->organizer_id !== 'all') {
                    // Valida se o usuário tem acesso ao organizador selecionado
                    if (!in_array($this->organizer_id, $allowedOrganizerIds) && !isAdmin() && !isOwner()) {
                        session()->flash('error', 'Você não tem permissão para acessar este organizador.');
                        $this->organizer_id = null;
                    } else {
                        $query->where('organizer_id', $this->organizer_id);
                    }
                }
                // Se 'all': não aplica filtro de organizador (mostra todos)

                $campaigns = $query->with(['customer', 'organization', 'organizer'])
                    ->withCount([
                        'orders as orders_total_count',
                        'orders as orders_paid_count' => function ($query) {
                            $query->where('status', 'paid');
                        },
                    ])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('livewire.dashboard.dashboard-campanhas', [
            'campaigns' => $campaigns
        ])->layout('layouts.app-pep-auth');
    }
}


