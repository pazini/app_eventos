<?php

namespace App\Http\Livewire\Campanha;

use App\Models\AppBuyers;
use App\Models\ModCampaign\CampaignOrder;
use Livewire\Component;

class AppCampanhaByAppMinhasDoacoes extends Component
{
    public $appUserUuid;
    public $appSource;
    public $buyer;
    public $orders;

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
        $this->loadOrders();
    }

    protected function loadOrders()
    {
        $this->buyer = null;
        $this->orders = collect();

        if (!$this->appUserUuid) {
            return;
        }

        $buyer = AppBuyers::where('app_user_uuid', $this->appUserUuid)->first();

        if (!$buyer) {
            return;
        }

        $this->buyer = $buyer;
        $this->orders = CampaignOrder::with(['campaign', 'campaign.customer', 'campaign.organization'])
            ->where('buyer_id', $buyer->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.campanha.app-campanhas-user-minhas-doacoes')->layout('layouts.app-guest-by-app');
    }
}
